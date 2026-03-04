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
        Schema::table('obats', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('gambar');
            $table->string('dosis_min')->nullable()->after('deskripsi');
            $table->string('dosis_max')->nullable()->after('dosis_min');
            $table->string('cara_pakai')->nullable()->after('dosis_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obats', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'dosis_min', 'dosis_max', 'cara_pakai']);
        });
    }
};
