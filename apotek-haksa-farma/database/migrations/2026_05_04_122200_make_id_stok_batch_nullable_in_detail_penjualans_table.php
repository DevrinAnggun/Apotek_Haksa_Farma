<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('detail_penjualans', function (Blueprint $table) {
            $table->unsignedBigInteger('id_stok_batch')->nullable()->change();
        });
    }


    public function down(): void
    {
        Schema::table('detail_penjualans', function (Blueprint $table) {
            $table->unsignedBigInteger('id_stok_batch')->nullable(false)->change();
        });
    }
};
