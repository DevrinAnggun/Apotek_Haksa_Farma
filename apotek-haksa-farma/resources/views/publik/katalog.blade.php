@extends('layouts.frontend')

@section('title', 'Katalog Produk - Apotek Haksa Farma')
@section('show_slider', true)

@section('content')
<!-- Main Content -->
<section id="katalog-produk" class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Sidebar: Search & Filter -->
            <aside class="w-full lg:w-1/4 space-y-8">
                <!-- Categories -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-green-700 px-6 py-4">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest text-center">Kategori Obat</h3>
                    </div>
                    <div class="p-2 space-y-1">
                        <a href="{{ route('publik.katalog') }}" 
                           class="flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-black transition-all {{ !request('kategori') ? 'bg-green-700 text-white shadow-lg' : 'text-gray-500 hover:bg-green-50 hover:text-green-700' }}">
                            <span>SEMUA PRODUK</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded-full text-[10px]">{{ $obats->total() }}</span>
                        </a>
                        @foreach($kategoris as $kat)
                            @if(strtoupper($kat->nama_kategori) === 'CEK') @continue @endif
                        <a href="{{ route('publik.katalog', ['kategori' => $kat->id]) }}" 
                           class="flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-black transition-all {{ request('kategori') == $kat->id ? 'bg-green-700 text-white shadow-lg' : 'text-gray-500 hover:bg-green-50 hover:text-green-700' }}">
                            <span class="truncate">{{ strtoupper($kat->nama_kategori) }}</span>
                            <span class="{{ request('kategori') == $kat->id ? 'bg-white/20' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full text-[10px]">{{ $kat->obats_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <!-- Product Grid & Search -->
            <div class="flex-1">
                <!-- Top Search Bar -->
                <div class="mb-8">
                    <form action="{{ route('publik.katalog') }}" method="GET" class="relative max-w-full">
                        @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                        <div class="relative flex items-center">
                            <svg class="absolute left-4 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari Obat Disini..." 
                                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm text-gray-700 transition-all shadow-sm">
                        </div>
                    </form>
                </div>

                @if($obats->isEmpty())
                    <div class="py-20 text-center">
                        @if(request('search'))
                            <p class="text-[13px] text-gray-400 font-medium">maaf, obat yang anda cari tidak ditemukan.</p>
                        @else
                            <p class="text-[13px] text-gray-400 font-medium">Belum ada produk yang tersedia.</p>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($obats as $obat)
                            @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') @continue @endif
                        <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-3 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col relative overflow-hidden">
                            <div class="mb-3 flex items-start justify-between">
                                <span class="bg-green-50 text-green-700 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-100">
                                    {{ $obat->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                                
                                <a href="https://wa.me/6285385984906?text=Halo%20Apotek%20Haksa%20Farma,%20saya%20ingin%20bertanya%20tentang%20obat%20{{ urlencode($obat->nama_obat) }}" target="_blank" 
                                   class="p-2 bg-gray-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Beli via WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                </a>
                            </div>

                            @if($obat->gambar && file_exists(public_path($obat->gambar)))
                                <div class="w-full h-36 mb-2 bg-white rounded-xl overflow-hidden flex items-center justify-center relative cursor-pointer group/img" 
                                     onclick='showProductDetail({!! json_encode($obat->nama_obat, JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->kategori->nama_kategori ?? "Umum", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode(number_format($obat->harga_jual, 0, ",", "."), JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->deskripsi ?? "Informasi detail obat belum tersedia.", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->cara_pakai ?? "-", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->gambar ? asset($obat->gambar) : "", JSON_HEX_APOS | JSON_HEX_QUOT) !!})'>
                                    <img src="{{ asset($obat->gambar) }}" alt="{{ $obat->nama_obat }}" class="w-full h-full object-cover transition-transform duration-300 group-hover/img:scale-110">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 rounded-xl">
                                        <span class="px-2.5 py-1 bg-green-600 text-white text-[9px] font-bold uppercase tracking-widest rounded-lg shadow-lg flex items-center gap-1.5 transform translate-y-2 group-hover/img:translate-y-0 transition-all duration-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-36 mb-2 bg-gray-50/50 rounded-xl overflow-hidden flex flex-col items-center justify-center relative cursor-pointer group/img"
                                     onclick='showProductDetail({!! json_encode($obat->nama_obat, JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->kategori->nama_kategori ?? "Umum", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode(number_format($obat->harga_jual, 0, ",", "."), JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->deskripsi ?? "Informasi detail obat belum tersedia.", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->cara_pakai ?? "-", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, "")'>
                                    <svg class="w-6 h-6 text-gray-200 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">No Image</span>
                                    
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 rounded-xl">
                                        <span class="px-2.5 py-1 bg-green-600 text-white text-[9px] font-bold uppercase tracking-widest rounded-lg shadow-lg flex items-center gap-1.5 transform translate-y-2 group-hover/img:translate-y-0 transition-all duration-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Detail
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex-1 flex flex-col cursor-pointer" onclick='showProductDetail({!! json_encode($obat->nama_obat, JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->kategori->nama_kategori ?? "Umum", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode(number_format($obat->harga_jual, 0, ",", "."), JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->deskripsi ?? "Informasi detail obat belum tersedia.", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->cara_pakai ?? "-", JSON_HEX_APOS | JSON_HEX_QUOT) !!}, {!! json_encode($obat->gambar ? asset($obat->gambar) : "", JSON_HEX_APOS | JSON_HEX_QUOT) !!})'>
                                <h3 class="text-sm font-extrabold text-gray-800 uppercase leading-snug tracking-tight mb-3 line-clamp-2">
                                    {{ $obat->nama_obat }}
                                </h3>

                                <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                                    <div>
                                        <p class="text-[9px] text-gray-900 font-black uppercase tracking-widest leading-none mb-1">Harga Satuan</p>
                                        <p class="text-sm font-extrabold text-green-700">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-900 font-black uppercase tracking-widest leading-none mb-1">Stok</p>
                                        @if($obat->total_stok > 0)
                                            <p class="text-sm font-extrabold text-gray-900">{{ (int) $obat->total_stok }} <span class="text-[10px] text-gray-900 font-black">Pcs</span></p>
                                        @else
                                            <p class="text-sm font-extrabold text-red-600 uppercase tracking-tighter">Habis</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-16">
                        {{ $obats->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Product Detail Modal -->
<div id="productModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md transition-opacity duration-300" onclick="hideProductDetail()"></div>
    <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-md mx-auto overflow-hidden animate-modal">
        <!-- Close Button -->
        <button onclick="hideProductDetail()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full flex items-center justify-center transition-all z-20 hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Body Modal -->
        <div class="p-6 bg-white">
            <!-- Top Section: Image & Basic Info Side by Side -->
            <div class="flex flex-col sm:flex-row gap-5 mb-6">
                <!-- Image Wrapper -->
                <div id="modalGambarWrapper" class="w-full sm:w-32 h-32 flex-shrink-0 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center">
                    <img id="modalGambar" src="" alt="Produk" class="w-full h-full object-cover">
                </div>
                
                <!-- Basic Info -->
                <div class="flex-1 min-w-0 flex flex-col justify-center">
                    <span id="modalKategori" class="bg-green-50 text-green-700 text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-100 w-fit mb-2">
                        KATEGORI
                    </span>
                    <h2 id="modalNama" class="text-lg font-black text-gray-800 uppercase tracking-tight leading-tight mb-2 truncate-2-lines">NAMA OBAT</h2>
                    <div class="bg-green-50 w-fit px-2.5 py-0.5 rounded-lg border border-green-100">
                        <span id="modalHarga" class="text-sm font-black text-green-700 tracking-tight">Rp0</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Info 1 -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm relative overflow-hidden group hover:border-green-300 hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-3 relative z-10">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-600 border border-green-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-[11px] font-extrabold text-gray-800 uppercase tracking-widest">Informasi Obat</h4>
                    </div>
                    <p id="modalDeskripsi" class="text-gray-600 text-[13px] leading-relaxed relative z-10 font-medium whitespace-pre-line">
                        Informasi detail obat belum tersedia.
                    </p>
                </div>

                <!-- Info 2 -->
                <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm relative overflow-hidden group hover:border-blue-300 hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-3 relative z-10">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-[11px] font-extrabold text-gray-800 uppercase tracking-widest">Cara Pakai</h4>
                    </div>
                    <p id="modalCaraPakai" class="text-gray-600 text-[13px] leading-relaxed relative z-10 font-medium whitespace-pre-line">
                        -
                    </p>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="mt-5">
                <a id="modalWaBtn" href="#" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl shadow-md hover:shadow-lg hover:shadow-green-500/30 hover:-translate-y-0.5 transition-all outline-none focus:ring-4 focus:ring-green-500/30 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    <span class="text-xs font-extrabold tracking-wider">PESAN VIA WHATSAPP</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(30px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-modal {
        animation: modalIn 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
    }
</style>

<script>
    function showProductDetail(nama, kat, harga, desk, cara, img) {
        document.getElementById('modalNama').textContent = nama;
        document.getElementById('modalKategori').textContent = kat;
        document.getElementById('modalHarga').textContent = 'Rp' + harga;
        document.getElementById('modalDeskripsi').textContent = desk || 'Informasi detail obat belum tersedia.';
        document.getElementById('modalCaraPakai').textContent = cara || '-';

        const imgEl = document.getElementById('modalGambar');
        const imgWrapper = document.getElementById('modalGambarWrapper');
        if (img) {
            imgEl.src = img;
            imgWrapper.classList.remove('hidden');
        } else {
            imgWrapper.classList.add('hidden');
        }

        let waUrl = "https://wa.me/6285385984906?text=Halo%20Apotek%20Haksa%20Farma,%20saya%20ingin%20bertanya%20tentang%20obat%20" + encodeURIComponent(nama);
        document.getElementById('modalWaBtn').href = waUrl;

        const modal = document.getElementById('productModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function hideProductDetail() {
        const modal = document.getElementById('productModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
</script>
@endsection
