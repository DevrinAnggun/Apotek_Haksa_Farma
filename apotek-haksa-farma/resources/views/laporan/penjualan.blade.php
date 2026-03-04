@extends('layouts.admin')

@section('content')
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase">HALAMAN PENJUALAN</h2>
</div>

<!-- Toolbar Filter dan Export -->
<div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <!-- Filter Berdasarkan Tanggal -->
    <form action="{{ route('laporan.penjualan') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Dari:</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-600">
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Sampai:</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-green-600">
        </div>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-4 rounded text-sm transition shadow-sm w-full sm:w-auto">
            Filter Data
        </button>
    </form>

    <!-- Tombol Tambah Transaksi & Export Laporan -->
    <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto mt-3 md:mt-0">
        <!-- Ke Halaman Tambah Transaksi Kasir -->
        <a href="{{ route('kasir.pos') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-4 rounded shadow-sm transition w-full text-center sm:w-auto text-sm">
            + Tambah Transaksi
        </a>
        
        <!-- Dropdown Cetak Laporan Harian / Bulanan -->
        <div class="relative inline-block text-left w-full sm:w-auto" x-data="{ open: false }">
            <div>
                <button type="button" @click="open = !open" @click.away="open = false" class="bg-green-600 hover:bg-green-700 text-white font-medium py-1.5 px-4 rounded shadow-sm transition flex items-center justify-center gap-2 w-full sm:w-auto text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Laporan
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
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
                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 py-1" style="display: none;">
                
                <!-- Laporan Harian (Hari ini saja) -->
                <a href="{{ route('laporan.cetak_pdf', ['start_date' => \Carbon\Carbon::today()->format('Y-m-d'), 'end_date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Laporan Harian (Hari Ini)
                </a>
                
                <!-- Laporan Bulanan (30 hari terakhir) -->
                <a href="{{ route('laporan.cetak_pdf', ['start_date' => \Carbon\Carbon::now()->subDays(30)->format('Y-m-d'), 'end_date' => \Carbon\Carbon::today()->format('Y-m-d')]) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 flex items-center gap-2 border-t border-gray-100">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Laporan Bulanan
                </a>

                <!-- Opsi Sesuai Filter -->
                <a href="{{ route('laporan.cetak_pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2 border-t border-gray-100 bg-gray-50 font-medium">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Cetak Sesuai Filter
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Card View Laporan Mirip Kasir (Sesuai Mockup) -->
<div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col mt-6">
    <div class="p-6 flex-1 overflow-auto">
        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4 flex justify-between items-center">
            Daftar Transaksi Selesai
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full w-auto">
                Riwayat Tersimpan
            </span>
        </h3>
    
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-500 text-sm uppercase">
                        <th class="py-3 px-3 font-semibold w-12 text-center">No</th>
                        <th class="py-3 px-3 font-semibold">Tanggal Transaksi</th>
                        <th class="py-3 px-3 font-semibold">Nama Obat</th>
                        <th class="py-3 px-3 font-semibold">Jenis Obat</th>
                        <th class="py-3 px-3 font-semibold">Satuan</th>
                        <th class="py-3 px-3 font-semibold text-center">Stok Sisa</th>
                        <th class="py-3 px-3 font-semibold text-right">Harga Satuan</th>
                        <th class="py-3 px-3 font-semibold text-center w-24">Qty</th>
                        <th class="py-3 px-3 font-semibold text-right">Total Transaksi</th>
                        <th class="py-3 px-3 font-semibold text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; $grandTotal = 0; @endphp
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
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-3 px-3 text-center text-gray-800">{{ $no++ }}</td>
                                <td class="py-3 px-3 text-gray-600 text-sm whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d/m/Y') }}
                                    <span class="text-[10px] text-gray-400 block">{{ \Carbon\Carbon::parse($penjualan->created_at)->format('H:i') }} WIB</span>
                                </td>
                                <td class="py-3 px-3 font-medium text-gray-800">{{ $detail->obat->nama_obat ?? '-' }}</td>
                                <td class="py-3 px-3 text-gray-600">{{ $detail->obat->kategori->nama_kategori ?? '-' }}</td>
                                <td class="py-3 px-3 text-gray-600">{{ $detail->obat->satuan->nama_satuan ?? '-' }}</td>
                                <td class="py-3 px-3 text-center font-bold {{ ($detail->obat->total_stok ?? 0) < 5 ? 'text-red-500' : 'text-green-600' }}">
                                    {{ $detail->obat->total_stok ?? 0 }}
                                </td>
                                <td class="py-3 px-3 text-right text-gray-600">Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="bg-gray-200 text-black px-2 py-1 rounded font-bold">{{ $detail->qty }}</span>
                                </td>
                                <td class="py-3 px-3 text-right font-bold text-gray-800">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-center">
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
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @php $grandTotal += $detail->subtotal; @endphp
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-gray-400 italic">Belum ada data penjualan pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($penjualans->count() > 0)
    <!-- Panel Footer Laporan -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-xl flex justify-end items-center gap-4">
        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan Terjual:</p>
        <span class="text-base font-extrabold text-gray-900">
            Rp{{ number_format($grandTotal, 0, ',', '.') }}
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
    /* Hide Tutup button when printing if needed later */
    @media print {
        .no-print { display: none; }
    }
</style>

<script>
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
    }

    function closeStruk() {
        const modal = document.getElementById('modalStruk');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeStruk();
    });
</script>

@endsection
