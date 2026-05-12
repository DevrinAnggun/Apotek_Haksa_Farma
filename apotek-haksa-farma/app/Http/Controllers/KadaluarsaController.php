<?php

namespace App\Http\Controllers;

use App\Models\StokBatch;
use App\Models\Obat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class KadaluarsaController extends Controller
{

    public function index()
    {
        $batasHari = Carbon::now()->addMonths(5);
        $kadaluarsas = $this->getKadaluarsaQuery($batasHari)->paginate(10);

        return view('kadaluarsa.index', compact('kadaluarsas', 'batasHari'));
    }

    private function getKadaluarsaQuery($batasHari)
    {
        return StokBatch::select(
                'stok_batches.id_obat',
                DB::raw('SUM(stok_batches.stok_sisa) as total_sisa'),
                DB::raw('MIN(stok_batches.tgl_expired) as earliest_expired')
            )
            ->join('obats', 'stok_batches.id_obat', '=', 'obats.id')
            ->with([
                'obat' => function($q) {
                    $q->withSum('penjualanDetails as total_terjual', 'qty');
                }, 
                'obat.kategori'
            ])
            ->whereHas('obat', function($q) {
                $q->whereNull('deleted_at');
                $q->whereHas('kategori', function($q2) {
                    $q2->where('nama_kategori', '!=', 'CEK');
                });
            })
            ->where('stok_batches.stok_sisa', '>', 0)
            ->whereDate('stok_batches.tgl_expired', '<=', $batasHari)
            ->groupBy('stok_batches.id_obat', 'obats.nama_obat')
            ->orderBy('obats.nama_obat', 'asc');
    }

    public function cetakPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = StokBatch::select(
                'stok_batches.id_obat',
                DB::raw('SUM(stok_batches.stok_sisa) as total_sisa'),
                DB::raw('MIN(stok_batches.tgl_expired) as earliest_expired')
            )
            ->join('obats', 'stok_batches.id_obat', '=', 'obats.id')
            ->with([
                'obat' => function($q) {
                    $q->withSum('penjualanDetails as total_terjual', 'qty');
                }, 
                'obat.kategori'
            ])
            ->whereHas('obat', function($q) {
                $q->whereNull('deleted_at');
                $q->whereHas('kategori', function($q2) {
                    $q2->where('nama_kategori', '!=', 'CEK');
                });
            })
            ->where('stok_batches.stok_sisa', '>', 0);
            
        if ($startDate && $endDate) {
            $query->whereBetween('stok_batches.tgl_expired', [$startDate, $endDate]);
            $batasHari = Carbon::parse($endDate);
        } else {
            $batasHari = Carbon::now()->addMonths(5);
            $query->whereDate('stok_batches.tgl_expired', '<=', $batasHari);
        }

        $kadaluarsas = $query->groupBy('stok_batches.id_obat', 'obats.nama_obat')
            ->orderBy('obats.nama_obat', 'asc')->get();

        $pdf = Pdf::loadView('kadaluarsa.pdf', compact('kadaluarsas', 'batasHari'));
        $pdf->setPaper('A4', 'landscape');

        $filename = 'Laporan_Data_Kadaluarsa_';
        if ($startDate && $endDate) {
             $filename .= "{$startDate}_sampai_{$endDate}.pdf";
        } else {
             $filename .= date('d_m_Y') . '.pdf';
        }

        return $pdf->download($filename);
    }

    public function show(StokBatch $kadaluarsa)
    {
        $kadaluarsa->load(['obat.kategori', 'obat.satuan']);
        return view('kadaluarsa.show', compact('kadaluarsa'));
    }

    public function destroy($id_obat)
    {
        try {
            $batasHari = Carbon::now()->addMonths(5);
            
            StokBatch::where('id_obat', $id_obat)
                ->where('stok_sisa', '>', 0)
                ->whereDate('tgl_expired', '<=', $batasHari)
                ->delete();

            return redirect()->route('kadaluarsa.index')
                ->with('success', 'Data kadaluarsa obat tersebut berhasil dibersihkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
