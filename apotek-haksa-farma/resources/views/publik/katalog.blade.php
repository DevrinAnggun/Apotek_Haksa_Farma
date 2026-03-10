@extends('layouts.publik')

@section('title', 'Katalog Produk')
@section('meta_desc', 'Temukan berbagai produk obat dan suplemen di Apotek Haksa Farma Banjarnegara.')

@section('content')

{{-- ===== HERO SLIDER ===== --}}
<div class="relative w-full overflow-hidden" style="height: 280px;" id="heroSlider">

    {{-- Slide 1 --}}
    <div class="slider-slide absolute inset-0 transition-opacity duration-700 opacity-100" data-index="0">
        <img src="{{ asset('images/haksa1.png') }}" alt="Apotek Haksa Farma Eksterior"
            class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-r from-green-900/70 to-black/30 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-2xl md:text-4xl font-extrabold tracking-wide mb-2 drop-shadow-lg">APOTEK HAKSA FARMA</h1>
                <p class="text-sm md:text-base text-green-100 font-medium drop-shadow">Solusi Kesehatan Keluarga Anda — Banjarnegara</p>
            </div>
        </div>
    </div>

    {{-- Slide 2 --}}
    <div class="slider-slide absolute inset-0 transition-opacity duration-700 opacity-0" data-index="1">
        <img src="{{ asset('images/haksa2.jpg') }}" alt="Apotek Haksa Farma Interior"
            class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-green-900/40 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-2xl md:text-4xl font-extrabold tracking-wide mb-2 drop-shadow-lg">Lengkap & Terpercaya</h1>
                <p class="text-sm md:text-base text-green-100 font-medium drop-shadow">Ribuan produk kesehatan siap melayani Anda</p>
            </div>
        </div>
    </div>

    {{-- Tombol Prev --}}
    <button onclick="sliderPrev()" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 text-white w-9 h-9 rounded-full flex items-center justify-center transition backdrop-blur-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
    </button>

    {{-- Tombol Next --}}
    <button onclick="sliderNext()" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 text-white w-9 h-9 rounded-full flex items-center justify-center transition backdrop-blur-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- Dot Indicators --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
        <button onclick="sliderGoTo(0)" class="slider-dot w-2.5 h-2.5 rounded-full bg-white transition" data-dot="0"></button>
        <button onclick="sliderGoTo(1)" class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 transition" data-dot="1"></button>
    </div>

    {{-- Gelombang bawah --}}
    <div class="absolute bottom-0 left-0 right-0 h-8 bg-white" style="clip-path: ellipse(55% 100% at 50% 100%); z-index:10;"></div>
</div>


