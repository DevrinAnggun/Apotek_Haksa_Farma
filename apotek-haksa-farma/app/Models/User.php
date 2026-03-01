<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'id_user');
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'id_user');
    }
}
