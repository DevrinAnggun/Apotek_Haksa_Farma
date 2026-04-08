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
    /**
     * Menampilkan daftar semua data batch obat yang sudah/mendekati kadaluarsa.
     */
    public function index()
    {
        // Tampilkan obat yang memiliki batch SUDAH expired atau H-7 (≤ 7 hari lagi akan expired)
        $batasHari = Carbon::now()->addDays(7);
        $kadaluarsas = $this->getKadaluarsaQuery($batasHari)->paginate(10);

        return view('kadaluarsa.index', compact('kadaluarsas', 'batasHari'));
    }

    /**
     * Helper untuk query data kadaluarsa agar konsisten antara index & cetak PDF
     */
    private function getKadaluarsaQuery($batasHari)
    {
        return StokBatch::select(
                'id_obat',
                DB::raw('SUM(stok_sisa) as total_sisa'),
                DB::raw('MIN(tgl_expired) as earliest_expired')
            )
            ->with([
                'obat' => function($q) {
                    $q->withSum('penjualanDetails as total_terjual', 'qty');
                }, 
                'obat.kategori'
            ])
            ->whereHas('obat', function($q) {
                // Dimana obat tersebut TIDAK dalam status terhapus (deleted_at IS NULL)
                $q->whereNull('deleted_at');
                $q->whereHas('kategori', function($q2) {
                    $q2->where('nama_kategori', '!=', 'CEK');
                });
            })
            ->where('stok_sisa', '>', 0)
            ->whereDate('tgl_expired', '<=', $batasHari)
            ->groupBy('id_obat')
            ->orderBy('earliest_expired', 'asc');
    }

    /**
     * Mengekspor data kadaluarsa ke PDF
     */
    public function cetakPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = StokBatch::select(
                'id_obat',
                DB::raw('SUM(stok_sisa) as total_sisa'),
                DB::raw('MIN(tgl_expired) as earliest_expired')
            )
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
            ->where('stok_sisa', '>', 0);
            
        if ($startDate && $endDate) {
            $query->whereBetween('tgl_expired', [$startDate, $endDate]);
            $batasHari = Carbon::parse($endDate);
        } else {
            $batasHari = Carbon::now()->addDays(7);
            $query->whereDate('tgl_expired', '<=', $batasHari);
        }

        $kadaluarsas = $query->groupBy('id_obat')
            ->orderBy('earliest_expired', 'asc')->get();

        $pdf = Pdf::loadView('kadaluarsa.pdf', compact('kadaluarsas', 'batasHari'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Laporan_Data_Kadaluarsa_';
        if ($startDate && $endDate) {
             $filename .= "{$startDate}_sampai_{$endDate}.pdf";
        } else {
             $filename .= date('d_m_Y') . '.pdf';
        }

        return $pdf->download($filename);
    }

    /**
     * Menampilkan detail satu data batch kadaluarsa.
     */
    public function show(StokBatch $kadaluarsa)
    {
        $kadaluarsa->load(['obat.kategori', 'obat.satuan']);
        return view('kadaluarsa.show', compact('kadaluarsa'));
    }

    /**
     * Menghapus data batch dari database (Mungkin untuk membersihkan log lama).
     */
    public function destroy($id_obat)
    {
        try {
            $batasHari = Carbon::now()->addDays(7);
            
            // Hapus semua batch obat ini yang sudah expired / H-7
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