{{-- ===== SECTION: KATEGORI IKON ===== --}}
<div class="max-w-6xl mx-auto px-4 mt-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-center font-extrabold text-gray-800 uppercase tracking-wider text-base mb-6">Katalog Produk</h2>

        {{-- Ikon Kategori --}}
        <div class="flex flex-wrap justify-center gap-4 md:gap-8 mb-6">
            @php
                $ikonKategori = [
                    ['label' => 'Obat',          'svg' => '<path d="M9 3H4a1 1 0 00-1 1v4a1 1 0 001 1h1l1 9h6l1-9h1a1 1 0 001-1V4a1 1 0 00-1-1h-5zm0 0V1m0 2v2m3-2v2"/>'],
                    ['label' => 'Herbal',        'svg' => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>'],
                    ['label' => 'Suplemen',      'svg' => '<path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
                    ['label' => 'Produk Bayi',   'svg' => '<path d="M12 2a5 5 0 015 5v2a5 5 0 01-10 0V7a5 5 0 015-5zm-7 9a7.001 7.001 0 0013.444-2.75A5 5 0 0112 17a5 5 0 01-7.444-8.75z"/>'],
                    ['label' => 'Kecantikan',    'svg' => '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>'],
                    ['label' => 'Alat Kesehatan','svg' => '<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
                    ['label' => 'Mata',          'svg' => '<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'],
                ];
            @endphp

            @foreach($ikonKategori as $ikon)
            <a href="{{ route('publik.katalog', ['search' => $ikon['label']]) }}"
                class="flex flex-col items-center gap-1 group cursor-pointer">
                <div class="w-12 h-12 bg-green-50 group-hover:bg-green-100 rounded-full flex items-center justify-center transition">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        {!! $ikon['svg'] !!}
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-600 group-hover:text-green-700 transition text-center leading-tight">{{ $ikon['label'] }}</span>
            </a>
            @endforeach
        </div>

        {{-- Search & Filter --}}
        <div class="flex flex-col md:flex-row gap-3">
            <form action="{{ route('publik.katalog') }}" method="GET" class="flex-1 flex gap-2">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Produk Disini"
                        class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-50">
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm transition">Cari</button>
            </form>
        </div>
    </div>
</div>

{{-- ===== SECTION: PRODUK (Sidebar + Grid) ===== --}}
<div class="max-w-6xl mx-auto px-4 mt-6 pb-10">
    <div class="flex flex-col md:flex-row gap-6">

        {{-- Sidebar Kategori --}}
        <div class="w-full md:w-52 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-green-700 px-4 py-3">
                    <p class="text-white font-bold text-sm text-center">Kategori Obat</p>
                </div>
                <div class="py-2">
                    <a href="{{ route('publik.katalog') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm transition {{ !request('kategori') ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="w-5 h-5 bg-gray-200 rounded-full inline-flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                        </span>
                        Semua
                    </a>
                    @foreach($kategoris as $kat)
                    <a href="{{ route('publik.katalog', array_merge(request()->query(), ['kategori' => $kat->id])) }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm transition {{ request('kategori') == $kat->id ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="w-5 h-5 bg-green-100 rounded-full inline-flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        {{ $kat->nama_kategori }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="flex-1">
            @if($obats->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-400 font-medium">Produk tidak ditemukan</p>
                <a href="{{ route('publik.katalog') }}" class="text-green-600 text-sm hover:underline mt-1 inline-block">Lihat semua produk</a>
            </div>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($obats as $obat)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden group cursor-pointer"
                    data-nama="{{ $obat->nama_obat }}"
                    data-kategori="{{ $obat->kategori->nama_kategori ?? '-' }}"
                    data-harga="{{ number_format($obat->harga_jual, 0, ',', '.') }}"
                    data-satuan="{{ $obat->satuan->nama_satuan ?? '-' }}"
                    data-stok="{{ $obat->total_stok }}"
                    data-gambar="{{ $obat->gambar ? asset($obat->gambar) : '' }}"
                    data-deskripsi="{{ $obat->deskripsi ?? '' }}"
                    data-dosis-min="{{ $obat->dosis_min ?? '' }}"
                    data-dosis-max="{{ $obat->dosis_max ?? '' }}"
                    data-cara-pakai="{{ $obat->cara_pakai ?? '' }}"
                    onclick="bukaDetailObat(this)">
        
                    {{-- Foto Obat --}}
                    <div class="h-32 flex items-center justify-center overflow-hidden">
                        @if($obat->gambar)
                            <img src="{{ asset($obat->gambar) }}" alt="{{ $obat->nama_obat }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                        @else
                            {{-- Placeholder jika tidak ada foto --}}
                            <div class="bg-gradient-to-br from-green-50 to-green-100 w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                        @endif
                    </div>
                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-green-700/10 opacity-0 group-hover:opacity-100 transition flex items-center justify-center pointer-events-none">
                        <span class="bg-green-700 text-white text-xs font-semibold px-3 py-1 rounded-full">Lihat Detail</span>
                    </div>
                    {{-- Info --}}
                    <div class="p-3">
                        <p class="text-xs text-green-600 font-medium mb-0.5">{{ $obat->kategori->nama_kategori ?? '—' }}</p>
                        <p class="text-sm font-bold text-gray-800 uppercase leading-tight mb-2 line-clamp-2">{{ $obat->nama_obat }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-extrabold text-green-700">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</span>
                            <span class="text-xs {{ $obat->total_stok > 0 ? 'text-green-500' : 'text-red-400' }} font-medium">
                                {{ $obat->total_stok > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                        @if($obat->satuan)
                        <p class="text-xs text-gray-400 mt-0.5">/ {{ $obat->satuan->nama_satuan }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($obats->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $obats->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>
</div>

{{-- ===== MODAL DETAIL OBAT ===== --}}
<div id="modalDetailObat" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="tutupDetailObat()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden" style="animation: popIn 0.25s ease-out both;">
        {{-- Gambar Obat --}}
        <div id="d-img-wrap" class="bg-gradient-to-br from-green-100 to-green-200 h-44 flex items-center justify-center relative overflow-hidden">
            <img id="d-img" src="" alt="" class="w-full h-full object-contain hidden">
            <svg id="d-img-placeholder" class="w-16 h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            {{-- Tombol tutup --}}
            <button onclick="tutupDetailObat()" class="absolute top-3 right-3 bg-white/70 hover:bg-white text-gray-600 rounded-full w-8 h-8 flex items-center justify-center text-lg font-bold leading-none transition">&times;</button>
            {{-- Badge kategori --}}
            <span id="d-kategori" class="absolute bottom-3 left-3 bg-green-700 text-white text-xs font-semibold px-2.5 py-1 rounded-full"></span>
        </div>

        {{-- Info Produk --}}
        <div class="p-5">
            <h3 id="d-nama" class="font-extrabold text-gray-800 uppercase text-base leading-snug mb-3"></h3>

            <div class="space-y-2 text-sm">
                {{-- Kegunaan (Deskripsi) --}}
                <div id="d-deskripsi-wrap" class="flex justify-between items-start py-2 border-b border-gray-100 hidden">
                    <span class="text-gray-500 font-medium shrink-0 mr-2">Kegunaan</span>
                    <span id="d-deskripsi" class="font-semibold text-gray-700 text-right text-xs"></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500 font-medium">Harga</span>
                    <span id="d-harga" class="font-extrabold text-green-700 text-base"></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500 font-medium">Satuan</span>
                    <span id="d-satuan" class="font-semibold text-gray-700"></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-500 font-medium">Stok</span>
                    <span id="d-stok" class="font-semibold"></span>
                </div>
                {{-- Usia --}}
                <div id="d-usia-wrap" class="flex justify-between items-center py-2 border-b border-gray-100 hidden">
                    <span class="text-gray-500 font-medium">Usia</span>
                    <span id="d-usia" class="font-semibold text-gray-700 text-right text-xs"></span>
                </div>
                <div id="d-cara-wrap" class="flex justify-between items-start py-2 hidden">
                    <span class="text-gray-500 font-medium shrink-0 mr-2">Cara Pakai</span>
                    <span id="d-cara" class="font-semibold text-gray-700 text-right text-xs"></span>
                </div>
            </div>

            {{-- Tombol Hubungi --}}
            <a href="https://wa.me/6208xxxxxxxxx" target="_blank"
                class="mt-4 w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.556 4.111 1.523 5.837L.057 24l6.305-1.654A11.882 11.882 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.013-1.374l-.36-.213-3.727.977.995-3.635-.234-.373A9.818 9.818 0 012.182 12C2.182 6.58 6.58 2.182 12 2.182c5.42 0 9.818 4.398 9.818 9.818 0 5.42-4.398 9.818-9.818 9.818z"/></svg>
                Tanya via WhatsApp
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes popIn {
        from { opacity: 0; transform: scale(0.85) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
@endsection

@push('scripts')
<script>
    /* ===== MODAL DETAIL OBAT ===== */
    function bukaDetailObat(el) {
        const d = el.dataset;
        const nama       = d.nama;
        const kategori   = d.kategori;
        const harga      = d.harga;
        const satuan     = d.satuan;
        const stok       = parseInt(d.stok);
        const gambarUrl  = d.gambar;
        const deskripsi  = d.deskripsi;
        const dosisMin   = d.dosisMin;
        const dosisMax   = d.dosisMax;
        const caraPakai  = d.caraPakai;
        document.getElementById('d-nama').textContent     = nama;
        document.getElementById('d-kategori').textContent = kategori;
        document.getElementById('d-harga').textContent    = 'Rp' + harga;
        document.getElementById('d-satuan').textContent   = satuan;

        // Stok
        const stokEl = document.getElementById('d-stok');
        stokEl.textContent = stok > 0 ? stok + ' (Tersedia)' : 'Habis';
        stokEl.className   = 'font-semibold ' + (stok > 0 ? 'text-green-600' : 'text-red-500');

        // Kegunaan (Deskripsi)
        const deskWrap = document.getElementById('d-deskripsi-wrap');
        const deskEl   = document.getElementById('d-deskripsi');
        if (deskripsi && deskripsi.trim() !== '') {
            deskEl.textContent = deskripsi;
            deskWrap.classList.remove('hidden');
        } else {
            deskWrap.classList.add('hidden');
        }

        // Usia (Dosis)
        const usiaWrap = document.getElementById('d-usia-wrap');
        const usiaEl   = document.getElementById('d-usia');
        if (dosisMin || dosisMax) {
            const txt = (dosisMin && dosisMax) ? dosisMin + ' – ' + dosisMax : (dosisMin || dosisMax);
            usiaEl.textContent = txt;
            usiaWrap.classList.remove('hidden');
        } else {
            usiaWrap.classList.add('hidden');
        }

        // Cara Pakai
        const caraWrap = document.getElementById('d-cara-wrap');
        const caraEl   = document.getElementById('d-cara');
        if (caraPakai && caraPakai.trim() !== '') {
            caraEl.textContent = caraPakai;
            caraWrap.classList.remove('hidden');
        } else {
            caraWrap.classList.add('hidden');
        }

        // Foto
        const img = document.getElementById('d-img');
        const ph  = document.getElementById('d-img-placeholder');
        if (gambarUrl && gambarUrl !== '') {
            img.src = gambarUrl; img.classList.remove('hidden'); ph.classList.add('hidden');
        } else {
            img.src = ''; img.classList.add('hidden'); ph.classList.remove('hidden');
        }

        const modal = document.getElementById('modalDetailObat');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function tutupDetailObat() {
        const modal = document.getElementById('modalDetailObat');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') tutupDetailObat();
    });

    /* ===== SLIDER ===== */

    const totalSlides = 2;
    let autoPlayTimer;

    function sliderGoTo(idx) {
        const slides = document.querySelectorAll('.slider-slide');
        const dots   = document.querySelectorAll('.slider-dot');

        slides.forEach(s => { s.style.opacity = '0'; s.style.pointerEvents = 'none'; });
        dots.forEach(d  => { d.style.backgroundColor = 'rgba(255,255,255,0.4)'; });

        slides[idx].style.opacity = '1';
        slides[idx].style.pointerEvents = '';
        dots[idx].style.backgroundColor = '#fff';

        currentSlide = idx;
    }

    function sliderNext() {
        resetAutoPlay();
        sliderGoTo((currentSlide + 1) % totalSlides);
    }

    function sliderPrev() {
        resetAutoPlay();
        sliderGoTo((currentSlide - 1 + totalSlides) % totalSlides);
    }

    function resetAutoPlay() {
        clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(() => sliderGoTo((currentSlide + 1) % totalSlides), 4000);
    }

    // Mulai auto-play saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        sliderGoTo(0);
        resetAutoPlay();
    });
</script>
@endpush
