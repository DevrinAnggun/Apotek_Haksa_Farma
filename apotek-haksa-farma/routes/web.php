<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KadaluarsaController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PembelianController; // Import PembelianController

// ===== HALAMAN PUBLIK USER (tanpa login) =====
Route::get('/publik/katalog', [PublicController::class, 'katalog'])->name('publik.katalog');
Route::get('/publik/artikel', [PublicController::class, 'artikel'])->name('publik.artikel');
Route::get('/publik/artikel/{slug}', [PublicController::class, 'bacaArtikel'])->name('publik.artikel.detail');
Route::get('/publik/kontak',  [PublicController::class, 'kontak'])->name('publik.kontak');


// Rute Publik (Belum Login)
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rute Wajib Login
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile & Password Update (Bisa Admin maupun Kasir)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('kategori', KategoriController::class);
        Route::resource('supplier', SupplierController::class);
        Route::resource('obat', ObatController::class);
        Route::get('/kadaluarsa/pdf', [KadaluarsaController::class, 'cetakPdf'])->name('kadaluarsa.pdf');
        Route::resource('kadaluarsa', KadaluarsaController::class);
        Route::get('/pembelian/riwayat/{id_detail}', [PembelianController::class, 'getRiwayat'])->name('pembelian.riwayat');
        Route::resource('pembelian', PembelianController::class);

        // Laporan & Print
        Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/penjualan/pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak_pdf');
        Route::get('/laporan/pembelian/pdf', [PembelianController::class, 'cetakPdf'])->name('pembelian.cetak_pdf');
        Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
        // Content Management
        Route::resource('artikel', \App\Http\Controllers\ArtikelController::class);
        Route::get('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    // Admin & Kasir Bisa Transaksi
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::get('/kasir/pos', [PenjualanController::class, 'create'])->name('kasir.pos');
        Route::post('/kasir/transaksi', [PenjualanController::class, 'store'])->name('kasir.store');
        Route::get('/kasir/riwayat', [PenjualanController::class, 'index']); // Hanya lihat riwayat dirinya
    });
});

