@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2 flex items-center gap-3">
        SUPPLIER
    </h2>
</div>

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

@if(session('error'))
    <div id="flash-error" class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-red-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        <button onclick="dismissAlert('flash-error')" class="text-red-500 hover:text-red-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

<!-- Toolbar: Search and Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row items-center gap-2">
    <!-- Search Bar -->
    <div class="relative w-full sm:w-1/2 md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
        <input type="text" placeholder="Cari Riwayat....." class="w-full pl-4 pr-2 py-2 focus:outline-none text-sm">
        <button class="px-3 flex items-center bg-gray-50 hover:bg-green-100 transition text-green-600 border-l border-gray-200 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
    </div>

    <!-- Plus Stok Button -->
    <button type="button" onclick="openModalStokMasuk()"
        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition text-center shadow flex items-center justify-center gap-2">
        + Stok Masuk
    </button>

    <!-- PDF Report Dropdown -->
    <div x-data="{ openPdf: false }" class="relative w-full sm:w-auto">
        <button type="button" @click="openPdf = !openPdf" @click.away="openPdf = false"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition shadow flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            PDF Laporan
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="openPdf" x-transition.opacity.duration.200ms
            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden" style="display: none;">
            <a href="{{ route('pembelian.cetak_pdf') }}" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-green-50 hover:text-green-700 transition border-b border-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan Stok Masuk
            </a>
            <button type="button" onclick="openUnifiedReturModal()" class="w-full text-left block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan & Rekap Retur
            </button>
        </div>
    </div>
</div>

