<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('riwayat_stok_masuk', function (Blueprint $row) {
            $row->id();
            $row->unsignedBigInteger('id_pembelian_detail')->nullable();
            $row->unsignedBigInteger('id_obat');
            $row->integer('qty_masuk');
            $row->integer('harga_beli');
            $row->integer('harga_jual');
            $row->string('keterangan')->nullable(); 
            $row->timestamps();

           
            $row->foreign('id_obat')->references('id')->on('obats')->onDelete('cascade');
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok_masuk');
    }
};
