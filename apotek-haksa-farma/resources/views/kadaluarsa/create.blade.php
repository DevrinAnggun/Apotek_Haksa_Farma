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
            <h2 class="text-white font-bold text-lg tracking-wide">Tambah Data Kadaluarsa</h2>
            <a href="{{ route('kadaluarsa.index') }}"
                class="text-white hover:text-green-200 transition text-2xl font-light leading-none"
                title="Tutup">&times;</a>
        </div>

        {{-- Form Body --}}
        <div class="bg-white px-8 py-7">
            <form action="{{ route('kadaluarsa.store') }}" method="POST">
                @csrf

                <div class="space-y-4">

                    {{-- Nama Obat --}}
                    <div>
                        <select id="id_obat" name="id_obat"
                            onchange="autoFillStok(this)"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white @error('id_obat') border-red-400 @enderror">
                            <option value="" disabled {{ old('id_obat') ? '' : 'selected' }}>-- Pilih Nama Obat --</option>
                            @foreach($obats as $obat)
                            <option value="{{ $obat->id }}"
                                data-stok="{{ $obat->total_stok }}"
                                {{ old('id_obat') == $obat->id ? 'selected' : '' }}
                                class="text-gray-800">
                                {{ $obat->nama_obat }} (Stok: {{ $obat->total_stok }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_obat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Stok Sisa (otomatis dari Data & Stok) --}}
                    <div>
                        <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3 text-sm flex items-center justify-between">
                            <span class="text-gray-500">Stok Sisa (dari Data &amp; Stok)</span>
                            <span id="stok_display" class="font-bold text-gray-800">—</span>
                        </div>
                        <p class="text-gray-400 text-xs mt-1 px-1">Otomatis terisi sesuai stok obat yang dipilih</p>
                        {{-- Hidden input untuk dikirim ke controller --}}
                        <input type="hidden" id="stok_awal_input" name="stok_awal" value="{{ old('stok_awal', 0) }}">
                    </div>

                    {{-- Tanggal Kadaluarsa --}}
                    <div>
                        <input type="date" id="tgl_expired" name="tgl_expired"
                            value="{{ old('tgl_expired') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tgl_expired') border-red-400 @enderror">
                        <p class="text-gray-400 text-xs mt-1 px-1">Tanggal Kadaluarsa Obat</p>
                        @error('tgl_expired')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>

                {{-- Tombol Tambah --}}
                <div class="flex justify-end mt-7">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-8 rounded-lg transition shadow text-sm">
                        Tambah
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function autoFillStok(select) {
    const option = select.options[select.selectedIndex];
    const stok = option.dataset.stok ?? 0;
    
    document.getElementById('stok_display').textContent = stok > 0 ? stok : '0 (Habis)';
    document.getElementById('stok_display').className = stok > 0 
        ? 'font-bold text-green-700' 
        : 'font-bold text-red-500';
    document.getElementById('stok_awal_input').value = stok;
}
</script>
@endpush
