<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $fillable = [
        'id_suplier',
        'id_user',
        'no_faktur',
        'tgl_pembelian',
        'total_bayar',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_suplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'id_pembelian');
    }
    
    public function stokBatches()
    {
        return $this->hasMany(StokBatch::class, 'id_pembelian');
    }
}
