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
    /**
     * Menampilkan riwayat transaksi penjualan
     */
    public function index()
    {
        $penjualans = Penjualan::with('user')->latest()->get();
        return view('penjualan.index', compact('penjualans'));
    }

    /**
     * Menampilkan antarmuka Kasir (Point of Sale)
     */
    public function create()
    {
        // Load medicines with positive stock to display in POS
        $obats = Obat::all(); 
        return view('penjualan.create', compact('obats'));
    }

    /**
     * Menyimpan transaksi Penjualan Kasir dan Menjalankan LOGIKA INT FEFO
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Payload dari Frontend (Keranjang Belanja)
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

            // 2. Buat Data Header Transaksi Penjualan (Tabel Penjualan)
            // Generate nomor invoice unik otomatis (Misal: INV-202X1025-XXXX)
            $noInvoice = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

            $penjualan = Penjualan::create([
                'id_user'       => auth()->id() ?? 1, // ID Kasir yang login
                'no_invoice'    => $noInvoice,
                'tgl_penjualan' => now(),
                'total_harga'   => 0, // Di-update nanti setelah perulangan
                'nominal_bayar' => $request->nominal_bayar,
                'kembalian'     => 0, // Di-update nanti
            ]);

            // 3. Looping seluruh item obat yang ada di keranjang Kasir
            foreach ($request->items as $item) {
                // Konfigurasi Kebutuhan dan Subtotal si Pelanggan untuk 1 item obat
                $qtyKebutuhanPembeli = $item['qty'];
                $hargaJualObat       = $item['harga_jual'];

                $subtotal = $qtyKebutuhanPembeli * $hargaJualObat;
                $totalHarga += $subtotal;

                // --- START GLOBAL VALIDATION STOK ---
                // Mengecek apakah total "seluruh" stok_sisa obat ini (dari semua batch) mencukupi permintaan pembeli?
                $obat = Obat::findOrFail($item['id_obat']);
                $totalStokTersedia = $obat->total_stok; // Memanggil Accessor model Obat

                if ($totalStokTersedia < $qtyKebutuhanPembeli) {
                    // Jika stok kurang, lempar Error dan batalkan semua transaksi via Exception Rollback DB
                    throw new \Exception("Stok tidak mencukupi untuk Obat: {$obat->nama_obat}. Sisa seluruh stok di apotek: {$totalStokTersedia}");
                }

                // --- START LOGIKA FEFO (First Expired First Out) ---
                // 3.A. Mencari daftar Batch obat ini yang MURNI masih ADA STOKNYA (> 0)
                // 3.B. DIURUTKAN SECARA ASCENDING (Dari yang paling cepat expired di indeks pertama)
                $batches = StokBatch::where('id_obat', $item['id_obat'])
                            ->where('stok_sisa', '>', 0)
                            ->orderBy('tgl_expired', 'asc')
                            // Berjaga-jaga buat urutan kedua dari yang paling duluan Masuk (FIFO Support) jika tgl_expired kebetulan sama
                            ->orderBy('id', 'asc') 
                            ->get();

                // 4. Looping untuk membedah keranjang tersebut dan membaginya (Potong) per id_stok_batch
                foreach ($batches as $batch) {
                    
                    // Jika pesanan pembeli untuk obat ini sudah 0 terpenuhi, hentikan foreach spesifik ke batch ini. (Break)
                    if ($qtyKebutuhanPembeli <= 0) {
                        break;
                    }

                    // SKENARIO A: Stok dari Kotak Batch ini Cukup atau Bersisa untuk memenuhi (sebagian/keseluruhan) kebutuhan
                    if ($batch->stok_sisa >= $qtyKebutuhanPembeli) {
                        
                        // Buat riwayat rincian detail khusus untuk obat ini DIPOTONG dari nomor BATCH ini (Penting buat Laporan Harian)
                        DetailPenjualan::create([
                            'id_penjualan'  => $penjualan->id,
                            'id_obat'       => $item['id_obat'],
                            'id_stok_batch' => $batch->id,
                            'qty'           => $qtyKebutuhanPembeli, // Rekam qty pemotongan
                            'harga_jual'    => $hargaJualObat,
                            'subtotal'      => $qtyKebutuhanPembeli * $hargaJualObat,
                        ]);
                        
                        // Kurangi Sisa Stok dari DB batch berjalan
                        $batch->stok_sisa = $batch->stok_sisa - $qtyKebutuhanPembeli;
                        $batch->save();

                        // Hentikan perburuan kebutuhan pembeli karena di batch ini sudah CUKUP. (Status Kebutuhan Nol Terpenuhi)
                        $qtyKebutuhanPembeli = 0; 
                    } 
                    
                    // SKENARIO B: Stok Kotak Batch ini Kurang (Isi tidak cukup/Hampir habis)
                    else {
                        // Karena isinya sudah tidak mencukupi untuk nutupin request pelanggan yang banyak, Habiskan saja box batch ini
                        $stokDikubas = $batch->stok_sisa; 
                        
                        // Rekam pergerakan potongannya 
                        DetailPenjualan::create([
                            'id_penjualan'  => $penjualan->id,
                            'id_obat'       => $item['id_obat'],
                            'id_stok_batch' => $batch->id,
                            'qty'           => $stokDikubas, // Hanya merekam apa yg dikuras
                            'harga_jual'    => $hargaJualObat,
                            'subtotal'      => $stokDikubas * $hargaJualObat,
                        ]);

                        // Nol-kan Kotak Batch tersebut di DB agar saat query transaksi berikutnya ia tidak lagi dipanggil di list tersedia
                        $batch->stok_sisa = 0;
                        $batch->save();

                        // Kurangi sisa tanggungjawab obat yang masih harus dicari dari List Batch berikutnya secara berantai di putaran loop
                        $qtyKebutuhanPembeli = $qtyKebutuhanPembeli - $stokDikubas;
                    }
                }
                // --- END LOGIKA FEFO ---
            }

            // 5. Cek Uang Bayar Kasir vs Total Struk Setelah Looping Keranjang Selesai
            if ($request->nominal_bayar < $totalHarga) {
                // Lempar abort rollback
                throw new \Exception("Uang pembayaran kurang! Total belanja adalah: Rp. " . number_format($totalHarga, 0));
            }

            // 6. Update Nilai Total Tagihan dan Uang Kembalian Pada Tabel Penjualan (Header Struk)
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

    /**
     * Membatalkan transaksi penjualan dan mengembalikan stok
     */
    public function destroy(Penjualan $penjualan)
    {
        try {
            DB::beginTransaction();

            // 1. Ambil semua detail penjualan terkait
            $details = DetailPenjualan::where('id_penjualan', $penjualan->id)->get();

            // 2. Kembalikan stok ke masing-masing batch
            foreach ($details as $detail) {
                if ($detail->id_stok_batch) {
                    $batch = StokBatch::find($detail->id_stok_batch);
                    if ($batch) {
                        $batch->stok_sisa += $detail->qty;
                        $batch->save();
                    }
                }
            }

            // 3. Hapus data transaksi utama (cascade/delete trigger di DB sebaiknya di set, atau otomatis karena relation laravel jika dikonfigurasi, tapi untuk amannya hapus manual detailnya)
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
