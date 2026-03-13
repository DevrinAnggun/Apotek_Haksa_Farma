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
            Katalog
        </button>
    </div>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-8 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-green-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="dismissAlert('flash-success')" class="text-green-500 hover:text-green-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

<div class="flex flex-col md:flex-row gap-8">
    {{-- Sidebar Kategori --}}
    <div class="w-full md:w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
            <div class="bg-green-700 px-5 py-4 border-b border-green-800">
                <p class="text-white font-extrabold text-xs uppercase tracking-widest text-center">Filter Kategori</p>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('obat.katalog') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ !request('kategori') ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span>Semua Produk</span>
                   <span class="{{ !request('kategori') ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ \App\Models\Obat::whereHas('kategori', fn($q) => $q->where('nama_kategori', '!=', 'CEK'))->count() }}</span>
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
                        data-id-merk="{{ $obat->id_merk }}"
                        data-gambar="{{ $obat->gambar ? asset($obat->gambar) : '' }}"
                        onclick="openEditModal(this)"
                        class="p-2.5 bg-white text-green-600 rounded-xl shadow-lg border border-green-50 hover:bg-green-600 hover:text-white transition-all">
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

{{--  MODAL TAMBAH KATALOG PRODUK --}}
<div id="modalTambahKatalog" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahKatalogModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Tambah Katalog Produk</h3>
            <button onclick="closeTambahKatalogModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-6 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formTambahKatalog" action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="kode_obat" id="tambah_kat_kode_obat">
                <input type="hidden" name="harga_beli" value="0">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat</label>
                            <input type="text" name="nama_obat" required placeholder="Contoh: Panadol Merah 500mg" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 uppercase text-sm font-medium">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                                <select name="id_kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm cursor-pointer uppercase text-xs font-bold">
                                    <option value="" class="normal-case">Pilih Kategori</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Satuan</label>
                                <select name="id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm text-xs font-bold">
                                    <option value="">Pilih Satuan</option>
                                    @foreach($satuans as $sat)
                                        <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                                    <input type="number" name="harga_jual" required placeholder="0" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Stok Awal</label>
                                <input type="number" name="stok" min="0" placeholder="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Gambar Obat</label>
                            <div class="space-y-2">
                                <div class="relative group border-2 border-dashed border-gray-200 rounded-2xl h-56 flex flex-col items-center justify-center transition hover:border-green-400 overflow-hidden bg-gray-50">
                                    <input type="file" id="input-tambah-gambar" accept="image/*" onchange="initCropHandler(this, 'preview-kat-img', 'preview-kat-placeholder', 1)" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                    <div id="preview-kat-placeholder" class="flex flex-col items-center">
                                        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Upload Foto</p>
                                    </div>
                                    <img id="preview-kat-img" class="max-w-full block hidden">
                                </div>
                                <button type="button" id="btn-crop-tambah" onclick="applyManualCrop('preview-kat-img', 'input-tambah-gambar', 1)" 
                                    class="hidden w-full py-2 bg-green-600 hover:bg-green-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition shadow-sm">
                                    Terapkan
                                </button>
                                <p id="hint-tambah" class="hidden text-[9px] text-gray-400 italic text-center">Geser kotak untuk memotong, lalu klik Terapkan</p>
                                <input type="file" name="gambar" id="final-tambah-gambar" class="hidden">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Deskripsi Obat</label>
                        <textarea name="deskripsi" rows="2" placeholder="Penjelasan singkat mengenai produk..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kegunaan</label>
                        <textarea name="cara_pakai" rows="2" placeholder="Manfaat obat atau cara pemakaian..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-8 py-5 border-t border-gray-100 bg-gray-50 uppercase tracking-widest font-bold">
            <button type="button" onclick="closeTambahKatalogModal()" class="text-sm text-gray-400 hover:text-gray-600 transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formTambahKatalog', 'Katalog Berhasil Ditambahkan!')" class="px-10 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl shadow-xl shadow-green-100 transition-all text-sm">Simpan Katalog</button>
        </div>
    </div>
</div>

