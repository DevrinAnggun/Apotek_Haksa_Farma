<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supliers';

    protected $fillable = [
        'nama_suplier',
        'kontak',
        'alamat',
    ];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_suplier');
    }
}
