@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight uppercase">MANAJEMEN KATALOG PRODUK</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola tampilan produk yang akan dilihat oleh pelanggan.</p>
    </div>
    <div class="flex gap-2">
        <button type="button" onclick="openTambahKatModal()"
            class="bg-white border border-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            Kategori
        </button>
        <button type="button" onclick="openTambahModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
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
    {{-- Sidebar Kategori (Admin Style) --}}
    <div class="w-full md:w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
            <div class="bg-gray-50 px-5 py-4 border-b border-gray-100">
                <p class="text-gray-800 font-extrabold text-xs uppercase tracking-widest text-center">Filter Kategori</p>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('obat.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ !request('kategori') ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span>Semua Produk</span>
                   <span class="{{ !request('kategori') ? 'bg-white/20' : 'bg-gray-100 opacity-0 group-hover:opacity-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ \App\Models\Obat::count() }}</span>
                </a>
                @foreach($kategoris as $kat)
                <a href="{{ route('obat.index', ['kategori' => $kat->id]) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ request('kategori') == $kat->id ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span class="truncate">{{ $kat->nama_kategori }}</span>
                   <span class="{{ request('kategori') == $kat->id ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ $kat->obats_count ?? $kat->obats()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Grid Management --}}
    <div class="flex-1 space-y-6">
        {{-- Search (Admin Style) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex items-center gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <form action="{{ route('obat.index') }}" method="GET">
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk berdasarkan nama atau kode..." 
                           class="w-full pl-12 pr-4 py-3 bg-transparent border-none focus:ring-0 text-sm font-medium text-gray-700" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($obats as $obat)
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative">
                {{-- Badge Kategori --}}
                <div class="absolute top-3 left-3 z-10">
                    <span class="bg-white/90 backdrop-blur shadow-sm text-green-700 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-100">
                        {{ $obat->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                </div>

                {{-- Action Buttons (Hover) --}}
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

                {{-- Image Area --}}
                <div class="h-44 bg-gray-50 flex items-center justify-center relative overflow-hidden">
                    @if($obat->gambar)
                        <img src="{{ asset($obat->gambar) }}" class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-50 to-green-100 flex flex-col items-center justify-center gap-2">
                            <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            <span class="text-[10px] font-bold text-green-300 uppercase tracking-widest">No Photos Yet</span>
                        </div>
                    @endif
                </div>

                {{-- Content Area --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="mb-auto">
                        <h3 class="text-sm font-extrabold text-gray-800 uppercase leading-snug tracking-tight mb-2 group-hover:text-green-700 transition line-clamp-2 min-h-[40px]">
                            {{ $obat->nama_obat }}
                        </h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Harga Retail</p>
                                <p class="text-base font-extrabold text-green-700">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Stok Sisa</p>
                                <p class="text-sm font-extrabold {{ $obat->total_stok <= 0 ? 'text-red-500' : 'text-gray-800' }}">
                                    {{ $obat->total_stok }} <span class="text-[10px] text-gray-400 font-medium">Brt</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada produk ditemukan</h3>
                <p class="text-gray-400 text-sm mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori Anda.</p>
                <a href="{{ route('obat.index') }}" class="mt-4 inline-block text-green-600 font-bold text-sm hover:underline">Reset Semua Filter</a>
            </div>
            @endforelse
        </div>

        {{-- Pagination (Admin Style) --}}
        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                Showing {{ $obats->firstItem() ?? 0 }}-{{ $obats->lastItem() ?? 0 }} of {{ $obats->total() }} results
            </p>
            <div class="flex gap-2">
                {{ $obats->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
</div>


{{-- ============================================================ --}}


{{-- ============================================================ --}}
{{--  MODAL TAMBAH BARANG                                          --}}
{{-- ============================================================ --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal">
        <!-- Header -->
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center">Tambah Barang</h3>
            <button onclick="closeTambahModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <!-- Body -->
        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <form id="formTambah" action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="kode_obat" id="tambah_kode_obat">
                <input type="hidden" name="harga_beli" value="0">

                <!-- Kategori -->
                <select name="id_kategori" id="tambah_id_kategori" onchange="toggleCekFields('tambah')" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-600 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm cursor-pointer uppercase">
                    <option value="" class="normal-case">-- Pilih Kategori (Contoh: Sirup) --</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>

                <!-- Nama Obat -->
                <input type="text" name="nama_obat" required placeholder="Nama Obat (Contoh: OB HERBAL SYR 60ML)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500 uppercase">

                <!-- Harga Jual -->
                <input type="number" name="harga_jual" min="0" required placeholder="Harga Jual (Contoh: 19000)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Stok -->
                <div id="tambah_stok_wrapper">
                    <input type="number" name="stok" id="tambah_field_stok" min="0" placeholder="Stok Fisik Awal (Contoh: 100)"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                </div>

                <!-- Satuan & Kadaluarsa -->
                <div class="grid grid-cols-2 gap-4 items-start">
                    <select name="id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-600 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm">
                        <option value="">-- Satuan (Botol/Strip) --</option>
                        @foreach($satuans as $sat)
                            <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                        @endforeach
                    </select>
                    <div class="flex flex-col" id="tambah_expired_wrapper">
                        <input type="date" name="expired_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-600 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                        <p class="text-[10px] text-gray-400 mt-1 ml-1">Tanggal Kadaluarsa Stok (Opsional)</p>
                    </div>
                </div>

                <!-- Gambar -->
                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">FOTO OBAT</label>
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden" id="tambah_preview_container">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <img id="tambah_preview_img" class="hidden w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="gambar" accept="image/*" onchange="previewImg(this, 'tambah')"
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, JPEG. Max 2MB.</p>
                        </div>
                    </div>
                </div>


                @if ($errors->any() && !session('_edit_mode'))
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">{{ $errors->first() }}</div>
                @endif
            </form>
        </div>
        <div class="flex justify-between items-center px-6 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeTambahModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 rounded-lg transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formTambah', 'Data Berhasil Ditambahkan!')" class="px-6 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Tambah</button>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{--  MODAL EDIT BARANG                                            --}}
{{-- ============================================================ --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal">
        <!-- Header -->
        <div class="bg-green-800 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Edit Obat</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-gray-200 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <!-- Body -->
        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="kode_obat" id="edit_kode_obat">
                <input type="hidden" name="harga_beli" id="edit_harga_beli">

                <!-- Kategori -->
                <select name="id_kategori" id="edit_id_kategori" onchange="toggleCekFields('edit')" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm uppercase">
                    <option value="" class="normal-case">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>

                <!-- Nama Obat -->
                <input type="text" name="nama_obat" id="edit_nama_obat" required placeholder="Nama Obat"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Harga Jual -->
                <input type="number" name="harga_jual" id="edit_harga_jual" min="0" required placeholder="Harga Jual"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Stok -->
                <div id="edit_stok_wrapper">
                    <input type="number" name="stok" id="edit_stok" min="0" placeholder="Stok Fisik Saat Ini"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                </div>

                <!-- Satuan & Kadaluarsa -->
                <div class="grid grid-cols-2 gap-4 items-start">
                    <select name="id_satuan" id="edit_id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm">
                        <option value="">-- Satuan --</option>
                        @foreach($satuans as $sat)
                            <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                        @endforeach
                    </select>
                    <div class="flex flex-col">
                        <input type="date" name="expired_date" id="edit_expired_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                        <div id="edit_expired_text">
                            <p class="text-[10px] text-gray-400 mt-1 ml-1">Tanggal Kadaluarsa Stok (Opsional)</p>
                        </div>
                    </div>
                </div>

                <!-- Gambar (Edit) -->
                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">FOTO OBAT</label>
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden" id="edit_preview_container">
                            <svg id="edit_placeholder_svg" class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <img id="edit_preview_img" class="hidden w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="gambar" accept="image/*" onchange="previewImg(this, 'edit')"
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-1">Ganti foto obat jika perlu. Max 2MB.</p>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="flex justify-between items-center px-6 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeEditModal()" class="px-5 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formEdit', 'Perubahan Berhasil Disimpan!')" class="px-6 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Simpan</button>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{--  MODAL KONFIRMASI HAPUS                                       --}}
{{-- ============================================================ --}}
<div id="modalHapus" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeHapusModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 animate-modal">
        <!-- Pesan -->
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">
                Yakin ingin menghapus <span id="hapus_nama_obat" class="text-green-700"></span>?
            </p>
            <p class="text-sm text-gray-500">Stok obat ini akan ikut terhapus dan tidak bisa dikembalikan.</p>
        </div>
        <!-- Tombol Aksi -->
        <div class="flex gap-3 px-6 pb-6">
            <button type="button" onclick="closeHapusModal()" class="flex-1 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
            <form id="formHapus" action="" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="button" onclick="showSuccessAnimation('formHapus', 'Data Berhasil Dihapus!')" class="w-full py-2.5 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{--  MODAL KONFIRMASI HAPUS                                       --}}
{{-- ============================================================ --}}
<div id="modalHapus" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeHapusModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 animate-modal">
        <!-- Pesan -->
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">
                Yakin ingin menghapus <span id="hapus_nama_obat" class="text-green-700"></span>?
            </p>
            <p class="text-sm text-gray-500">Stok obat ini akan ikut terhapus dan tidak bisa dikembalikan.</p>
        </div>
        <!-- Tombol Aksi -->
        <div class="flex gap-3 px-6 pb-6">
            <button type="button" onclick="closeHapusModal()" class="flex-1 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
            <form id="formHapus" action="" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="button" onclick="showSuccessAnimation('formHapus', 'Data Berhasil Dihapus!')" class="w-full py-2.5 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{--  MODAL TAMBAH KATEGORI                                        --}}
{{-- ============================================================ --}}
<!-- Tabs Navigation & Toolbar -->
<div id="modalTambahKat" class="fixed inset-0 z-50 hidden flex items-start sm:items-center justify-center p-4 overflow-y-auto">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahKatModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md my-8 overflow-hidden animate-modal flex flex-col max-h-[90vh]">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white text-center shrink-0">
            <h3 class="text-xl font-bold tracking-wide w-full uppercase">Tambah Kategori</h3>
            <button onclick="closeTambahKatModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
            <!-- Form Tambah -->
            <form id="formTambahKat" action="{{ route('kategori.store') }}" method="POST" class="space-y-4 mb-6">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="nama_kategori" required placeholder="Nama Kategori Baru"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500 uppercase text-sm">
                    <button type="button" onclick="showSuccessAnimation('formTambahKat', 'Kategori Berhasil Ditambahkan!')" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold shadow transition text-sm">
                        Simpan
                    </button>
                </div>
            </form>

            <!-- List Kategori -->
            <div class="border-t border-gray-100 pt-4">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Daftar Kategori Saat Ini</h4>
                <div class="space-y-2 pr-1">
                    @foreach($kategoris as $kat)
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-100 group hover:border-green-200 transition">
                            <span class="text-sm font-semibold text-gray-700 uppercase">{{ $kat->nama_kategori }}</span>
                            <!-- Form Hapus Kategori -->
                            <form id="formHapusKat{{ $kat->id }}" action="{{ route('kategori.destroy', $kat->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmHapusKat('{{ $kat->id }}', '{{ $kat->nama_kategori }}')" 
                                    class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex justify-end px-6 py-4 border-t border-gray-100 bg-gray-50 mt-auto shrink-0">
            <button type="button" onclick="closeTambahKatModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 rounded-lg transition">Selesai</button>
        </div>
    </div>
</div>
    </div>
</div>

{{-- ===== MODAL SUKSES DENGAN ANIMASI CENTANG ===== --}}
<div id="modalSukses" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-72 mx-4 py-8 px-6 text-center sukses-box">
        <!-- Animated Checkmark SVG -->
        <div class="flex justify-center mb-5">
            <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="44" stroke="#16a34a" stroke-width="6"
                    stroke-dasharray="276" stroke-dashoffset="276"
                    class="circle-anim">
                </circle>
                <polyline points="28,52 44,68 73,34" stroke="#16a34a" stroke-width="7"
                    stroke-linecap="round" stroke-linejoin="round"
                    stroke-dasharray="80" stroke-dashoffset="80"
                    class="check-anim">
                </polyline>
            </svg>
        </div>
        <h3 id="sukses_title" class="text-xl font-extrabold text-gray-800 mb-1">Berhasil!</h3>
        <p class="text-sm text-gray-400 mt-1">Sedang memperbarui data...</p>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(-12px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
    .animate-modal { animation: modalIn 0.2s ease-out both; }

    .sukses-box {
        animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
    }
    @keyframes popIn {
        from { opacity: 0; transform: scale(0.7); }
        to   { opacity: 1; transform: scale(1); }
    }
    .circle-anim { animation: drawCircle 0.65s ease forwards; }
    .check-anim { animation: drawCheck 0.45s ease 0.55s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }

    /* Custom Scrollbar for Tabs (Matching user reference) */
    .custom-scrollbar::-webkit-scrollbar {
        height: 12px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
        margin: 0 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
        border: 2px solid #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Arrows logic for horizontal scroll area */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
        position: relative;
    }

    /* Visual pseudo-arrows (decoration) */
    .custom-scrollbar::before, .custom-scrollbar::after {
        content: '';
        position: absolute;
        bottom: 0;
        width: 15px;
        height: 12px;
        background-repeat: no-repeat;
        background-size: contain;
        z-index: 10;
        pointer-events: none;
    }
    .custom-scrollbar::before {
        left: -18px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23888'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E");
    }
    .custom-scrollbar::after {
        right: -18px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23888'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E");
    }
</style>

@endsection

@push('scripts')
<script>
    /* ===== IMAGE PREVIEW ===== */
    function previewImg(input, prefix) {
        const preview = document.getElementById(prefix + '_preview_img');
        const placeholder = document.getElementById(prefix === 'edit' ? 'edit_placeholder_svg' : 'tambah_preview_container').querySelector('svg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }
    }

    /* ===== MODAL TAMBAH ===== */
    function openTambahModal() {
        // Generate kode otomatis
        document.getElementById('tambah_kode_obat').value = 'OBT-' + Date.now();
        document.getElementById('modalTambah').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        toggleCekFields('tambah');
    }
    function closeTambahModal() {
        document.getElementById('modalTambah').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ===== TOGGLE FIELDS UNTUK KATEGORI CEK ===== */
    function toggleCekFields(prefix) {
        const sel = document.getElementById(prefix + '_id_kategori');
        if (!sel) return;
        
        const selectedOption = sel.options[sel.selectedIndex];
        const isCek = selectedOption && selectedOption.getAttribute('data-nama') === 'CEK';

        const stokWrapper = document.getElementById(prefix + '_stok_wrapper');
        const expiredWrapper = prefix === 'edit' ? document.getElementById('edit_expired_date') : document.getElementById('tambah_expired_wrapper');
        const expiredText = prefix === 'edit' ? document.getElementById('edit_expired_text') : null;
        
        if (isCek) {
            if (stokWrapper) stokWrapper.classList.add('hidden');
            if (expiredWrapper) expiredWrapper.classList.add('hidden');
            if (expiredText) expiredText.classList.add('hidden');
            
            // Auto fill stok tinggi untuk jasa cek
            const stokInput = document.getElementById(prefix === 'tambah' ? 'tambah_field_stok' : 'edit_stok');
            if (stokInput) stokInput.value = 9999;
        } else {
            if (stokWrapper) stokWrapper.classList.remove('hidden');
            if (expiredWrapper) expiredWrapper.classList.remove('hidden');
            if (expiredText) expiredText.classList.remove('hidden');
        }
    }

    /* ===== MODAL EDIT ===== */
    function openEditModal(el) {
        const d = el.dataset;
        const id          = d.id;
        const nama        = d.nama;
        const idKategori  = d.idKategori;
        const hargaJual   = d.hargaJual;
        const stok        = d.stok;
        const idSatuan    = d.idSatuan;
        const kodeObat    = d.kodeObat;
        const hargaBeli   = d.hargaBeli;
        const expiredDate = d.expiredDate;

        const modal = document.getElementById('modalEdit');
        const form  = document.getElementById('formEdit');
        form.action = '{{ url("obat") }}/' + id;

        document.getElementById('edit_kode_obat').value    = kodeObat;
        document.getElementById('edit_harga_beli').value   = hargaBeli;
        document.getElementById('edit_nama_obat').value    = nama;
        document.getElementById('edit_harga_jual').value   = hargaJual;
        document.getElementById('edit_stok').value         = stok;
        document.getElementById('edit_expired_date').value = expiredDate;

        const selKat = document.getElementById('edit_id_kategori');
        selKat.value = idKategori;

        const selSat = document.getElementById('edit_id_satuan');
        selSat.value = idSatuan;

        // Image Preview
        const preview = document.getElementById('edit_preview_img');
        const placeholder = document.getElementById('edit_placeholder_svg');
        if (d.gambar && d.gambar !== '') {
            preview.src = d.gambar;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        toggleCekFields('edit');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
        document.body.style.overflow = '';
    }


    /* ===== MODAL HAPUS ===== */
    function openHapusModal(id, nama) {
        document.getElementById('hapus_nama_obat').textContent = nama;
        document.getElementById('formHapus').action = '{{ url("obat") }}/' + id;
        document.getElementById('modalHapus').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeHapusModal() {
        document.getElementById('modalHapus').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ===== TUTUP SEMUA MODAL DENGAN ESC ===== */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTambahModal();
            closeEditModal();
            closeHapusModal();
            closeTambahKatModal();
        }
    });

    /* ===== MODAL KATEGORI ===== */
    function openTambahKatModal() {
        document.getElementById('modalTambahKat').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeTambahKatModal() {
        document.getElementById('modalTambahKat').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function confirmHapusKat(id, nama) {
        if(confirm('Yakin ingin menghapus kategori ' + nama + '?')) {
            showSuccessAnimation('formHapusKat' + id, 'Kategori Berhasil Dihapus!');
        }
    }

    /* ===== AUTO BUKA TAMBAH MODAL jika ada error validasi dari tambah ===== */
    @if ($errors->any() && old('_form_type') === 'tambah')
        document.addEventListener('DOMContentLoaded', () => openTambahModal());
    @endif

    /* ===== ANIMASI SUKSES SEBELUM SUBMIT ===== */
    function showSuccessAnimation(formId, titleText) {
        const form = document.getElementById(formId);
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const modal = document.getElementById('modalSukses');
        document.getElementById('sukses_title').textContent = titleText;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const circle = modal.querySelector('.circle-anim');
        const check  = modal.querySelector('.check-anim');
        circle.style.animation = 'none';
        check.style.animation  = 'none';
        circle.getBoundingClientRect(); // trigger reflow
        check.getBoundingClientRect();
        circle.style.animation = '';
        check.style.animation  = '';

        setTimeout(() => {
            form.submit();
        }, 1500);
    }

    /* ===== MODAL STOK MASUK ===== */
    function openModalStokMasuk() {
        // Auto-generate hidden fields for DB requirements
        const now = Date.now();
        document.getElementById('restock_no_faktur').value = 'INV-RESTOCK-' + now;
        document.getElementById('restock_no_batch').value = 'BATCH-TEMP-' + now;

        document.getElementById('restock_id_obat').removeAttribute('disabled');
        document.getElementById('restock_id_obat').classList.remove('bg-gray-100');
        document.getElementById('formStokMasuk').reset();
        
        // Restore values after reset
        document.getElementById('restock_no_faktur').value = 'INV-RESTOCK-' + now;
        document.getElementById('restock_no_batch').value = 'BATCH-TEMP-' + now;

        document.getElementById('modalStokMasuk').classList.remove('hidden');
        document.getElementById('modalStokMasuk').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModalStokMasuk() {
        document.getElementById('modalStokMasuk').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ===== QUICK RESTOCK FROM ROW ===== */
    function openQuickRestock(el) {
        const id = el.dataset.id;
        const nama = el.dataset.nama;
        
        openModalStokMasuk();
        
        const select = document.getElementById('restock_id_obat');
        select.value = id;
    }

    // Update Escape handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTambahModal();
            closeEditModal();
            closeHapusModal();
            closeTambahKatModal();
            closeModalStokMasuk();
        }
    });
</script>
@endpush

