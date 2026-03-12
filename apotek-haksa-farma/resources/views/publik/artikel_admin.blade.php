@extends('layouts.admin')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight uppercase">KELOLA ARTIKEL</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola konten edukasi dan tips kesehatan untuk pelanggan.</p>
    </div>
    <button type="button" onclick="openCreateArtikelModal()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-green-200 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Artikel Baru
    </button>
</div>

@if(session('success'))
    <div id="flash-success" class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-8 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-green-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="dismissAlert('flash-success')" class="text-green-500 hover:text-green-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($artikels as $artikel)
    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative">
        {{-- Kategori Badge --}}
        <div class="absolute top-4 left-4 z-10">
            <span class="bg-green-600 text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest shadow-md">
                {{ $artikel->kategori ?? 'Umum' }}
            </span>
        </div>

        {{-- Action Buttons --}}
        <div class="absolute top-4 right-4 z-10 flex gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
            <button type="button" 
                onclick="openEditArtikelModal({{ json_encode($artikel) }})"
                class="p-2.5 bg-white text-green-600 rounded-xl shadow-lg hover:bg-green-600 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <button type="button" 
                onclick="openDeleteModal('{{ route('artikel.destroy', $artikel->id) }}')"
                class="p-2.5 bg-white text-red-600 rounded-xl shadow-lg hover:bg-red-600 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </div>

        {{-- Image --}}
        <div class="h-48 overflow-hidden bg-gray-50">
            @if($artikel->gambar)
                <img src="{{ asset($artikel->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center bg-green-50/50">
                    <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="p-6 flex-1 flex flex-col">
            <h3 class="text-lg font-extrabold text-gray-800 line-clamp-2 leading-tight mb-3">
                {{ $artikel->judul }}
            </h3>
            <p class="text-sm text-gray-500 line-clamp-3 mb-6 flex-1">
                {{ $artikel->ringkasan }}
            </p>
            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                    {{ $artikel->tanggal_publish ? \Carbon\Carbon::parse($artikel->tanggal_publish)->format('d M Y') : 'Draft' }}
                </span>
                <span class="text-xs font-bold text-green-600 group-hover:translate-x-1 transition flex items-center gap-1">
                    Manage <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        <h3 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Belum Ada Artikel</h3>
        <p class="text-gray-400 text-sm mt-1">Mulai tulis artikel edukasi pertama Anda hari ini.</p>
    </div>
    @endforelse
</div>

{{-- MODAL CREATE --}}
<div id="modalCreateArtikel" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeCreateArtikelModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Tulis Artikel Baru</h3>
            <button onclick="closeCreateArtikelModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light">&times;</button>
        </div>
        <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[85vh]">
            @csrf
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Judul Artikel</label>
                            <input type="text" name="judul" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Kategori</label>
                            <select name="kategori" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm font-medium">
                                <option value="Edukasi">Edukasi</option>
                                <option value="Tips Kesehatan">Tips Kesehatan</option>
                                <option value="Keamanan">Keamanan</option>
                                <option value="Info Produk">Info Produk</option>
                                <option value="Berita">Berita</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Gambar Utama</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl h-44 flex flex-col items-center justify-center hover:border-green-400 transition relative overflow-hidden group">
                            <input type="file" name="gambar" accept="image/*" onchange="previewImage(event, 'preview-create')" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            <div id="placeholder-create" class="text-center">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Upload Foto</p>
                            </div>
                            <img id="preview-create" class="absolute inset-0 w-full h-full object-cover hidden">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Ringkasan</label>
                    <textarea name="ringkasan" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm" placeholder="Tuliskan 1-2 kalimat pengantar..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Isi Lengkap</label>
                    <textarea name="konten" rows="8" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm font-serif"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-8 py-5 border-t border-gray-100 bg-gray-50">
                <button type="button" onclick="closeCreateArtikelModal()" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-0.5">Simpan Artikel</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEditArtikel" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditArtikelModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Edit Artikel</h3>
            <button onclick="closeEditArtikelModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light">&times;</button>
        </div>
        <form id="formEditArtikel" action="" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[85vh]">
            @csrf @method('PUT')
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Judul Artikel</label>
                            <input type="text" name="judul" id="edit_judul" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Kategori</label>
                            <select name="kategori" id="edit_kategori" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm font-medium">
                                <option value="Edukasi">Edukasi</option>
                                <option value="Tips Kesehatan">Tips Kesehatan</option>
                                <option value="Keamanan">Keamanan</option>
                                <option value="Info Produk">Info Produk</option>
                                <option value="Berita">Berita</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Gambar Utama</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl h-44 flex flex-col items-center justify-center hover:border-green-400 transition relative overflow-hidden group">
                            <input type="file" name="gambar" accept="image/*" onchange="previewImage(event, 'preview-edit')" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            <div id="placeholder-edit" class="text-center">
                                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Ganti Foto</p>
                            </div>
                            <img id="preview-edit" class="absolute inset-0 w-full h-full object-cover hidden">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Ringkasan</label>
                    <textarea name="ringkasan" id="edit_ringkasan" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Isi Lengkap</label>
                    <textarea name="konten" id="edit_konten" rows="8" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 transition shadow-sm font-serif"></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-8 py-5 border-t border-gray-100 bg-gray-50">
                <button type="button" onclick="closeEditArtikelModal()" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-lg transition transform hover:-translate-y-0.5">Perbarui Artikel</button>
            </div>
        </form>
    </div>
</div>

{{-- ========== MODAL KONFIRMASI HAPUS ========== --}}
<div id="modalHapusArtikel" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-[320px] mx-4 overflow-hidden animate-modal">
        <div class="bg-red-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">KONFIRMASI HAPUS</h4>
        </div>
        <div class="px-6 pt-8 pb-4 text-center">
            <p class="text-base font-bold text-gray-800 mb-2 leading-relaxed">
                Hapus Artikel ini?
            </p>
            <p class="text-[11px] text-gray-500 italic leading-relaxed">
                Data yang dihapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-8 mt-4">
            <button type="button" onclick="closeDeleteModal()"
                class="flex-1 py-2.5 text-xs font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition uppercase tracking-widest">BATAL</button>
            <form id="formDeleteArtikel" action="" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                    class="w-full py-2.5 text-xs font-extrabold bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-lg transition uppercase tracking-widest">YA, HAPUS</button>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event, targetId) {
        const reader = new FileReader();
        const preview = document.getElementById(targetId);
        const placeholderId = targetId === 'preview-create' ? 'placeholder-create' : 'placeholder-edit';
        const placeholder = document.getElementById(placeholderId);
        
        reader.onload = function() {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            if(placeholder) placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    function openCreateArtikelModal() {
        document.getElementById('modalCreateArtikel').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeCreateArtikelModal() {
        document.getElementById('modalCreateArtikel').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openEditArtikelModal(artikel) {
        const form = document.getElementById('formEditArtikel');
        form.action = `/artikel/${artikel.id}`;
        document.getElementById('edit_judul').value = artikel.judul;
        document.getElementById('edit_kategori').value = artikel.kategori;
        document.getElementById('edit_ringkasan').value = artikel.ringkasan;
        document.getElementById('edit_konten').value = artikel.konten;
        
        const preview = document.getElementById('preview-edit');
        const placeholder = document.getElementById('placeholder-edit');
        if(artikel.gambar) {
            preview.src = `/${artikel.gambar}`;
            preview.classList.remove('hidden');
            if(placeholder) placeholder.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
            if(placeholder) placeholder.classList.remove('hidden');
        }

        document.getElementById('modalEditArtikel').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditArtikelModal() {
        document.getElementById('modalEditArtikel').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ===== DELETE MODAL LOGIC ===== */
    function openDeleteModal(actionUrl) {
        const modal = document.getElementById('modalHapusArtikel');
        const form = document.getElementById('formDeleteArtikel');
        form.action = actionUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('modalHapusArtikel');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
</script>

<style>
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-modal { animation: modalIn 0.2s ease-out both; }
</style>
@endsection
