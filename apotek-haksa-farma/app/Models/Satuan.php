<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $fillable = ['nama_satuan'];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'id_satuan');
    }
}
