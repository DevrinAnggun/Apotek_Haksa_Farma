@extends('layouts.publik')

@section('title', 'Katalog Produk')
@section('meta_desc', 'Lihat daftar produk obat dan perlengkapan kesehatan di Apotek Haksa Farma.')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-br from-green-700 to-green-900 py-12 text-center text-white">
    <h1 class="text-3xl font-extrabold tracking-wide mb-2 uppercase">Katalog Produk</h1>
    <p class="text-green-200 text-sm">Temukan obat dan kebutuhan kesehatan Anda dengan harga terjangkau</p>
</div>

<div class="max-w-7xl mx-auto px-4 py-10 flex flex-col md:flex-row gap-8">
    
    {{-- Sidebar Kategori --}}
    <aside class="w-full md:w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
            <div class="bg-green-700 px-5 py-4 border-b border-green-800">
                <p class="text-white font-extrabold text-xs uppercase tracking-widest text-center">Filter Kategori</p>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('publik.katalog') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ !request('kategori') ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span>Semua Produk</span>
                   <span class="{{ !request('kategori') ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ \App\Models\Obat::whereHas('kategori', fn($q) => $q->where('nama_kategori', '!=', 'CEK'))->count() }}</span>
                </a>
                @foreach($kategoris as $kat)
                <a href="{{ route('publik.katalog', ['kategori' => $kat->id]) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ request('kategori') == $kat->id ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span class="truncate">{{ $kat->nama_kategori }}</span>
                   <span class="{{ request('kategori') == $kat->id ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ $kat->obats_count ?? $kat->obats()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- Grid Content --}}
    <div class="flex-1 space-y-6">
        {{-- Search Bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex items-center gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <form action="{{ route('publik.katalog') }}" method="GET">
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari obat atau produk kesehatan..." 
                           class="w-full pl-12 pr-4 py-3 bg-transparent border-none focus:ring-0 text-sm font-medium text-gray-700" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($obats as $obat)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative">
                {{-- Category Badge --}}
                <div class="absolute top-3 left-3 z-10">
                    <span class="bg-white/90 backdrop-blur shadow-sm text-green-700 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-100">
                        {{ $obat->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                </div>

                {{-- Product Image --}}
                <div class="h-44 bg-gray-50 flex items-center justify-center relative overflow-hidden">
                    @if($obat->gambar)
                        <img src="{{ asset($obat->gambar) }}" class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-50 to-green-100 flex flex-col items-center justify-center gap-2 text-green-300 font-bold uppercase text-[10px]">
                            <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tanpa Foto
                        </div>
                    @endif

                    {{-- Overlay "Detail" button for mobile/hover --}}
                    <div class="absolute inset-0 bg-green-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center px-4">
                        <button type="button" onclick='openPublicDetail(@json($obat))' 
                            class="bg-white text-green-700 font-bold py-2 px-5 rounded-full shadow-lg text-xs uppercase tracking-wider transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            Lihat Detail
                        </button>
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-sm font-extrabold text-gray-800 uppercase leading-snug tracking-tight mb-4 line-clamp-2 min-h-[40px]">
                        {{ $obat->nama_obat }}
                    </h3>
                    
                    <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Harga</p>
                            <p class="text-base font-extrabold text-green-700">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Status</p>
                            @if($obat->total_stok > 0)
                                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Tersedia</span>
                            @else
                                <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-100 shadow-sm">
                <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Produk tidak ditemukan</p>
                <p class="text-xs text-gray-300 mt-2">Coba kata kunci lain atau pilih kategori lain.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="pt-10 flex items-center justify-between border-t border-gray-100">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                Menampilkan {{ $obats->firstItem() ?? 0 }}-{{ $obats->lastItem() ?? 0 }} dari {{ $obats->total() }} produk
            </p>
            <div class="flex gap-2">
                {{ $obats->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL PRODUK (PUBLIC) --}}
<div id="modalPublicDetail" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePublicDetail()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden animate-modal">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-lg font-bold uppercase tracking-widest w-full text-center" id="detail_nama">Detail Produk</h3>
            <button onclick="closePublicDetail()" class="absolute right-5 text-2xl font-light hover:text-white/80">&times;</button>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Gambar --}}
                <div class="bg-gray-50 rounded-2xl flex items-center justify-center p-6 h-64 border border-gray-100">
                    <img id="detail_gambar" src="" class="max-w-full max-h-full object-contain drop-shadow-md">
                </div>

                {{-- Deskripsi Utama --}}
                <div class="space-y-6">
                    <div>
                        <h4 id="detail_nama_h4" class="text-xl font-extrabold text-gray-800 uppercase tracking-tight leading-tight"></h4>
                        <span id="detail_kategori" class="inline-block mt-2 bg-green-100 text-green-700 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider"></span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1 leading-none">Harga</p>
                            <p id="detail_harga" class="text-xl font-extrabold text-green-700"></p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1 leading-none">Satuan</p>
                            <p id="detail_satuan" class="text-base font-extrabold text-gray-700"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div id="status_pill" class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest">
                            <span id="stok_text"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 space-y-6 pt-8 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2 italic">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Deskripsi Obat
                        </h5>
                        <p id="detail_deskripsi" class="text-sm text-gray-600 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-50"></p>
                    </div>
                    <div class="space-y-2">
                        <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2 italic">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.644.322a6 6 0 01-3.86.517l-2.387-.477a2 2 0 00-1.022.547l-1.16 1.16a2 2 0 00.442 3.298l1.135.568a10 10 0 0012.294-12.294l-.568-1.135a2 2 0 00-3.298-.442l-1.16 1.16z"/></svg>
                            Kegunaan / Cara Pakai
                        </h5>
                        <p id="detail_cara_pakai" class="text-sm text-gray-600 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-50"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-100">
            <button onclick="closePublicDetail()" class="text-xs font-bold text-gray-400 hover:text-gray-600 uppercase tracking-widest transition">Tutup</button>
            <a href="{{ route('publik.kontak') }}" class="bg-gray-800 hover:bg-black text-white px-8 py-3 rounded-2xl text-xs font-bold uppercase tracking-widest shadow-xl transition-all">
                Tanya Apoteker
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .animate-modal { animation: modalIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
</style>

@push('scripts')
<script>
    function openPublicDetail(obat) {
        document.getElementById('detail_nama').textContent = 'Detail: ' + obat.nama_obat;
        document.getElementById('detail_nama_h4').textContent = obat.nama_obat;
        document.getElementById('detail_kategori').textContent = obat.kategori ? obat.kategori.nama_kategori : 'Umum';
        document.getElementById('detail_harga').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(obat.harga_jual);
        document.getElementById('detail_satuan').textContent = obat.satuan ? obat.satuan.nama_satuan : '-';
        document.getElementById('detail_deskripsi').textContent = obat.deskripsi || 'Tidak ada deskripsi.';
        document.getElementById('detail_cara_pakai').textContent = obat.cara_pakai || 'Tidak ada penjelasan kegunaan.';
        
        const img = document.getElementById('detail_gambar');
        if (obat.gambar) {
            img.src = '{{ asset("") }}' + obat.gambar;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }

        const pill = document.getElementById('status_pill');
        const text = document.getElementById('stok_text');
        if (obat.total_stok > 0) {
            pill.className = 'flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest bg-green-50 text-green-700 border border-green-100';
            text.textContent = 'Stok Tersedia (' + obat.total_stok + ')';
        } else {
            pill.className = 'flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest bg-red-50 text-red-500 border border-red-100';
            text.textContent = 'Stok Habis';
        }

        const modal = document.getElementById('modalPublicDetail');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closePublicDetail() {
        const modal = document.getElementById('modalPublicDetail');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
</script>
@endpush

@endsection
