<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\StokBatch;
use App\Models\Supplier; // Import Supplier
use App\Models\Pembelian; // Import Pembelian
use App\Models\DetailPembelian; // Import DetailPembelian
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::with(['kategori', 'satuan'])
                     ->withSum('penjualanDetails as total_terjual', 'qty');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%");
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        $obats = $query->paginate(10)->withQueryString();
        $kategoris = Kategori::all();
        $satuans   = Satuan::all();
        
        return view('obat.index', compact('obats', 'kategoris', 'satuans'));
    }

    public function katalogAdmin(Request $request)
    {
        $query = Obat::whereHas('kategori', function($q) {
                        $q->where('nama_kategori', '!=', 'CEK');
                     })
                     ->whereHas('stokBatches', function($q) {
                        $q->where('tgl_expired', '>=', date('Y-m-d'));
                     })
                     ->with(['kategori', 'satuan'])
                     ->withSum('penjualanDetails as total_terjual', 'qty');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%");
            });
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        $obats = $query->paginate(12)->withQueryString();

        // Exclude 'CEK' from filter options
        $kategoris = Kategori::where('nama_kategori', '!=', 'CEK')->get();
        $satuans   = Satuan::all();
        
        return view('publik.katalog_admin', compact('obats', 'kategoris', 'satuans'));
    }

    public function create()
    {
        return redirect()->route('obat.index');
    }

    public function store(Request $request)
    {
        // Validasi Relasi dan Nilai Numerik
        $request->validate([
            'kode_obat'   => 'required|string|unique:obats,kode_obat',
            'nama_obat'   => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategoris,id',
            'id_satuan'   => 'required|exists:satuans,id',
            'harga_beli'  => 'required|integer|min:0',
            'harga_jual'  => 'required|integer|min:0',
            'stok'        => 'nullable|integer|min:0',
            'expired_date'=> 'nullable|date',
            'deskripsi'   => 'nullable|string',
            'cara_pakai'  => 'nullable|string',
        ]);

        $obatData = $request->except(['stok', 'expired_date', 'gambar']);
        $obatData['batas_stok_minimal'] = 5;

        // Handle Image Upload
        if ($request->hasFile('gambar')) {
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('uploads/obat'), $imageName);
            $obatData['gambar'] = 'uploads/obat/' . $imageName;
        }

        $obat = Obat::create($obatData);

        // Inject initial stok directly to StokBatch table if user filled it
        if ($request->filled('stok') && $request->stok > 0) {
            $expiredDate = $request->filled('expired_date') ? $request->expired_date : Carbon::now()->addYears(2)->format('Y-m-d');
            StokBatch::create([
                'id_obat' => $obat->id,
                'no_batch' => 'BATCH-INIT-' . time(),
                'tgl_expired' => $expiredDate, 
                'stok_awal' => $request->stok,
                'stok_sisa' => $request->stok,
            ]);
        }

        return redirect()->back()->with('success', 'Data Obat berhasil ditambahkan!');
    }

    public function edit(Obat $obat)
    {
        return redirect()->route('obat.index');
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'kode_obat'   => 'required|string|unique:obats,kode_obat,'.$obat->id,
            'nama_obat'   => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategoris,id',
            'id_satuan'   => 'required|exists:satuans,id',
            'harga_beli'  => 'required|integer|min:0',
            'harga_jual'  => 'required|integer|min:0',
            'stok'        => 'nullable|integer|min:0',
            'expired_date'=> 'nullable|date',
            'deskripsi'   => 'nullable|string',
            'cara_pakai'  => 'nullable|string',
        ]);

        $obatData = $request->except(['stok', 'expired_date', 'gambar']);
        $obatData['batas_stok_minimal'] = 5;

        // Handle Image Update
        if ($request->hasFile('gambar')) {
            if ($obat->gambar && file_exists(public_path($obat->gambar))) {
                unlink(public_path($obat->gambar));
            }
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('uploads/obat'), $imageName);
            $obatData['gambar'] = 'uploads/obat/' . $imageName;
        }

        $obat->update($obatData);

        // Handle direct edit stok
        if ($request->filled('stok')) {
            // Find existing generic batch or get any first batch
            $batch = StokBatch::where('id_obat', $obat->id)->orderBy('created_at', 'asc')->first();
            $expiredDate = $request->filled('expired_date') ? $request->expired_date : Carbon::now()->addYears(2)->format('Y-m-d');
            
            if ($batch) {
                // Update existing batch
                $batch->update([
                    'stok_sisa' => $request->stok,
                    'stok_awal' => $request->stok,
                    'tgl_expired' => $expiredDate,
                ]);
            } else if ($request->stok > 0) {
                // Generate a new one if completely empty and user put > 0
                StokBatch::create([
                    'id_obat' => $obat->id,
                    'no_batch' => 'BATCH-ADJ-' . time(),
                    'tgl_expired' => $expiredDate,
                    'stok_awal' => $request->stok,
                    'stok_sisa' => $request->stok,
                ]);
            }
        } else if ($request->filled('expired_date')) {
             $batch = StokBatch::where('id_obat', $obat->id)->orderBy('created_at', 'asc')->first();
             if ($batch) {
                 $batch->update(['tgl_expired' => $request->expired_date]);
             }
        }

        return redirect()->back()->with('success', 'Data Obat berhasil diperbarui!');
    }

    public function destroy(Obat $obat)
    {
        // Hapus gambar jika ada
        if ($obat->gambar && file_exists(public_path($obat->gambar))) {
            unlink(public_path($obat->gambar));
        }
        $obat->delete();
        return redirect()->back()->with('success', 'Data Obat berhasil dihapus!');
    }
}
