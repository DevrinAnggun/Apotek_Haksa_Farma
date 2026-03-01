<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan form filter laporan penjualan & tabel hasilnya.
     */
    public function penjualan(Request $request)
    {
        // Set default filter: 30 hari ke belakang (bulanan) sampai hari ini
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Query berdasarkan range tanggal (mengabaikan jam dengan date())
        $penjualans = Penjualan::with('user')
            ->whereDate('tgl_penjualan', '>=', $startDate)
            ->whereDate('tgl_penjualan', '<=', $endDate)
            ->latest('tgl_penjualan')
            ->get();

        $totalPendapatan = $penjualans->sum('total_harga');

        return view('laporan.penjualan', compact('penjualans', 'startDate', 'endDate', 'totalPendapatan'));
    }

    /**
     * Mengekspor data laporan ke dalam format PDF.
     */
    public function cetakPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $penjualans = Penjualan::with('user')
            ->whereDate('tgl_penjualan', '>=', $startDate)
            ->whereDate('tgl_penjualan', '<=', $endDate)
            ->orderBy('tgl_penjualan', 'asc') // Urutkan dari terlama ke terbaru untuk laporan
            ->get();

        $totalPendapatan = $penjualans->sum('total_harga');

        // Render blade view menjadi halaman HTML untuk PDF
        // Load view 'laporan.pdf_penjualan' dan passing datanya
        $pdf = Pdf::loadView('laporan.pdf_penjualan', compact(
            'penjualans', 
            'startDate', 
            'endDate', 
            'totalPendapatan'
        ));

        // Set ukuran kertas (A4 Landscape agar tabel lebar tidak terpotong)
        $pdf->setPaper('A4', 'landscape');

        // Memberikan file PDF tersebut untuk diunduh (downloadable)
        return $pdf->download("Laporan_Penjualan_{$startDate}_sampai_{$endDate}.pdf");
    }
}
