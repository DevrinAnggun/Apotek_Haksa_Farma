@extends('layouts.publik')

@section('title', 'Artikel Kesehatan')
@section('meta_desc', 'Baca artikel seputar kesehatan dan informasi obat dari Apotek Haksa Farma.')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-br from-green-700 to-green-900 py-12 text-center text-white">
    <h1 class="text-3xl font-extrabold tracking-wide mb-2">Artikel Kesehatan</h1>
    <p class="text-green-200 text-sm">Tips dan informasi seputar kesehatan dari Apotek Haksa Farma</p>
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
            {{-- Thumbnail Placeholder --}}
            <div class="bg-gradient-to-br from-green-100 to-green-200 h-44 flex items-center justify-center relative overflow-hidden">
                <svg class="w-14 h-14 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{-- Badge Kategori --}}
                <span class="absolute top-3 left-3 bg-green-700 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                    {{ $artikel['kategori'] }}
                </span>
            </div>

            {{-- Konten --}}
            <div class="p-5">
                <p class="text-xs text-gray-400 mb-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $artikel['tanggal'] }}
                </p>
                <h3 class="font-bold text-gray-800 text-sm leading-snug mb-2 group-hover:text-green-700 transition artikel-judul">
                    {{ $artikel['judul'] }}
                </h3>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-3">{{ $artikel['ringkas'] }}</p>
                <div class="mt-4">
                    <span class="inline-flex items-center gap-1 text-green-700 text-xs font-semibold hover:underline cursor-pointer">
                        Baca Selengkapnya
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
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
