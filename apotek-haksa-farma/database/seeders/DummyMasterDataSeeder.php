<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Satuan;

class DummyMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Masukkan data kategori obat minimal
        Kategori::firstOrCreate(['nama_kategori' => 'Sirup']);
        Kategori::firstOrCreate(['nama_kategori' => 'Generik']);
        Kategori::firstOrCreate(['nama_kategori' => 'Paten']);
        Kategori::firstOrCreate(['nama_kategori' => 'Suplemen & Vitamin']);
        Kategori::firstOrCreate(['nama_kategori' => 'Alkes']);
        Kategori::firstOrCreate(['nama_kategori' => 'Herbal']);

        // Masukkan data satuan
        Satuan::firstOrCreate(['nama_satuan' => 'Strip']);
        Satuan::firstOrCreate(['nama_satuan' => 'Tab']);
        Satuan::firstOrCreate(['nama_satuan' => 'Box']);
        Satuan::firstOrCreate(['nama_satuan' => 'Botol']);
        Satuan::firstOrCreate(['nama_satuan' => 'FSL']);
        Satuan::firstOrCreate(['nama_satuan' => 'Sach']);
        Satuan::firstOrCreate(['nama_satuan' => 'Pcs']);
    }
}
