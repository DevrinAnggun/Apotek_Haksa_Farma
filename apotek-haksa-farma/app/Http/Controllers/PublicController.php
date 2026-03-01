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
        $query = Obat::with(['kategori', 'satuan', 'stokBatches']);

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('nama_obat', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        $obats    = $query->orderBy('nama_obat')->paginate(12)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('publik.katalog', compact('obats', 'kategoris'));
    }

    /**
     * Artikel
     */
    public function artikel()
    {
        // Data artikel statis (bisa dikembangkan ke DB nanti)
        $artikels = [
            [
                'judul'   => 'Mengenal Logo dan Simbol pada Kemasan Obat',
                'ringkas' => 'Logo dan simbol pada kemasan obat memiliki arti penting untuk keamanan penggunaan. Kenali perbedaan logo obat bebas, bebas terbatas, keras, dan psikotropika.',
                'tanggal' => '15 Februari 2026',
                'kategori'=> 'Edukasi',
                'slug'    => 'logo-simbol-kemasan-obat',
            ],
            [
                'judul'   => 'Tips Menyimpan Obat dengan Benar di Rumah',
                'ringkas' => 'Penyimpanan obat yang salah dapat menurunkan efektivitasnya. Pelajari cara menyimpan obat dengan benar agar tetap aman dan efektif untuk dikonsumsi.',
                'tanggal' => '10 Februari 2026',
                'kategori'=> 'Tips Kesehatan',
                'slug'    => 'tips-menyimpan-obat',
            ],
            [
                'judul'   => 'Bahaya Membeli Obat Tanpa Resep Dokter',
                'ringkas' => 'Membeli obat keras tanpa resep dokter berisiko menimbulkan efek samping serius. Simak penjelasan lengkapnya dan cara aman mendapatkan obat yang tepat.',
                'tanggal' => '05 Februari 2026',
                'kategori'=> 'Keamanan',
                'slug'    => 'bahaya-obat-tanpa-resep',
            ],
            [
                'judul'   => 'Perbedaan Obat Generik dan Obat Bermerek',
                'ringkas' => 'Banyak yang bertanya, apakah obat generik sama efektifnya dengan obat bermerek? Temukan jawabannya dalam artikel berikut ini.',
                'tanggal' => '01 Februari 2026',
                'kategori'=> 'Edukasi',
                'slug'    => 'obat-generik-vs-bermerek',
            ],
            [
                'judul'   => 'Cara Membaca Label Informasi Obat dengan Tepat',
                'ringkas' => 'Label pada kemasan obat memuat informasi penting seperti dosis, efek samping, dan kontraindikasi. Pelajari cara membacanya dengan benar.',
                'tanggal' => '25 Januari 2026',
                'kategori'=> 'Edukasi',
                'slug'    => 'cara-membaca-label-obat',
            ],
            [
                'judul'   => 'Pentingnya Minum Obat Sesuai Anjuran Dokter',
                'ringkas' => 'Kepatuhan minum obat sangat berpengaruh pada proses penyembuhan. Ketahui mengapa penting untuk mengikuti anjuran dokter dalam penggunaan obat.',
                'tanggal' => '20 Januari 2026',
                'kategori'=> 'Kesehatan',
                'slug'    => 'minum-obat-sesuai-anjuran',
            ],
        ];

        return view('publik.artikel', compact('artikels'));
    }

    /**
     * Kontak Kami
     */
    public function kontak()
    {
        return view('publik.kontak');
    }
}
