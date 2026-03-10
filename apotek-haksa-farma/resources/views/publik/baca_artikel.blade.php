@extends('layouts.publik')

@section('title', $artikel->judul . ' - Apotek Haksa Farma')
@section('meta_desc', $artikel->ringkasan)

@section('content')
{{-- Hero/Header Section --}}
<div class="bg-gradient-to-br from-green-700 to-green-900 py-16 text-center text-white relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 relative z-10">
        <span class="inline-block bg-green-600/30 backdrop-blur-sm border border-green-500/30 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-[0.2em] mb-4">
            {{ $artikel->kategori ?? 'Kesehatan' }}
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4 leading-tight">
            {{ $artikel->judul }}
        </h1>
        <div class="flex items-center justify-center gap-4 text-green-200 text-xs font-semibold">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $artikel->tanggal_publish ? $artikel->tanggal_publish->format('d F Y') : '' }}
            </span>
            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
            <span>Oleh Admin Haksa Farma</span>
        </div>
    </div>
    {{-- Design elements --}}
    <div class="absolute top-0 right-0 w-64 h-64 bg-green-600/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
</div>

<div class="max-w-4xl mx-auto px-4 py-12">
    
    {{-- Main Content --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-green-900/5 border border-gray-100 p-6 md:p-12 -mt-20 relative z-20">
        
        {{-- Cover Image --}}
        @if($artikel->gambar)
            <div class="rounded-2xl overflow-hidden mb-10 shadow-lg">
                <img src="{{ asset($artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-auto object-cover max-h-[500px]">
            </div>
        @endif

        {{-- Abstract/Summary --}}
        @if($artikel->ringkasan)
            <div class="border-l-4 border-green-500 pl-6 mb-10 italic text-gray-600 text-lg leading-relaxed">
                "{{ $artikel->ringkasan }}"
            </div>
        @endif

        {{-- Content --}}
        <div class="prose prose-green max-w-none text-gray-700 leading-loose text-base space-y-6">
            {!! nl2br(e($artikel->konten)) !!}
        </div>

        {{-- Share & Footer --}}
        <div class="mt-16 pt-10 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-6">
            <a href="{{ route('publik.artikel') }}" class="flex items-center gap-2 text-green-700 font-bold text-sm hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Artikel
            </a>
            
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Bagikan:</span>
                <div class="flex gap-2">
                    <button class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-green-600 hover:text-white transition">
                        <span class="text-xs">FB</span>
                    </button>
                    <button class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-green-600 hover:text-white transition">
                        <span class="text-xs">WA</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    /* Subtle typography improvements */
    .prose p { margin-bottom: 1.5rem; }
</style>
@endsection
