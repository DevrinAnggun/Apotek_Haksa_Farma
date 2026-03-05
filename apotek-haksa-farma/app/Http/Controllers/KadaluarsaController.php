<?php

namespace App\Http\Controllers;

use App\Models\StokBatch;
use App\Models\Obat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KadaluarsaController extends Controller
{
    /**
     * Menampilkan daftar semua data batch obat yang sudah/mendekati kadaluarsa.
     */
    public function index()
    {
        // Tampilkan batch yang SUDAH expired atau H-7 (≤ 7 hari lagi akan expired)
        $batasHari = Carbon::now()->addDays(7);

        $kadaluarsas = StokBatch::with([
            'obat' => function($q) {
                $q->withSum('penjualanDetails as total_terjual', 'qty');
            }, 
            'obat.kategori'
        ])
            ->where('stok_sisa', '>', 0)
            ->whereDate('tgl_expired', '<=', $batasHari)
            ->orderBy('tgl_expired', 'asc')
            ->get();

        return view('kadaluarsa.index', compact('kadaluarsas'));
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
    public function destroy(StokBatch $kadaluarsa)
    {
        $kadaluarsa->delete();
        return redirect()->route('kadaluarsa.index')
            ->with('success', 'Data batch obat berhasil dihapus.');
    }
}
