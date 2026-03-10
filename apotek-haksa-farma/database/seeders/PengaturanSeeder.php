<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'kontak_telepon' => '0812-3456-7890',
            'kontak_email'   => 'apotek.haksafarma@gmail.com',
            'kontak_alamat'  => 'Jl. Raya Apotek No. 123, Kota Sehat, Indonesia',
            'kontak_jam_buka' => 'Senin - Sabtu: 08:00 - 21:00',
            'kontak_maps'    => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.11111111111!2d106.8451!3d-6.2088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMzEuNyJTIDEwNsKwNTAnNDIuNCJF!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
