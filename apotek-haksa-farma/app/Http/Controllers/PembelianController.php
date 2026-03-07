<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\StokBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PembelianController extends Controller
{
    /**
     * Menampilkan daftar riwayat penerimaan barang (pembelian).
     */
    public function index()
    {
        // Data for purchase listing
        $pembelians = Pembelian::with(['supplier', 'user', 'details.obat'])
                               ->latest()
                               ->paginate(10);

        // Data for the modal (Add Stock)
        $suppliers = \App\Models\Supplier::all();
        $obats = \App\Models\Obat::all();

        return view('pembelian.index', compact('pembelians', 'suppliers', 'obats'));
    }

    /**
     * Menampilkan form untuk menambah/merestock barang masuk.
     */
    public function create()
    {
        // Pada praktek nyatanya, Anda perlu mengirim data Supplier dan Obat ke Dropdown view
        // $suppliers = \App\Models\Supplier::all();
        // $obats = \App\Models\Obat::all();
        // return view('pembelian.create', compact('suppliers', 'obats'));
        return view('pembelian.create');
    }

    /**
     * Menyimpan transaksi pembelian dan men-generate stok batch (DB Transaction).
     */
    public function store(Request $request)
    {
        // 1. Validasi Input Array Request
        $request->validate([
            'nama_suplier' => 'required|string|max:255',
            'no_faktur' => 'required|string|max:100', // Relaxed unique check for flexibility if needed, or keep if strict
            'tgl_pembelian' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_obat' => 'required|exists:obats,id',
            'items.*.no_batch' => 'required|string|max:100',
            'items.*.tgl_expired' => 'required|date|after:today',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|integer|min:0',
        ]);

        // Mulai Database Transaction untuk keamanan aliran data
        // Jika salah satu proses (misal: generate batch gagal), maka seluruh penyimpanan dari header hingga detail akan dibatalkan (Rollback) otomatis.
        try {
            // A. Cari atau Buat Supplier Berdasarkan Nama
            $supplier = \App\Models\Supplier::firstOrCreate(
                ['nama_suplier' => strtoupper($request->nama_suplier)]
            );

            $totalBayar = 0;

            // 2. Simpan Header: Tabel Pembelian
            $pembelian = Pembelian::create([
                'id_suplier' => $supplier->id,
                'id_user' => auth()->id(), // Diisi oleh ID Akun Admin yang Login
                'no_faktur' => $request->no_faktur,
                'tgl_pembelian' => $request->tgl_pembelian,
                'total_bayar' => 0, // Inisiasi nilai awal, nanti akan diupdate setelah looping kalkulasi item
            ]);

            // 3. Looping untuk Simpan Detail dan Generate Batch
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['harga_beli'];
                $totalBayar += $subtotal;

                // A. Simpan Rincian Pembelian: Tabel DetailPembelian
                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id,
                    'id_obat' => $item['id_obat'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $subtotal,
                ]);

                // B. **GENERATE STOK BATCH**: Tabel StokBatch (Kunci Core Sistem)
                // Di sinilah stok secara fisik dicatat riwayat kadaluarsanya.
                StokBatch::create([
                    'id_obat' => $item['id_obat'],
                    'id_pembelian' => $pembelian->id,
                    'no_batch' => $item['no_batch'],
                    'tgl_expired' => $item['tgl_expired'],
                    'stok_awal' => $item['qty'], // Stok dasar awal saat diterima
                    'stok_sisa' => $item['qty'], // Sisa stok (awalnya sama dengan qty pembelian)
                ]);
                
                // C. (Opsional) Update Harga Beli dasar obat di Master Data Obat (jika harga dari supplier naik/turun)
                // \App\Models\Obat::where('id', $item['id_obat'])->update(['harga_beli' => $item['harga_beli']]);
            }

            // 4. Update Ulang Total Pembayaran pada Tabel Utama (Header)
            $pembelian->update(['total_bayar' => $totalBayar]);

            // 5. Finalisasi Sukses dan Simpan ke Server Permanen (Commit)
            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Penerimaan Stok Berhasil Disimpan!');

        } catch (\Exception $e) {
            // Jika terjadi kesalahan baris kode / trigger query gagal di loop manapun, Cancel semuanya (Rollback)
            DB::rollBack();
            Log::error('Gagal saat menyimpan data Penerimaan Barang (Pembelian): ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan nota: ' . $e->getMessage());
        }
    }

    /**
     * Mengekspor data laporan pembelian/supplier ke PDF.
     */
    public function cetakPdf(Request $request)
    {
        // Default start date (misal 3 bulan terakhir jika tidak filter)
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $pembelians = Pembelian::with(['supplier', 'user', 'details.obat'])
            ->whereDate('tgl_pembelian', '>=', $startDate)
            ->whereDate('tgl_pembelian', '<=', $endDate)
            ->latest('tgl_pembelian')
            ->get();

        $totalPembelian = $pembelians->sum('total_bayar');

        $pdf = Pdf::loadView('pembelian.pdf', compact(
            'pembelians', 
            'startDate', 
            'endDate', 
            'totalPembelian'
        ));

        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("Laporan_Stok_Masuk_{$startDate}_sampai_{$endDate}.pdf");
    }

    /**
     * Memperbarui data riwayat pembelian dan stok batch.
     */
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
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Supplier
            $supplier = \App\Models\Supplier::firstOrCreate(
                ['nama_suplier' => strtoupper($request->nama_suplier)]
            );

            // 2. Update Pembelian Header
            $pembelian->update([
                'id_suplier' => $supplier->id,
                'tgl_pembelian' => $request->tgl_pembelian,
            ]);

            // 3. Update Detail & Stok Batch
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

                // Update Stok Batch
                $batch = StokBatch::where('id_pembelian', $pembelian->id)->first();
                if ($batch) {
                    // Jika ada tambah_stok, kita tambahkan ke sisa yang ada
                    // Jika tidak ada tambah_stok (hanya edit qty awal), kita sesuaikan sisa berdasarkan selisih
                    if ($tambahStok > 0) {
                        $batch->stok_awal = $newQtyTotal;
                        $batch->stok_sisa += $tambahStok;
                    } else {
                        // Jika mereka merubah angka Qty Masuk secara manual, kita sesuaikan sisa
                        $selisih = $request->qty - $qtyLama;
                        $batch->stok_awal = $request->qty;
                        $batch->stok_sisa += $selisih;
                    }

                    $batch->id_obat = $request->id_obat;
                    $batch->tgl_expired = $request->tgl_expired;
                    $batch->save();
                }
            }

            // Sync total_bayar header
            $pembelian->total_bayar = $pembelian->details->sum('subtotal');
            $pembelian->save();

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Riwayat stok berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui riwayat: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus riwayat pembelian dan stok batch terkait.
     */
    public function destroy(Pembelian $pembelian)
    {
        try {
            DB::beginTransaction();

            // 1. Hapus Stok Batch yang terkait dengan pembelian ini
            StokBatch::where('id_pembelian', $pembelian->id)->delete();

            // 2. Hapus Detail Pembelian
            DetailPembelian::where('id_pembelian', $pembelian->id)->delete();

            // 3. Hapus Data Utama Pembelian
            $pembelian->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Riwayat stok masuk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal hapus riwayat pembelian: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }
}
