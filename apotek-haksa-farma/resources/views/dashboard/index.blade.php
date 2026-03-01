@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
        <!-- Icon home mini -->
        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Dashboard
    </h2>
</div>

<!-- Welcome Notification Banner -->
<div id="welcome-banner" class="flex justify-between items-center bg-green-50 border border-green-200 p-4 rounded-lg shadow-sm mb-8 transition-opacity duration-300">
    <div class="text-gray-700">
        <span class="font-bold text-green-800">Halo {{ auth()->user()->nama ?? 'Admin' }},</span> Selamat Datang di Website Apotek Haksa Farma
    </div>
    <!-- Close cross -->
    <button onclick="dismissAlert('welcome-banner')" class="text-gray-400 hover:text-green-800 focus:outline-none ml-4 text-xl font-bold">&times;</button>
</div>

<!-- Grid System for the cards -->
<!-- Baris 1: 3 Kolom -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    
    <!-- Card: Data Barang → ke halaman Data & Stok -->
    <a href="{{ route('obat.index') }}" class="block bg-green-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer">
        <div class="text-4xl font-extrabold mb-1">{{ number_format($totalDataBarang ?? 0, 0, ',', '.') }}</div>
        <div class="text-green-100 font-semibold text-lg tracking-wide uppercase">Data Barang</div>
        <div class="text-green-200 text-xs mt-2 flex items-center gap-1">Lihat Data &amp; Stok <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
    </a>
    
    <!-- Card: Penjualan Total Count → ke halaman Penjualan -->
    <a href="{{ route('laporan.penjualan') }}" class="block bg-green-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer">
        <div class="text-4xl font-extrabold mb-1">{{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</div>
        <div class="text-green-100 font-semibold text-lg tracking-wide uppercase">Penjualan</div>
        <div class="text-green-200 text-xs mt-2 flex items-center gap-1">Lihat Data Penjualan <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
    </a>
    
    <!-- Card: Penjualan Hari Ini → ke halaman Penjualan -->
    <a href="{{ route('laporan.penjualan') }}" class="block bg-green-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer">
        <div class="text-3xl font-extrabold mb-2 break-all">Rp. {{ number_format($totalPendapatanHariIni ?? 0, 0, ',', '.') }}</div>
        <div class="text-green-100 font-semibold text-sm md:text-md tracking-wide uppercase">Penjualan Hari Ini</div>
        <div class="text-green-200 text-xs mt-2 flex items-center gap-1">Lihat Data Penjualan <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
    </a>

</div>

<!-- Baris 2: 2 Kolom Rata Kiri -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <!-- Card: Barang Kadaluarsa → ke halaman Data Kadaluarsa -->
    <a href="{{ route('kadaluarsa.index') }}" class="block bg-orange-500 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer overflow-hidden relative">
        <div class="text-4xl font-extrabold mb-1 relative z-10">{{ number_format($jumlahObatKadaluarsa ?? 0, 0, ',', '.') }}</div>
        <div class="text-orange-50 font-semibold text-lg tracking-wide uppercase relative z-10">Barang Kadaluarsa</div>
        <div class="text-orange-100 text-xs mt-2 flex items-center gap-1 relative z-10">Lihat Data Kadaluarsa <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
        <!-- bg icon abstrak -->
        <svg class="absolute -bottom-4 -right-4 w-28 h-28 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
    </a>

    <!-- Card: Semua Penjualan (Rupiah) → ke halaman Penjualan -->
    <a href="{{ route('laporan.penjualan') }}" class="block bg-green-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer">
        <div class="text-3xl font-extrabold mb-2 break-all">Rp. {{ number_format($totalSemuaPenjualan ?? 0, 0, ',', '.') }}</div>
        <div class="text-green-100 font-semibold text-md tracking-wide uppercase">Semua Penjualan</div>
        <div class="text-green-200 text-xs mt-2 flex items-center gap-1">Lihat Data Penjualan <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></div>
    </a>

</div>

@endsection
