<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PublicController extends Controller
{
    public function katalog(Request $request)
    {
        $query = Obat::whereHas('kategori', function($q) {
                        $q->where('nama_kategori', '!=', 'CEK');
                     })
                     ->whereHas('stokBatches', function($q) {
                        $q->where('stok_sisa', '>', 0)
                          ->where('tgl_expired', '>=', date('Y-m-d'));
                     })
                     ->when(Schema::hasColumn('obats', 'tampil_di_pelanggan'), function($q) {
                        $q->where('tampil_di_pelanggan', true);
                     })
                     ->with(['kategori', 'satuan'])
                     ->withSum(['stokBatches as total_stok' => function($q) {
                        $q->where('tgl_expired', '>=', date('Y-m-d'));
                     }], 'stok_sisa');

        if ($request->filled('search')) {
            $query->where('nama_obat', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        $obats    = $query->orderBy('nama_obat')->paginate(12)->withQueryString();

        $kategoris = Kategori::where('nama_kategori', '!=', 'CEK')
                             ->withCount('obats')
                             ->orderBy('nama_kategori')
                             ->get();

        return view('publik.katalog', compact('obats', 'kategoris'));
    }

    public function artikel()
    {
        $artikels = \App\Models\Artikel::orderBy('tanggal_publish', 'desc')->get();
        return view('publik.artikel', compact('artikels'));
    }

    public function bacaArtikel($slug)
    {
        $artikel = \App\Models\Artikel::where('slug', $slug)->firstOrFail();
        return view('publik.baca_artikel', compact('artikel'));
    }

    public function kontak()
    {
        $settings = \App\Models\Pengaturan::pluck('value', 'key');
        return view('publik.kontak', compact('settings'));
    }
}
