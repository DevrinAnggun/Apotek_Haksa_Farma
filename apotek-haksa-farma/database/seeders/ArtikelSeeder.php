<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artikels = [
            [
                'judul'   => 'Mengenal Logo dan Simbol pada Kemasan Obat',
                'slug'    => 'logo-simbol-kemasan-obat',
                'ringkasan' => 'Logo dan simbol pada kemasan obat memiliki arti penting untuk keamanan penggunaan. Kenali perbedaan logo obat bebas, bebas terbatas, keras, dan psikotropika.',
                'konten'  => 'Logo dan simbol pada kemasan obat memiliki arti penting untuk keamanan penggunaan. Kenali perbedaan logo obat bebas, bebas terbatas, keras, dan psikotropika.',
                'kategori'=> 'Edukasi',
                'tanggal_publish' => now(),
            ],
            [
                'judul'   => 'Tips Menyimpan Obat dengan Benar di Rumah',
                'slug'    => 'tips-menyimpan-obat',
                'ringkasan' => 'Penyimpanan obat yang salah dapat menurunkan efektivitasnya. Pelajari cara menyimpan obat dengan benar agar tetap aman dan efektif untuk dikonsumsi.',
                'konten'  => 'Penyimpanan obat yang salah dapat menurunkan efektivitasnya. Pelajari cara menyimpan obat dengan benar agar tetap aman dan efektif untuk dikonsumsi.',
                'kategori'=> 'Tips Kesehatan',
                'tanggal_publish' => now(),
            ],
            [
                'judul'   => 'Bahaya Membeli Obat Tanpa Resep Dokter',
                'slug'    => 'bahaya-obat-tanpa-resep',
                'ringkasan' => 'Membeli obat keras tanpa resep dokter berisiko menimbulkan efek samping serius. Simak penjelasan lengkapnya dan cara aman mendapatkan obat yang tepat.',
                'konten'  => 'Membeli obat keras tanpa resep dokter berisiko menimbulkan efek samping serius. Simak penjelasan lengkapnya dan cara aman mendapatkan obat yang tepat.',
                'kategori'=> 'Keamanan',
                'tanggal_publish' => now(),
            ],
        ];

        foreach ($artikels as $artikel) {
            \App\Models\Artikel::updateOrCreate(['slug' => $artikel['slug']], $artikel);
        }
    }
}
