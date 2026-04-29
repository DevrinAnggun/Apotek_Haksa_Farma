@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
        <!-- Icon home mini -->
        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Dashboard
    </h2>
</div>


@if(session('error'))
    <div id="flash-error" class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between animate-modal">
        <div class="flex items-center">
            <div class="bg-red-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        <button onclick="dismissAlert('flash-error')" class="text-red-500 hover:text-red-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

<!-- Welcome Notification Banner -->
<div id="welcome-banner" class="flex justify-between items-center bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm mb-8 transition-opacity duration-300">
    <div class="flex items-center">
        <div class="text-sm border-l-0">
            <span class="font-bold text-green-800">Halo {{ auth()->user()->nama ?? 'Admin' }},</span> 
            <span class="text-green-700">Selamat Datang di Website Apotek Haksa Farma</span>
        </div>
    </div>
    <!-- Close cross -->
    <button onclick="dismissAlert('welcome-banner')" class="text-green-500 hover:text-green-800 focus:outline-none ml-4 text-xl font-bold">&times;</button>
</div>

<!-- Grid System for all cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Card: Data Barang → Emerald -->
    <a href="{{ route('obat.index') }}" class="block bg-emerald-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer h-44 flex flex-col justify-between overflow-hidden relative group">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-7 h-7 text-emerald-100 opacity-90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="text-3xl font-extrabold mb-1">{{ number_format($totalDataBarang ?? 0, 0, ',', '.') }}</div>
            <div class="text-emerald-100 font-semibold text-base tracking-wide uppercase">Data Obat</div>
        </div>
        <div class="relative z-10 flex items-center text-[10px] font-bold bg-white bg-opacity-10 py-1.5 px-3 rounded-lg w-max hover:bg-opacity-20 transition">
            Lihat Data &amp; Stok <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <!-- bg icon abstrak -->
        <svg class="absolute -bottom-4 -right-4 w-28 h-28 text-white opacity-20 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
    </a>
    
    <!-- Card: Transaksi Penjualan → Indigo -->
    <a href="{{ route('kasir.pos') }}" class="block bg-indigo-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer h-44 flex flex-col justify-between overflow-hidden relative group">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-7 h-7 text-indigo-100 opacity-90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="text-indigo-100 font-semibold text-lg tracking-wide uppercase">Transaksi Penjualan</div>
        </div>
        <div class="relative z-10 flex items-center text-[10px] font-bold bg-white bg-opacity-10 py-1.5 px-3 rounded-lg w-max hover:bg-opacity-20 transition">
            Buka Kasir <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <!-- bg icon abstrak -->
        <svg class="absolute -bottom-4 -right-4 w-28 h-28 text-white opacity-20 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" clip-rule="evenodd"/></svg>
    </a>
    
    <!-- Card: Stok Supplier → Green -->
    <a href="{{ route('pembelian.index') }}" class="block bg-green-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer h-44 flex flex-col justify-between overflow-hidden relative group">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-7 h-7 text-green-100 opacity-90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div class="text-green-100 font-semibold text-base tracking-wide uppercase">Supplier</div>
        </div>
        <div class="relative z-10 flex items-center text-[10px] font-bold bg-white bg-opacity-10 py-1.5 px-3 rounded-lg w-max hover:bg-opacity-20 transition">
            Lihat Stok Masuk <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <!-- bg icon abstrak -->
        <svg class="absolute -bottom-4 -right-4 w-28 h-28 text-white opacity-20 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 7h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zM10 4h4v3h-4V4zm10 16H4V9h16v11z"/>
        </svg>
    </a>

    <!-- Card: Barang Kadaluarsa → Orange -->
    <a href="{{ route('kadaluarsa.index') }}" class="block bg-orange-500 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer overflow-hidden relative group h-44 flex flex-col justify-between">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-7 h-7 text-orange-100 opacity-90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="text-4xl font-extrabold mb-1">{{ number_format($jumlahObatKadaluarsa ?? 0, 0, ',', '.') }}</div>
            <div class="text-orange-50 font-semibold text-base tracking-wide uppercase">Barang Kadaluarsa</div>
        </div>
        <div class="flex items-center text-[10px] font-bold bg-white bg-opacity-10 py-1.5 px-3 rounded-lg w-max hover:bg-opacity-20 transition relative z-10">
            Lihat Data Kadaluarsa <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <!-- bg icon abstrak -->
        <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-white opacity-20 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
    </a>

    <!-- Card: Semua Penjualan (Rupiah) → Purple -->
    <a href="{{ route('laporan.penjualan') }}" class="block bg-purple-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer relative overflow-hidden group h-44 flex flex-col justify-between">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-7 h-7 text-purple-100 opacity-90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="text-2xl font-extrabold mb-1 break-all">Rp. {{ number_format($totalSemuaPenjualan ?? 0, 0, ',', '.') }}</div>
            <div class="text-purple-100 font-semibold text-base tracking-wide uppercase">Semua Penjualan</div>
        </div>
        <div class="flex items-center text-[10px] font-bold bg-white bg-opacity-10 py-1.5 px-3 rounded-lg w-max hover:bg-opacity-20 transition relative z-10">
            Lihat Data Penjualan <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <!-- Abstract BG Decoration -->
        <svg class="absolute -bottom-4 -right-4 w-28 h-28 text-white opacity-20 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z"/><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" clip-rule="evenodd"/></svg>
    </a>

    <!-- Card: Unduh Laporan → Blue -->
    <div onclick="openReportModal()" class="block bg-blue-600 rounded-xl shadow p-6 text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-200 cursor-pointer overflow-hidden relative group h-44 flex flex-col justify-between">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-7 h-7 text-blue-100 opacity-90 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="text-blue-100 font-semibold text-base tracking-wide uppercase">Unduh Laporan</div>
        </div>
        <div class="flex items-center text-[10px] font-bold bg-white bg-opacity-10 py-1.5 px-3 rounded-lg w-max hover:bg-opacity-20 transition relative z-10">
            Buka Menu Laporan <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
        <!-- bg icon abstrak -->
        <svg class="absolute -bottom-4 -right-4 w-28 h-28 text-white opacity-20 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/><path d="M7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg>
    </div>
