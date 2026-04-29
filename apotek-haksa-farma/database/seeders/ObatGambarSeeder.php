<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatGambarSeeder extends Seeder
{

    protected array $mapping = [

        'Bisolvon'             => 'bisolvon.jpg',
        'Masker Medis'         => 'masker.jpg',
        'Panadol Extra'        => 'panadol.jpg',
        'Sarung Tangan Lateks' => 'sarung-tangan.jpg',
        'Teh China'            => 'teh-china.jpg',
        'Teh Sari Sehat'       => 'teh-sari-sehat.jpg',
        'Tolak Angin Cair'     => 'tolak-angin.jpg',
        'Vitamin C'            => 'vitamin-c.jpg',
    ];

    public function run(): void
    {
        foreach ($this->mapping as $namaObat => $namaFile) {
            $updated = Obat::where('nama_obat', $namaObat)
                ->update(['gambar' => 'images/obat/' . $namaFile]);

            if ($updated) {
                $this->command->info("✅ {$namaObat} → {$namaFile}");
            } else {
                $this->command->warn("⚠️  Tidak ditemukan: {$namaObat}");
            }
        }

        $this->command->info('Selesai!');
    }
}
