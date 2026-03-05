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
        // Ambil range tanggal yang tersedia di database untuk default UI
        $firstSale = Penjualan::orderBy('tgl_penjualan', 'asc')->first();
        $lastSale  = Penjualan::orderBy('tgl_penjualan', 'desc')->first();
        
        $defaultStart = $firstSale ? \Carbon\Carbon::parse($firstSale->tgl_penjualan)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $defaultEnd   = $lastSale ? \Carbon\Carbon::parse($lastSale->tgl_penjualan)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        // Gunakan input user jika ada, jika tidak ada (klik "Lihat Semua"), jangan filter database
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $query = Penjualan::has('details')->with(['user', 'details.obat.kategori', 'details.obat.satuan']);

        if ($startDate && $endDate) {
            $query->whereDate('tgl_penjualan', '>=', $startDate)
                  ->whereDate('tgl_penjualan', '<=', $endDate);
        }

        $penjualans = $query->latest('tgl_penjualan')->get();
        $totalPendapatan = $penjualans->sum('total_harga');

        // Untuk input field di view, tampilkan tanggal yang sedang aktif atau default
        $viewStart = $startDate ?: $defaultStart;
        $viewEnd   = $endDate   ?: $defaultEnd;

        return view('penjualan.index', [
            'penjualans' => $penjualans,
            'startDate' => $viewStart,
            'endDate' => $viewEnd,
            'totalPendapatan' => $totalPendapatan
        ]);
    }

    /**
     * Mengekspor data laporan ke dalam format PDF.
     */
    public function cetakPdf(Request $request)
    {
        $firstSale = Penjualan::orderBy('tgl_penjualan', 'asc')->first();
        $defaultStart = $firstSale ? \Carbon\Carbon::parse($firstSale->tgl_penjualan)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');

        $startDate = $request->input('start_date', $defaultStart);
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $penjualans = Penjualan::with('user')
            ->whereDate('tgl_penjualan', '>=', $startDate)
            ->whereDate('tgl_penjualan', '<=', $endDate)
            ->orderBy('tgl_penjualan', 'asc') // Urutkan dari terlama ke terbaru untuk laporan
            ->get();

        $totalPendapatan = $penjualans->sum('total_harga');

        // Render blade view menjadi halaman HTML untuk PDF
        // Load view 'penjualan.pdf' dan passing datanya
        $pdf = Pdf::loadView('penjualan.pdf', compact(
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
