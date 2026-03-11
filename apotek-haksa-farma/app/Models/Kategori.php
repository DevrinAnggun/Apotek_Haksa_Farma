<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use SoftDeletes;
    protected $fillable = ['nama_kategori'];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'id_kategori');
    }
}