{{--  MODAL EDIT BARANG --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-800 px-6 py-4 flex items-center justify-between text-white border-b border-green-900">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Edit Obat</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-gray-200 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-2 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')
                <input type="hidden" name="kode_obat" id="edit_kode_obat">
                <input type="hidden" name="harga_beli" id="edit_harga_beli">
                
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                    <select name="id_kategori" id="edit_id_kategori" onchange="toggleCekFields('edit')" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm uppercase text-sm font-medium">
                        <option value="" class="normal-case">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat</label>
                    <input type="text" name="nama_obat" id="edit_nama_obat" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 uppercase text-sm font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual</label>
                        <input type="number" name="harga_jual" id="edit_harga_jual" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                    <div class="space-y-1" id="edit_stok_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Stok Fisik</label>
                        <input type="number" name="stok" id="edit_stok" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Satuan</label>
                        <select name="id_satuan" id="edit_id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm text-sm font-medium">
                            <option value="">-- Satuan --</option>
                            @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1 flex flex-col">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kadaluarsa</label>
                        <input type="date" name="expired_date" id="edit_expired_date" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-gray-50">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Deskripsi Obat</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kegunaan</label>
                        <textarea name="cara_pakai" id="edit_cara_pakai" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Gambar</label>
                        <div class="relative w-full h-64 border border-gray-100 rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center">
                            <img id="edit_preview_kat" src="" class="max-w-full block hidden">
                            <div id="edit_kat_placeholder" class="w-full h-full flex flex-col items-center justify-center text-[10px] text-gray-300 font-bold uppercase text-center p-4">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Pilih Gambar Untuk Mengubah
                            </div>
                        </div>
                        <input type="file" id="input-edit-gambar" accept="image/*" onchange="initCropHandler(this, 'edit_preview_kat', 'edit_kat_placeholder', 1)" 
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition shadow-sm">
                        <button type="button" id="btn-crop-edit" onclick="applyManualCrop('edit_preview_kat', 'input-edit-gambar', 1)" 
                            class="hidden w-full py-2 bg-green-600 hover:bg-green-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition shadow-sm">
                            Terapkan
                        </button>
                        <input type="file" name="gambar" id="final-edit-gambar" class="hidden">
                    </div>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-8 py-5 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition uppercase tracking-wider">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formEdit', 'Perubahan Berhasil Disimpan!')" class="px-8 py-2.5 text-sm font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg transition uppercase tracking-wider">Simpan</button>
        </div>
    </div>
</div>

{{--  MODAL KONFIRMASI HAPUS --}}
<div id="modalHapus" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeHapusModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-80 mx-4 overflow-hidden animate-modal">
        <div class="bg-red-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">KONFIRMASI HAPUS</h4>
        </div>
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">Hapus Obat ini?</p>
            <p class="text-[11px] text-gray-500 italic leading-relaxed">
                Data yang dihapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 mt-2">
            <button type="button" onclick="closeHapusModal()" class="flex-1 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition uppercase tracking-wider">BATAL</button>
            <form id="formHapus" action="" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="button" onclick="showSuccessAnimation('formHapus', 'Data Berhasil Dihapus!')" class="w-full py-2 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition uppercase tracking-wider">YA, HAPUS</button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SUKSES --}}
<div id="modalSukses" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-64 py-8 px-6 text-center animasi-pop">
        <div class="flex justify-center mb-5">
            <svg class="w-20 h-20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="44" stroke="#16a34a" stroke-width="6" stroke-dasharray="276" stroke-dashoffset="276" class="circle-anim"></circle>
                <polyline points="28,52 44,68 73,34" stroke="#16a34a" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="80" stroke-dashoffset="80" class="check-anim"></polyline>
            </svg>
        </div>
        <h3 id="sukses_title" class="text-xl font-extrabold text-gray-800 mb-1">Berhasil!</h3>
    </div>
</div>

