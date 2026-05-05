<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('retur_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembelian')->constrained('pembelians')->onDelete('cascade');
            $table->foreignId('id_obat')->constrained('obats')->onDelete('cascade');
            $table->integer('qty_retur');
            $table->date('tgl_retur');
            $table->string('alasan');
            $table->integer('nominal_potongan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur_pembelians');
    }
};
