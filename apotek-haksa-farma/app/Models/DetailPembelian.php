<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $fillable = [
        'id_pembelian',
        'id_obat',
        'qty',
        'harga_beli',
        'subtotal',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatStokMasuk::class, 'id_pembelian_detail');
    }
}
