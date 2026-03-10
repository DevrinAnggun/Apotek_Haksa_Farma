@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight uppercase">KATALOG PRODUK</h2>
    </div>
    <div class="flex gap-4">
        <button type="button" onclick="openTambahKatalogModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Katalog
        </button>
    </div>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-8 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button onclick="dismissAlert('flash-success')" class="text-green-500 hover:text-green-700 font-bold text-xl leading-none">&times;</button>
    </div>
@endif

<div class="flex flex-col md:flex-row gap-8">
    {{-- Sidebar Kategori --}}
    <div class="w-full md:w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
            <div class="bg-gray-50 px-5 py-4 border-b border-gray-100">
                <p class="text-gray-800 font-extrabold text-xs uppercase tracking-widest text-center">Filter Kategori</p>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('obat.katalog') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ !request('kategori') ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span>Semua Produk</span>
                   <span class="{{ !request('kategori') ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ \App\Models\Obat::count() }}</span>
                </a>
                @foreach($kategoris as $kat)
                <a href="{{ route('obat.katalog', ['kategori' => $kat->id]) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ request('kategori') == $kat->id ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span class="truncate">{{ $kat->nama_kategori }}</span>
                   <span class="{{ request('kategori') == $kat->id ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ $kat->obats_count ?? $kat->obats()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Grid Content --}}
    <div class="flex-1 space-y-6">
        {{-- Search --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex items-center gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <form action="{{ route('obat.katalog') }}" method="GET">
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di katalog produk..." 
                           class="w-full pl-12 pr-4 py-3 bg-transparent border-none focus:ring-0 text-sm font-medium text-gray-700" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($obats as $obat)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative">
                <div class="absolute top-3 left-3 z-10">
                    <span class="bg-white/90 backdrop-blur shadow-sm text-green-700 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-100">
                        {{ $obat->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                </div>

                <div class="absolute top-3 right-3 z-20 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300">
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
                        class="p-2.5 bg-white text-blue-600 rounded-xl shadow-lg border border-blue-50 hover:bg-blue-600 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <button type="button"
                        onclick="openHapusModal({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}')"
                        class="p-2.5 bg-white text-red-600 rounded-xl shadow-lg border border-red-50 hover:bg-red-600 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>

                <div class="h-44 bg-gray-50 flex items-center justify-center relative overflow-hidden">
                    @if($obat->gambar)
                        <img src="{{ asset($obat->gambar) }}" class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-50 to-green-100 flex flex-col items-center justify-center gap-2 text-green-300 font-bold uppercase text-[10px]">
                            No Product Photo
                        </div>
                    @endif
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-sm font-extrabold text-gray-800 uppercase leading-snug tracking-tight mb-4 line-clamp-2 min-h-[40px]">
                        {{ $obat->nama_obat }}
                    </h3>
                    
                    <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Harga Pelanggan</p>
                            <p class="text-base font-extrabold text-green-700">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Stok</p>
                            <p class="text-sm font-extrabold text-gray-700">{{ $obat->total_stok }} <span class="text-[10px] text-gray-400">Pcs</span></p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center text-gray-400">
                Tidak ada produk untuk ditampilkan di katalog.
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                Showing {{ $obats->firstItem() ?? 0 }}-{{ $obats->lastItem() ?? 0 }} of {{ $obats->total() }} results
            </p>
            <div>
                {{ $obats->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

{{-- Shared Modals --}}
@include('obat._modals')
@endsection
