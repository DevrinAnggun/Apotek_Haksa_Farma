<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Katalog Produk — halaman utama publik
     */
    public function katalog(Request $request)
    {
        // Query Dasar: Hanya ambil obat yang kategorinya BUKAN 'CEK'
        $query = Obat::whereHas('kategori', function($q) {
                        $q->where('nama_kategori', '!=', 'CEK');
                     })
                     ->whereHas('stokBatches', function($q) {
                        $q->where('stok_sisa', '>', 0)
                          ->where('tgl_expired', '>=', date('Y-m-d'));
                     })
                     ->with(['kategori', 'satuan'])
                     ->withSum(['stokBatches as total_stok' => function($q) {
                        $q->where('tgl_expired', '>=', date('Y-m-d'));
                     }], 'stok_sisa');

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('nama_obat', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        $obats    = $query->orderBy('nama_obat')->paginate(12)->withQueryString();

        // Ambil kategori untuk sidebar (Kecuali 'CEK') + jumlah obat
        $kategoris = Kategori::where('nama_kategori', '!=', 'CEK')
                             ->withCount('obats')
                             ->orderBy('nama_kategori')
                             ->get();

        return view('publik.katalog', compact('obats', 'kategoris'));
    }

    /**
     * Artikel — List
     */
    public function artikel()
    {
        $artikels = \App\Models\Artikel::orderBy('tanggal_publish', 'desc')->get();
        return view('publik.artikel', compact('artikels'));
    }

    /**
     * Artikel — Detail
     */
    public function bacaArtikel($slug)
    {
        $artikel = \App\Models\Artikel::where('slug', $slug)->firstOrFail();
        return view('publik.baca_artikel', compact('artikel'));
    }

    /**
     * Kontak Kami
     */
    public function kontak()
    {
        $settings = \App\Models\Pengaturan::pluck('value', 'key');
        return view('publik.kontak', compact('settings'));
    }
}
