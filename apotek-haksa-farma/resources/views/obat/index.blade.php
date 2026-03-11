@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2 flex items-center gap-3">
        DATA OBAT
    </h2>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-green-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="dismissAlert('flash-success')" class="text-green-500 hover:text-green-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

{{-- Category Tabs --}}
<div class="mb-8 border-b border-gray-100">
    <div class="flex items-center gap-10 overflow-x-auto whitespace-nowrap custom-scrollbar px-2">
        <a href="{{ route('obat.index') }}" 
           class="pb-4 text-sm font-extrabold transition-all relative {{ !request('kategori') ? 'text-black' : 'text-gray-400 hover:text-gray-600' }}">
           Semua
           @if(!request('kategori'))
               <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-black rounded-full"></div>
           @endif
        </a>
        @foreach($kategoris as $kat)
        <a href="{{ route('obat.index', ['kategori' => $kat->id]) }}" 
           class="pb-4 text-sm font-extrabold transition-all relative {{ request('kategori') == $kat->id ? 'text-black' : 'text-gray-400 hover:text-gray-600' }}">
           {{ strtoupper($kat->nama_kategori) }}
           @if(request('kategori') == $kat->id)
               <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-black rounded-full"></div>
           @endif
        </a>
        @endforeach
    </div>
</div>

<!-- Toolbar: Search and Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row items-center gap-3">
    <!-- Search Bar -->
    <div class="relative w-full sm:w-1/2 md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
        <form action="{{ route('obat.index') }}" method="GET" class="w-full flex">
            @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
            <input type="text" name="search" value="{{ request('search') }}" oninput="this.form.submit()" autofocus placeholder="Cari Obat....." class="w-full pl-4 pr-2 py-2 focus:outline-none text-sm">
            <button type="submit" class="px-3 flex items-center bg-gray-50 hover:bg-green-100 transition text-green-600 border-l border-gray-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
    </div>

    <!-- Group Action Buttons next to search -->
    <div class="flex flex-wrap items-center gap-2">
        <!-- Plus Obat Button -->
        <button type="button" onclick="openTambahModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg transition text-center shadow flex items-center justify-center gap-1.5 text-sm uppercase tracking-wide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
            Obat
        </button>

        <!-- Category Management Button -->
        <button type="button" onclick="openTambahKatModal()"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg transition text-center shadow flex items-center justify-center gap-1.5 text-sm uppercase tracking-wide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
            Kategori
        </button>
    </div>
</div>

