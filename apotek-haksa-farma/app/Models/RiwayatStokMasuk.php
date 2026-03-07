<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStokMasuk extends Model
{
    use HasFactory;

    protected $table = 'riwayat_stok_masuk';

    protected $fillable = [
        'id_pembelian_detail',
        'id_obat',
        'qty_masuk',
        'harga_beli',
        'harga_jual',
        'keterangan'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function detailPembelian()
    {
        return $this->belongsTo(DetailPembelian::class, 'id_pembelian_detail');
    }
}
