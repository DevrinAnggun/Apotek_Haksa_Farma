<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\StokBatch;
use App\Models\RiwayatStokMasuk;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PembelianController extends Controller
{

    public function index()
    {
        $pembelians = Pembelian::whereHas('details.obat', function($q) {
                                   $q->whereNull('deleted_at');
                               })
                               ->with(['supplier', 'user', 'details.obat'])
                               ->latest()
                               ->paginate(10);
        $suppliers = \App\Models\Supplier::all();
        $obats = \App\Models\Obat::all();

        return view('pembelian.index', compact('pembelians', 'suppliers', 'obats'));

    }
    public function create()
    {
        return view('pembelian.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama_suplier' => 'required|string|max:255',
            'no_faktur' => 'required|string|max:100',
            'tgl_pembelian' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_obat' => 'required|exists:obats,id',
            'items.*.no_batch' => 'required|string|max:100',
            'items.*.tgl_expired' => 'required|date|after:today',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|integer|min:0',
            'items.*.harga_jual' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $supplier = \App\Models\Supplier::firstOrCreate(
                ['nama_suplier' => strtoupper($request->nama_suplier)]
            );

            $totalBayar = 0;

            $pembelian = Pembelian::create([
                'id_suplier' => $supplier->id,
                'id_user' => auth()->id(),
                'no_faktur' => $request->no_faktur,
                'tgl_pembelian' => $request->tgl_pembelian,
                'total_bayar' => 0,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga_beli'];
                $totalBayar += $subtotal;

                $detail = DetailPembelian::create([
                    'id_pembelian' => $pembelian->id,
                    'id_obat' => $item['id_obat'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $subtotal,
                ]);

                StokBatch::create([
                    'id_obat' => $item['id_obat'],
                    'id_pembelian' => $pembelian->id,
                    'no_batch' => $item['no_batch'],
                    'tgl_expired' => $item['tgl_expired'],
                    'stok_awal' => $item['qty'],
                    'stok_sisa' => $item['qty'],
                ]);
                
                RiwayatStokMasuk::create([
                    'id_pembelian_detail' => $detail->id,
                    'id_obat' => $item['id_obat'],
                    'qty_masuk' => $item['qty'],
                    'harga_beli' => $item['harga_beli'],
                    'harga_jual' => $item['harga_jual'],
                    'tgl_expired' => $item['tgl_expired'],
                    'keterangan' => 'Penerimaan Stok Awal'
                ]);

                \App\Models\Obat::where('id', $item['id_obat'])->update([
                    'harga_beli' => $item['harga_beli'],
                    'harga_jual' => $item['harga_jual']
                ]);

                $tglTerima = $request->tgl_pembelian;
                $existing = StockOpname::where('id_obat', $item['id_obat'])
                    ->where('tanggal', $tglTerima)->first();
                if ($existing) {
                    $existing->increment('jumlah', $item['qty']);
                } else {
                    StockOpname::create([
                        'id_obat' => $item['id_obat'],
                        'tanggal' => $tglTerima,
                        'jumlah' => $item['qty'],
                    ]);
                }
            }

            $pembelian->update(['total_bayar' => $totalBayar]);
            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Penerimaan Stok Berhasil Disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal saat menyimpan data Penerimaan Barang: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function cetakPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $pembelians = Pembelian::whereHas('details.obat', function($q) {
                $q->whereNull('deleted_at');
            })
            ->with(['supplier', 'user', 'details.obat'])
            ->whereDate('tgl_pembelian', '>=', $startDate)
            ->whereDate('tgl_pembelian', '<=', $endDate)
            ->latest('tgl_pembelian')
            ->get();

        $totalPembelian = $pembelians->sum('total_bayar');

        $customTitle = 'LAPORAN PEMBELIAN';
        if ($startDate === $endDate) {
            $customTitle = 'LAPORAN PEMBELIAN HARIAN';
        } else {
            $start = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);
            // Jika rentang adalah satu bulan penuh
            if ($start->day === 1 && $end->day === $start->daysInMonth && $start->month === $end->month && $start->year === $end->year) {
                $customTitle = 'LAPORAN PEMBELIAN BULANAN';
            }
        }

        $pdf = Pdf::loadView('pembelian.pdf', compact(
            'pembelians', 
            'startDate', 
            'endDate', 
            'totalPembelian',
            'customTitle'
        ));

        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("Laporan_Pembelian_{$startDate}_sampai_{$endDate}.pdf");
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'id_obat' => 'required|exists:obats,id',
            'tgl_pembelian' => 'required|date',
            'nama_suplier' => 'required|string|max:255',
            'tgl_expired' => 'required|date',
            'qty' => 'required|integer|min:1',
            'tambah_stok' => 'nullable|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $supplier = \App\Models\Supplier::firstOrCreate(
                ['nama_suplier' => strtoupper($request->nama_suplier)]
            );

            $pembelian->update([
                'id_suplier' => $supplier->id,
                'tgl_pembelian' => $request->tgl_pembelian,
            ]);

            $detail = DetailPembelian::where('id_pembelian', $pembelian->id)->first();
            if ($detail) {
                $qtyLama = $detail->qty;
                $tambahStok = $request->tambah_stok ?? 0;
                $newQtyTotal = $request->qty + $tambahStok;

                $detail->update([
                    'id_obat' => $request->id_obat,
                    'qty' => $newQtyTotal,
                    'harga_beli' => $request->harga_beli,
                    'subtotal' => $newQtyTotal * $request->harga_beli,
                ]);

                $batch = StokBatch::where('id_pembelian', $pembelian->id)->first();
                if ($batch) {
                    if ($tambahStok > 0) {
                        $batch->stok_awal = $newQtyTotal;
                        $batch->stok_sisa += $tambahStok;
                    } else {
                        $selisih = $request->qty - $qtyLama;
                        $batch->stok_awal = $request->qty;
                        $batch->stok_sisa += $selisih;
                    }
                    $batch->id_obat = $request->id_obat;
                    $batch->tgl_expired = $request->tgl_expired;
                    $batch->save();
                }

                if ($tambahStok > 0) {
                    RiwayatStokMasuk::create([
                        'id_pembelian_detail' => $detail->id,
                        'id_obat' => $request->id_obat,
                        'qty_masuk' => $tambahStok,
                        'harga_beli' => $request->harga_beli,
                        'harga_jual' => $request->harga_jual,
                        'tgl_expired' => $request->tgl_expired,
                        'keterangan' => 'Penambahan Stok Baru (Edit)'
                    ]);

                    $tglTerima = now()->toDateString();
                    $existingSO = StockOpname::where('id_obat', $request->id_obat)
                        ->where('tanggal', $tglTerima)->first();
                    if ($existingSO) {
                        $existingSO->increment('jumlah', $tambahStok);
                    } else {
                        StockOpname::create([
                            'id_obat' => $request->id_obat,
                            'tanggal' => $tglTerima,
                            'jumlah' => $tambahStok,
                        ]);
                    }
                }

                $obat = \App\Models\Obat::find($request->id_obat);
                if ($obat) {
                    $obat->update([
                        'harga_beli' => $request->harga_beli,
                        'harga_jual' => $request->harga_jual
                    ]);
                }
            }

            $pembelian->total_bayar = $pembelian->details->sum('subtotal');
            $pembelian->save();

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Riwayat stok berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui riwayat: ' . $e->getMessage());
        }
    }

    public function destroy(Pembelian $pembelian)
    {
        try {
            DB::beginTransaction();

            $detailIds = DetailPembelian::where('id_pembelian', $pembelian->id)->pluck('id');
            RiwayatStokMasuk::whereIn('id_pembelian_detail', $detailIds)->delete();

            StokBatch::where('id_pembelian', $pembelian->id)->delete();
            DetailPembelian::where('id_pembelian', $pembelian->id)->delete();
            $pembelian->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Riwayat stok masuk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus riwayat pembelian: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }

    public function getRiwayat($id_detail)
    {
        $riwayat = RiwayatStokMasuk::where('id_pembelian_detail', $id_detail)
            ->latest('created_at')
            ->get();

        return response()->json($riwayat);
    }
    public function storeRetur(Request $request)
    {
        $request->validate([
            'id_pembelian' => 'required|exists:pembelians,id',
            'id_obat' => 'required|exists:obats,id',
            'qty_retur' => 'required|integer|min:1',
            'alasan' => 'required|string',
            'nominal_potongan' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $pembelian = Pembelian::findOrFail($request->id_pembelian);
            $obat = \App\Models\Obat::findOrFail($request->id_obat);

            // 1. Simpan data Retur 
            \App\Models\ReturPembelian::create([
                'id_pembelian' => $pembelian->id,
                'id_obat' => $obat->id,
                'qty_retur' => $request->qty_retur,
                'tgl_retur' => now()->toDateString(),
                'alasan' => $request->alasan,
                'nominal_potongan' => $request->nominal_potongan,
                'foto' => null,
            ]);

            // 2. Kurangi stok batch dan stok sisa
            $batch = StokBatch::where('id_pembelian', $pembelian->id)->where('id_obat', $obat->id)->first();
            if ($batch) {

                $batch->stok_sisa -= $request->qty_retur;
                if ($batch->stok_sisa < 0) {
                    $batch->stok_sisa = 0;
                }
                $batch->save();
            }

            // 3. Tambahkan ke riwayat stok masuk dengan nilai negatif
            $detail = DetailPembelian::where('id_pembelian', $pembelian->id)->where('id_obat', $obat->id)->first();
            if ($detail) {
                RiwayatStokMasuk::create([
                    'id_pembelian_detail' => $detail->id,
                    'id_obat' => $obat->id,
                    'qty_masuk' => -$request->qty_retur,
                    'harga_beli' => $detail->harga_beli,
                    'harga_jual' => $obat->harga_jual,
                    'tgl_expired' => $batch ? $batch->tgl_expired : null,
                    'keterangan' => 'Retur: ' . $request->alasan
                ]);

                // 4. Update total bayar pembelian jika ada pemotongan tagihan
                if ($request->nominal_potongan > 0) {
                    $detail->subtotal -= $request->nominal_potongan;
                    if($detail->subtotal < 0) $detail->subtotal = 0;
                    $detail->save();
                }
            }

            if ($request->nominal_potongan > 0) {
                $pembelian->total_bayar -= $request->nominal_potongan;
                if ($pembelian->total_bayar < 0) {
                    $pembelian->total_bayar = 0;
                }
                $pembelian->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Retur barang berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memproses retur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses retur: ' . $e->getMessage());
        }
    }

   

    public function getAllReturRecap()
    {
        $returs = \App\Models\ReturPembelian::with('obat')
            ->latest('tgl_retur')
            ->get();

        return response()->json($returs);
    }
}
