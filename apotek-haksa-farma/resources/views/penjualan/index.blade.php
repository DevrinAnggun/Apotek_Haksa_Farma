@extends('layouts.admin')

@section('content')
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase">DATA PENJUALAN</h2>
</div>

<!-- Toolbar Filter dan Export -->
<div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 mb-6">
    <div class="flex items-center justify-between gap-4">
        <!-- Filter Berdasarkan Tanggal -->
        <form action="{{ route('laporan.penjualan') }}" method="GET" class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-extrabold text-gray-600 uppercase">Dari:</span>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-green-500 transition shadow-sm font-bold bg-white h-[40px]">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-extrabold text-gray-600 uppercase">Sampai:</span>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-green-500 transition shadow-sm font-bold bg-white h-[40px]">
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 rounded-lg text-xs transition shadow-sm h-[40px] uppercase whitespace-nowrap">
                Filter
            </button>
        </form>
        
        <!-- Tombol Tambah Transaksi & Export Laporan -->
        <div class="flex items-center gap-2">
            <!-- Ke Halaman Tambah Transaksi Kasir -->
            <a href="{{ route('kasir.pos') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 rounded-lg shadow-sm transition h-[40px] text-xs uppercase flex items-center justify-center whitespace-nowrap">
                + Tambah Transaksi
            </a>
            
            <!-- Dropdown Cetak Laporan -->
            <div class="relative inline-block text-left" x-data="{ open: false, idObat: '' }" @click.away="open = false">
                <div>
                    <button type="button" @click="open = !open" class="bg-gray-800 hover:bg-black text-white font-bold px-4 rounded-lg shadow-sm transition flex items-center justify-center gap-2 text-xs uppercase h-[40px] whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export Laporan
                        <svg class="w-4 h-4 ml-1" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>

            <!-- Panel Dropdown AlpineJS -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="origin-top-right absolute right-0 mt-2 w-72 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 py-2 border border-gray-100" style="display: none;">
                
                <div class="px-4 py-2 border-b border-gray-50 mb-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Pilih Format Cetak
                </div>

                <a href="{{ route('laporan.cetak_pdf', ['start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')]) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Laporan Hari Ini
                </a>
                
                <button type="button" @click="open = false; openMonthModal()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Laporan Bulanan...
                </button>

                <div class="border-t border-gray-50 my-1"></div>

                <a href="{{ route('laporan.cetak_pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-900 hover:bg-orange-50 hover:text-orange-700 flex items-center gap-2 bg-gray-50 font-bold">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Cetak Hasil Filter
                </a>

                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2 rounded-b-xl">
                    <a :href="'{{ route('laporan.penjualan_sebelum_kadaluarsa_pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}' + (idObat ? '&id_obat=' + idObat : '')" target="_blank" class="flex-1 py-1 text-[11px] text-gray-700 hover:text-orange-700 flex items-center gap-1.5 transition font-bold leading-tight">
                        <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Penjualan Sblm Exp
                    </a>
                    
                    <div class="w-28 shrink-0">
                        <select x-model="idObat" class="w-full bg-white border border-gray-200 rounded px-1.5 py-1 text-[10px] focus:ring-1 focus:ring-orange-500 font-bold uppercase text-gray-700 outline-none cursor-pointer">
                            <option value="">Semua</option>
                            @foreach($obats as $o)
                                <option value="{{ $o->id }}">{{ $o->nama_obat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card View Laporan Mirip Kasir (Sesuai Mockup) -->
<div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col mt-6">
    <div class="p-6 flex-1 overflow-auto">
        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4 flex justify-between items-center">
            Daftar Transaksi Selesai
            <div class="flex items-center gap-2">
                <a href="{{ route('laporan.penjualan') }}" class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full hover:bg-blue-200 transition">
                    Lihat Semua
                </a>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full w-auto">
                    Riwayat Tersimpan
                </span>
            </div>
        </h3>
    
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max border border-gray-400 shadow-sm rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-4 px-3 font-bold text-gray-800 text-center w-12 border border-gray-300 uppercase text-xs tracking-wider">No</th>
                        <th class="py-4 px-4 font-bold text-gray-800 text-left w-32 border border-gray-300 uppercase text-xs tracking-wider">Tanggal</th>
                        <th class="py-4 px-4 font-bold text-gray-800 text-left border border-gray-300 uppercase text-xs tracking-wider">Nama Obat</th>
                        <th class="py-4 px-4 font-bold text-gray-800 text-center w-24 border border-gray-300 uppercase text-xs tracking-wider">Satuan</th>
                        <th class="py-4 px-4 font-bold text-gray-800 text-center w-20 border border-gray-300 uppercase text-xs tracking-wider">Sisa Stok</th>
                        <th class="py-4 px-4 font-bold text-gray-800 text-center w-32 border border-gray-300 uppercase text-xs tracking-wider">Harga</th>
                        <th class="py-4 px-2 font-bold text-gray-800 text-center w-12 border border-gray-300 uppercase text-xs tracking-wider">Qty</th>
                        <th class="py-4 px-4 font-bold text-gray-800 text-center w-36 border border-gray-300 uppercase text-xs tracking-wider">Total</th>
                        <th class="py-4 px-4 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = ($penjualans->currentPage()-1) * $penjualans->perPage() + 1; $grandTotal = 0; @endphp
                    @forelse($penjualans as $penjualan)
                        @php 
                            // Prepare items for this transaction for the receipt modal
                            $itemsJson = $penjualan->details->map(function($d) {
                                return [
                                    'nama' => $d->obat->nama_obat ?? '-',
                                    'qty' => $d->qty,
                                    'harga' => number_format($d->harga_jual, 0, ',', '.'),
                                    'sub' => number_format($d->subtotal, 0, ',', '.')
                                ];
                            });
                        @endphp
                        @foreach($penjualan->details as $detail)
                            <tr class="hover:bg-gray-50 transition text-sm">
                                <td class="py-2 px-3 text-center font-medium text-gray-800 border border-gray-300">{{ $no++ }}</td>
                                <td class="py-2 px-3 font-medium text-gray-800 border border-gray-300 text-left whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d/m/Y') }}
                                    <span class="text-[10px] text-gray-400 block font-normal">{{ \Carbon\Carbon::parse($penjualan->created_at)->format('H:i') }}</span>
                                </td>
                                <td class="py-2 px-3 font-medium text-gray-800 border border-gray-300 text-left uppercase tracking-wide">
                                    {{ $detail->obat->nama_obat ?? '-' }}
                                    <span class="text-[10px] text-gray-400 block font-normal normal-case">{{ $detail->obat->kategori->nama_kategori ?? '-' }}</span>
                                    @if($detail->obat->tanggal_kadaluarsa)
                                        <span class="text-[9px] block {{ \Carbon\Carbon::parse($detail->obat->tanggal_kadaluarsa)->isPast() ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                                            Exp: {{ \Carbon\Carbon::parse($detail->obat->tanggal_kadaluarsa)->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 font-medium text-gray-800 border border-gray-300 text-center">{{ $detail->obat->satuan->nama_satuan ?? '-' }}</td>
                                <td class="py-2 px-3 text-center border border-gray-300">
                                    @if(($detail->obat->kategori->nama_kategori ?? '') === 'CEK')
                                        <span class="text-gray-400 font-semibold">—</span>
                                    @else
                                        <span class="font-bold {{ ($detail->obat->total_stok ?? 0) < 5 ? 'text-red-500' : 'text-green-600' }}">
                                            {{ $detail->obat->total_stok ?? 0 }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-3 text-center font-medium text-gray-800 border border-gray-300">Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                <td class="py-2 px-2 text-center border border-gray-300">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-bold text-xs border border-blue-100">{{ $detail->qty }}</span>
                                </td>
                                <td class="py-2 px-3 text-center font-bold text-gray-900 border border-gray-300">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td class="py-2 px-3 text-center border border-gray-300">
                                    {{-- Tombol Lihat Struk --}}
                                    <button type="button" 
                                        onclick='showStruk({
                                            id: "{{ $penjualan->id }}",
                                            tgl: "{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d/m/Y') }}",
                                            jam: "{{ \Carbon\Carbon::parse($penjualan->created_at)->format('H:i') }}",
                                            total: "{{ number_format($penjualan->total_harga, 0, ',', '.') }}",
                                            items: {!! $itemsJson->toJson() !!}
                                        })'
                                        class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded transition shadow-sm group"
                                        title="Lihat Struk">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @php $grandTotal += $detail->subtotal; @endphp
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-gray-400 italic border border-gray-300">Belum ada data penjualan pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Data Penjualan -->
        <div class="mt-6 flex items-center justify-between px-2">
            <div class="text-xs text-gray-500 font-medium">
                Menampilkan {{ $penjualans->firstItem() ?? 0 }} - {{ $penjualans->lastItem() ?? 0 }} dari {{ $penjualans->total() }} transaksi
            </div>
            <div class="flex gap-2">
                @if($penjualans->onFirstPage())
                    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed flex items-center gap-2">
                        <span class="bg-gray-300 text-white w-5 h-5 flex items-center justify-center rounded">&#9664;</span> Back
                    </span>
                @else
                    <a href="{{ $penjualans->previousPageUrl() }}" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md flex items-center gap-2">
                        <span class="bg-green-500 text-white w-5 h-5 flex items-center justify-center rounded">&#9664;</span> Back
                    </a>
                @endif

                @if($penjualans->hasMorePages())
                    <a href="{{ $penjualans->nextPageUrl() }}" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md flex items-center gap-2">
                        Next <span class="bg-green-500 text-white w-5 h-5 flex items-center justify-center rounded">&#9654;</span>
                    </a>
                @else
                    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed flex items-center gap-2">
                        Next <span class="bg-gray-300 text-white w-5 h-5 flex items-center justify-center rounded">&#9654;</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if($penjualans->count() > 0)
    <!-- Panel Footer Laporan -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-xl flex justify-end items-center gap-4">
        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan Terjual:</p>
        <span class="text-base font-extrabold text-gray-900">
            Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
        </span>
    </div>
    @endif
</div>

{{-- ===== MODAL STRUK (DETAIL TRANSAKSI) ===== --}}
<div id="modalStruk" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeStruk()"></div>
    <div class="relative bg-white w-full max-w-sm mx-4 overflow-hidden shadow-2xl animate-receipt">
        <!-- Paper Top (Dashed) -->
        <div class="w-full h-2 bg-gray-100 border-b border-dashed border-gray-300"></div>
        
        <div class="p-8 font-mono text-[13px] leading-relaxed text-gray-800">
            <!-- Header Apotek -->
            <div class="text-center mb-6">
                <h2 class="font-bold text-xl mb-1 tracking-tight uppercase">APOTEK HAKSA FARMA</h2>
                <div class="text-[9px] text-gray-500 leading-tight mb-2">
                    Jl. Purwareja No.82, Purwareja Klampok<br>
                    Banjarnegara, Jawa Tengah 53474<br>
                    Telp/WA: 0822-1234-5678
                </div>
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] mb-3">Struk Pembayaran Sah</p>
                <div class="border-y border-dashed border-gray-300 py-1 flex justify-between px-1">
                    <span id="struk-tgl">--/--/----</span>
                    <span id="struk-jam">--:-- WIB</span>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-3">
                <div class="flex justify-between font-bold border-b border-gray-100 pb-1">
                    <span class="w-1/2">Barang</span>
                    <span class="w-1/4 text-center">Qty</span>
                    <span class="w-1/4 text-right">Total</span>
                </div>
                
                <div id="struk-items" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    <!-- Items injected here -->
                </div>

                <div class="border-t-2 border-dashed border-gray-300 pt-3 mt-4">
                    <div class="flex justify-between items-center text-base font-bold">
                        <span>TOTAL AKHIR</span>
                        <span id="struk-total">Rp0</span>
                    </div>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="text-center mt-8 pt-4 border-t border-gray-100">
                <p class="italic text-gray-400 mb-4">Terima kasih atas kepercayaannya.<br>Semoga lekas sembuh.</p>
                <button type="button" onclick="closeStruk()" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2 rounded-lg text-xs font-bold transition uppercase tracking-widest no-print">
                    Tutup
                </button>
            </div>
        </div>

        <!-- Paper Bottom (Dashed) -->
        <div class="w-full h-2 bg-gray-100 border-t border-dashed border-gray-300"></div>
    </div>
