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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_obat');
            $table->date('tanggal');
            $table->integer('jumlah')->default(0);
            $table->timestamps();
            
            $table->foreign('id_obat')->references('id')->on('obats')->onDelete('cascade');
            // Ensure unique combination of obat and date
            $table->unique(['id_obat', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
