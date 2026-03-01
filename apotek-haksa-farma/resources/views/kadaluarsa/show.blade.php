@extends('layouts.admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-extrabold text-black tracking-wide uppercase">Detail Data Kadaluarsa</h2>
        <p class="text-sm text-gray-500 mt-1">Informasi lengkap batch obat kadaluarsa</p>
    </div>
    <a href="{{ route('kadaluarsa.index') }}" class="text-sm text-green-700 hover:underline flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Data Kadaluarsa
    </a>
</div>

@php
    $now      = \Carbon\Carbon::now();
    $expired  = \Carbon\Carbon::parse($kadaluarsa->tgl_expired);
    $diffDays = (int)$now->diffInDays($expired, false);

    if ($diffDays < 0) {
        $statusLabel = 'Sudah Kadaluarsa';
        $statusClass = 'bg-red-100 text-red-700 border border-red-200';
        $badgeBg     = 'bg-red-50 border-red-200';
    } elseif ($diffDays <= 7) {
        $statusLabel = 'Segera — H-' . $diffDays;
        $statusClass = 'bg-red-100 text-red-700 border border-red-200';
        $badgeBg     = 'bg-red-50 border-red-200';
    } else {
        $statusLabel = 'Segera — H-' . $diffDays;
        $statusClass = 'bg-orange-100 text-orange-700 border border-orange-200';
        $badgeBg     = 'bg-orange-50 border-orange-200';
    }
@endphp

<div class="max-w-2xl">

    {{-- Card Status --}}
    <div class="rounded-xl border {{ $badgeBg }} px-6 py-4 mb-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-0.5">Status Kadaluarsa</p>
            <span class="px-3 py-1 rounded-full text-sm font-bold border {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 mb-0.5">Tanggal Kadaluarsa</p>
            <p class="text-lg font-extrabold {{ $diffDays < 0 ? 'text-red-600' : 'text-gray-800' }}">
                {{ $expired->format('d M Y') }}
            </p>
        </div>
    </div>

    {{-- Card Detail --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        {{-- Header Card --}}
        <div class="bg-green-700 px-6 py-4">
            <h3 class="text-white font-bold text-base uppercase tracking-wide">
                {{ $kadaluarsa->obat->nama_obat ?? '—' }}
            </h3>
            <p class="text-green-200 text-xs mt-0.5">{{ $kadaluarsa->obat->kategori->nama_kategori ?? '—' }}</p>
        </div>

        {{-- Info Grid --}}
        <div class="divide-y divide-gray-100">

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Nama Obat</span>
                <span class="text-sm font-semibold text-gray-800 uppercase">{{ $kadaluarsa->obat->nama_obat ?? '—' }}</span>
            </div>

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Kategori</span>
                <span class="text-sm font-semibold text-gray-800">{{ $kadaluarsa->obat->kategori->nama_kategori ?? '—' }}</span>
            </div>

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Satuan</span>
                <span class="text-sm font-semibold text-gray-800">{{ $kadaluarsa->obat->satuan->nama_satuan ?? '—' }}</span>
            </div>

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Harga Jual</span>
                <span class="text-sm font-semibold text-gray-800">
                    Rp{{ number_format($kadaluarsa->obat->harga_jual ?? 0, 0, ',', '.') }}
                </span>
            </div>

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Stok Awal</span>
                <span class="text-sm font-semibold text-gray-800">{{ number_format($kadaluarsa->stok_awal, 0, ',', '.') }}</span>
            </div>

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Stok Sisa</span>
                <span class="text-sm font-bold {{ $kadaluarsa->stok_sisa <= 0 ? 'text-red-500' : 'text-green-700' }}">
                    {{ number_format($kadaluarsa->stok_sisa, 0, ',', '.') }}
                    @if($kadaluarsa->stok_sisa <= 0)
                        <span class="text-xs font-normal text-red-400 ml-1">(Habis)</span>
                    @endif
                </span>
            </div>

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Tgl Kadaluarsa</span>
                <span class="text-sm font-semibold {{ $diffDays < 0 ? 'text-red-600' : 'text-gray-800' }}">
                    {{ $expired->format('d M Y') }}
                    <span class="text-xs font-normal text-gray-400 ml-1">({{ $expired->diffForHumans() }})</span>
                </span>
            </div>

            <div class="flex items-center px-6 py-4">
                <span class="w-44 text-sm text-gray-500 font-medium">Terakhir Diperbarui</span>
                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($kadaluarsa->updated_at)->format('d M Y, H:i') }}</span>
            </div>

        </div>

        {{-- Footer Aksi --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center gap-3">
            <a href="{{ route('kadaluarsa.edit', $kadaluarsa->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-lg transition text-sm shadow">
                Edit Data
            </a>
            <form action="{{ route('kadaluarsa.destroy', $kadaluarsa->id) }}" method="POST"
                onsubmit="return confirm('Hapus data batch ini?');">
                @csrf @method('DELETE')
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-5 rounded-lg transition text-sm shadow">
                    Hapus
                </button>
            </form>
            <a href="{{ route('kadaluarsa.index') }}"
                class="text-gray-500 hover:text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>

    </div>
</div>
@endsection
