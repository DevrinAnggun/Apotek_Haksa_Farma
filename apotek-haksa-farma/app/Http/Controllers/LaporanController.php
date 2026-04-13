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

        // Ambil input user
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $month     = $request->input('month');
        $year      = $request->input('year');

        // Jika filter bulanan digunakan (shortcut)
        if ($month && $year) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
            $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
        }

        $query = Penjualan::has('details')->with(['user', 'details.obat.kategori', 'details.obat.satuan']);

        if ($startDate && $endDate) {
            $query->whereDate('tgl_penjualan', '>=', $startDate)
                  ->whereDate('tgl_penjualan', '<=', $endDate);
        }

        $totalPendapatan = $query->sum('total_harga'); 
        $penjualans = $query->latest('tgl_penjualan')->paginate(10)->withQueryString();

        // Untuk input field di view, tampilkan tanggal yang sedang aktif atau default
        $viewStart = $startDate ?: $defaultStart;
        $viewEnd   = $endDate   ?: $defaultEnd;

        $obats = \App\Models\Obat::orderBy('nama_obat', 'asc')->get();

        return view('penjualan.index', [
            'penjualans' => $penjualans,
            'startDate' => $viewStart,
            'endDate' => $viewEnd,
            'totalPendapatan' => $totalPendapatan,
            'obats' => $obats
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

        $penjualans = Penjualan::whereHas('details.obat', function($q) {
                $q->whereNull('deleted_at');
            })
            ->with(['user', 'details.obat'])
            ->whereDate('tgl_penjualan', '>=', $startDate)
            ->whereDate('tgl_penjualan', '<=', $endDate)
            ->orderBy('tgl_penjualan', 'asc')
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

    public function cetakPenjualanSebelumKadaluarsaPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $id_obat = $request->input('id_obat');

        $query = Penjualan::whereDate('tgl_penjualan', '>=', $startDate)
            ->whereDate('tgl_penjualan', '<=', $endDate)
            ->whereHas('details.obat', function($q) use ($id_obat) {
                $q->whereNull('deleted_at'); 
                if($id_obat) $q->where('id', $id_obat); // Filter obat jika dipilih
                $q->whereHas('kategori', function($qKat) {
                    $qKat->where('nama_kategori', '!=', 'CEK');
                })->whereHas('stokBatches', function($qBatch) {
                    $qBatch->where('stok_sisa', '>', 0)
                           ->whereDate('tgl_expired', '>', Carbon::today());
                });
            })
            ->with(['user', 'details' => function($q) use ($id_obat) {
                $q->whereHas('obat', function($q2) use ($id_obat) {
                    if($id_obat) $q2->where('id', $id_obat);
                    $q2->whereHas('kategori', function($qKat2) {
                        $qKat2->where('nama_kategori', '!=', 'CEK');
                    })->whereHas('stokBatches', function($qBatch2) {
                        $qBatch2->where('stok_sisa', '>', 0)
                               ->whereDate('tgl_expired', '>', Carbon::today());
                    });
                })->with('obat');
            }])
            ->orderBy('tgl_penjualan', 'asc');

        $penjualans = $query->get();

        $totalPendapatan = 0;
        foreach($penjualans as $trx) {
            foreach($trx->details as $d) {
                $totalPendapatan += $d->subtotal;
            }
        }

        $pdf = Pdf::loadView('penjualan.pdf', [
            'penjualans' => $penjualans,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPendapatan' => $totalPendapatan,
            'customTitle' => 'LAPORAN PENJUALAN OBAT SEBELUM KADALUARSA'
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("Laporan_Penjualan_Sebelum_Kadaluarsa_{$startDate}_sampai_{$endDate}.pdf");
    }

    public function cetakPenjualanKadaluarsaPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $penjualans = Penjualan::whereDate('tgl_penjualan', '>=', $startDate)
            ->whereDate('tgl_penjualan', '<=', $endDate)
            ->whereHas('details.obat', function($q) {
                $q->whereNull('deleted_at'); // Filter obat yang belum dihapus
                $q->whereHas('kategori', function($qKat) {
                    $qKat->where('nama_kategori', '!=', 'CEK');
                })->whereHas('stokBatches', function($qBatch) {
                    $qBatch->where('stok_sisa', '>', 0)
                           ->whereDate('tgl_expired', '<=', Carbon::today());
                });
            })
            ->with(['user', 'details' => function($q) {
                $q->whereHas('obat', function($q2) {
                    $q2->whereHas('kategori', function($qKat2) {
                        $qKat2->where('nama_kategori', '!=', 'CEK');
                    })->whereHas('stokBatches', function($qBatch2) {
                        $qBatch2->where('stok_sisa', '>', 0)
                               ->whereDate('tgl_expired', '<=', Carbon::today());
                    });
                })->with('obat');
            }])
            ->orderBy('tgl_penjualan', 'asc')
            ->get();

        $totalPendapatan = 0;
        foreach($penjualans as $trx) {
            foreach($trx->details as $d) {
                $totalPendapatan += $d->subtotal;
            }
        }

        $pdf = Pdf::loadView('penjualan.pdf', [
            'penjualans' => $penjualans,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPendapatan' => $totalPendapatan,
            'customTitle' => 'LAPORAN PENJUALAN OBAT SUDAH KADALUARSA'
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("Laporan_Penjualan_Kadaluarsa_{$startDate}_sampai_{$endDate}.pdf");
    }
    public function cetakReturPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $month     = $request->input('month');
        $year      = $request->input('year');
        $id_obat   = $request->input('id_obat');

        if ($month && $year) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
            $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');
        } else {
            $startDate = $startDate ?: Carbon::now()->subDays(30)->format('Y-m-d');
            $endDate   = $endDate ?: Carbon::now()->format('Y-m-d');
        }

        $query = \App\Models\ReturPembelian::whereHas('obat', function($q) {
                $q->whereNull('deleted_at');
            })
            ->with(['pembelian.supplier', 'obat'])
            ->whereDate('tgl_retur', '>=', $startDate)
            ->whereDate('tgl_retur', '<=', $endDate);

        if ($id_obat) {
            $query->where('id_obat', $id_obat);
        }

        $returs = $query->orderBy('tgl_retur', 'desc')->get();

        $totalPotongan = $returs->sum('nominal_potongan');

        $pdf = Pdf::loadView('pembelian.retur_pdf', compact('returs', 'startDate', 'endDate', 'totalPotongan'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download("Laporan_Retur_Obat_{$startDate}_sampai_{$endDate}.pdf");
    }
}
