@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2">DATA OBAT</h2>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="dismissAlert('flash-success')" class="ml-4 text-green-700 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif
@if(session('error'))
    <div id="flash-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="dismissAlert('flash-error')" class="ml-4 text-red-700 hover:text-red-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif

<!-- Tabs Navigation & Toolbar -->
<div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-300 pb-2 mb-6 gap-4 mt-8">
    <!-- Tab Links -->
    <div class="flex space-x-6 overflow-x-auto pb-4 font-bold text-gray-800 uppercase tracking-widest w-full custom-scrollbar">
        <a href="{{ route('obat.index', request()->except('kategori')) }}" 
            class="pb-1 transition whitespace-nowrap {{ !request('kategori') ? 'text-black border-b-[3px] border-black' : 'text-gray-500 hover:text-black hover:border-b-[3px] hover:border-gray-400' }}">
            Semua Obat
        </a>
        @foreach($kategoris as $kat)
            <a href="{{ route('obat.index', array_merge(request()->query(), ['kategori' => $kat->id])) }}" 
                class="pb-1 transition whitespace-nowrap {{ request('kategori') == $kat->id ? 'text-black border-b-[3px] border-black' : 'text-gray-500 hover:text-black hover:border-b-[3px] hover:border-gray-400' }}">
                {{ $kat->nama_kategori }}
            </a>
        @endforeach
    </div>
</div>

<!-- Search and Add Action (Obat) -->
<div class="mb-6">
    <form action="{{ route('obat.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
        @if(request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <div class="relative w-full sm:w-1/2 md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Barang....." class="w-full pl-4 pr-2 py-2 focus:outline-none">
            <button type="submit" class="px-3 flex items-center bg-gray-50 hover:bg-green-100 transition text-green-600 border-l border-gray-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>
        <!-- Tombol Tambah Obat -->
        <button type="button" onclick="openTambahModal()"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition text-center shadow">
            + Obat
        </button>
        <!-- Tombol Tambah Kategori -->
        <button type="button" onclick="openTambahKatModal()"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition text-center shadow">
            + Kategori
        </button>
    </form>
</div>

    <!-- Table Data (Obat) -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max border border-gray-400 shadow-sm rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-4 px-4 font-bold text-gray-800 text-center w-16 border border-gray-300 uppercase text-xs tracking-wider">No</th>
                    <th class="py-4 px-6 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Nama Barang</th>
                    <th class="py-4 px-6 font-bold text-gray-800 text-center w-40 border border-gray-300 uppercase text-xs tracking-wider">Harga</th>
                    <th class="py-4 px-6 font-bold text-gray-800 text-center w-28 border border-gray-300 uppercase text-xs tracking-wider">Satuan</th>
                    <th class="py-4 px-6 font-bold text-gray-800 text-center w-24 border border-gray-300 uppercase text-xs tracking-wider">Stok</th>
                    <th class="py-4 px-6 font-bold text-gray-800 text-center w-24 border border-gray-300 uppercase text-xs tracking-wider">Terjual</th>
                    <th class="py-4 px-6 font-bold text-gray-800 text-center w-40 border border-gray-300 uppercase text-xs tracking-wider">Tgl Kadaluarsa</th>
                    <th class="py-4 px-6 font-bold text-gray-800 text-center w-28 border border-gray-300 uppercase text-xs tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obats as $index => $obat)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 text-center text-gray-800 font-medium border border-gray-300">{{ $index + 1 }}</td>
                    <td class="py-3 px-6 text-center text-gray-800 font-medium uppercase border border-gray-300">{{ $obat->nama_obat }}</td>
                    <td class="py-3 px-6 text-center text-gray-800 font-medium border border-gray-300">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                    <td class="py-3 px-6 text-center text-gray-800 font-medium border border-gray-300">
                        @if($obat->satuan)
                            {{ $obat->satuan->nama_satuan }}
                        @else
                            <span class="text-gray-400 font-semibold">—</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center text-gray-800 font-medium border border-gray-300 font-bold {{ $obat->total_stok < 5 && ($obat->kategori->nama_kategori ?? '') !== 'CEK' ? 'text-red-500' : 'text-gray-800' }}">
                        @if(($obat->kategori->nama_kategori ?? '') === 'CEK')
                            <span class="text-gray-400 font-semibold">—</span>
                        @else
                            {{ $obat->total_stok }}
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center text-gray-800 border border-gray-300">
                        <span class="bg-green-50 text-green-700 font-bold px-2 py-0.5 rounded shadow-sm text-xs border border-green-100">
                            {{ $obat->total_terjual ?? 0 }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center border border-gray-300">
                        @if(($obat->kategori->nama_kategori ?? '') === 'CEK')
                            <span class="text-gray-400 font-semibold">—</span>
                        @elseif($obat->tanggal_kadaluarsa)
                            <span class="font-medium {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->isPast() ? 'text-red-500 font-bold' : 'text-gray-800' }}">
                                {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d-m-Y') }}
                            </span>
                        @else
                            <span class="text-gray-400 font-semibold">—</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 border border-gray-300">
                        <div class="flex justify-center items-center gap-1">
                            <!-- Tombol Edit (buka modal edit) -->
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
                                onclick="openEditModal(this)"
                                class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm"
                                title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>

                            <!-- Tombol Hapus (buka modal konfirmasi) -->
                            <button type="button"
                                onclick="openHapusModal({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}')"
                                class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded transition shadow-sm"
                                title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-6 text-center text-gray-500 italic border border-gray-300">Tidak ada data obat pada kategori ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Data Obat -->
    <div class="mt-6 flex items-center justify-between px-2">
        <div class="text-xs text-gray-500 font-medium">
            Menampilkan {{ $obats->firstItem() ?? 0 }} - {{ $obats->lastItem() ?? 0 }} dari {{ $obats->total() }} barang
        </div>
        <div class="flex gap-2">
            @if($obats->onFirstPage())
                <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                    &#9664; Back
                </span>
            @else
                <a href="{{ $obats->previousPageUrl() }}" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                    &#9664; Back
                </a>
            @endif

            @if($obats->hasMorePages())
                <a href="{{ $obats->nextPageUrl() }}" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                    Next &#9654;
                </a>
            @else
                <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                    Next &#9654;
                </span>
            @endif
        </div>
    </div>

    </div>


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

