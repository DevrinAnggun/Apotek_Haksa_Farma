<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'id_user',
        'no_invoice',
        'tgl_penjualan',
        'total_harga',
        'nominal_bayar',
        'kembalian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }
}
