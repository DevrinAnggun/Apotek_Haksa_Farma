<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\StokBatch;
use App\Models\Obat;
use App\Models\Pembelian; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mengambil dan memproses data untuk ditampilkan di halaman Dashboard / Ringkasan.
     */
    public function index()
    {
        $hariIni = Carbon::today(); // Mengambil waktu 00:00:00 dari hari ini

        // -----------------------------------------------------------------------------
        // 1. Total Transaksi Hari Ini
        // Menggunakan count() untuk menghitung jumlah baris/struk yang dibuat hari ini
        // -----------------------------------------------------------------------------
        $totalTransaksiHariIni = Penjualan::has('details')->whereDate('tgl_penjualan', $hariIni)->count();

        // -----------------------------------------------------------------------------
        // 2. Stok Masuk / Pembelian dari Supplier (Bulan Ini)
        // Menjumlahkan total harga beli dari tabel pembelian untuk bulan berjalan.
        // -----------------------------------------------------------------------------
        $totalRestockBulanIni = Pembelian::whereMonth('tgl_pembelian', Carbon::now()->month)
            ->whereYear('tgl_pembelian', Carbon::now()->year)
            ->sum('total_bayar');

        // -----------------------------------------------------------------------------
        // 3. Obat dengan Stok Menipis (Berdasarkan Batas Minimal)
        // Logika: 
        // a. Karena stok dipecah di tabel stok_batches, kita memanfaatkan fitur 
        //    withSum('stokBatches', 'stok_sisa') dari Eloquent untuk mem-virtualisasi
        //    Total Keseluruhan dari tiap kotak batch.
        // b. Lalu memfilternya: WHERE (Virtual Total Stok <= batas_stok_minimal DB)
        // -----------------------------------------------------------------------------
        $obatStokMenipis = Obat::withSum('stokBatches as total_stok_global', 'stok_sisa')
            ->having('total_stok_global', '<=', DB::raw('batas_stok_minimal'))
            // Catatan: Jika tidak pakai view index, having() bisa tidak bekerja tanpa groupBy,
            // jadi alternatif lainnya memanggil data ke Collection Laravel, lalu mem-filternya di memory:
            ->get()
            // Pakai method ->filter() untuk komparasi dengan Accessor buatan kita (getTotalStokAttribute)
            // Ini akan mereturn daftar Obat yang kondisi Total Stoknya lebih kecil sama dengan Batas Minimal DB
            ->filter(function($obat) {
                return $obat->total_stok <= $obat->batas_stok_minimal;
            });

        // -----------------------------------------------------------------------------
        // 4. Obat Mendekati Expired (<= 30 Hari & <= 90 Hari)
        // Logika: Mengambil data dari tabel `stok_batches` di mana tgl_expired 
        // berada di antara Hari Ini sampai dengan +30 hari ke depan.
        // -----------------------------------------------------------------------------
        $batasWaktuWarning = Carbon::now()->addMonths(5);

        $obatMendekatiExpired = StokBatch::whereHas('obat', function($q) {
                $q->whereNull('deleted_at');
            })
            ->with('obat')
            ->where('stok_sisa', '>', 0)
            ->whereBetween('tgl_expired', [Carbon::now(), $batasWaktuWarning])
            ->orderBy('tgl_expired', 'asc')
            ->get();
            
        // (Opsi Tambahan Laporan Realita: List Obat yang sudah BENAR-BENAR EXPIRED atau H-5 Bulan)
        $batasKadaluarsa = Carbon::now()->addMonths(5);
        $obatSudahExpired = StokBatch::whereHas('obat', function($q) {
                $q->whereNull('deleted_at');
            })
            ->with('obat')
            ->where('stok_sisa', '>', 0)
            ->whereDate('tgl_expired', '<=', $batasKadaluarsa)
            ->get();

        // 5. Variabel Tambahan Untuk Desain Dashboard (Data Barang & Semua Penjualan)
        $totalDataBarang = Obat::count();
        
        // Hanya hitung penjualan yang memiliki detail/item (menghindari discrepancy data kosong)
        $penjualanValid = Penjualan::has('details');
        
        $totalPenjualan = $penjualanValid->count();
        $totalSemuaPenjualan = $penjualanValid->sum('total_harga');
        $jumlahObatKadaluarsa = $obatSudahExpired->count();

        // Ambil daftar obat untuk filter laporan
        $obats = Obat::orderBy('nama_obat', 'asc')->get();

        // Lempar data ke halaman blade view dashboard
        return view('dashboard.index', compact(
            'totalTransaksiHariIni',
            'totalRestockBulanIni',
            'obatStokMenipis',
            'obatMendekatiExpired',
            'obatSudahExpired',
            'totalDataBarang',
            'totalPenjualan',
            'totalSemuaPenjualan',
            'jumlahObatKadaluarsa',
            'obats'
        ));
    }
}
