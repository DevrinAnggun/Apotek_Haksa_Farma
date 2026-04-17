@extends('layouts.frontend')

@section('title', 'Artikel Kesehatan')
@section('meta_desc', 'Baca artikel seputar kesehatan dan informasi obat dari Apotek Haksa Farma.')

@section('content')

{{-- Header --}}
<div class="bg-green-700 border-b border-green-800 py-10 text-center">
    <h1 class="text-3xl font-extrabold tracking-wide mb-2 text-white uppercase">Artikel Kesehatan</h1>
    <p class="text-green-100 text-sm font-medium">Tips dan informasi seputar kesehatan dari Apotek Haksa Farma</p>
</div>

<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- Search --}}
    <div class="flex justify-end mb-8">
        <div class="relative w-full max-w-xs">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" id="searchArtikel" placeholder="Cari Artikel Disini"
                class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white shadow-sm"
                oninput="filterArtikel(this.value)">
        </div>
    </div>

    {{-- Grid Artikel --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="artikelGrid">
        @foreach($artikels as $artikel)
        <article class="artikel-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
            {{-- Thumbnail --}}
            <div class="h-44 relative overflow-hidden bg-gray-100">
                @if($artikel->gambar)
                    <img src="{{ asset($artikel->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                        <svg class="w-14 h-14 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                @endif
                {{-- Badge Kategori --}}
                <span class="absolute top-3 left-3 bg-green-700/80 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                    {{ $artikel->kategori ?? 'Umum' }}
                </span>
            </div>

            {{-- Konten --}}
            <div class="p-5">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $artikel->tanggal_publish ? $artikel->tanggal_publish->format('d M Y') : '' }}
                </p>
                <a href="{{ route('publik.artikel.detail', $artikel->slug) }}">
                    <h3 class="font-bold text-gray-800 text-sm leading-snug mb-2 group-hover:text-green-700 transition artikel-judul line-clamp-2 min-h-[40px]">
                        {{ $artikel->judul }}
                    </h3>
                </a>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-3">{{ $artikel->ringkasan }}</p>
                <div class="mt-4 pt-4 border-t border-gray-50">
                    <a href="{{ route('publik.artikel.detail', $artikel->slug) }}" class="inline-flex items-center gap-1 text-green-700 text-xs font-bold hover:gap-2 transition-all">
                        Baca Selengkapnya
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    {{-- Empty state --}}
    <div id="emptyArtikel" class="hidden text-center py-16">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-gray-400 font-medium">Artikel tidak ditemukan</p>
    </div>

</div>

@endsection

@push('scripts')
<script>
function filterArtikel(keyword) {
    const q = keyword.toLowerCase().trim();
    const cards = document.querySelectorAll('.artikel-card');
    let visible = 0;
    cards.forEach(card => {
        const judul = card.querySelector('.artikel-judul')?.textContent.toLowerCase() || '';
        const show = judul.includes(q);
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('emptyArtikel').classList.toggle('hidden', visible > 0);
}
</script>
@endpush
