@extends('layouts.admin')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight uppercase">MANAJEMEN ARTIKEL</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola konten edukasi dan tips kesehatan untuk pelanggan.</p>
    </div>
    <a href="{{ route('artikel.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-green-200 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tulis Artikel Baru
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-8 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($artikels as $artikel)
    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative">
        {{-- Kategori Badge --}}
        <div class="absolute top-4 left-4 z-10">
            <span class="bg-green-600 text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest shadow-md">
                {{ $artikel->kategori ?? 'Umum' }}
            </span>
        </div>

        {{-- Action Buttons --}}
        <div class="absolute top-4 right-4 z-10 flex gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
            <a href="{{ route('artikel.edit', $artikel->id) }}" class="p-2.5 bg-white text-blue-600 rounded-xl shadow-lg hover:bg-blue-600 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </a>
            <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-2.5 bg-white text-red-600 rounded-xl shadow-lg hover:bg-red-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </form>
        </div>

        {{-- Image --}}
        <div class="h-48 overflow-hidden bg-gray-50">
            @if($artikel->gambar)
                <img src="{{ asset($artikel->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center bg-green-50/50">
                    <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="p-6 flex-1 flex flex-col">
            <h3 class="text-lg font-extrabold text-gray-800 line-clamp-2 leading-tight mb-3">
                {{ $artikel->judul }}
            </h3>
            <p class="text-sm text-gray-500 line-clamp-3 mb-6 flex-1">
                {{ $artikel->ringkasan }}
            </p>
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                    {{ $artikel->tanggal_publish ? $artikel->tanggal_publish->format('D, d M Y') : 'Draft' }}
                </span>
                <span class="text-xs font-bold text-green-600 group-hover:translate-x-1 transition flex items-center gap-1">
                    Manage <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        <h3 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Belum Ada Artikel</h3>
        <p class="text-gray-400 text-sm mt-1">Mulai tulis artikel edukasi pertama Anda hari ini.</p>
    </div>
    @endforelse
</div>
@endsection
