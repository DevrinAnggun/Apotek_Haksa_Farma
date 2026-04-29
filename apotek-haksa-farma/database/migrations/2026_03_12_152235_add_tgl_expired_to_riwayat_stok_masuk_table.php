<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('riwayat_stok_masuk', function (Blueprint $table) {
            $table->date('tgl_expired')->nullable()->after('harga_jual');
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_stok_masuk', function (Blueprint $table) {
            $table->dropColumn('tgl_expired');
        });
    }
};
