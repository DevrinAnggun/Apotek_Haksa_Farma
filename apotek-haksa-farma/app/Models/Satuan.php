<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satuan extends Model
{
    use SoftDeletes;
    protected $fillable = ['nama_satuan'];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'id_satuan');
    }
}
