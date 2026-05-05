<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Obat extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id_kategori',
        'id_satuan',
        'kode_obat',
        'nama_obat',
        'harga_beli',
        'harga_jual',
        'batas_stok_minimal',
        'gambar',
        'deskripsi',
        'cara_pakai',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }

    public function pembelianDetails()
    {
        return $this->hasMany(DetailPembelian::class, 'id_obat');
    }

    public function penjualanDetails()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_obat');
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class, 'id_obat');
    }

    public function returPembelians()
    {
        return $this->hasMany(ReturPembelian::class, 'id_obat');
    }

    public function stokBatches()
    {
        return $this->hasMany(StokBatch::class, 'id_obat');
    }

    public function getTotalStokAttribute()
    {
        // Hanya hitung stok yang BELUM kadaluarsa
        return $this->stokBatches()
            ->where('tgl_expired', '>=', date('Y-m-d'))
            ->sum('stok_sisa');
    }

    public function getTanggalKadaluarsaAttribute()
    {
        // 1. Prioritaskan stok yang BELUM kadaluarsa
        $validBatch = $this->stokBatches()
            ->where('stok_sisa', '>', 0)
            ->where('tgl_expired', '>=', date('Y-m-d'))
            ->orderBy('tgl_expired', 'asc')
            ->first();

        if ($validBatch) {
            return $validBatch->tgl_expired;
        }
        
        // 2. Jika semua stok sisa sudah kadaluarsa, ambil yang paling mendekati sekarang
        $expiredBatch = $this->stokBatches()
            ->where('stok_sisa', '>', 0)
            ->orderBy('tgl_expired', 'asc')
            ->first();

        if ($expiredBatch) {
            return $expiredBatch->tgl_expired;
        }

        // 3. Fallback jika tidak ada stok sisa sama sekali
        $anyBatch = $this->stokBatches()->orderBy('tgl_expired', 'asc')->first();
        return $anyBatch ? $anyBatch->tgl_expired : null;
    }
}
