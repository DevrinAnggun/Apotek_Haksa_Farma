@extends('layouts.admin')

@section('content')

{{-- Validation Errors --}}
@if($errors->any())
<div class="mb-4 mx-auto max-w-2xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
    <p class="font-bold mb-1">Terdapat kesalahan input:</p>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Card Container --}}
<div class="flex justify-center">
    <div class="w-full max-w-xl rounded-xl overflow-hidden shadow-lg border border-gray-200">

        {{-- Header Hijau --}}
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between">
            <h2 class="text-white font-bold text-lg tracking-wide uppercase">Edit Data Kadaluarsa</h2>
            <a href="{{ route('kadaluarsa.index') }}"
                class="text-white hover:text-green-200 transition text-2xl font-light leading-none"
                title="Tutup">&times;</a>
        </div>

        {{-- Form Body --}}
        <div class="bg-white px-8 py-7">
            <form action="{{ route('kadaluarsa.update', $kadaluarsa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">

                    {{-- Nama Obat --}}
                    <div>
                        <select id="id_obat" name="id_obat"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white @error('id_obat') border-red-400 @enderror">
                            <option value="">-- Pilih Nama Obat --</option>
                            @foreach($obats as $obat)
                            <option value="{{ $obat->id }}" {{ old('id_obat', $kadaluarsa->id_obat) == $obat->id ? 'selected' : '' }}>
                                {{ $obat->nama_obat }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_obat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tanggal Kadaluarsa --}}
                    <div>
                        <input type="date" id="tgl_expired" name="tgl_expired"
                            value="{{ old('tgl_expired', \Carbon\Carbon::parse($kadaluarsa->tgl_expired)->format('Y-m-d')) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tgl_expired') border-red-400 @enderror">
                        <p class="text-gray-400 text-xs mt-1 px-1">Tanggal Kadaluarsa Obat</p>
                        @error('tgl_expired')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-between mt-7">
                    <button type="button" onclick="closeTambahModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 rounded-lg transition">Batal</button>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-7 rounded-lg transition shadow text-sm">
                        Simpan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection
