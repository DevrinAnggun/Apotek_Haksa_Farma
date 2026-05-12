<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\StokBatch;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('user')->latest()->get();
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $obats = Obat::where(function($q) {
            $q->whereHas('stokBatches', function($query) {
                $query->where('stok_sisa', '>', 0)
                      ->where('tgl_expired', '>=', date('Y-m-d'));
            })->orWhereHas('kategori', function($query) {
                $query->where('nama_kategori', 'CEK');
            });
        })->orderBy('nama_obat', 'asc')->get();

        return view('penjualan.create', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal_bayar' => 'required|integer|min:0',
            'items'         => 'required|array|min:1',
            'items.*.id_obat'    => 'required|exists:obats,id',
            'items.*.qty'        => 'required|integer|min:1',
            'items.*.harga_jual' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalHarga = 0;
            $noInvoice = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

            $penjualan = Penjualan::create([
                'id_user'       => auth()->id() ?? 1, 
                'no_invoice'    => $noInvoice,
                'tgl_penjualan' => now(),
                'total_harga'   => 0, 
                'nominal_bayar' => $request->nominal_bayar,
                'kembalian'     => 0, 
            ]);

            foreach ($request->items as $item) {
                $qtyKebutuhanPembeli = $item['qty'];
                $hargaJualObat       = $item['harga_jual'];

                $subtotal = $qtyKebutuhanPembeli * $hargaJualObat;
                $totalHarga += $subtotal;

                $obat = Obat::findOrFail($item['id_obat']);
                
                $isCek = $obat->kategori && strtoupper($obat->kategori->nama_kategori) === 'CEK';

                if (!$isCek) {
                    $totalStokTersedia = $obat->total_stok;

                    if ($totalStokTersedia < $qtyKebutuhanPembeli) {
                        throw new \Exception("Stok tidak mencukupi untuk Obat: {$obat->nama_obat}. Sisa seluruh stok di apotek: {$totalStokTersedia}");
                    }

                    $batches = StokBatch::where('id_obat', $item['id_obat'])
                                ->where('stok_sisa', '>', 0)
                                ->orderBy('tgl_expired', 'asc')
                                ->orderBy('id', 'asc') 
                                ->get();

                    foreach ($batches as $batch) {
                        
                        if ($qtyKebutuhanPembeli <= 0) {
                            break;
                        }
                        if ($batch->stok_sisa >= $qtyKebutuhanPembeli) {
                            
                            DetailPenjualan::create([
                                'id_penjualan'  => $penjualan->id,
                                'id_obat'       => $item['id_obat'],
                                'id_stok_batch' => $batch->id,
                                'qty'           => $qtyKebutuhanPembeli,
                                'harga_jual'    => $hargaJualObat,
                                'subtotal'      => $qtyKebutuhanPembeli * $hargaJualObat,
                            ]);
                            
                            $batch->stok_sisa = $batch->stok_sisa - $qtyKebutuhanPembeli;
                            $batch->save();

                            $qtyKebutuhanPembeli = 0; 
                        } 
                        
                        else {
                            $stokDikubas = $batch->stok_sisa; 
                            
                            DetailPenjualan::create([
                                'id_penjualan'  => $penjualan->id,
                                'id_obat'       => $item['id_obat'],
                                'id_stok_batch' => $batch->id,
                                'qty'           => $stokDikubas,
                                'harga_jual'    => $hargaJualObat,
                                'subtotal'      => $stokDikubas * $hargaJualObat,
                            ]);

                            $batch->stok_sisa = 0;
                            $batch->save();

                            $qtyKebutuhanPembeli = $qtyKebutuhanPembeli - $stokDikubas;
                        }
                    }
                } else {
                    DetailPenjualan::create([
                        'id_penjualan'  => $penjualan->id,
                        'id_obat'       => $item['id_obat'],
                        'id_stok_batch' => null,
                        'qty'           => $qtyKebutuhanPembeli,
                        'harga_jual'    => $hargaJualObat,
                        'subtotal'      => $qtyKebutuhanPembeli * $hargaJualObat,
                    ]);
                }
            }

            if ($request->nominal_bayar < $totalHarga) {
                throw new \Exception("Uang pembayaran kurang! Total belanja adalah: Rp. " . number_format($totalHarga, 0));
            }

            $kembalian = $request->nominal_bayar - $totalHarga;
            $penjualan->update([
                'total_harga' => $totalHarga,
                'kembalian'   => $kembalian,
            ]);

            DB::commit();

            return redirect()->route('laporan.penjualan')->with('success', "Transaksi Selesai! Kembalian: Rp. " . number_format($kembalian, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Transaksi Penjualan POS: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Penjualan $penjualan)
    {
        try {
            DB::beginTransaction();

            $details = DetailPenjualan::where('id_penjualan', $penjualan->id)->get();

            foreach ($details as $detail) {
                if ($detail->id_stok_batch) {
                    $batch = StokBatch::find($detail->id_stok_batch);
                    if ($batch) {
                        $batch->stok_sisa += $detail->qty;
                        $batch->save();
                    }
                }
            }

            DetailPenjualan::where('id_penjualan', $penjualan->id)->delete();
            $penjualan->delete();

            DB::commit();

            return redirect()->back()->with('success', "Transaksi berhasil dihapus dan stok telah dikembalikan.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Hapus Transaksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
