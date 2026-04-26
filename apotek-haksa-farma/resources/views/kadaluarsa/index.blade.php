@extends('layouts.admin')

@section('content')
{{-- Header --}}
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2">DATA KADALUARSA</h2>
</div>

{{-- Alert Success --}}
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

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row items-center justify-start mb-6 gap-3">
    {{-- Search --}}
    <div class="flex w-full sm:w-1/2 md:w-1/3 border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
        <input type="text" id="searchKadaluarsa" placeholder="Cari Barang....."
            class="w-full pl-4 pr-2 py-2 text-sm focus:outline-none"
            oninput="filterTable(this.value)">
        <div class="px-3 flex items-center bg-gray-50 border-l border-gray-200">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    {{-- Export Dropdown (Ganti Modal jadi Dropdown Kecil) --}}
    <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
        <button type="button" @click="open = !open"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm transition flex items-center gap-2 text-xs uppercase tracking-widest h-[40px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Cetak Laporan
            <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100" 
             x-transition:enter-start="transform opacity-0 scale-95" 
             x-transition:enter-end="transform opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-75" 
             x-transition:leave-start="transform opacity-100 scale-100" 
             x-transition:leave-end="transform opacity-0 scale-95" 
             class="absolute left-0 mt-2 w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 border border-gray-100 overflow-hidden" 
             style="display: none;">
            
            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Pilih Jenis Laporan
            </div>

            <a href="{{ route('kadaluarsa.pdf') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-green-600 hover:bg-green-50 transition border-b border-gray-50 uppercase tracking-tighter">
                <div class="bg-green-100 p-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                DATA STOK KADALUARSA
            </a>

            <a href="{{ route('laporan.penjualan_sebelum_kadaluarsa_pdf') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-green-600 transition uppercase tracking-tighter border-b border-gray-50">
                <div class="bg-gray-100 p-1.5 rounded-lg">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                PENJUALAN SBLM EXP
            </a>
        </div>
    </div>
</div>

{{-- Tabel Data Kadaluarsa --}}
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max border-2 border-gray-500 shadow-sm rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-4 px-4 font-bold text-gray-800 text-center w-14 border border-gray-400 uppercase text-xs tracking-wider">No</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-left border border-gray-400 uppercase text-xs tracking-wider">Nama Obat</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-32 border border-gray-400 uppercase text-xs tracking-wider">Stok Sisa</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-40 border border-gray-400 uppercase text-xs tracking-wider">Tgl Kadaluarsa</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-24 border border-gray-400 uppercase text-xs tracking-wider">Terjual</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-32 border border-gray-400 uppercase text-xs tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody id="kadaluarsaTableBody">
            @php $displayCounter = 1; @endphp
            @forelse($kadaluarsas as $index => $item)
            @if(isset($item->obat->kategori) && strtoupper($item->obat->kategori->nama_kategori) === 'CEK') @continue @endif
            @php
                $now     = \Carbon\Carbon::now();
                $expired = \Carbon\Carbon::parse($item->earliest_expired);
                $diffDays = (int)$now->diffInDays($expired, false); // negatif = sudah expired

                if ($diffDays <= 0) {
                    $statusLabel = 'Kadaluarsa';
                    $statusClass = 'bg-red-100 text-red-700 border border-red-200';
                    $rowClass    = 'bg-red-50';
                } else {
                    $hLabel      = 'H-' . $diffDays;
                    $statusLabel = $hLabel;
                    $statusClass = $diffDays <= 150
                        ? 'bg-red-100 text-red-700 border border-red-200'
                        : 'bg-blue-100 text-blue-700 border border-blue-200';
                    $rowClass    = $diffDays <= 150 ? 'bg-red-50' : 'bg-blue-50';
                }
            @endphp
            <tr class="kadaluarsa-row hover:bg-gray-100 transition text-xs {{ \Carbon\Carbon::parse($item->earliest_expired)->isPast() ? 'bg-red-50' : (\Carbon\Carbon::parse($item->earliest_expired)->diffInDays(now()) <= 150 ? 'bg-red-50' : 'bg-blue-50') }}"
                data-nama="{{ strtolower($item->obat->nama_obat ?? '') }}">
                <td class="py-3 px-4 text-center font-medium text-gray-800 border border-gray-400">{{ (($kadaluarsas->currentPage()-1) * $kadaluarsas->perPage()) + ($displayCounter++) }}</td>
                <td class="py-3 px-5 font-semibold text-gray-800 uppercase border border-gray-400 text-left">
                    {{ $item->obat->nama_obat ?? '—' }}
                    <span class="text-xs font-medium text-gray-900 block normal-case mt-0.5">{{ $item->obat->kategori->nama_kategori ?? '—' }}</span>
                </td>
                <td class="py-3 px-5 text-center font-bold border border-gray-400 {{ $item->total_sisa <= 0 ? 'text-red-500' : 'text-gray-800' }}">
                    {{ number_format($item->total_sisa, 0, ',', '.') }}
                </td>
                <td class="py-3 px-5 text-center border border-gray-400">
                    <span class="font-semibold {{ $diffDays < 0 ? 'text-red-600' : 'text-gray-800' }}">
                        @if($item->obat->kategori && strtoupper($item->obat->kategori->nama_kategori) === 'CEK')
                            <span class="text-gray-400 font-normal">-</span>
                        @else
                            {{ $expired->format('d-m-Y') }}
                        @endif
                    </span>
                </td>
                <td class="py-3 px-5 text-center border border-gray-400">
                    <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded shadow-sm text-xs border border-blue-100">
                        {{ $item->obat->total_terjual ?? 0 }}
                    </span>
                </td>
                <td class="py-3 px-5 text-center border border-gray-400">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-10 text-center border border-gray-300">
                    <div class="flex flex-col items-center gap-2">
                        <p class="text-gray-400 font-medium text-[13px]">Tidak ada obat yang kadaluarsa atau mendekati H-5 Bulan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="mt-6">
    {{ $kadaluarsas->links() }}
</div>




 {{-- Modal Hapus Dihapus --}} 








<script>


function filterTable(keyword) {
    const q = keyword.toLowerCase().trim();
    document.querySelectorAll('.kadaluarsa-row').forEach(row => {
        const nama = row.dataset.nama || '';
        row.style.display = nama.includes(q) ? '' : 'none';
    });
}



document.addEventListener('DOMContentLoaded', function () {
    // Escape tutup semua modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { 
            // modal ops cleanup
        }
    });
});

</script>
@endsection