{{-- Table --}}
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max border border-gray-400 shadow-sm rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-4 px-4 font-bold text-gray-800 text-center w-16 border border-gray-300 uppercase text-xs tracking-wider">No</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-left border border-gray-300 uppercase text-xs tracking-wider">Nama Barang</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Harga</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Satuan</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-24">Stok</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-24">Terjual</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-44">Tgl Kadaluarsa</th>
                <th class="py-4 px-6 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-28">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($obats as $index => $obat)
            <tr class="hover:bg-gray-50 transition text-sm">
                <td class="py-3 px-4 text-center text-gray-800 font-medium border border-gray-300">
                    {{ $obats->firstItem() + $index }}
                </td>
                <td class="py-3 px-5 text-left text-gray-800 font-bold uppercase border border-gray-300">
                    {{ $obat->nama_obat }}
                </td>
                <td class="py-3 px-5 text-center text-gray-900 font-bold border border-gray-300">
                    Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}
                </td>
                <td class="py-3 px-5 text-center text-gray-800 font-medium border border-gray-300">
                    {{ $obat->satuan->nama_satuan ?? '-' }}
                </td>
                <td class="py-3 px-5 text-center border border-gray-300 font-extrabold {{ (isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') ? 'text-gray-400 font-normal' : ($obat->total_stok <= 5 ? 'text-red-500' : 'text-gray-800') }}">
                    @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK')
                        -
                    @else
                        {{ $obat->total_stok }}
                    @endif
                </td>
                <td class="py-3 px-5 text-center border border-gray-300">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                        {{ $obat->total_terjual ?? 0 }}
                    </span>
                </td>
                <td class="py-3 px-5 text-center text-gray-900 font-bold border border-gray-300">
                    @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK')
                        <span class="text-gray-400 font-normal">-</span>
                    @elseif($obat->tanggal_kadaluarsa)
                        {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d-m-Y') }}
                    @else
                        <span class="text-gray-300">-</span>
                    @endif
                </td>
                <td class="py-3 px-6 border border-gray-300">
                    <div class="flex justify-center items-center gap-1">
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
                            class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button type="button"
                            onclick="openHapusModal({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}')"
                            class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="py-12 text-center text-gray-400 italic border border-gray-300">Data obat belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Section -->
<div class="mt-8 mb-10 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
    <div class="text-xs text-gray-400 italic">
        * Menampilkan seluruh data obat dan stok yang tersedia.
    </div>
    <div class="flex gap-2">
        @if($obats->onFirstPage())
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                &#9664; Back
            </span>
        @else
            <a href="{{ $obats->previousPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                &#9664; Back
            </a>
        @endif

        @if($obats->hasMorePages())
            <a href="{{ $obats->nextPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                Next &#9654;
            </a>
        @else
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                Next &#9654;
            </span>
        @endif
    </div>
</div>

{{--  MODAL TAMBAH BARANG --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Tambah Barang</h3>
            <button onclick="closeTambahModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-2 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formTambah" action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="kode_obat" id="tambah_kode_obat">
                <input type="hidden" name="harga_beli" value="0">
                
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                    <select name="id_kategori" id="tambah_id_kategori" onchange="toggleCekFields('tambah')" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm cursor-pointer uppercase text-sm">
                        <option value="" class="normal-case">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat</label>
                    <input type="text" name="nama_obat" required placeholder="Contoh: OB HERBAL SYR 60ML" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 uppercase text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual</label>
                        <input type="number" name="harga_jual" min="0" required placeholder="Rp" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                    </div>
                    <div class="space-y-1" id="tambah_stok_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Stok Awal</label>
                        <input type="number" name="stok" id="tambah_field_stok" min="0" placeholder="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Satuan</label>
                        <select name="id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1 flex flex-col" id="tambah_expired_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kadaluarsa</label>
                        <input type="date" name="expired_date" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    </div>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-8 py-5 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeTambahModal()" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition uppercase tracking-wider">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formTambah', 'Data Berhasil Ditambahkan!')" class="px-8 py-2.5 text-sm font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg transition uppercase tracking-wider">Simpan</button>
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

{{-- MODAL TAMBAH KATEGORI --}}
<div id="modalTambahKat" class="fixed inset-0 z-50 hidden flex items-start sm:items-center justify-center p-4 overflow-y-auto">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahKatModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md my-8 overflow-hidden animate-modal flex flex-col max-h-[90vh]">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white shrink-0">
            <h3 class="text-xl font-bold tracking-wide w-full uppercase text-center">Kategori</h3>
            <button onclick="closeTambahKatModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="p-8 overflow-y-auto flex-1 custom-scrollbar">
            <form id="formTambahKat" action="{{ route('kategori.store') }}" method="POST" class="space-y-4 mb-6">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="nama_kategori" required placeholder="Kategori Baru" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 uppercase text-sm font-bold">
                    <button type="button" onclick="showSuccessAnimation('formTambahKat', 'Kategori Ditambahkan!')" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-extrabold shadow-lg transition text-sm">Simpan</button>
                </div>
            </form>
            <div class="border-t border-gray-100 pt-6">
                <div class="space-y-2">
                    @foreach($kategoris as $kat)
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100 group hover:border-green-200 transition-all">
                            <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">{{ $kat->nama_kategori }}</span>
                            <form id="formHapusKat{{ $kat->id }}" action="{{ route('kategori.destroy', $kat->id) }}" method="POST">@csrf @method('DELETE')<button type="button" onclick="confirmHapusKat('{{ $kat->id }}', '{{ $kat->nama_kategori }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex justify-end px-8 py-5 bg-gray-50 border-t border-gray-100 shrink-0">
            <button type="button" onclick="closeTambahKatModal()" class="px-8 py-2 text-xs font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-md transition uppercase tracking-[0.1em]">Tutup</button>
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
    function openTambahModal() {
        document.getElementById('tambah_kode_obat').value = 'OBT-' + Date.now();
        document.getElementById('modalTambah').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        toggleCekFields('tambah');
    }
    function closeTambahModal() { document.getElementById('modalTambah').classList.add('hidden'); document.body.style.overflow = ''; }
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
    function openTambahKatModal() { document.getElementById('modalTambahKat').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeTambahKatModal() { document.getElementById('modalTambahKat').classList.add('hidden'); document.body.style.overflow = ''; }
    function confirmHapusKat(id, nama) {
        if(confirm('Hapus kategori ' + nama + '?')) document.getElementById('formHapusKat' + id).submit();
    }
    function showSuccessAnimation(formId, titleText) {
        const form = document.getElementById(formId);
        if (!form.checkValidity()) { form.reportValidity(); return; }
        document.getElementById('sukses_title').textContent = titleText;
        document.getElementById('modalSukses').classList.remove('hidden');
        document.getElementById('modalSukses').classList.add('flex');
        setTimeout(() => form.submit(), 1200);
    }
</script>
@endpush

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection
