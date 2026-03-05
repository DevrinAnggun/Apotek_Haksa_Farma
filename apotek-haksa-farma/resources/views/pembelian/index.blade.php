@extends('layouts.admin')

@section('content')
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2">RIWAYAT STOK MASUK</h2>
    <p class="text-sm text-gray-500">Daftar penerimaan barang dari supplier (Pembelian)</p>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden animate-modal">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-4 font-bold text-gray-700 text-xs uppercase tracking-widest text-center w-12">No</th>
                        <th class="py-4 px-4 font-bold text-gray-700 text-xs uppercase tracking-widest text-center">Tgl Terima</th>
                        <th class="py-4 px-4 font-bold text-gray-700 text-xs uppercase tracking-widest text-center">No. Faktur</th>
                        <th class="py-4 px-4 font-bold text-gray-700 text-xs uppercase tracking-widest text-center">Supplier</th>
                        <th class="py-4 px-4 font-bold text-gray-700 text-xs uppercase tracking-widest text-center">Total Bayar</th>
                        <th class="py-4 px-4 font-bold text-gray-700 text-xs uppercase tracking-widest text-center">Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pembelians as $pembelian)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="py-4 px-4 text-center text-gray-400 font-medium">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-center font-bold text-gray-800 uppercase tracking-wide">
                                {{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-bold uppercase">{{ $pembelian->no_faktur }}</span>
                            </td>
                            <td class="py-4 px-4 text-center font-semibold text-blue-700 uppercase tracking-wide">
                                {{ $pembelian->supplier->nama_suplier ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-center font-extrabold text-gray-900 italic">
                                Rp{{ number_format($pembelian->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span class="text-xs font-medium text-gray-500">{{ $pembelian->user->nama ?? 'Sistem' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 italic">
                                Belum ada riwayat stok masuk dari supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-100 italic">
        <p class="text-[10px] text-gray-400 font-medium">* Seluruh data di atas terhubung langsung dengan kartu stok barang dan data kadaluarsa.</p>
        <a href="{{ route('obat.index') }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase tracking-widest flex items-center gap-1 transition">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Data & Stok
        </a>
    </div>
</div>
@endsection
