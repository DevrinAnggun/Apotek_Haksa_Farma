<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $fillable = [
        'id_penjualan',
        'id_obat',
        'id_stok_batch',
        'qty',
        'harga_jual',
        'subtotal',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function stokBatch()
    {
        return $this->belongsTo(StokBatch::class, 'id_stok_batch');
    }
}
