<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBatch extends Model
{
    protected $fillable = [
        'id_obat',
        'id_pembelian',
        'no_batch',
        'tgl_expired',
        'stok_awal',
        'stok_sisa',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat')->withTrashed();
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_stok_batch');
    }
}
