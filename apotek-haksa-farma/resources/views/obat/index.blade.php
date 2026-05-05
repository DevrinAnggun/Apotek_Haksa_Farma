@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2 flex items-center gap-3">
        DATA OBAT
    </h2>
</div>

@if(session('success'))
    <div id="flash-success" class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between animate-modal">
        <div class="flex items-center">
            <div class="bg-green-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="dismissAlert('flash-success')" class="text-green-500 hover:text-green-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

@if(session('error'))
    <div id="flash-error" class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between animate-modal">
        <div class="flex items-center">
            <div class="bg-red-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        <button onclick="dismissAlert('flash-error')" class="text-red-500 hover:text-red-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div id="flash-errors" class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between animate-modal">
        <div class="flex items-center">
            <div class="bg-red-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div class="text-sm font-bold leading-relaxed">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
        <button onclick="dismissAlert('flash-errors')" class="text-red-500 hover:text-red-700 transition font-bold text-xl leading-none">&times;</button>
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
<div class="mb-6 space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-start gap-4">
        <!-- Search Bar -->
        <div class="relative w-full md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
            <form action="{{ route('obat.index') }}" method="GET" class="w-full flex">
                @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                @if(request('month')) <input type="hidden" name="month" value="{{ request('month') }}"> @endif
                @if(request('year')) <input type="hidden" name="year" value="{{ request('year') }}"> @endif
                <input type="text" name="search" value="{{ request('search') }}" oninput="this.form.submit()" autofocus placeholder="Cari Obat....." class="w-full pl-4 pr-2 py-2 focus:outline-none text-sm">
                <button type="submit" class="px-3 flex items-center bg-gray-50 hover:bg-green-100 transition text-green-600 border-l border-gray-200 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="openTambahModal()"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg transition text-center shadow flex items-center justify-center gap-1.5 text-sm uppercase tracking-wide">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
                Obat
            </button>

            <button type="button" onclick="openTambahKatModal()"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg transition text-center shadow flex items-center justify-center gap-1.5 text-sm uppercase tracking-wide">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v12m6-6H6"></path></svg>
                Kategori
            </button>

            <div class="flex flex-col gap-1 items-center">
                <button type="button" onclick="openRekapSOModal()"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg transition text-center shadow flex items-center justify-center gap-1.5 text-sm uppercase tracking-wide w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Stock Opname
                </button>
                
                <!-- Period Filter Moved Here -->
                <form action="{{ route('obat.index') }}" method="GET" class="flex items-center gap-1.5 bg-gray-100/50 border border-gray-200 rounded-md px-1.5 py-0.5 w-full justify-center">
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    
                    <select name="month" onchange="this.form.submit()" class="text-[9px] font-bold uppercase bg-transparent outline-none border-none text-gray-500 hover:text-green-700 cursor-pointer px-0.5">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                    <span class="text-gray-300 text-[9px]">|</span>
                    <select name="year" onchange="this.form.submit()" class="text-[9px] font-bold uppercase bg-transparent outline-none border-none text-gray-500 hover:text-green-700 cursor-pointer px-0.5">
                        @for($y=2026; $y<=2040; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
@endfor
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max border-2 border-gray-500 shadow-sm rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100 uppercase text-xs font-bold text-gray-800 text-center">
                <th class="border border-gray-400 p-2 w-10">No</th>
                <th class="border border-gray-400 p-2 min-w-[200px]">NAMA BARANG</th>
                <th class="border border-gray-400 p-2 min-w-[120px]">Kategori</th>
                <th class="border border-gray-400 p-2 min-w-[100px]">HARGA</th>
                <th class="border border-gray-400 p-2 min-w-[80px]">SATUAN</th>
                <th class="border border-gray-400 p-2 min-w-[80px]">STOK AWAL</th>
                <th class="border border-gray-400 p-2 min-w-[90px] leading-tight">TOTAL<br>PENJUALAN</th>
                <th class="border border-gray-400 p-2 min-w-[90px] leading-tight">SISA<br>STOK</th>
                <th class="border border-gray-400 p-2 min-w-[120px] leading-tight">TOTAL HARGA<br>PENJUALAN</th>
                <th class="border border-gray-400 p-2 min-w-[120px]">TGL KADALUARSA</th>
                <th class="border border-gray-400 p-2 min-w-[100px]">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($obats as $index => $obat)
            @php $totalRow = $obat->stok_awal + $obat->masuk_bulan_ini; @endphp
            <tr class="hover:bg-gray-50 transition text-xs">
                <td class="py-2 px-2 text-center text-gray-800 font-medium border border-gray-400">
                    {{ $obats->firstItem() + $index }}
                </td>
                <td class="py-2 px-3 text-left text-gray-800 border border-gray-400">
                    <div class="flex items-center justify-between gap-2 group">
                        <span class="font-bold">{{ $obat->nama_obat }}</span>
                        @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) !== 'CEK')
                        <button type="button" 
                                onclick="openSOModal({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}', '{{ json_encode($obat->daily_so) }}', {{ $obat->terjual_bulan_ini }})"
                                class="opacity-0 group-hover:opacity-100 bg-green-50 text-green-600 p-1 rounded transition-all hover:bg-green-600 hover:text-white" title="Riwayat Harian">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                        @endif
                    </div>
                </td>
                <td class="py-2 px-3 text-left text-gray-800 font-medium border border-gray-400">
                    {{ $obat->kategori->nama_kategori ?? '-' }}
                </td>
                <td class="py-2 px-3 text-left text-gray-900 border border-gray-400 whitespace-nowrap">
                    Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}
                </td>
                <td class="py-2 px-3 text-center text-gray-800 border border-gray-400">
                    {{ (isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') ? '-' : ($obat->satuan->nama_satuan ?? '-') }}
                </td>
                <td class="py-2 px-3 text-center border border-gray-400 font-bold">
                    {{ (isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') ? '-' : $obat->stok_awal }}
                </td>
                <td class="py-2 px-3 text-center border border-gray-400 font-bold text-red-600">
                    {{ $obat->terjual_bulan_ini }}
                </td>
                <td class="py-2 px-3 text-center border border-gray-400 font-bold {{ (isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) !== 'CEK' && $obat->current_stok <= 5) ? 'text-red-500' : 'text-green-700' }}">
                    {{ (isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') ? '-' : $obat->current_stok }}
                </td>
                <td class="py-2 px-3 text-left text-gray-900 font-bold border border-gray-400 whitespace-nowrap">
                    Rp{{ number_format($obat->terjual_bulan_ini * $obat->harga_jual, 0, ',', '.') }}
                </td>
                <td class="py-2 px-3 text-center text-gray-900 border border-gray-400 whitespace-nowrap">
                    @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK')
                        <span class="text-gray-400 font-normal">-</span>
                    @elseif($obat->tanggal_kadaluarsa)
                        <span class="{{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d-m-Y') }}
                        </span>
                    @else
                        <span class="text-gray-300">-</span>
                    @endif
                </td>
                <td class="py-2 px-2 border border-gray-400">
                    <div class="flex justify-center items-center gap-1">
                        <button type="button"
                            data-id="{{ $obat->id }}"
                            data-nama="{{ $obat->nama_obat }}"
                            data-id-kategori="{{ $obat->id_kategori }}"
                            data-harga-jual="{{ $obat->harga_jual }}"
                            data-stok="{{ $obat->current_stok }}"
                            data-stok-awal="{{ $obat->stok_awal }}"
                            data-barang-datang="{{ $obat->masuk_bulan_ini }}"
                            data-id-satuan="{{ $obat->id_satuan }}"
                            data-kode-obat="{{ $obat->kode_obat }}"
                            data-harga-beli="{{ $obat->harga_beli }}"
                            data-expired-date="{{ $obat->tanggal_kadaluarsa ?? '' }}"
                            data-stok-minimal="{{ $obat->batas_stok_minimal }}"
                            onclick="openEditModal(this)"
                            class="bg-green-600 hover:bg-green-700 text-white p-1 rounded transition shadow-sm" title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button type="button"
                            onclick="openHapusModal({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}')"
                            class="bg-red-600 hover:bg-red-700 text-white p-1 rounded transition shadow-sm" title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="13" class="py-12 text-center text-gray-400 border border-gray-300">Data obat belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Section -->
<div class="mt-8 mb-10 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
    <div class="text-xs text-gray-400">
        * Menampilkan seluruh data obat dan stok yang tersedia.
    </div>
    <div class="flex gap-2">
        @if($obats->onFirstPage())
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed flex items-center">
                <span class="bg-gray-300 text-white w-5 h-5 flex items-center justify-center rounded mr-2">&#9664;</span> Back
            </span>
        @else
            <a href="{{ $obats->previousPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md flex items-center">
                <span class="bg-green-500 text-white w-5 h-5 flex items-center justify-center rounded mr-2">&#9664;</span> Back
            </a>
        @endif

        @if($obats->hasMorePages())
            <a href="{{ $obats->nextPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md flex items-center">
                Next <span class="bg-green-500 text-white w-5 h-5 flex items-center justify-center rounded ml-2">&#9654;</span>
            </a>
        @else
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed flex items-center">
                Next <span class="bg-gray-300 text-white w-5 h-5 flex items-center justify-center rounded ml-2">&#9654;</span>
            </span>
        @endif
    </div>
</div>


@endsection

@section('modals')
{{--  MODAL TAMBAH BARANG --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/60" onclick="closeTambahModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Tambah Barang</h3>
            <button onclick="closeTambahModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-2 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formTambah" action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <input type="hidden" name="kode_obat" id="tambah_kode_obat">
                
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori Obat <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="tambah_id_kategori" onchange="toggleCekFields('tambah')" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm cursor-pointer text-sm">
                        <option value="" class="normal-case">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label id="tambah_nama_label" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat <span class="text-red-500 star-required">*</span></label>
                    <input type="text" name="nama_obat" id="tambah_nama_obat" required placeholder="Contoh: OB Herbal Syr 60ml" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                </div>

                <div class="space-y-1" id="tambah_gambar_wrapper">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Gambar Obat (Opsional)</label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-gray-600 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>

                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="space-y-1" id="tambah_harga_beli_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Beli (Modal) <span class="text-red-500 star-required">*</span></label>
                        <input type="number" name="harga_beli" id="tambah_harga_beli" min="0" required placeholder="Rp" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual <span class="text-red-500 star-required">*</span></label>
                        <input type="number" name="harga_jual" id="tambah_harga_jual" min="0" required placeholder="Rp" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="space-y-1" id="tambah_satuan_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Satuan <span class="text-red-500 star-required">*</span></label>
                        <select name="id_satuan" id="tambah_id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm text-sm font-medium">
                            <option value="">-- Pilih --</option>
                            @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1 flex flex-col" id="tambah_expired_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Tgl Kadaluarsa</label>
                        <input type="date" name="expired_date" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1" id="tambah_stok_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Stok Awal <span class="text-red-500 star-required">*</span></label>
                        <input type="number" name="stok_awal" id="tambah_stok_awal" min="0" placeholder="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold" oninput="calculateSisaStokTambah()">
                    </div>

                    <div class="space-y-1" id="tambah_sisa_stok_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Sisa Stok</label>
                        <input type="number" id="tambah_sisa_stok" min="0" placeholder="0" readonly class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-500 bg-gray-100 shadow-sm focus:outline-none focus:ring-0 text-sm font-bold cursor-not-allowed">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Minimal Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="batas_stok_minimal" id="tambah_batas_stok_minimal" min="0" value="5" required placeholder="Contoh: 5" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
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
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/60" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-800 px-6 py-4 flex items-center justify-between text-white border-b border-green-900">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Edit Obat</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-gray-200 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-2 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')
                <input type="hidden" name="kode_obat" id="edit_kode_obat">
                
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori Obat <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="edit_id_kategori" onchange="toggleCekFields('edit')" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm uppercase text-sm font-medium">
                        <option value="" class="normal-case">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label id="edit_nama_label" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat <span class="text-red-500 star-required">*</span></label>
                    <input type="text" name="nama_obat" id="edit_nama_obat" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                </div>

                <div class="space-y-1" id="edit_gambar_wrapper">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Update Gambar Obat (Opsional)</label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-gray-600 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>

                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="space-y-1" id="edit_harga_beli_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Beli (Modal) <span class="text-red-500 star-required">*</span></label>
                        <input type="number" name="harga_beli" id="edit_harga_beli" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual <span class="text-red-500 star-required">*</span></label>
                        <input type="number" name="harga_jual" id="edit_harga_jual" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="space-y-1" id="edit_satuan_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Satuan <span class="text-red-500 star-required">*</span></label>
                        <select name="id_satuan" id="edit_id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm text-sm font-medium">
                            <option value="">-- Satuan --</option>
                            @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1 flex flex-col" id="edit_expired_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Tgl Kadaluarsa</label>
                        <input type="date" name="expired_date" id="edit_expired_date" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1" id="edit_stok_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Stok Awal <span class="text-red-500 star-required">*</span></label>
                        <input type="number" name="stok_awal" id="edit_stok_awal" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium" oninput="calculateSisaStokEdit()">
                    </div>

                    <div class="space-y-1" id="edit_sisa_stok_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Sisa Stok</label>
                        <input type="number" id="edit_sisa_stok" min="0" readonly class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-500 bg-gray-100 shadow-sm focus:outline-none focus:ring-0 text-sm font-medium cursor-not-allowed">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Minimal Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="batas_stok_minimal" id="edit_batas_stok_minimal" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
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
<div id="modalHapus" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/60" onclick="closeHapusModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-80 mx-4 overflow-hidden animate-modal">
        <div class="bg-red-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">KONFIRMASI HAPUS</h4>
        </div>
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">Hapus Obat ini?</p>
            <p class="text-[11px] text-gray-500 leading-relaxed">
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
<div id="modalTambahKat" class="fixed inset-0 z-50 hidden flex items-start sm:items-center justify-center p-4 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-black/60" onclick="closeTambahKatModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md my-8 overflow-hidden animate-modal flex flex-col max-h-[90vh]">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white shrink-0">
            <h3 class="text-xl font-bold tracking-wide w-full text-center">Kategori</h3>
            <button onclick="closeTambahKatModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="p-8 overflow-y-auto flex-1 custom-scrollbar">
            <form id="formTambahKat" action="{{ route('kategori.store') }}" method="POST" class="space-y-4 mb-6">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="nama_kategori" required placeholder="Kategori Baru" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                    <button type="button" onclick="showSuccessAnimation('formTambahKat', 'Kategori Ditambahkan!')" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-extrabold shadow-lg transition text-sm">Simpan</button>
                </div>
            </form>
            <div class="border-t border-gray-100 pt-6">
                <div class="space-y-2">
                    @foreach($kategoris as $kat)
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100 group hover:border-green-200 transition-all">
                            <span class="text-sm font-bold text-gray-700 tracking-tight">{{ $kat->nama_kategori }}</span>
                            <form id="formHapusKat{{ $kat->id }}" action="{{ route('kategori.destroy', $kat->id) }}" method="POST">@csrf @method('DELETE')<button type="button" onclick="confirmHapusKat('{{ $kat->id }}', '{{ $kat->nama_kategori }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex justify-end px-8 py-5 bg-gray-50 border-t border-gray-100 shrink-0">
            <button type="button" onclick="closeTambahKatModal()" class="px-8 py-2 text-xs font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-md transition tracking-[0.1em]">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS KATEGORI --}}
<div id="modalHapusKat" class="fixed inset-0 z-[110] hidden flex items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/60" onclick="closeHapusKatModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-80 mx-4 overflow-hidden animate-modal">
        <div class="bg-red-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">KONFIRMASI HAPUS</h4>
        </div>
        <div class="px-6 pt-6 pb-4 text-center">
            <p id="hapus_kat_title" class="text-base font-semibold text-gray-800 mb-2 truncate px-2 text-center">Hapus Kategori ini?</p>
            <p class="text-[11px] text-gray-500 leading-relaxed text-center">
                Data yang dihapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 mt-2">
            <button type="button" onclick="closeHapusKatModal()" class="flex-1 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition uppercase tracking-wider">BATAL</button>
            <form id="formHapusKatFinal" action="" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="button" onclick="showSuccessAnimation('formHapusKatFinal', 'Kategori Berhasil Dihapus!')" class="w-full py-2 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition uppercase tracking-wider">YA, HAPUS</button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL SO --}}
<div id="modalSO" class="fixed inset-0 z-[110] hidden flex items-center justify-center p-4" style="display: none;">
    <div class="absolute inset-0 bg-black/60" onclick="closeSOModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto overflow-hidden animate-modal flex flex-col max-h-[90vh]">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white shrink-0">
            <h3 class="text-xl font-bold tracking-wide uppercase flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span id="so_modal_title">Stock Opname</span>
            </h3>
            <button onclick="closeSOModal()" class="text-green-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 custom-scrollbar w-full bg-gray-50">
            <div class="mb-4 flex gap-4 text-sm bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="flex-1">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Periode</p>
                    <div class="flex gap-2 items-center mt-1">
                        <select id="so_modal_month" onchange="changeSOPeriod()" class="border border-gray-300 rounded px-2 py-1 bg-white font-bold text-gray-800 text-xs focus:ring-green-500 focus:border-green-500 shadow-sm cursor-pointer border-r-[8px] border-r-transparent">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        <select id="so_modal_year" onchange="changeSOPeriod()" class="border border-gray-300 rounded px-2 py-1 bg-white font-bold text-gray-800 text-xs focus:ring-green-500 focus:border-green-500 shadow-sm cursor-pointer border-r-[8px] border-r-transparent">
                            @for ($y = 2026; $y <= 2030; $y++)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-1">Total Penjualan</p>
                    <p class="font-bold text-red-600" id="so_modal_total_penjualan">0</p>
                </div>
            </div>
            <div class="overflow-x-auto w-full border border-gray-300 rounded-lg shadow-sm">
                <table class="w-full text-left border-collapse min-w-max bg-white">
                    <thead>
                        <tr id="so_modal_header_row" class="bg-gray-100 uppercase text-[10px] font-bold text-gray-800 text-center">
                            <!-- JS populated -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="so_modal_row" class="text-sm hover:bg-gray-50 transition">
                            <!-- JS will populate td here -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex justify-end px-8 py-5 bg-white border-t border-gray-200 shrink-0">
            <button type="button" onclick="simpanSOModal()" class="px-8 py-2.5 text-xs font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-md transition uppercase tracking-[0.1em]">Simpan</button>
        </div>
    </div>
</div>


<style>
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-modal { animation: modalIn 0.2s ease-out both; }
    .no-spinners::-webkit-outer-spin-button,
    .no-spinners::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .no-spinners[type=number] {
        -moz-appearance: textfield;
    }
</style>

@push('scripts')
<script>
    let currentSOObatId = null;
    let hasSOSaved = false;

    function openSOModal(idObat, namaObat, dailySOJson, totalPenjualan) {
        currentSOObatId = idObat;
        hasSOSaved = false;
        document.getElementById('so_modal_title').textContent = namaObat;
        
        const month = parseInt('{{ $month }}');
        const year = '{{ $year }}';
        const daysInMonth = {{ $daysInMonth }};
        const dailySO = JSON.parse(dailySOJson);
        
        document.getElementById('so_modal_month').value = month;
        document.getElementById('so_modal_year').value = year;
        
        rebuildSOTable(dailySO, daysInMonth, month, year);
        
        document.getElementById('so_modal_total_penjualan').textContent = totalPenjualan + ' Terjual (Bulan ini)';
        const modal = document.getElementById('modalSO');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function rebuildSOTable(dailySO, daysInMonth, rawMonth, rawYear) {
        const headerRow = document.getElementById('so_modal_header_row');
        const boxRow = document.getElementById('so_modal_row');
        headerRow.innerHTML = '';
        boxRow.innerHTML = '';
        
        const year = rawYear;
        const month = rawMonth.toString().padStart(2, '0');
        
        for(let i = 1; i <= daysInMonth; i++) {
            const th = document.createElement('th');
            th.className = 'border border-gray-300 px-1 py-1 bg-red-600 text-white w-8 font-bold text-center text-[10px]';
            th.textContent = i;
            headerRow.appendChild(th);
            
            const val = dailySO[i] || 0;
            const td = document.createElement('td');
            td.className = 'p-0 border border-gray-300 relative w-8';
            
            const input = document.createElement('input');
            input.type = 'number';
            input.min = '0';
            input.className = 'w-10 h-8 text-center focus:outline-none focus:ring-inset focus:ring-2 focus:ring-green-500 text-xs font-bold transition-all bg-transparent no-spinners ' + (val > 0 ? 'bg-green-50 text-green-600' : 'text-gray-600 hover:bg-gray-50');
            input.value = val > 0 ? val : '';
            input.placeholder = '';
            
            input.addEventListener('blur', function() {
                const newValue = this.value === '' ? 0 : parseInt(this.value);
                const oldValue = val;
                
                if (newValue !== oldValue) {
                    const paddedDay = i.toString().padStart(2, '0');
                    const tanggal = `${year}-${month}-${paddedDay}`;
                    saveSO(currentSOObatId, tanggal, newValue, this);
                    dailySO[i] = newValue; // Update ref locally
                }
            });
            
            td.appendChild(input);
            boxRow.appendChild(td);
        }
    }

    function changeSOPeriod() {
        if (!currentSOObatId) return;
        const month = document.getElementById('so_modal_month').value;
        const year = document.getElementById('so_modal_year').value;
        
        document.getElementById('so_modal_row').style.opacity = '0.5';
        
        fetch(`/obat/${currentSOObatId}/so-data?month=${month}&year=${year}`)
            .then(res => res.json())
            .then(data => {
                rebuildSOTable(data.daily_so, data.daysInMonth, month, year);
                document.getElementById('so_modal_total_penjualan').textContent = data.terjual_bulan_ini + ' Terjual';
                document.getElementById('so_modal_row').style.opacity = '1';
            }).catch(() => {
                document.getElementById('so_modal_row').style.opacity = '1';
                alert('Gagal memuat data periode tersebut.');
            });
    }

    function saveSO(idObat, tanggal, jumlah, inputElement) {
        hasSOSaved = true;
        inputElement.classList.add('opacity-50', 'animate-pulse');
        fetch('{{ route("obat.save_so") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_obat: idObat,
                tanggal: tanggal,
                jumlah: jumlah
            })
        }).then(r => r.json()).then(data => {
            inputElement.classList.remove('opacity-50', 'animate-pulse');
            if(jumlah > 0) {
                inputElement.classList.add('bg-green-50', 'text-green-600');
                inputElement.classList.remove('text-gray-600', 'hover:bg-gray-50');
            } else {
                inputElement.classList.remove('bg-green-50', 'text-green-600');
                inputElement.classList.add('text-gray-600', 'hover:bg-gray-50');
            }
        }).catch(err => {
            console.error('Failed to save', err);
            inputElement.classList.remove('opacity-50', 'animate-pulse');
            alert('Gagal menyimpan SO. Silakan coba lagi.');
        });
    }

    function closeSOModal() {
        const modal = document.getElementById('modalSO');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function openTambahModal() {
        document.getElementById('tambah_kode_obat').value = 'OBT-' + Date.now();
        const modal = document.getElementById('modalTambah');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        toggleCekFields('tambah');
    }
    function closeTambahModal() { 
        const modal = document.getElementById('modalTambah');
        modal.classList.add('hidden'); 
        modal.style.display = 'none';
        document.body.style.overflow = ''; 
    }
    function toggleCekFields(prefix) {
        const sel = document.getElementById(prefix + '_id_kategori');
        if (!sel) return;
        const selectedOption = sel.options[sel.selectedIndex];
        const isCek = selectedOption && selectedOption.getAttribute('data-nama') && selectedOption.getAttribute('data-nama').toUpperCase() === 'CEK';
        
        const stokWrapper = document.getElementById(prefix + '_stok_wrapper');
        const sisaStokWrapper = document.getElementById(prefix + '_sisa_stok_wrapper');
        const expWrapper = document.getElementById(prefix + '_expired_wrapper');
        const hargaBeliWrapper = document.getElementById(prefix + '_harga_beli_wrapper');
        const gambarWrapper = document.getElementById(prefix + '_gambar_wrapper');
        const satuanWrapper = document.getElementById(prefix + '_satuan_wrapper');
        const minStokInput = document.getElementById(prefix + '_batas_stok_minimal');
        const minStokWrapper = minStokInput ? minStokInput.parentElement : null;
        
        const namaLabel = document.getElementById(prefix + '_nama_label');
        
        // Fields that should become optional
        const fieldsToToggle = [
            prefix + '_nama_obat',
            prefix + '_harga_beli',
            prefix + '_harga_jual',
            prefix + '_id_satuan',
            prefix + '_stok_awal'
        ];

        // Stars to hide/show
        const modalId = prefix === 'tambah' ? 'modalTambah' : 'modalEdit';
        const stars = document.querySelectorAll('#' + modalId + ' .star-required');

        if (isCek) {
            // Hide everything except Category, Name (Jenis Cek), and Price Jual
            if (stokWrapper) stokWrapper.classList.add('hidden');
            if (sisaStokWrapper) sisaStokWrapper.classList.add('hidden');
            if (expWrapper) expWrapper.classList.add('hidden');
            if (hargaBeliWrapper) hargaBeliWrapper.classList.add('hidden');
            if (gambarWrapper) gambarWrapper.classList.add('hidden');
            if (satuanWrapper) satuanWrapper.classList.add('hidden');
            if (minStokWrapper) minStokWrapper.classList.add('hidden');
            
            // Rename Label
            if (namaLabel) {
                namaLabel.innerHTML = 'Jenis Cek <span class="text-red-500">*</span>';
            }
            
            // Remove required labels (red stars) for hidden fields
            stars.forEach(star => {
                if (!star.parentElement.id.includes('nama_label')) {
                    star.classList.add('hidden');
                }
            });
            
            // Remove required attribute from inputs
            fieldsToToggle.forEach(id => {
                const el = document.getElementById(id);
                if (el && !id.includes('nama_obat') && !id.includes('harga_jual')) {
                    el.required = false;
                }
            });

            if (minStokInput) minStokInput.required = false;

            // Set internal defaults
            if (prefix === 'tambah') {
                const stokInput = document.getElementById('tambah_stok_awal');
                if (stokInput) stokInput.value = 9999; 
            }
        } else {
            // Restore visibility
            if (stokWrapper) stokWrapper.classList.remove('hidden');
            if (sisaStokWrapper) sisaStokWrapper.classList.remove('hidden');
            if (expWrapper) expWrapper.classList.remove('hidden');
            if (hargaBeliWrapper) hargaBeliWrapper.classList.remove('hidden');
            if (gambarWrapper) gambarWrapper.classList.remove('hidden');
            if (satuanWrapper) satuanWrapper.classList.remove('hidden');
            if (minStokWrapper) minStokWrapper.classList.remove('hidden');
            
            // Restore Label
            if (namaLabel) {
                namaLabel.innerHTML = 'Nama Obat <span class="text-red-500">*</span>';
            }
            
            // Show required labels (red stars)
            stars.forEach(star => star.classList.remove('hidden'));
            
            // Add required attribute back to inputs
            fieldsToToggle.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.required = true;
            });

            if (minStokInput) minStokInput.required = true;
        }
    }

    function calculateSisaStokTambah() {
        let awal = parseInt(document.getElementById('tambah_stok_awal').value) || 0;
        document.getElementById('tambah_sisa_stok').value = awal;
    }
    function calculateSisaStokEdit() {
        let awal = parseInt(document.getElementById('edit_stok_awal').value) || 0;
        document.getElementById('edit_sisa_stok').value = awal;
    }
    
    function openEditModal(el) {
        const d = el.dataset;
        const form = document.getElementById('formEdit');
        form.action = '{{ url("obat") }}/' + d.id;
        document.getElementById('edit_kode_obat').value = d.kodeObat;
        document.getElementById('edit_harga_beli').value = d.hargaBeli;
        document.getElementById('edit_nama_obat').value = d.nama;
        document.getElementById('edit_harga_jual').value = d.hargaJual;
        document.getElementById('edit_batas_stok_minimal').value = d.stokMinimal || 10;
        
        document.getElementById('edit_stok_awal').value = d.stokAwal || 0;
        calculateSisaStokEdit();
        
        document.getElementById('edit_expired_date').value = d.expiredDate;
        document.getElementById('edit_id_kategori').value = d.idKategori;
        document.getElementById('edit_id_satuan').value = d.idSatuan;
        toggleCekFields('edit');
        const modal = document.getElementById('modalEdit');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() { 
        const modal = document.getElementById('modalEdit');
        modal.classList.add('hidden'); 
        modal.style.display = 'none';
        document.body.style.overflow = ''; 
    }
    function openHapusModal(id, nama) {
        document.getElementById('formHapus').action = '{{ url("obat") }}/' + id;
        const modal = document.getElementById('modalHapus');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeHapusModal() { 
        const modal = document.getElementById('modalHapus');
        modal.classList.add('hidden'); 
        modal.style.display = 'none';
        document.body.style.overflow = ''; 
    }
    function openTambahKatModal() { 
        const modal = document.getElementById('modalTambahKat');
        modal.classList.remove('hidden'); 
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; 
    }
    function closeTambahKatModal() { 
        const modal = document.getElementById('modalTambahKat');
        modal.classList.add('hidden'); 
        modal.style.display = 'none';
        document.body.style.overflow = ''; 
    }
    
    function confirmHapusKat(id, nama) {
        const modal = document.getElementById('modalHapusKat');
        document.getElementById('formHapusKatFinal').action = '{{ url("kategori") }}/' + id;
        document.getElementById('hapus_kat_title').textContent = 'Hapus kategori ' + nama + '?';
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeHapusKatModal() {
        const modal = document.getElementById('modalHapusKat');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function simpanSOModal() {
        if (document.activeElement && document.activeElement.tagName === 'INPUT') {
            document.activeElement.blur();
        }
        
        // Timeout to allow blur to trigger saveSO if necessary
        setTimeout(() => {
            let adaIsi = false;
            const inputs = document.querySelectorAll('#so_modal_row input');
            inputs.forEach(input => {
                if (input.value && parseInt(input.value) > 0) {
                    adaIsi = true;
                }
            });
            
            if (hasSOSaved || adaIsi) {
                showSuccessPopup('Tersimpan!', () => {
                    closeSOModal();
                    if (hasSOSaved) {
                        window.location.reload();
                    }
                });
            } else {
                closeSOModal();
            }
        }, 100);
    }
</script>
@endpush




{{-- MODAL REKAP STOCK OPNAME --}}
<div id="modalRekapSO" class="fixed inset-0 z-50 hidden flex items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/60 transition-opacity" onclick="closeRekapSOModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-5xl mx-4 animate-modal overflow-hidden flex flex-col max-h-[90vh] border border-gray-100">
        <div class="flex items-center justify-between px-8 py-5 bg-green-700 text-white shadow-lg">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <div class="flex flex-col">
                    <h3 class="text-lg font-black uppercase tracking-widest leading-none">Rekap Stock Opname</h3>
                    <p class="text-xs font-bold text-green-200 mt-1 uppercase">{{ $monthName }}</p>
                </div>
            </div>
            <button onclick="closeRekapSOModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-all text-white text-2xl font-light">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                 <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Perbandingan Data Sistem & Fisik</h4>
                 
                 <div class="flex items-center gap-3 w-full md:w-auto">
                     <!-- Category & Search in Modal -->
                     <div class="flex items-center gap-2">
                         <select id="filterCategorySO" onchange="filterSOObat()" 
                             class="px-3 py-1.5 border border-gray-300 rounded-xl text-[10px] font-bold tracking-widest focus:ring-2 focus:ring-green-500 outline-none bg-white shadow-sm">
                             <option value="">SEMUA KATEGORI</option>
                             @foreach($kategoris as $kat)
                                 <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                             @endforeach
                         </select>
  
                         <div class="relative flex-1 md:w-64 border border-gray-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-green-500 bg-white transition-all shadow-sm">
                             <input type="text" id="searchSO" placeholder="Cari Nama Obat..." oninput="filterSOObat()"
                                 class="w-full pl-9 pr-4 py-1.5 text-[10px] font-bold outline-none uppercase text-gray-900">
                             <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-900">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                             </div>
                         </div>
                     </div>

                    <a href="{{ route('obat.cetak_so', ['month' => $month, 'year' => $year]) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-black text-[9px] flex items-center gap-2 transition-all shadow-md uppercase tracking-wide">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </a>
                 </div>
            </div>

            <div class="border border-gray-200 shadow-inner rounded-2xl overflow-hidden mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 font-black text-[11px] uppercase tracking-widest border-b border-gray-200">
                            <th class="px-5 py-4 text-center font-black text-gray-900 border-x border-gray-100">No</th>
                            <th class="px-5 py-4 text-center text-gray-900 border-x border-gray-100">Nama Obat</th>
                            <th class="px-2 py-4 text-center font-black text-gray-900 border-x border-gray-100">Satuan</th>
                            <th class="px-2 py-4 text-center font-black text-gray-900 border-x border-gray-100">Awal</th>
                            <th class="px-2 py-4 text-center font-black text-gray-900 border-x border-gray-100">Masuk</th>
                            <th class="px-2 py-4 text-center font-black text-gray-900 border-x border-gray-100">Terjual</th>
                            <th class="px-3 py-4 text-center font-black text-gray-900 border-x border-gray-100">Sistem</th>
                            <th class="px-5 py-4 text-center font-black text-gray-900 border-x border-gray-100">Fisik</th>
                            <th class="px-2 py-4 text-center font-black text-gray-900 border-x border-gray-100">Tanggal SO</th>
                            <th class="px-3 py-4 text-center font-black text-gray-900 border-x border-gray-100">Selisih</th>
                            <th class="px-5 py-4 text-center font-black text-gray-900 border-x border-gray-100">Selisih Keuangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $totalKerugian = 0; $counter = 1; @endphp
                        @foreach($obats as $o)
                        @if(isset($o->kategori) && strtoupper($o->kategori->nama_kategori) === 'CEK') @continue @endif
                        @php 
                            $nominalSelisih = $o->selisih * $o->harga_beli; 
                            $totalKerugian += $nominalSelisih;
                        @endphp
                        <tr class="text-xs hover:bg-gray-50 transition-colors so-row" data-name="{{ strtolower($o->nama_obat) }}" data-category="{{ $o->id_kategori }}">
                            <td class="px-5 py-4 text-center font-medium">{{ $counter++ }}</td>
                            <td class="px-5 py-4">
                                <span class="font-black uppercase text-gray-800">{{ $o->nama_obat }}</span>
                                <span class="block text-xs text-gray-900">Modal: Rp{{ number_format($o->harga_beli, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-2 py-4 text-center font-bold text-gray-900 uppercase">{{ $o->satuan->nama_satuan ?? '-' }}</td>
                            <td class="px-2 py-4 text-center font-bold">{{ $o->stok_awal }}</td>
                            <td class="px-2 py-4 text-center font-bold text-green-600">{{ $o->masuk_bulan_ini }}</td>
                            <td class="px-2 py-4 text-center font-bold text-red-600">{{ $o->terjual_bulan_ini }}</td>
                            <td class="px-3 py-4 text-center font-black text-gray-900 bg-gray-50/50">{{ $o->expected_stok }}</td>
                             @php 
                                $today = date('Y-m-d');
                                $isAuditedToday = (!empty($o->last_so_id) && $o->last_so_date == $today);
                                // All SO related inputs now always blue as per request
                                $colorClass = 'text-green-800 border-green-300 bg-green-50';
                            @endphp
                            <td class="px-5 py-4 text-center border-l border-green-100/30">
                                <input type="number" value="{{ $o->total_so > 0 ? $o->total_so : '' }}" id="rekap_fisik_{{ $o->id }}" 
                                    oninput="updateSelisihLive({{ $o->id }}, {{ $o->expected_stok }}, {{ $o->harga_beli }})"
                                    onblur="saveSyncSO({{ $o->id }})"
                                    class="w-16 px-1 py-1 text-center font-black rounded-lg focus:ring-2 focus:ring-green-500 outline-none shadow-inner transition-all sm:text-xs {{ $colorClass }}">
                            </td>
                            <td class="px-2 py-4 text-center border-r border-green-100/30">
                                <input type="date" value="{{ $o->last_so_date }}" id="rekap_date_{{ $o->id }}"
                                    onchange="updateSODateSync({{ $o->last_so_id }}, this, {{ $o->id }})"
                                    class="text-xs font-bold px-2 py-1 rounded border focus:ring-1 focus:ring-green-500 outline-none cursor-pointer {{ $colorClass }}">
                            </td>
                            <td class="px-3 py-4 text-center">
                                <div id="selisih_wrapper_{{ $o->id }}">
                                    @if($o->selisih == 0)
                                        <span class="text-gray-900 font-black">OK</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-lg font-black {{ $o->selisih < 0 ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                                            {{ ($o->selisih > 0 ? '+' : '') . $o->selisih }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span id="nominal_wrapper_{{ $o->id }}" class="font-black {{ $nominalSelisih < 0 ? 'text-red-600' : ($nominalSelisih > 0 ? 'text-green-600' : 'text-gray-900') }}">
                                        {{ $nominalSelisih == 0 ? 'Rp 0' : ($nominalSelisih > 0 ? '+' : '-') . ' Rp' . number_format(abs($nominalSelisih), 0, ',', '.') }}
                                    </span>
                                    <button type="button" id="btn_sync_{{ $o->id }}"
                                        onclick="syncStockQuick({{ $o->id }}, '{{ addslashes($o->nama_obat) }}')"
                                        class="text-xs font-black text-red-600 uppercase border-b border-red-200 hover:border-red-600 transition-all {{ $o->selisih == 0 ? 'hidden' : '' }}">
                                        Sesuaikan
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                <div class="bg-gray-100 p-3 rounded-xl border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-900 uppercase tracking-widest">Total Selisih Keuangan</p>
                        <h5 id="total_nominal_rekap" class="text-sm font-black {{ $totalKerugian < 0 ? 'text-red-700' : ($totalKerugian > 0 ? 'text-green-700' : 'text-gray-900') }}">
                            {{ $totalKerugian == 0 ? 'Rp 0' : ($totalKerugian > 0 ? '+' : '-') . ' Rp' . number_format(abs($totalKerugian), 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
                <div class="bg-green-50/50 p-3 rounded-xl border border-green-100 flex items-center">
                    <p class="text-xs text-gray-900 font-bold">
                        * Selisih Keuangan = (Fisik - Sistem) × Modal. <br>
                        * Klik "Sesuaikan" untuk memperbarui stok sistem.
                    </p>
                </div>
            </div>
        </div>
        <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button onclick="simpanRekapFinal()" class="px-10 py-3 bg-green-600 text-white hover:bg-green-700 rounded-xl transition-all font-black uppercase tracking-widest text-[10px] shadow-lg hover:shadow-green-200 flex items-center gap-2">
                Simpan
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openRekapSOModal() {
        const modal = document.getElementById('modalRekapSO');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeRekapSOModal() {
        const modal = document.getElementById('modalRekapSO');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    function updateSelisihLive(id, exp, price) {
        const fisik = parseInt(document.getElementById(`rekap_fisik_${id}`).value) || 0;
        const sel = fisik - exp;
        const nominal = sel * price;
        
        const wrapper = document.getElementById(`selisih_wrapper_${id}`);
        const nomWrapper = document.getElementById(`nominal_wrapper_${id}`);
        const btn = document.getElementById(`btn_sync_${id}`);
        
        // Update Unit Wrap
        if(sel === 0) {
            wrapper.innerHTML = '<span class="text-gray-300 font-black">OK</span>';
            nomWrapper.innerHTML = 'Rp 0';
            nomWrapper.className = 'font-black text-gray-300';
            btn.classList.add('hidden');
        } else {
            wrapper.innerHTML = `<span class="px-2 py-0.5 rounded-lg font-black ${sel < 0 ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}">${sel > 0 ? '+' : ''}${sel}</span>`;
            
            const formattedNom = (nominal > 0 ? '+' : '-') + ' Rp' + Math.abs(nominal).toLocaleString('id-ID');
            nomWrapper.innerHTML = formattedNom;
            nomWrapper.className = `font-black ${nominal < 0 ? 'text-red-600' : 'text-green-600'}`;
            btn.classList.remove('hidden');
        }

        // Re-calculate Total Overall (Basic estimate)
        updateTotalNominalOverall();
    }

    function updateTotalNominalOverall() {
        let total = 0;
        const nominalElements = document.querySelectorAll('[id^="nominal_wrapper_"]');
        
        nominalElements.forEach(el => {
            const text = el.innerText.replace(/[^0-9-]/g, '');
            if (text && text !== '-') {
                total += parseInt(text);
            }
        });

        const totalEl = document.getElementById('total_nominal_rekap');
        if (totalEl) {
            const formattedTotal = (total === 0 ? 'Rp 0' : (total > 0 ? '+' : '-') + ' Rp' + Math.abs(total).toLocaleString('id-ID'));
            totalEl.innerText = formattedTotal;
            
            // Update color
            totalEl.className = `text-sm font-black ${total < 0 ? 'text-red-700' : (total > 0 ? 'text-green-700' : 'text-gray-900')}`;
        }
    }
    function syncStockQuick(id, nama) {
        const fisik = parseInt(document.getElementById(`rekap_fisik_${id}`).value) || 0;
        const date = "{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-01";
        if(confirm(`Sinkronkan "${nama}" ke ${fisik} unit untuk periode {{ $monthName }}?`)) {
            fetch("{{ route('obat.save_so') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ id_obat: id, tanggal: date, jumlah: fisik })
            }).then(() => {
                return fetch("{{ route('obat.sync_stock') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ id_obat: id, fisik: fisik })
                });
            }).then(r => r.json()).then(data => {
                if(data.success) showSuccessPopup('Sinkronisasi Berhasil!', () => window.location.reload());
            });
        }
    }

    function filterSOObat() {
        const q = document.getElementById('searchSO').value.toLowerCase().trim();
        const catId = document.getElementById('filterCategorySO').value;
        
        document.querySelectorAll('.so-row').forEach(row => {
            const name = row.dataset.name || '';
            const rowCat = row.dataset.category || '';
            
            const matchName = name.includes(q);
            const matchCat = catId === "" || rowCat === catId;
            
            if (matchName && matchCat) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
        
        // Update total live
        updateTotalNominalOverall();
    }

    function applyAuditedColor(target, isToday) {
        if (!target) return;
        target.classList.remove('text-red-700', 'border-red-300', 'bg-red-50', 'text-green-800', 'border-green-300', 'bg-green-50');
        // Always apply green theme now
        target.classList.add('text-green-800', 'border-green-300', 'bg-green-50');
    }

    function saveSyncSO(id) {
        const input = document.getElementById(`rekap_fisik_${id}`);
        const dateInput = document.getElementById(`rekap_date_${id}`);
        const fisik = parseInt(input.value) || 0;
        
        let day = "01";
        const now = new Date();
        const currentMonth = (now.getMonth() + 1).toString().padStart(2, '0');
        const currentYear = now.getFullYear().toString();
        const selectedMonth = "{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}";
        const selectedYear = "{{ $year }}";

        if (selectedMonth === currentMonth && selectedYear === currentYear) {
            day = now.getDate().toString().padStart(2, '0');
        }
        
        const date = `${selectedYear}-${selectedMonth}-${day}`;
        const today = now.toLocaleDateString('en-CA');
        
        input.classList.add('opacity-50', 'animate-pulse');
        
        fetch("{{ route('obat.save_so') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ id_obat: id, tanggal: date, jumlah: fisik })
        }).then(res => res.json()).then(data => {
            input.classList.remove('opacity-50', 'animate-pulse');
            if(data.success) {
                const isToday = (date === today);
                applyAuditedColor(input, isToday);
                if(dateInput) {
                    dateInput.value = date;
                    applyAuditedColor(dateInput, isToday);
                }
                
                input.classList.add('border-green-400');
                setTimeout(() => input.classList.remove('border-green-400'), 1000);
            }
        }).catch(() => {
            input.classList.remove('opacity-50', 'animate-pulse');
            input.classList.add('border-red-400');
        });
    }

    function simpanRekapFinal() {
        showSuccessPopup('Data Rekap Tersimpan!', () => {
            closeRekapSOModal();
            window.location.reload();
        });
    }

    function updateSODateSync(id_so, input, id_obat) {
        if(!id_so) return;
        const newDate = input.value;
        const fisInput = document.getElementById(`rekap_fisik_${id_obat}`);
        const today = new Date().toLocaleDateString('en-CA');
        
        input.classList.add('opacity-50', 'animate-pulse');
        
        fetch("{{ route('obat.update_so_date') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ id_so: id_so, tanggal: newDate })
        }).then(res => res.json()).then(data => {
            input.classList.remove('opacity-50', 'animate-pulse');
            if(data.success) {
                const isToday = (newDate === today);
                applyAuditedColor(input, isToday);
                applyAuditedColor(fisInput, isToday);
                
                input.classList.add('border-green-400');
                setTimeout(() => input.classList.remove('border-green-400'), 1000);
            } else {
                alert(data.message || 'Gagal mengubah tanggal');
                window.location.reload();
            }
        }).catch(() => {
            input.classList.remove('opacity-50', 'animate-pulse');
            input.classList.add('border-red-400');
        });
    }
</script>
@endpush
