@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2 flex items-center gap-3">
        DATA OBAT
    </h2>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="dismissAlert('flash-success')" class="ml-4 text-green-700 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif

{{-- Category Tabs --}}
<div class="mb-8 border-b border-gray-100">
    <div class="flex items-center gap-10 overflow-x-auto whitespace-nowrap custom-scrollbar px-2">
        <a href="{{ route('obat.index') }}" 
           class="pb-4 text-sm font-extrabold transition-all relative {{ !request('kategori') ? 'text-black' : 'text-gray-400 hover:text-gray-600' }}">
           Semua
           @if(!request('kategori'))
               <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-black rounded-full"></div>
           @endif
        </a>
        @foreach($kategoris as $kat)
        <a href="{{ route('obat.index', ['kategori' => $kat->id]) }}" 
           class="pb-4 text-sm font-extrabold transition-all relative {{ request('kategori') == $kat->id ? 'text-black' : 'text-gray-400 hover:text-gray-600' }}">
           {{ strtoupper($kat->nama_kategori) }}
           @if(request('kategori') == $kat->id)
               <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-black rounded-full"></div>
           @endif
        </a>
        @endforeach
    </div>
</div>

<!-- Toolbar: Search and Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row items-center gap-3">
    <!-- Search Bar -->
    <div class="relative w-full sm:w-1/2 md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
        <form action="{{ route('obat.index') }}" method="GET" class="w-full flex">
            @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
            <input type="text" name="search" value="{{ request('search') }}" oninput="this.form.submit()" autofocus placeholder="Cari Obat....." class="w-full pl-4 pr-2 py-2 focus:outline-none text-sm">
            <button type="submit" class="px-3 flex items-center bg-gray-50 hover:bg-green-100 transition text-green-600 border-l border-gray-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    <!-- Group Action Buttons next to search -->
    <div class="flex flex-wrap items-center gap-2">
        <!-- Plus Obat Button -->
        <button type="button" onclick="openTambahModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg transition text-center shadow flex items-center justify-center gap-1.5 text-sm uppercase tracking-wide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
            Obat
        </button>

        <!-- Category Management Button -->
        <button type="button" onclick="openTambahKatModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg transition text-center shadow flex items-center justify-center gap-1.5 text-sm uppercase tracking-wide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
            Kategori
        </button>
    </div>
</div>

{{-- Table --}}
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max border border-gray-400 shadow-sm rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-4 px-4 font-bold text-gray-800 text-center w-16 border border-gray-300 uppercase text-xs tracking-wider">No</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Nama Barang</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Harga</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Satuan</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-24">Stok</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-24">Terjual</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-44">Tgl Kadaluarsa</th>
                <th class="py-4 px-6 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-28">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($obats as $index => $obat)
            <tr class="hover:bg-gray-50 transition text-sm">
                <td class="py-3 px-4 text-center text-gray-800 font-medium border border-gray-300">
                    {{ $obats->firstItem() + $index }}
                </td>
                <td class="py-3 px-5 text-center text-gray-800 font-bold uppercase border border-gray-300">
                    {{ $obat->nama_obat }}
                </td>
                <td class="py-3 px-5 text-center text-gray-900 font-bold border border-gray-300">
                    Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}
                </td>
                <td class="py-3 px-5 text-center text-gray-800 font-medium border border-gray-300">
                    {{ $obat->satuan->nama_satuan ?? '-' }}
                </td>
                <td class="py-3 px-5 text-center border border-gray-300 font-extrabold {{ (isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') ? 'text-gray-400 font-normal' : ($obat->total_stok <= 5 ? 'text-red-500' : 'text-gray-800') }}">
                    @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK')
                        -
                    @else
                        {{ $obat->total_stok }}
                    @endif
                </td>
                <td class="py-3 px-5 text-center border border-gray-300">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                        {{ $obat->total_terjual ?? 0 }}
                    </span>
                </td>
                <td class="py-3 px-5 text-center text-gray-900 font-bold border border-gray-300">
                    @if($obat->tanggal_kadaluarsa)
                        {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d-m-Y') }}
                    @else
                        <span class="text-gray-300">-</span>
                    @endif
                </td>
                <td class="py-3 px-6 border border-gray-300">
                    <div class="flex justify-center items-center gap-1">
                        <button type="button"
                            data-id="{{ $obat->id }}"
                            data-nama="{{ $obat->nama_obat }}"
                            data-id-kategori="{{ $obat->id_kategori }}"
                            data-harga-jual="{{ $obat->harga_jual }}"
                            data-stok="{{ $obat->total_stok }}"
                            data-id-satuan="{{ $obat->id_satuan }}"
                            data-kode-obat="{{ $obat->kode_obat }}"
                            data-harga-beli="{{ $obat->harga_beli }}"
                            data-expired-date="{{ $obat->tanggal_kadaluarsa ?? '' }}"
                            data-gambar="{{ $obat->gambar ? asset($obat->gambar) : '' }}"
                            onclick="openEditModal(this)"
                            class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button type="button"
                            onclick="openHapusModal({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}')"
                            class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-12 text-center text-gray-400 italic border border-gray-300">Data obat belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Section -->
<div class="mt-8 mb-10 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
    <div class="text-xs text-gray-400 italic">
        * Menampilkan seluruh data obat dan stok yang tersedia.
    </div>
    <div class="flex gap-2">
        @if($obats->onFirstPage())
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                &#9664; Back
            </span>
        @else
            <a href="{{ $obats->previousPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                &#9664; Back
            </a>
        @endif

        @if($obats->hasMorePages())
            <a href="{{ $obats->nextPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                Next &#9654;
            </a>
        @else
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                Next &#9654;
            </span>
        @endif
    </div>
</div>

{{-- Shared Modals --}}
@include('obat._modals')

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection
