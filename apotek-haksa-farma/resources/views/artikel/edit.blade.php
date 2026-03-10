@extends('layouts.admin')

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('artikel.index') }}" class="p-2.5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition shadow-sm">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Edit Artikel</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui informasi edukasi untuk pelanggan.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="space-y-6">
                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Judul Artikel</label>
                    <input type="text" name="judul" value="{{ old('judul', $artikel->judul) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                        placeholder="Contoh: Tips Menyimpan Obat dengan Benar">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                        @foreach(['Edukasi', 'Tips Kesehatan', 'Keamanan', 'Info Produk', 'Berita'] as $cat)
                            <option value="{{ $cat }}" {{ $artikel->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Gambar --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Gambar Utama (Opsional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-2xl hover:border-green-400 transition cursor-pointer relative" id="dropzone">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                <span>Ganti file</span>
                                <input id="gambar" name="gambar" type="file" class="sr-only" onchange="previewImage(event)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
                    </div>
                    <img id="preview" src="{{ $artikel->gambar ? asset($artikel->gambar) : '' }}" class="{{ $artikel->gambar ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover rounded-2xl p-1 bg-white">
                </div>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="mb-8">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Ringkasan (Pendek)</label>
            <textarea name="ringkasan" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition" placeholder="Tuliskan 1-2 kalimat pengantar artikel ini...">{{ old('ringkasan', $artikel->ringkasan) }}</textarea>
        </div>

        {{-- Konten --}}
        <div class="mb-8">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Isi Lengkap Artikel</label>
            <textarea name="konten" rows="12" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition" placeholder="Tuliskan isi artikel selengkap mungkin...">{{ old('konten', $artikel->konten) }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-8">
            <a href="{{ route('artikel.index') }}" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5">
                Perbarui Artikel
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        const preview = document.getElementById('preview');
        reader.onload = function() {
            preview.src = reader.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
