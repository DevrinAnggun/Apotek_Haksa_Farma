<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = ['id_obat', 'tanggal', 'jumlah'];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