<style>
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-modal { animation: modalIn 0.2s ease-out both; }
    .animasi-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
    @keyframes pop { from { opacity: 0; transform: scale(0.7); } to { opacity: 1; transform: scale(1); } }
    .circle-anim { animation: drawCircle 0.6s ease forwards; }
    .check-anim { animation: drawCheck 0.4s ease 0.5s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>

@push('scripts')
<script>
    function previewImageKatalog(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-kat-img').src = e.target.result;
                document.getElementById('preview-kat-img').classList.remove('hidden');
                document.getElementById('preview-kat-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function openTambahKatalogModal() {
        document.getElementById('tambah_kat_kode_obat').value = 'KAT-' + Date.now();
        document.getElementById('modalTambahKatalog').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeTambahKatalogModal() {
        document.getElementById('modalTambahKatalog').classList.add('hidden');
        document.body.style.overflow = '';
    }
    function toggleCekFields(prefix) {
        const sel = document.getElementById(prefix + '_id_kategori');
        if (!sel) return;
        const selectedOption = sel.options[sel.selectedIndex];
        const isCek = selectedOption && selectedOption.getAttribute('data-nama') === 'CEK';
        const stokWrapper = document.getElementById(prefix + '_stok_wrapper');
        const expWrapper = document.getElementById(prefix + '_expired_wrapper');
        if (isCek) {
            if (stokWrapper) stokWrapper.classList.add('hidden');
            if (expWrapper) expWrapper.classList.add('hidden');
            const stokInput = document.getElementById(prefix === 'tambah' ? 'tambah_field_stok' : 'edit_stok');
            if (stokInput) stokInput.value = 9999;
        } else {
            if (stokWrapper) stokWrapper.classList.remove('hidden');
            if (expWrapper) expWrapper.classList.remove('hidden');
        }
    }
    function openEditModal(el) {
        const d = el.dataset;
        const form = document.getElementById('formEdit');
        form.action = '{{ url("obat") }}/' + d.id;
        document.getElementById('edit_kode_obat').value = d.kodeObat;
        document.getElementById('edit_harga_beli').value = d.hargaBeli;
        document.getElementById('edit_nama_obat').value = d.nama;
        document.getElementById('edit_harga_jual').value = d.hargaJual;
        document.getElementById('edit_stok').value = d.stok;
        document.getElementById('edit_expired_date').value = d.expiredDate;
        document.getElementById('edit_id_kategori').value = d.idKategori;
        document.getElementById('edit_id_satuan').value = d.idSatuan;
        
        // Handle Preview Image
        const preview = document.getElementById('edit_preview_kat');
        const placeholder = document.getElementById('edit_kat_placeholder');
        if (d.gambar) {
            preview.src = d.gambar;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        const editDeskripsi = document.getElementById('edit_deskripsi');
        if (editDeskripsi) editDeskripsi.value = d.deskripsi || '';
        const editCaraPakai = document.getElementById('edit_cara_pakai');
        if (editCaraPakai) editCaraPakai.value = d.caraPakai || '';
        toggleCekFields('edit');
        document.getElementById('modalEdit').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); document.body.style.overflow = ''; }
    function openHapusModal(id, nama) {
        document.getElementById('formHapus').action = '{{ url("obat") }}/' + id;
        document.getElementById('modalHapus').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeHapusModal() { document.getElementById('modalHapus').classList.add('hidden'); document.body.style.overflow = ''; }
    function showSuccessAnimation(formId, titleText) {
        const form = document.getElementById(formId);
        if (!form.checkValidity()) { form.reportValidity(); return; }
        
        // Ensure we explicitly apply crop if user hasn't
        if (formId === 'formTambahKatalog' && currentCropper) {
            applyManualCrop('preview-kat-img', 'input-tambah-gambar', 1);
        } else if (formId === 'formEdit' && currentCropper) {
            applyManualCrop('edit_preview_kat', 'input-edit-gambar', 1);
        }

        document.getElementById('sukses_title').textContent = titleText;
        document.getElementById('modalSukses').classList.remove('hidden');
        document.getElementById('modalSukses').classList.add('flex');
        setTimeout(() => form.submit(), 1200);
    }

    /* ===== IMPROVED CROP LOGIC ===== */
    let activeCroppers = {};

    function initCropHandler(input, imgId, placeholderId, ratio) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(imgId);
                const placeholder = document.getElementById(placeholderId);
                const isEdit = imgId.includes('edit');
                const btnId = isEdit ? 'btn-crop-edit' : 'btn-crop-tambah';
                const hintId = isEdit ? '' : 'hint-tambah';
                
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                
                if (activeCroppers[imgId]) activeCroppers[imgId].destroy();
                
                setTimeout(() => {
                    activeCroppers[imgId] = new Cropper(img, {
                        aspectRatio: ratio,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        background: false,
                        modal: true,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                    });
                    
                    document.getElementById(btnId).classList.remove('hidden');
                    if(hintId) document.getElementById(hintId).classList.remove('hidden');
                }, 200);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function applyManualCrop(imgId, inputId, ratio) {
        const cropper = activeCroppers[imgId];
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 / ratio });
        canvas.toBlob((blob) => {
            const isEdit = imgId.includes('edit');
            const finalInputId = isEdit ? 'final-edit-gambar' : 'final-tambah-gambar';
            const btnId = isEdit ? 'btn-crop-edit' : 'btn-crop-tambah';
            
            const file = new File([blob], 'cropped.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById(finalInputId).files = dataTransfer.files;
            
            // UI Feedback
            const btn = document.getElementById(btnId);
            const originalText = btn.innerText;
            btn.innerText = 'BERHASIL DIPOTONG!';
            btn.classList.add('bg-emerald-600');
            btn.classList.remove('bg-green-600');
            
            setTimeout(() => {
                btn.innerText = originalText;
                btn.classList.remove('bg-emerald-600');
                btn.classList.add('bg-green-600');
            }, 1500);
        }, 'image/jpeg', 0.9);
    }
</script>
@endpush
@endsection