</div>

@endsection

@section('modals')
{{-- ===== MODAL OPSI UNDUH LAPORAN ===== --}}
<div id="reportModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden" style="display: none;">
    <div class="absolute inset-0 bg-black bg-opacity-60" onclick="closeReportModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden flex flex-col max-h-[90vh]" x-data="{ reportType: 'penjualan', showMonthly: false, kadaluarsaType: 'stok', idObat: '' }">
        <!-- Header -->
        <div class="bg-blue-700 px-6 py-6 flex items-center justify-between text-white rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-tight uppercase tracking-wide text-white">Unduh Laporan</h3>
                </div>
            </div>
            <button onclick="closeReportModal()" class="text-white/80 hover:text-white transition text-2xl leading-none">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <div class="mb-6">
                <label class="block text-[10px] text-gray-400 font-bold uppercase mb-2 ml-1 tracking-widest">Pilih Tipe Laporan</label>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="reportType = 'penjualan'" 
                            :class="reportType === 'penjualan' ? 'bg-blue-600 text-white shadow-md scale-105' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="py-2.5 rounded-xl text-[10px] font-extrabold uppercase tracking-tighter transition-all">
                        Penjualan
                    </button>
                    <button @click="reportType = 'pembelian'" 
                            :class="reportType === 'pembelian' ? 'bg-blue-600 text-white shadow-md scale-105' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="py-2.5 rounded-xl text-[10px] font-extrabold uppercase tracking-tighter transition-all">
                        Stok Masuk
                    </button>
                    <button @click="reportType = 'kadaluarsa'" 
                            :class="reportType === 'kadaluarsa' ? 'bg-orange-500 text-white shadow-md scale-105' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="py-2.5 rounded-xl text-[10px] font-extrabold uppercase tracking-tighter transition-all">
                        Kadaluarsa
                    </button>
                    <button @click="reportType = 'retur'" 
                            :class="reportType === 'retur' ? 'bg-blue-600 text-white shadow-md scale-105' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                            class="py-2.5 rounded-xl text-[10px] font-extrabold uppercase tracking-tighter transition-all">
                        Retur Obat
                    </button>
                </div>
            </div>

            <div x-show="true" x-transition>
                <!-- Pilihan Cepat (Non-Kadaluarsa & Non-Retur) -->
                <div x-show="reportType !== 'kadaluarsa' && reportType !== 'retur'" class="grid grid-cols-2 gap-3 mb-6">
                    <!-- Harian -->
                    <button type="button" @click="downloadQuick('daily', reportType)"
                       class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-xl border-2 border-blue-100 hover:border-blue-500 hover:shadow-md transition-all group">
                        <div class="bg-blue-100 p-2.5 rounded-full mb-2 text-blue-600 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="font-bold text-blue-700 text-[11px] uppercase">Hari Ini</span>
                    </button>
                    
                    <!-- Toggle Bulanan -->
                    <button type="button" @click="showMonthly = !showMonthly"
                       class="flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all group"
                       :class="showMonthly ? 'bg-blue-600 border-blue-600 shadow-lg scale-105' : 'bg-blue-50 border-blue-100 hover:border-blue-500'">
                        <div class="p-2.5 rounded-full mb-2 transition-transform group-hover:scale-110"
                             :class="showMonthly ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="font-bold text-[11px] uppercase" :class="showMonthly ? 'text-white' : 'text-blue-700'">Bulanan</span>
                    </button>
                </div>

                <!-- Pilihan Cepat (Khusus Kadaluarsa) -->
                <div x-show="reportType === 'kadaluarsa'" class="grid grid-cols-2 gap-3 mb-6" style="display: none;">
                    <button type="button" @click="kadaluarsaType = 'stok'" 
                       class="flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all group relative"
                       :class="kadaluarsaType === 'stok' ? 'bg-orange-500 border-orange-500 shadow-md scale-105' : 'bg-orange-50 border-orange-100 hover:border-orange-500'">

                       <div class="p-2.5 rounded-full mb-2 transition-transform group-hover:scale-110"
                            :class="kadaluarsaType === 'stok' ? 'bg-white/20' : 'bg-orange-100 text-orange-600'">
                            <svg class="w-5 h-5" :class="kadaluarsaType === 'stok' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                       </div>
                        <span class="font-bold text-[10px] uppercase text-center tracking-normal leading-relaxed" :class="kadaluarsaType === 'stok' ? 'text-white' : 'text-orange-700'">Data Stok<br>Kadaluarsa</span>
                    </button>
                    
                    <button type="button" @click="kadaluarsaType = 'penjualan'" 
                       class="flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-all group relative"
                       :class="kadaluarsaType === 'penjualan' ? 'bg-orange-500 border-orange-500 shadow-md scale-105' : 'bg-orange-50 border-orange-100 hover:border-orange-500'">

                       <div class="p-2.5 rounded-full mb-2 transition-transform group-hover:scale-110"
                            :class="kadaluarsaType === 'penjualan' ? 'bg-white/20' : 'bg-orange-100 text-orange-600'">
                            <svg class="w-5 h-5" :class="kadaluarsaType === 'penjualan' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                       </div>
                        <span class="font-bold text-[10px] uppercase text-center tracking-normal leading-relaxed mt-1" :class="kadaluarsaType === 'penjualan' ? 'text-white' : 'text-orange-700'">Penjualan Sblm<br>Expired</span>
                    </button>
                </div>
                
                <!-- Filter Obat Tertentu (Hanya muncul jika tipenya Penjualan Sblm Expired atau Retur) -->
                <div x-show="(reportType === 'kadaluarsa' && kadaluarsaType === 'penjualan') || reportType === 'retur'" x-transition class="mb-4 animate-fadeIn">
                    <label class="block text-[10px] text-gray-400 font-bold uppercase mb-2 ml-1 tracking-widest">Filter Barang Tertentu (Opsional)</label>
                    <select x-model="idObat" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:ring-2 focus:ring-blue-500 font-bold uppercase text-gray-700">
                        <option value="" class="normal-case">-- Semua Obat --</option>
                        @foreach($obats as $o)
                            @if(isset($o->kategori) && strtoupper($o->kategori->nama_kategori) === 'CEK') @continue @endif
                            <option value="{{ $o->id }}">{{ $o->nama_obat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Download Per Bulan (Hidden by default) -->
                <div x-show="(showMonthly && reportType !== 'kadaluarsa')" x-transition class="bg-blue-50 rounded-xl p-4 border border-blue-200 mb-2 animate-fadeIn">
                    <header class="flex items-center gap-2 mb-3">
                        <span class="text-[10px] font-bold text-blue-700 uppercase tracking-widest">Pilih Bulan & Tahun</span>
                    </header>
                    <div class="flex items-center gap-2">
                        <select id="dash_month" class="flex-1 bg-white border border-gray-200 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        <select id="dash_year" class="w-20 bg-white border border-gray-200 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @for($y=2024; $y<=2040; $y++)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="button" @click="downloadDashMonthly(reportType)"
                            class="bg-blue-600 hover:bg-black text-white p-2 rounded-lg transition shadow flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Opsi Semua Riwayat / Custom Date (Hanya untuk Retur) -->
                <div x-show="reportType === 'retur'" class="mb-6">
                    <button type="button" @click="downloadCustom(reportType)"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold text-[10px] uppercase tracking-widest transition shadow-lg mt-2 flex items-center justify-center gap-2 hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Unduh Semua Riwayat
                    </button>
                </div>

                <!-- Filter Custom -->
                <div x-show="reportType !== 'retur'" class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <header class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span class="text-sm font-bold text-gray-700 uppercase tracking-wider">Filter Tanggal Spesifik</span>
                    </header>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1 ml-1 tracking-widest">Dari</label>
                                <input type="date" id="cust_start" required value="{{ date('Y-m-d') }}"
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1 ml-1 tracking-widest">Sampai</label>
                                <input type="date" id="cust_end" required value="{{ date('Y-m-d') }}"
                                    class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>
                        </div>
                        <button type="button" @click="downloadCustom(reportType, kadaluarsaType)"
                            :class="reportType === 'kadaluarsa' ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-600 hover:bg-blue-700'"
                            class="w-full text-white py-3 rounded-lg font-bold text-xs uppercase tracking-widest transition shadow-lg mt-2 flex items-center justify-center gap-2 hover:scale-[1.02]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export PDF
                        </button>
                    </div>
                </div>
            </div>

            </div>

        <!-- Footer Info -->
        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-100">
            <p class="text-[10px] text-gray-400 font-medium">Laporan akan secara otomatis terunduh dalam format PDF.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openReportModal() {
        const modal = document.getElementById('reportModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeReportModal() {
        const modal = document.getElementById('reportModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    function downloadDashMonthly(type, kadaluarsaType) {
        const m = document.getElementById('dash_month').value;
        const y = document.getElementById('dash_year').value;
        const startDate = `${y}-${m.padStart(2, '0')}-01`;
        const lastDay = new Date(y, m, 0).getDate();
        const endDate = `${y}-${m.padStart(2, '0')}-${lastDay}`;
        
        let route;
        if (type === 'penjualan') route = '{{ route("laporan.cetak_pdf") }}';
        else if (type === 'pembelian') route = '{{ route("pembelian.cetak_pdf") }}';
        else if (type === 'retur') route = '{{ route("laporan.retur_pdf") }}';
        else if (type === 'kadaluarsa') {
             route = kadaluarsaType === 'stok' ? '{{ route("kadaluarsa.pdf") }}' : '{{ route("laporan.penjualan_sebelum_kadaluarsa_pdf") }}';
        }

        const idObat = document.querySelector('[x-model="idObat"]').value;
        const idObatParam = idObat ? `&id_obat=${idObat}` : '';

        window.open(`${route}?start_date=${startDate}&end_date=${endDate}${idObatParam}`, '_blank');
    }

    function downloadQuick(mode, type) {
        let route;
        if (type === 'penjualan') route = '{{ route("laporan.cetak_pdf") }}';
        else if (type === 'pembelian') route = '{{ route("pembelian.cetak_pdf") }}';
        else if (type === 'retur') route = '{{ route("laporan.retur_pdf") }}';
        else if (type === 'kadaluarsa') {
             route = '{{ route("laporan.penjualan_sebelum_kadaluarsa_pdf") }}';
        }

        const date = new Date().toISOString().split('T')[0];
        const idObat = document.querySelector('[x-model="idObat"]').value;
        const idObatParam = idObat ? `&id_obat=${idObat}` : '';

        window.open(`${route}?start_date=${date}&end_date=${date}${idObatParam}`, '_blank');
    }

    function downloadCustom(type, kadaluarsaType) {
        const s = document.getElementById('cust_start').value;
        const e = document.getElementById('cust_end').value;
        
        let route;
        if (type === 'penjualan') route = '{{ route("laporan.cetak_pdf") }}';
        else if (type === 'pembelian') route = '{{ route("pembelian.cetak_pdf") }}';
        else if (type === 'retur') route = '{{ route("laporan.retur_pdf") }}';
        else if (type === 'kadaluarsa') {
             route = kadaluarsaType === 'stok' ? '{{ route("kadaluarsa.pdf") }}' : '{{ route("laporan.penjualan_sebelum_kadaluarsa_pdf") }}';
        }

        const idObat = document.querySelector('[x-model="idObat"]').value;
        const idObatParam = idObat ? `&id_obat=${idObat}` : '';

        if (type === 'retur' && !s && !e) {
             window.open(`${route}?all=true${idObatParam}`, '_blank');
             return;
        }

        window.open(`${route}?start_date=${s}&end_date=${e}${idObatParam}`, '_blank');
    }
</script>
@endpush
