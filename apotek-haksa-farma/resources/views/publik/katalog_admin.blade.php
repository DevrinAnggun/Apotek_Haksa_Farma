@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight uppercase">KATALOG PRODUK</h2>
    </div>
</div>

{{-- Flash Message --}}
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

<div class="flex flex-col md:flex-row gap-8">
    {{-- Sidebar Kategori --}}
    <div class="w-full md:w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
            <div class="bg-green-700 px-5 py-4 border-b border-green-800">
                <p class="text-white font-extrabold text-xs uppercase tracking-widest text-center">Filter Kategori</p>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('obat.katalog') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ !request('kategori') ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span>Semua Produk</span>
                   <span class="{{ !request('kategori') ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ \App\Models\Obat::whereHas('kategori', fn($q) => $q->where('nama_kategori', '!=', 'CEK'))->count() }}</span>
                </a>
                @foreach($kategoris as $kat)
                    @if(strtoupper($kat->nama_kategori) === 'CEK') @continue @endif
                <a href="{{ route('obat.katalog', ['kategori' => $kat->id]) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition {{ request('kategori') == $kat->id ? 'bg-green-600 text-white shadow-md' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                   <span class="truncate">{{ $kat->nama_kategori }}</span>
                   <span class="{{ request('kategori') == $kat->id ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-md text-[10px]">{{ $kat->obats_count ?? $kat->obats()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Grid Content --}}
    <div class="flex-1 space-y-6">
        {{-- Search --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex items-center gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <form action="{{ route('obat.katalog') }}" method="GET">
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di katalog produk..." 
                           class="w-full pl-12 pr-4 py-3 bg-transparent border-none focus:ring-0 text-sm font-medium text-gray-700" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($obats as $obat)
            @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK') @continue @endif
            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative p-4">
                <div class="flex items-start justify-between mb-3">
                    <span class="bg-green-50 text-green-700 text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider border border-green-100">
                        {{ $obat->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                    
                    <button type="button"
                        data-id="{{ $obat->id }}"
                        data-nama="{{ $obat->nama_obat }}"
                        data-id-kategori="{{ $obat->id_kategori }}"
                        data-harga-jual="{{ $obat->harga_jual }}"
                        data-stok="{{ $obat->total_stok }}"
                        data-id-satuan="{{ $obat->id_satuan }}"
                        data-kode-obat="{{ $obat->kode_obat }}"
                        data-harga-beli="{{ $obat->harga_beli }}"
                        data-expired-date="{{ $obat->tanggal_kadaluarsa ?? '' }}"
                        data-id-merk="{{ $obat->id_merk }}"
                        data-deskripsi="{{ $obat->deskripsi }}"
                        data-cara-pakai="{{ $obat->cara_pakai }}"
                        onclick="openEditModal(this)"
                        class="p-2 bg-gray-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                </div>

                @if($obat->gambar && file_exists(public_path($obat->gambar)))
                    <div class="w-full h-48 mb-3 bg-white rounded-2xl overflow-hidden flex items-center justify-center">
                        <img src="{{ asset($obat->gambar) }}" alt="{{ $obat->nama_obat }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="flex-1 flex flex-col">
                    <h3 class="text-sm font-extrabold text-gray-800 uppercase leading-snug tracking-tight mb-3 line-clamp-2">
                        {{ $obat->nama_obat }}
                    </h3>
                    
                    <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-gray-900 font-black uppercase tracking-widest leading-none mb-1">Harga Jual</p>
                            <p class="text-base font-extrabold text-green-700">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-900 font-black uppercase tracking-widest leading-none mb-1">Stok</p>
                            @if(isset($obat->kategori) && strtoupper($obat->kategori->nama_kategori) === 'CEK')
                                <p class="text-sm font-extrabold text-gray-900">-</p>
                            @else
                                <p class="text-sm font-extrabold text-gray-900">{{ $obat->total_stok ?? 0 }} <span class="text-[10px] text-gray-900 font-black">Pcs</span></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center text-gray-900 font-black uppercase tracking-widest">
                Tidak ada produk untuk ditampilkan di katalog.
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                Showing {{ $obats->firstItem() ?? 0 }}-{{ $obats->lastItem() ?? 0 }} of {{ $obats->total() }} results
            </p>
            <div>
                {{ $obats->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>


{{--  MODAL EDIT BARANG --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-800 px-6 py-4 flex items-center justify-between text-white border-b border-green-900">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Edit Katalog</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-gray-200 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-2 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formEdit" action="" method="POST" class="space-y-5">
                @csrf @method('PUT')
                <input type="hidden" name="kode_obat" id="edit_kode_obat">
                <input type="hidden" name="harga_beli" id="edit_harga_beli">
                <input type="hidden" name="stok" id="edit_stok">
                <input type="hidden" name="id_satuan" id="edit_id_satuan">
                <input type="hidden" name="expired_date" id="edit_expired_date">
                
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                    <select name="id_kategori" id="edit_id_kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-gray-50 focus:outline-none appearance-none shadow-sm uppercase text-sm font-bold cursor-not-allowed" onmousedown="return false;">
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat</label>
                    <input type="text" name="nama_obat" id="edit_nama_obat" readonly class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-gray-50 shadow-sm focus:outline-none uppercase text-sm font-bold cursor-not-allowed">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_jual" id="edit_harga_jual" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                </div>

                <div class="space-y-4 pt-4 border-t border-gray-50">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Deskripsi Obat <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3" required placeholder="Tambahkan informasi lengkap mengenai obat..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kegunaan / Cara Pakai <span class="text-red-500">*</span></label>
                        <textarea name="cara_pakai" id="edit_cara_pakai" rows="3" required placeholder="Contoh: Dewasa 3x1 sehari setelah makan" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-8 py-5 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition uppercase tracking-wider">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formEdit', 'Data Berhasil Disimpan!')" class="px-8 py-2.5 text-sm font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg transition uppercase tracking-wider">Simpan</button>
        </div>
    </div>
</div>



<style>
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-modal { animation: modalIn 0.2s ease-out both; }
</style>

@push('scripts')
<script>
    function openEditModal(el) {
        const d = el.dataset;
        const form = document.getElementById('formEdit');
        form.action = '{{ url("obat") }}/' + d.id;
        
        // Populate Hidden & Readonly Fields
        document.getElementById('edit_kode_obat').value = d.kodeObat;
        document.getElementById('edit_harga_beli').value = d.hargaBeli;
        document.getElementById('edit_stok').value = d.stok;
        document.getElementById('edit_id_satuan').value = d.idSatuan;
        document.getElementById('edit_expired_date').value = d.expiredDate;
        document.getElementById('edit_id_kategori').value = d.idKategori;
        document.getElementById('edit_nama_obat').value = d.nama;

        // Editable Content
        document.getElementById('edit_harga_jual').value = d.hargaJual;
        document.getElementById('edit_deskripsi').value = d.deskripsi || '';
        document.getElementById('edit_cara_pakai').value = d.caraPakai || '';

        document.getElementById('modalEdit').classList.remove('hidden');
        document.getElementById('modalEdit').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); document.getElementById('modalEdit').classList.remove('flex'); document.body.style.overflow = ''; }

</script>
@endpush
@endsection
