@extends('layouts.frontend')

@section('title', $artikel->judul . ' - Apotek Haksa Farma')
@section('meta_desc', $artikel->ringkasan)

@section('content')
{{-- Header Section --}}
<div class="bg-white border-b border-gray-100 pt-16 pb-12 text-center text-gray-800 relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 relative z-10">
        <span class="inline-block bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-[0.2em] mb-4">
            {{ $artikel->kategori ?? 'Kesehatan' }}
        </span>
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight mb-4 leading-tight text-gray-900">
            {{ $artikel->judul }}
        </h1>
        <div class="flex items-center justify-center gap-4 text-gray-400 text-xs font-semibold">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $artikel->tanggal_publish ? $artikel->tanggal_publish->format('d F Y') : '' }}
            </span>
            <span class="w-1.5 h-1.5 bg-gray-200 rounded-full"></span>
            <span>Oleh Admin Haksa Farma</span>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 -mt-8 md:-mt-10">
    
    {{-- Main Content --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-900/5 border border-gray-100 p-6 md:px-12 md:pb-12 md:pt-6 relative z-20">
        
        {{-- Cover Image --}}
        @if($artikel->gambar)
            <div class="flex justify-center mb-10">
                <div class="rounded-2xl overflow-hidden shadow-md border border-gray-50 max-w-md w-full">
                    <img src="{{ asset($artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-auto max-h-[250px] object-cover">
                </div>
            </div>
        @endif

        {{-- Abstract/Summary --}}
        @if($artikel->ringkasan)
            <div class="border-l-4 border-green-600 bg-gray-50 pl-6 py-3 mb-10 italic text-gray-600 text-sm leading-relaxed rounded-r-xl">
                "{{ $artikel->ringkasan }}"
            </div>
        @endif

        {{-- Content --}}
        <div class="prose prose-slate max-w-none text-gray-700 leading-loose text-xs space-y-6">
            {!! nl2br(e($artikel->konten)) !!}
        </div>

        {{-- Share & Footer --}}
        <div class="mt-16 pt-10 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-6">
            <a href="{{ route('publik.artikel') }}" class="flex items-center gap-2 text-slate-800 font-bold text-sm hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Artikel
            </a>
            
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Bagikan:</span>
                <button onclick="copyToClipboard(this)" 
                        class="flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-full hover:bg-green-700 transition-all duration-300 group shadow-lg shadow-green-100">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    <span id="copy-btn-text" class="text-[11px] font-bold uppercase tracking-wider text-white">Salin Tautan</span>
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function copyToClipboard(btn) {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const textSpan = document.getElementById('copy-btn-text');
            const originalText = textSpan.innerText;
            
            // UI Feedback
            textSpan.innerText = 'Tersalin!';
            btn.classList.add('bg-green-700');
            btn.classList.remove('bg-green-600');
            
            setTimeout(() => {
                textSpan.innerText = originalText;
                btn.classList.remove('bg-green-700');
                btn.classList.add('bg-green-600');
            }, 2000);
        });
    }
</script>

<style>
    /* Subtle typography improvements */
    .prose p { margin-bottom: 1.5rem; }
</style>
@endsection