{{-- ===== TABEL RIWAYAT STOK MASUK ===== --}}
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max border-2 border-gray-500 shadow-sm rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100 uppercase text-xs font-bold text-gray-800 text-center">
                <th class="border border-gray-400 p-2 w-10">No</th>
                <th class="border border-gray-400 p-2 min-w-[120px]">Tanggal</th>
                <th class="border border-gray-400 p-2 min-w-[150px]">Supplier</th>
                <th class="border border-gray-400 p-2 min-w-[200px]">Nama Barang</th>
                <th class="border border-gray-400 p-2 w-24">Qty</th>
                <th class="border border-gray-400 p-2 w-32">Harga Beli</th>
                <th class="border border-gray-400 p-2 w-32">Harga Jual</th>
                <th class="border border-gray-400 p-2 w-40">Tgl Kadaluarsa</th>
                <th class="border border-gray-400 p-2 w-48">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = ($pembelians->currentPage()-1) * $pembelians->perPage() + 1; @endphp
            @forelse($pembelians as $beli)
                @foreach($beli->details as $detail)
                @php 
                    $batch = $detail->obat->stokBatches()->where('id_pembelian', $beli->id)->first();
                @endphp
                <tr class="hover:bg-gray-50 transition text-xs">
                    <td class="py-2 px-2 text-center text-gray-800 font-medium border border-gray-400">
                        {{ $no++ }}
                    </td>
                    <td class="py-2 px-3 text-center text-gray-800 font-medium border border-gray-400">
                        {{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d-m-Y') }}
                    </td>
                    <td class="py-2 px-3 text-center text-gray-900 font-bold uppercase border border-gray-400">
                        {{ $beli->supplier->nama_suplier ?? '-' }}
                    </td>
                    <td class="py-2 px-3 text-left text-gray-800 font-bold uppercase border border-gray-400">
                        {{ $detail->obat->nama_obat ?? '-' }}
                    </td>
                    <td class="py-2 px-3 text-center border border-gray-400 font-bold">
                        {{ $detail->qty }}
                    </td>
                    <td class="py-2 px-3 text-center border border-gray-400 font-medium whitespace-nowrap">
                        Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}
                    </td>
                    <td class="py-2 px-3 text-center border border-gray-400 font-bold text-gray-900 whitespace-nowrap">
                        Rp{{ number_format($detail->obat->harga_jual ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="py-2 px-3 text-center border border-gray-400 font-bold text-gray-900">
                        @if($detail->obat->kategori && strtoupper($detail->obat->kategori->nama_kategori) === 'CEK')
                            <span class="text-gray-400 font-normal">-</span>
                        @else
                            {{ \Carbon\Carbon::parse($batch->tgl_expired ?? now())->format('d-m-Y') }}
                        @endif
                    </td>
                    <td class="py-3 px-6 border border-gray-400">
                        <div class="flex justify-center items-center gap-1">
                        <!-- Tombol Riwayat -->
                        <button type="button"
                            onclick="openRiwayatModal('{{ $detail->id }}', '{{ $detail->obat->nama_obat ?? '' }}')"
                            class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm"
                            title="Riwayat Penambahan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>

                        <!-- Tombol Retur -->
                        <button type="button"
                            onclick="openReturModal('{{ $beli->id }}', '{{ $detail->id_obat }}', '{{ $detail->obat->nama_obat ?? '' }}')"
                            class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded transition shadow-sm"
                            title="Retur Obat">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                        </button>

                        <!-- Tombol Edit -->
                        <button type="button"
                            onclick="openEditRestockModal(this)"
                            data-id-pembelian="{{ $beli->id }}"
                            data-id-detail="{{ $detail->id }}"
                            data-id-obat="{{ $detail->id_obat }}"
                            data-nama-obat="{{ $detail->obat->nama_obat ?? '' }}"
                            data-tgl-pembelian="{{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('Y-m-d') }}"
                            data-nama-suplier="{{ $beli->supplier->nama_suplier ?? '' }}"
                            data-tgl-expired="{{ \Carbon\Carbon::parse($batch->tgl_expired ?? now())->format('Y-m-d') }}"
                            data-qty="{{ $detail->qty }}"
                            data-harga-beli="{{ $detail->harga_beli }}"
                            data-harga-jual="{{ $detail->obat->harga_jual ?? 0 }}"
                            class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm"
                            title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>

                    </td>
                </tr>
                @endforeach
            @empty
            <tr>
                <td colspan="10" class="py-12 text-center text-[13px] text-gray-400 font-medium border border-gray-300">Belum ada riwayat pengadaan stok dari supplier.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Section -->
<div class="mt-8 mb-10 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
    <span></span>
    <div class="flex gap-2">
        @if($pembelians->onFirstPage())
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed flex items-center">
                <span class="bg-gray-300 text-white w-5 h-5 flex items-center justify-center rounded mr-2">&#9664;</span> Back
            </span>
        @else
            <a href="{{ $pembelians->previousPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md flex items-center">
                <span class="bg-green-500 text-white w-5 h-5 flex items-center justify-center rounded mr-2">&#9664;</span> Back
            </a>
        @endif

        @if($pembelians->hasMorePages())
            <a href="{{ $pembelians->nextPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md flex items-center">
                Next <span class="bg-green-500 text-white w-5 h-5 flex items-center justify-center rounded ml-2">&#9654;</span>
            </a>
        @else
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed flex items-center">
                Next <span class="bg-gray-300 text-white w-5 h-5 flex items-center justify-center rounded ml-2">&#9654;</span>
            </span>
        @endif
    </div>
</div>

{{-- Modals moved to @section('modals') --}}

@endsection

@section('modals')
{{-- ===== MODAL SUKSES DENGAN ANIMASI CENTANG ===== --}}
<div id="modalSukses" class="fixed inset-0 z-[200] hidden items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-72 mx-4 py-8 px-6 text-center sukses-box">
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

    .sukses-box { animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
    @keyframes popIn { from { opacity: 0; transform: scale(0.7); } to { opacity: 1; transform: scale(1); } }
    .circle-anim { animation: drawCircle 0.65s ease forwards; }
    .check-anim { animation: drawCheck 0.45s ease 0.55s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>

{{-- ===== MODAL TERPADU REKAP & LAPORAN RETUR ===== --}}
<div id="modalUnifiedRetur" class="fixed inset-0 z-[150] hidden flex items-center justify-center font-sans" x-data="{ tab: 'rekap' }" style="display: none;">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeUnifiedReturModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="font-bold text-lg uppercase tracking-widest flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Laporan & Rekap Retur
            </h3>
            <button onclick="closeUnifiedReturModal()" class="text-blue-100 hover:text-white transition text-3xl font-light leading-none">&times;</button>
        </div>

        <!-- Tab Navigation -->
        <div class="flex bg-blue-50 border-b border-blue-100 p-1 gap-1">
            <button @click="tab = 'rekap'; fetchGlobalRetur();" 
                    :class="tab === 'rekap' ? 'bg-white text-blue-600 shadow-sm border-blue-200' : 'text-gray-500 hover:bg-blue-100'"
                    class="flex-1 py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border border-transparent">
                Lihat Rekap Data
            </button>
            <button @click="tab = 'unduh'" 
                    :class="tab === 'unduh' ? 'bg-white text-blue-600 shadow-sm border-blue-200' : 'text-gray-500 hover:bg-blue-100'"
                    class="flex-1 py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider transition-all border border-transparent">
                Unduh PDF Laporan
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <!-- TAB 1: LIHAT REKAP -->
            <div x-show="tab === 'rekap'" class="animate-fadeIn">
                <div class="p-0">
                    <table class="w-full text-[11px] text-left border-collapse">
                        <thead class="bg-gray-100 sticky top-0 border-b border-gray-200">
                            <tr>
                                <th class="p-4 font-bold text-gray-700 text-center uppercase tracking-tighter">Nama Barang</th>
                                <th class="p-4 font-bold text-gray-700 text-center uppercase tracking-tighter">Tanggal</th>
                                <th class="p-4 font-bold text-gray-700 text-center uppercase tracking-tighter w-12">Qty</th>
                                <th class="p-4 font-bold text-gray-700 text-center uppercase tracking-tighter">Potongan</th>
                                <th class="p-4 font-bold text-gray-700 text-left uppercase tracking-tighter">Alasan</th>
                            </tr>
                        </thead>
                        <tbody id="unified_rekap_body">
                            <!-- Data injected via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: UNDUH LAPORAN -->
            <div x-show="tab === 'unduh'" class="p-6 space-y-4 animate-fadeIn" x-data="{ filterType: 'semua' }">
                <div class="max-w-md mx-auto space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 mb-2 uppercase tracking-widest text-center">Pilih Tipe Laporan</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button @click="filterType = 'semua'" :class="filterType === 'semua' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500'" class="py-2.5 rounded-xl text-[10px] font-bold uppercase transition shadow-sm">Semua Riwayat</button>
                            <button @click="filterType = 'obat'" :class="filterType === 'obat' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500'" class="py-2.5 rounded-xl text-[10px] font-bold uppercase transition shadow-sm">Per Obat</button>
                        </div>
                    </div>

                    <form action="{{ route('laporan.retur_pdf') }}" method="GET" target="_blank" class="space-y-4">
                        <!-- Range Date -->
                        <div x-show="filterType === 'semua'" class="grid grid-cols-2 gap-3 p-4 bg-blue-50 rounded-2xl border border-blue-100 animate-fadeIn">
                             <div>
                                <label class="block text-[9px] font-bold text-blue-400 mb-1 uppercase">Mulai</label>
                                <input type="date" name="start_date" value="{{ date('Y-m-d', strtotime('-30 days')) }}" class="w-full border border-blue-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none font-bold">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-blue-400 mb-1 uppercase">Selesai</label>
                                <input type="date" name="end_date" value="{{ date('Y-m-d') }}" class="w-full border border-blue-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none font-bold">
                            </div>
                        </div>

                        <!-- Filter Obat -->
                        <div x-show="filterType === 'obat'" class="p-4 bg-blue-50 rounded-2xl border border-blue-100 animate-fadeIn">
                            <label class="block text-[9px] font-bold text-blue-400 mb-1 uppercase text-center mb-2">Pilih Nama Obat</label>
                            <select name="id_obat" class="w-full border border-blue-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none font-bold uppercase">
                                <option value="">-- Pilih Obat --</option>
                                @foreach($obats as $o)
                                    @if(isset($o->kategori) && strtoupper($o->kategori->nama_kategori) === 'CEK') @continue @endif
                                    <option value="{{ $o->id }}">{{ $o->nama_obat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 rounded-2xl transition shadow-xl text-xs flex items-center justify-center gap-2 uppercase tracking-widest active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Unduh Laporan PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
            <span class="text-[10px] text-gray-400 italic">Data diperbarui otomatis secara real-time.</span>
            <button type="button" onclick="closeUnifiedReturModal()" class="px-8 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-bold transition rounded-xl text-[10px] uppercase tracking-widest shadow-sm active:scale-95">Tutup</button>
        </div>
    </div>
</div>

{{-- ===== MODAL EDIT STOK MASUK ===== --}}
<div id="modalEditStok" class="fixed inset-0 z-[100] hidden flex items-center justify-center" style="display: none;"
    x-data="{ 
        openObat: false, 
        searchObat: '', 
        selectedObatId: '', 
        selectedObatName: '-- Pilih Barang / Obat --',
        obats: [
            @foreach($obats as $obat)
                @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') @continue @endif
                { id: '{{ $obat->id }}', name: '{{ strtoupper($obat->nama_obat) }}' },
            @endforeach
        ],
        selectObat(id, name) {
            this.selectedObatId = id;
            this.selectedObatName = name;
            this.openObat = false;
            this.searchObat = '';
        },
        initFromId(id) {
            if(!id) {
                this.selectedObatId = '';
                this.selectedObatName = '-- Pilih Barang / Obat --';
                return;
            }
            const found = this.obats.find(o => o.id == id);
            if(found) {
                this.selectedObatId = found.id;
                this.selectedObatName = found.name;
            }
        }
    }"
    @set-edit-obat.window="initFromId($event.detail.id)">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col">
        <!-- Header -->
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between text-white text-center">
            <h3 class="font-bold text-xl uppercase tracking-widest w-full">Edit Penerimaan Stok</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-green-100 hover:text-white transition text-3xl font-light">&times;</button>
        </div>

        <form action="" method="POST" id="formEditStok" onsubmit="event.preventDefault(); showSuccessAnimation('formEditStok', 'Data Berhasil Diperbarui!');">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                <!-- Data Header -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Terima -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Tanggal Terima <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_pembelian" id="edit_tgl_pembelian" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Nama Supplier -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Nama Supplier <span class="text-red-500">*</span></label>
                        <input list="supplier_list" name="nama_suplier" id="edit_nama_suplier" required placeholder="Ketik nama supplier..."
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                </div>

                <!-- Nama Barang (Searchable Dropdown) -->
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Barang <span class="text-red-500">*</span></label>
                    <button type="button" @click="openObat = !openObat" 
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm flex justify-between items-center focus:ring-2 focus:ring-green-500 transition font-bold uppercase text-gray-800 shadow-sm">
                        <span x-text="selectedObatName"></span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="openObat" @click.away="openObat = false" x-transition
                        class="absolute z-[110] mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden animate-modal">
                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                            <input type="text" x-model="searchObat" placeholder="Cari nama obat..." 
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 outline-none uppercase font-bold">
                        </div>
                        <ul class="max-h-60 overflow-y-auto py-1">
                            <template x-for="obat in obats.filter(o => o.name.includes(searchObat.toUpperCase()))" :key="obat.id">
                                <li @click="selectObat(obat.id, obat.name)" 
                                    class="px-4 py-2.5 text-xs font-bold uppercase text-gray-700 hover:bg-green-50 hover:text-green-700 cursor-pointer transition flex items-center justify-between">
                                    <span x-text="obat.name"></span>
                                    <svg x-show="selectedObatId == obat.id" class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </li>
                            </template>
                            <li x-show="obats.filter(o => o.name.includes(searchObat.toUpperCase())).length === 0" class="px-4 py-3 text-xs text-gray-400 italic text-center">
                                Obat tidak ditemukan...
                            </li>
                        </ul>
                    </div>
                    {{-- Hidden Input for original logic --}}
                    <input type="hidden" name="id_obat" id="edit_id_obat" x-model="selectedObatId" required>
                </div>

                <!-- Detail Barang -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Kadaluarsa -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Tanggal Kadaluarsa</label>
                        <input type="date" name="tgl_expired" id="edit_tgl_expired" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Barang Masuk (Qty) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Qty Masuk <span class="text-red-500">*</span></label>
                        <input type="number" name="qty" id="edit_qty" min="1" required placeholder="0"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-green-600 shadow-sm">
                    </div>
                </div>

                <!-- Harga Beli & Jual -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Harga Beli -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Harga Beli Per Item <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="harga_beli" id="edit_harga_beli" min="0" required placeholder="0"
                                class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-gray-800 shadow-sm">
                        </div>
                    </div>
                    <!-- Harga Jual -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Harga Jual Per Item <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="harga_jual" id="edit_harga_jual" min="0" required placeholder="0"
                                class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-gray-800 shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Tambah Stok Baru (Optional) -->
                <div class="bg-green-50 p-4 rounded-xl border border-green-100 mt-2">
                    <label class="block text-xs font-bold text-green-600 mb-2 uppercase tracking-widest text-left flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Stok Baru (Opsional)
                    </label>
                    <input type="number" name="tambah_stok" id="edit_tambah_stok" min="0" placeholder="0"
                        class="w-full bg-white border border-green-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-green-700 shadow-sm"
                        title="Isi jika ada stok masuk baru untuk item ini tanpa merubah data awal">
                    <p class="text-[10px] text-green-400 italic mt-1 font-medium">* Stok ini akan ditambahkan ke jumlah yang sudah ada.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-gray-500 hover:text-gray-700 font-bold transition text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-extrabold py-3 px-8 rounded-xl transition shadow-lg text-xs flex items-center gap-2 uppercase tracking-widest active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL STOK MASUK (SUPPLIER) ===== --}}
<div id="modalStokMasuk" class="fixed inset-0 z-[100] hidden flex items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeModalStokMasuk()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col"
        x-data="{ 
            openObat: false, 
            searchObat: '', 
            selectedObatId: '', 
            selectedObatName: '-- Pilih Barang / Obat --',
            obats: [
                @foreach($obats as $obat)
                    @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') @continue @endif
                    { id: '{{ $obat->id }}', name: '{{ strtoupper($obat->nama_obat) }}' },
                @endforeach
            ],
            selectObat(id, name) {
                this.selectedObatId = id;
                this.selectedObatName = name;
                this.openObat = false;
                this.searchObat = '';
            },
            resetForm() {
                this.selectedObatId = '';
                this.selectedObatName = '-- Pilih Barang / Obat --';
                this.searchObat = '';
                this.openObat = false;
            }
        }"
        @reset-restock.window="resetForm()">
        <!-- Header -->
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between text-white text-center">
            <h3 class="font-bold text-xl uppercase tracking-widest w-full">Penerimaan Stok (Supplier)</h3>
            <button onclick="closeModalStokMasuk()" class="absolute right-5 text-green-100 hover:text-white transition text-3xl font-light">&times;</button>
        </div>

        <form action="{{ route('pembelian.store') }}" method="POST" id="formStokMasuk" onsubmit="event.preventDefault(); showSuccessAnimation('formStokMasuk', 'Stok Berhasil Ditambahkan!');">
            @csrf
            {{-- Hidden Fields for System Requirements --}}
            <input type="hidden" name="no_faktur" id="restock_no_faktur">
            <input type="hidden" name="items[0][no_batch]" id="restock_no_batch">

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                <!-- Data Header -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Terima -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Tanggal Terima <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_pembelian" required value="{{ date('Y-m-d') }}"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Nama Supplier -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Nama Supplier <span class="text-red-500">*</span></label>
                        <input list="supplier_list" name="nama_suplier" required placeholder="Ketik nama supplier..."
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                        <datalist id="supplier_list">
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->nama_suplier }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <!-- Nama Barang (Searchable Dropdown) -->
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Barang <span class="text-red-500">*</span></label>
                    <button type="button" @click="openObat = !openObat" 
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm flex justify-between items-center focus:ring-2 focus:ring-green-500 transition font-bold uppercase text-gray-800 shadow-sm">
                        <span x-text="selectedObatName"></span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="openObat" @click.away="openObat = false" x-transition
                        class="absolute z-[110] mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden animate-modal">
                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                            <input type="text" x-model="searchObat" placeholder="Cari nama obat..." 
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 outline-none uppercase font-bold">
                        </div>
                        <ul class="max-h-60 overflow-y-auto py-1">
                            <template x-for="obat in obats.filter(o => o.name.includes(searchObat.toUpperCase()))" :key="obat.id">
                                <li @click="selectObat(obat.id, obat.name)" 
                                    class="px-4 py-2.5 text-xs font-bold uppercase text-gray-700 hover:bg-green-50 hover:text-green-700 cursor-pointer transition flex items-center justify-between">
                                    <span x-text="obat.name"></span>
                                    <svg x-show="selectedObatId == obat.id" class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </li>
                            </template>
                            <li x-show="obats.filter(o => o.name.includes(searchObat.toUpperCase())).length === 0" class="px-4 py-3 text-xs text-gray-400 italic text-center">
                                Obat tidak ditemukan...
                            </li>
                        </ul>
                    </div>
                    {{-- Hidden Input for original logic --}}
                    <input type="hidden" name="items[0][id_obat]" x-model="selectedObatId" required>
                </div>

                <!-- Detail Barang -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Kadaluarsa -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Tanggal Kadaluarsa</label>
                        <input type="date" name="items[0][tgl_expired]" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Barang Masuk (Qty) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Qty Masuk <span class="text-red-500">*</span></label>
                        <input type="number" name="items[0][qty]" min="1" required placeholder="0"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-green-600 shadow-sm">
                    </div>
                </div>

                <!-- Harga Beli & Jual -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Harga Beli -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Harga Beli Per Item <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="items[0][harga_beli]" min="0" required placeholder="0"
                                class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-gray-800 shadow-sm">
                        </div>
                    </div>
                    <!-- Harga Jual -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Harga Jual Per Item <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="items[0][harga_jual]" min="0" required placeholder="0"
                                class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-gray-800 shadow-sm">
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-gray-400 italic text-center pt-2">
                    * Penambahan stok ini akan otomatis memperbarui data stok utama dan laporan kadaluarsa.
                </p>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t border-gray-100">
                <button type="button" onclick="closeModalStokMasuk()" class="px-5 py-2.5 text-gray-500 hover:text-gray-700 font-bold transition text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-extrabold py-3 px-8 rounded-xl transition shadow-lg text-xs flex items-center gap-2 uppercase tracking-widest active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL RIWAYAT STOK MASUK ===== --}}
<div id="modalRiwayatStok" class="fixed inset-0 z-[100] hidden flex items-center justify-center font-sans" style="display: none;">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeRiwayatModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col">
        <!-- Header -->
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white text-center">
            <h3 class="font-bold text-lg uppercase tracking-widest w-full">Riwayat Stok: <span id="riwayat_nama_obat">--</span></h3>
            <button onclick="closeRiwayatModal()" class="absolute right-5 text-green-100 hover:text-white transition text-3xl font-light">&times;</button>
        </div>

        <div class="p-4 overflow-y-auto max-h-[60vh]">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="p-2 font-bold text-gray-700 text-center">Tgl & Jam</th>
                        <th class="p-2 font-bold text-gray-700 text-center">Jumlah</th>
                        <th class="p-2 font-bold text-gray-700 text-center">Harga Beli</th>
                        <th class="p-2 font-bold text-gray-700 text-center">Tgl Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody id="riwayat_body">
                    <!-- Data injected via JS -->
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeRiwayatModal()" class="px-5 py-2 text-gray-500 hover:text-gray-700 font-bold transition text-xs uppercase tracking-widest">Tutup</button>
        </div>
    </div>
</div>

 {{-- Modal Hapus Dihapus --}} 


{{-- ===== MODAL RETUR PEMBELIAN ===== --}}
<div id="modalReturPembelian" class="fixed inset-0 z-[120] hidden items-center justify-center" style="display: none;">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeReturModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden animate-modal flex flex-col">
        <!-- Header -->
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between text-white text-center">
            <h3 class="font-bold text-xl uppercase tracking-widest w-full">Retur Obat</h3>
            <button onclick="closeReturModal()" class="absolute right-5 text-blue-100 hover:text-white transition text-3xl font-light">&times;</button>
        </div>

        <form action="{{ route('pembelian.retur') }}" method="POST" id="formReturPembelian" onsubmit="event.preventDefault(); showSuccessAnimation('formReturPembelian', 'Retur Berhasil Diproses!');">
            @csrf
            <input type="hidden" name="id_pembelian" id="retur_id_pembelian">
            <input type="hidden" name="id_obat" id="retur_id_obat">
            
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" id="retur_nama_obat" readonly
                        class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none transition font-bold uppercase text-gray-600 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Jumlah Retur (Qty) <span class="text-red-500">*</span></label>
                    <input type="number" name="qty_retur" min="1" required placeholder="Contoh: 10"
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-bold text-blue-600 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Nominal Potongan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                        <input type="number" name="nominal_potongan" min="0" required placeholder="0" value="0"
                            class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-bold text-gray-800 shadow-sm"
                            title="Nominal potongan yang akan mengurangi tagihan pembayaran ke supplier">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Alasan Retur <span class="text-red-500">*</span></label>
                    <textarea name="alasan" required placeholder="Contoh: Barang kadaluarsa" rows="3"
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition shadow-sm"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t border-gray-100">
                <button type="button" onclick="closeReturModal()" class="px-5 py-2.5 text-gray-500 hover:text-gray-700 font-bold transition text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 px-8 rounded-xl transition shadow-lg text-xs flex items-center gap-2 uppercase tracking-widest active:scale-95">
                    Proses Retur
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Single Form for Deletion --}}
<form id="form-delete-pembelian" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    /* ===== MODAL STOK MASUK ===== */
    function openModalStokMasuk() {
        const now = Date.now();
        document.getElementById('restock_no_faktur').value = 'INV-RESTOCK-' + now;
        document.getElementById('restock_no_batch').value = 'BATCH-TEMP-' + now;
        document.getElementById('formStokMasuk').reset();
        document.getElementById('restock_no_faktur').value = 'INV-RESTOCK-' + now;
        document.getElementById('restock_no_batch').value = 'BATCH-TEMP-' + now;
        
        // Reset Alpine searchable dropdown state
        window.dispatchEvent(new CustomEvent('reset-restock'));
        
        const modal = document.getElementById('modalStokMasuk');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModalStokMasuk() {
        const modal = document.getElementById('modalStokMasuk');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    /* ===== LOGIKA RIWAYAT STOK ===== */
    function openRiwayatModal(idDetail, namaObat) {
        document.getElementById('riwayat_nama_obat').innerText = namaObat;
        const body = document.getElementById('riwayat_body');
        body.innerHTML = '<tr><td colspan="4" class="p-4 text-center italic text-gray-400">Memuat data...</td></tr>';
        
        const modal = document.getElementById('modalRiwayatStok');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        fetch(`/pembelian/riwayat/${idDetail}`)
            .then(response => response.json())
            .then(data => {
                body.innerHTML = '';
                if (data.length === 0) {
                    body.innerHTML = '<tr><td colspan="4" class="p-5 text-center text-gray-400">Tidak ada riwayat penambahan.</td></tr>';
                    return;
                }
                data.forEach(item => {
                    const dateObj = new Date(item.created_at);
                    const formattedDate = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    const formattedTime = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    
                    const expDateObj = item.tgl_expired ? new Date(item.tgl_expired) : null;
                    const formattedExp = expDateObj 
                        ? expDateObj.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })
                        : '-';
                    
                    body.innerHTML += `
                        <tr class="border-b border-gray-100 text-center">
                            <td class="p-3 text-gray-600">${formattedDate} <br> <span class="text-[10px] text-gray-400">${formattedTime} WIB</span></td>
                            <td class="p-3 font-bold ${item.qty_masuk >= 0 ? 'text-blue-600' : 'text-red-500'}">
                                ${item.qty_masuk >= 0 ? '+' : ''}${item.qty_masuk}
                            </td>
                            <td class="p-3 text-gray-600">Rp${new Intl.NumberFormat('id-ID').format(item.harga_beli)}</td>
                            <td class="p-3 text-xs font-bold ${item.tgl_expired && new Date(item.tgl_expired) < new Date() ? 'text-red-500' : 'text-gray-600'}">${formattedExp}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => {
                body.innerHTML = '<tr><td colspan="4" class="p-4 text-center text-red-500">Gagal memuat data.</td></tr>';
                console.error('Error fetching riwayat:', error);
            });
    }

    function closeRiwayatModal() {
        const modal = document.getElementById('modalRiwayatStok');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    /* ===== LOGIKA EDIT RIWAYAT ===== */
    function openEditRestockModal(btn) {
        const idPembelian = btn.getAttribute('data-id-pembelian');
        const idObat = btn.getAttribute('data-id-obat');
        const tglBeli = btn.getAttribute('data-tgl-pembelian');
        const supplier = btn.getAttribute('data-nama-suplier');
        const tglExp = btn.getAttribute('data-tgl-expired');
        const qty = btn.getAttribute('data-qty');
        const hargaBeli = btn.getAttribute('data-harga-beli');
        const hargaJual = btn.getAttribute('data-harga-jual');

        // Set Form Action
        document.getElementById('formEditStok').action = '/pembelian/' + idPembelian;

        // Populate Fields
        document.getElementById('edit_id_obat').value = idObat;
        
        // Notify Alpine to update its internal state for the searchable dropdown
        window.dispatchEvent(new CustomEvent('set-edit-obat', { detail: { id: idObat } }));
        
        document.getElementById('edit_tgl_pembelian').value = tglBeli;
        document.getElementById('edit_nama_suplier').value = supplier;
        document.getElementById('edit_tgl_expired').value = tglExp;
        document.getElementById('edit_qty').value = qty;
        document.getElementById('edit_harga_beli').value = hargaBeli;
        document.getElementById('edit_harga_jual').value = hargaJual;
        document.getElementById('edit_tambah_stok').value = '';

        // Show Modal
        const modal = document.getElementById('modalEditStok');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        const modal = document.getElementById('modalEditStok');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    /* Delete Logic Removed */


    /* ===== LOGIKA RETUR ===== */
    function openReturModal(idPembelian, idObat, namaObat) {
        document.getElementById('retur_id_pembelian').value = idPembelian;
        document.getElementById('retur_id_obat').value = idObat;
        document.getElementById('retur_nama_obat').value = namaObat;

        document.getElementById('formReturPembelian').reset();
        
        // Repopulate invisible fields since reset clears them
        document.getElementById('retur_id_pembelian').value = idPembelian;
        document.getElementById('retur_id_obat').value = idObat;
        document.getElementById('retur_nama_obat').value = namaObat;

        const modal = document.getElementById('modalReturPembelian');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeReturModal() {
        const modal = document.getElementById('modalReturPembelian');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    /* ===== LOGIKA MODAL TERPADU RETUR ===== */
    function openUnifiedReturModal() {
        const modal = document.getElementById('modalUnifiedRetur');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        fetchGlobalRetur();
    }

    function closeUnifiedReturModal() {
        const modal = document.getElementById('modalUnifiedRetur');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function fetchGlobalRetur() {
        const body = document.getElementById('unified_rekap_body');
        body.innerHTML = '<tr><td colspan="6" class="p-8 text-center italic text-gray-400">Memuat riwayat retur...</td></tr>';
        
        fetch(`/pembelian/rekap-retur-semua`)
            .then(response => response.json())
            .then(data => {
                body.innerHTML = '';
                if (data.length === 0) {
                    body.innerHTML = '<tr><td colspan="6" class="p-10 text-center text-gray-400 italic font-medium">Belum ada riwayat retur ditemukan.</td></tr>';
                    return;
                }
                data.forEach(item => {
                    const dateObj = new Date(item.tgl_retur);
                    const formattedDate = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    const namaBarang = item.obat ? item.obat.nama_obat : '-';

                    body.innerHTML += `
                        <tr class="border-b border-gray-50 hover:bg-green-50 transition">
                            <td class="p-4 text-center font-bold text-gray-800 uppercase text-[10px] leading-tight">${namaBarang}</td>
                            <td class="p-4 text-center font-medium text-gray-600">${formattedDate}</td>
                            <td class="p-4 text-center text-red-600 font-extrabold">${item.qty_retur}</td>
                            <td class="p-4 text-center font-bold text-gray-800">Rp${new Intl.NumberFormat('id-ID').format(item.nominal_potongan)}</td>
                            <td class="p-4 text-left text-gray-500 italic leading-relaxed text-[10px]">${item.alasan}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => {
                body.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-red-500 font-bold">Gagal memuat rekap retur.</td></tr>';
            });
    }

    function openRekapReturModal(idPembelian, idObat, namaObat) {
        // Reuse unified modal but filter or show specific message if needed
        // For simplicity, we now use the Global/Unified view as requested
        openUnifiedReturModal();
    }

    function openGlobalReturModal() { openUnifiedReturModal(); }
    function openFilterReturModal() { openUnifiedReturModal(); }



    /* ===== ANIMASI SUKSES ===== */
    function showSuccessAnimation(formId, message) {
        const modal = document.getElementById('modalSukses');
        const title = document.getElementById('sukses_title');
        title.innerText = message;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.style.display = 'flex';

        // Restart animasi centang
        const circle = modal.querySelector('.circle-anim');
        const check  = modal.querySelector('.check-anim');
        if(circle && check) {
            circle.style.animation = 'none';
            check.style.animation  = 'none';
            circle.offsetHeight; // trigger reflow
            circle.style.animation = '';
            check.style.animation  = '';
        }

        setTimeout(() => { document.getElementById(formId).submit(); }, 800);
    }
</script>
@endpush
