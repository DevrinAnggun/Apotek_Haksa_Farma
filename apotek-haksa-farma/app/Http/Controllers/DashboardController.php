<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\StokBatch;
use App\Models\Obat;
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
        $totalTransaksiHariIni = Penjualan::whereDate('tgl_penjualan', $hariIni)->count();

        // -----------------------------------------------------------------------------
        // 2. Total Pendapatan / Omset Hari Ini
        // Menggunakan sum('total_harga') untuk menjumlahkan isi kolom uang
        // -----------------------------------------------------------------------------
        $totalPendapatanHariIni = Penjualan::whereDate('tgl_penjualan', $hariIni)->sum('total_harga');

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
        $batasWaktuWarning = Carbon::now()->addDays(30);

        $obatMendekatiExpired = StokBatch::with('obat')
            ->where('stok_sisa', '>', 0) // Pastikan hanya obat yang MURNI masih ADA di apotek. (Jangan obat basi 2 tahun kemarin tapi sisa=0 dimunculin alert)
            ->whereBetween('tgl_expired', [Carbon::now(), $batasWaktuWarning])
            ->orderBy('tgl_expired', 'asc') // Urutkan dari yang sisa waktu paling mepet ke hari ini
            ->get();
            
        // (Opsi Tambahan Laporan Realita: List Obat yang sudah BENAR-BENAR EXPIRED)
        $obatSudahExpired = StokBatch::with('obat')
            ->where('stok_sisa', '>', 0) // Karena stok belum dibuang ke tong / retur, sistem masih ngebaca fisiknya ada di apotek
            ->whereDate('tgl_expired', '<', Carbon::now()) // Tanggal tgl_expired lebih KECIL dari hari ini (Masa lalu)
            ->get();

        // 5. Variabel Tambahan Untuk Desain Dashboard (Data Barang & Semua Penjualan)
        $totalDataBarang = Obat::count();
        $totalPenjualan = Penjualan::count();
        $totalSemuaPenjualan = Penjualan::sum('total_harga');
        $jumlahObatKadaluarsa = $obatSudahExpired->count();

        // Lempar data ke halaman blade view dashboard
        return view('dashboard.index', compact(
            'totalTransaksiHariIni',
            'totalPendapatanHariIni',
            'obatStokMenipis',
            'obatMendekatiExpired',
            'obatSudahExpired',
            'totalDataBarang',
            'totalPenjualan',
            'totalSemuaPenjualan',
            'jumlahObatKadaluarsa'
        ));
    }
}
