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
     * Menampilkan form tambah data kadaluarsa (batch baru).
     */
    public function create()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('kadaluarsa.create', compact('obats'));
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
     * Menyimpan data batch baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_obat'     => 'required|exists:obats,id',
            'tgl_expired' => 'required|date',
        ], [
            'id_obat.required'     => 'Pilih obat terlebih dahulu.',
            'tgl_expired.required' => 'Tanggal kadaluarsa wajib diisi.',
        ]);

        // Ambil total stok dari Data & Stok sebagai stok_awal
        $obat     = Obat::findOrFail($request->id_obat);
        $stokAwal = (int)($request->stok_awal ?? $obat->total_stok);

        StokBatch::create([
            'id_obat'     => $request->id_obat,
            'id_pembelian'=> null,
            'no_batch'    => '-',
            'stok_awal'   => $stokAwal,
            'stok_sisa'   => $stokAwal,
            'tgl_expired' => $request->tgl_expired,
        ]);

        return redirect()->route('kadaluarsa.index')
            ->with('success', 'Data kadaluarsa obat berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data batch.
     */
    public function edit(StokBatch $kadaluarsa)
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('kadaluarsa.edit', compact('kadaluarsa', 'obats'));
    }

    /**
     * Update data batch di database.
     */
    public function update(Request $request, StokBatch $kadaluarsa)
    {
        $request->validate([
            'id_obat'     => 'required|exists:obats,id',
            'tgl_expired' => 'required|date',
            'harga_beli'  => 'nullable|integer|min:0',
        ], [
            'id_obat.required'     => 'Pilih obat terlebih dahulu.',
            'tgl_expired.required' => 'Tanggal kadaluarsa wajib diisi.',
        ]);

        $kadaluarsa->update([
            'id_obat'    => $request->id_obat,
            'tgl_expired'=> $request->tgl_expired,
        ]);

        return redirect()->route('kadaluarsa.index')
            ->with('success', 'Data kadaluarsa obat berhasil diperbarui.');
    }

    /**
     * Menghapus data batch dari database.
     */
    public function destroy(StokBatch $kadaluarsa)
    {
        $kadaluarsa->delete();
        return redirect()->route('kadaluarsa.index')
            ->with('success', 'Data batch obat berhasil dihapus.');
    }
}
