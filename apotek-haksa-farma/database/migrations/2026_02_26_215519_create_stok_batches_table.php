<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stok_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obat')->constrained('obats')->onDelete('cascade');
            $table->foreignId('id_pembelian')->constrained('pembelians')->onDelete('cascade');
            $table->string('no_batch');
            $table->date('tgl_expired');
            $table->integer('stok_awal');
            $table->integer('stok_sisa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_batches');
    }
};