</div>

<style>
    @keyframes receiptIn {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-receipt {
        animation: receiptIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        #modalStruk, #modalStruk * {
            visibility: visible;
        }
        #modalStruk {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            display: block !important;
        }
        .bg-black.bg-opacity-60 { 
            display: none !important; 
        }
        .no-print { 
            display: none !important; 
        }
    }
</style>

<script>
    function openMonthModal() {
        document.getElementById('monthModal').classList.remove('hidden');
        document.getElementById('monthModal').classList.add('flex');
    }
    function closeMonthModal() {
        document.getElementById('monthModal').classList.add('hidden');
        document.getElementById('monthModal').classList.remove('flex');
    }
    function filterByMonth() {
        const m = document.getElementById('modal_month').value;
        const y = document.getElementById('modal_year').value;
        window.location.href = `{{ route('laporan.penjualan') }}?month=${m}&year=${y}`;
    }
    function downloadMonthPdf() {
        const m = document.getElementById('modal_month').value;
        const y = document.getElementById('modal_year').value;
        const startDate = `${y}-${m.padStart(2, '0')}-01`;
        const lastDay = new Date(y, m, 0).getDate();
        const endDate = `${y}-${m.padStart(2, '0')}-${lastDay}`;
        window.open(`{{ route('laporan.cetak_pdf') }}?start_date=${startDate}&end_date=${endDate}`, '_blank');
    }

    // Modal show/hide logic (existing for detail)
    function showStruk(data) {
        const modal = document.getElementById('modalStruk');
        const container = document.getElementById('struk-items');
        
        // Set Header Data
        document.getElementById('struk-tgl').textContent = data.tgl;
        document.getElementById('struk-jam').textContent = data.jam + ' WIB';
        document.getElementById('struk-total').textContent = 'Rp' + data.total;

        // Populate Items
        container.innerHTML = '';
        data.items.forEach(item => {
            container.innerHTML += `
                <div class="flex justify-between gap-2 border-b border-gray-50 pb-1">
                    <div class="w-1/2 flex flex-col">
                        <span class="font-semibold text-gray-900 leading-tight">${item.nama}</span>
                        <span class="text-[10px] text-gray-400">@Rp${item.harga}</span>
                    </div>
                    <span class="w-1/4 text-center font-bold">${item.qty}</span>
                    <span class="w-1/4 text-right">Rp${item.sub}</span>
                </div>
            `;
        });

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Auto print after animation completes (approx 300ms)
        setTimeout(() => {
            window.print();
        }, 500);
    }

    function closeStruk() {
        const modal = document.getElementById('modalStruk');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeStruk();
            closeDelete();
        }
    });

    /* ===== LOGIKA HAPUS TRANSAKSI ===== */
    let activeDeleteId = null;

    function confirmDelete(id) {
        activeDeleteId = id;
        const modal = document.getElementById('modalHapus');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDelete() {
        const modal = document.getElementById('modalHapus');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        activeDeleteId = null;
    }

    function executeDelete() {
        if (activeDeleteId) {
            showSuccessAnimation('form-delete-' + activeDeleteId, 'Transaksi Berhasil Dihapus!');
        }
    }

    /* ===== ANIMASI SUKSES SEBELUM SUBMIT ===== */
    function showSuccessAnimation(formId, titleText) {
        const modal = document.getElementById('modalSukses');
        document.getElementById('sukses_title').textContent = titleText;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Restart animasi SVG
        const circle = modal.querySelector('.circle-anim');
        const check  = modal.querySelector('.check-anim');
        circle.style.animation = 'none';
        check.style.animation  = 'none';
        circle.getBoundingClientRect(); // trigger reflow
        check.getBoundingClientRect();
        circle.style.animation = '';
        check.style.animation  = '';

        setTimeout(() => {
            document.getElementById(formId).submit();
        }, 800);
    }
</script>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div id="modalHapus" class="fixed inset-0 z-[110] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDelete()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-80 mx-4 overflow-hidden animate-modal">
        <div class="bg-red-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">KONFIRMASI HAPUS</h4>
        </div>
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">Hapus Transaksi ini?</p>
            <p class="text-[11px] text-gray-500 italic leading-relaxed">
                Stok barang pada transaksi ini akan dikembalikan ke data stok.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 mt-2">
            <button type="button" onclick="closeDelete()" class="flex-1 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition uppercase tracking-wider">BATAL</button>
            <button type="button" onclick="executeDelete()" class="flex-1 py-2 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition uppercase tracking-wider">YA, HAPUS</button>
        </div>
    </div>
</div>

{{-- ===== MODAL SUKSES DENGAN ANIMASI CENTANG ===== --}}
<div id="modalSukses" class="fixed inset-0 z-[120] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-72 mx-4 py-8 px-6 text-center sukses-box">
        <div class="flex justify-center mb-5">
            <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="44" stroke="#16a34a" stroke-width="6" stroke-dasharray="276" stroke-dashoffset="276" class="circle-anim"></circle>
                <polyline points="28,52 44,68 73,34" stroke="#16a34a" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="80" stroke-dashoffset="80" class="check-anim"></polyline>
            </svg>
        </div>
        <h3 id="sukses_title" class="text-xl font-extrabold text-gray-800 mb-1">Berhasil!</h3>
        <p class="text-sm text-gray-400 mt-1">Sedang memperbarui data...</p>
    </div>
</div>

<style>
    .sukses-box { animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
    @keyframes popIn { from { opacity: 0; transform: scale(0.7); } to { opacity: 1; transform: scale(1); } }
    .circle-anim { animation: drawCircle 0.65s ease forwards; }
    .check-anim { animation: drawCheck 0.45s ease 0.55s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>

@endsection

@section('modals')
    {{-- Modal Pilih Bulan --}}
    <div id="monthModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeMonthModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-visible border border-gray-100 flex flex-col" x-data="{ 
            month: '{{ date('n') }}', 
            year: '{{ date('Y') }}',
            openM: false,
            openY: false,
            months: [
                {val: '1', label: 'Januari'}, {val: '2', label: 'Februari'}, {val: '3', label: 'Maret'},
                {val: '4', label: 'April'}, {val: '5', label: 'Mei'}, {val: '6', label: 'Juni'},
                {val: '7', label: 'Juli'}, {val: '8', label: 'Agustus'}, {val: '9', label: 'September'},
                {val: '10', label: 'Oktober'}, {val: '11', label: 'November'}, {val: '12', label: 'Desember'}
            ],
            years: Array.from({length: 15}, (v, i) => 2026 + i)
        }">
            <div class="bg-gray-800 px-6 py-5 flex items-center justify-between text-white shrink-0 rounded-t-2xl">
                <h3 class="font-bold uppercase tracking-wider text-sm">Pilih Bulan Laporan</h3>
                <button onclick="closeMonthModal()" class="text-white/80 hover:text-white text-2xl transition">&times;</button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-8">
                    {{-- Custom Month Selector --}}
                    <div class="relative">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Bulan</label>
                        <button @click="openM = !openM; openY = false" type="button" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm flex items-center justify-between hover:border-blue-400 transition">
                            <span x-text="months.find(m => m.val == month).label"></span>
                            <svg class="w-4 h-4 text-gray-400 transition" :class="openM ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="openM" @click.away="openM = false" class="absolute left-0 right-0 mt-1 bg-white border border-gray-100 rounded-xl shadow-xl z-[110] max-h-48 overflow-y-auto py-1 animate-fadeInDown">
                            <template x-for="m in months" :key="m.val">
                                <div @click="month = m.val; openM = false" class="px-4 py-2 text-sm hover:bg-blue-50 cursor-pointer transition text-gray-700 font-medium" :class="month == m.val ? 'bg-blue-50 text-blue-600 font-bold' : ''">
                                    <span x-text="m.label"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Custom Year Selector --}}
                    <div class="relative">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Tahun</label>
                        <button @click="openY = !openY; openM = false" type="button" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm flex items-center justify-between hover:border-blue-400 transition">
                            <span x-text="year"></span>
                            <svg class="w-4 h-4 text-gray-400 transition" :class="openY ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="openY" @click.away="openY = false" class="absolute left-0 right-0 mt-1 bg-white border border-gray-100 rounded-xl shadow-xl z-[110] max-h-48 overflow-y-auto py-1 animate-fadeInDown">
                            <template x-for="y in years" :key="y">
                                <div @click="year = y; openY = false" class="px-4 py-2 text-sm hover:bg-blue-50 cursor-pointer transition text-gray-700 font-medium" :class="year == y ? 'bg-blue-50 text-blue-600 font-bold' : ''">
                                    <span x-text="y"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Hidden inputs for JS functions to use --}}
                <input type="hidden" id="modal_month" :value="month">
                <input type="hidden" id="modal_year" :value="year">

                <div class="flex flex-col gap-2">
                    <button onclick="downloadMonthPdf()" class="w-full bg-gray-800 hover:bg-black text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
