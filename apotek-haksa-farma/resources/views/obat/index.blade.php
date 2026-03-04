@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-8">DATA & STOK OBAT</h2>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="dismissAlert('flash-success')" class="ml-4 text-green-700 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif
@if(session('error'))
    <div id="flash-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="dismissAlert('flash-error')" class="ml-4 text-red-700 hover:text-red-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif

<!-- Tabs Navigation & Toolbar -->
<div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-300 pb-2 mb-6 gap-4 mt-8">
    <!-- Tab Links -->
    <div class="flex space-x-6 overflow-x-auto pb-1 font-bold text-gray-800 uppercase tracking-widest min-w-max">
        <a href="{{ route('obat.index', request()->except('kategori')) }}" class="pb-1 transition {{ !request('kategori') ? 'text-black border-b-[3px] border-black' : 'text-gray-500 hover:text-black hover:border-b-[3px] hover:border-gray-400' }}">Semua</a>
        @foreach($kategoris as $kat)
            <a href="{{ route('obat.index', array_merge(request()->query(), ['kategori' => $kat->id])) }}" class="pb-1 transition {{ request('kategori') == $kat->id ? 'text-black border-b-[3px] border-black' : 'text-gray-500 hover:text-black hover:border-b-[3px] hover:border-gray-400' }}">
                {{ $kat->nama_kategori }}
            </a>
        @endforeach
    </div>
</div>

<!-- Search and Add Action -->
<div class="mb-6">
    <form action="{{ route('obat.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2">
        @if(request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif
        <div class="relative w-full sm:w-1/2 md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Barang....." class="w-full pl-4 pr-2 py-2 focus:outline-none">
            <button type="submit" class="px-3 flex items-center bg-gray-50 hover:bg-green-100 transition text-green-600 border-l border-gray-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>
        <!-- Tombol + Tambah (buka modal) -->
        <button type="button" onclick="openTambahModal()"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition text-center shadow">
            + Tambah
        </button>
    </form>
</div>

<!-- Table Data -->
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max">
        <thead>
            <tr class="border-b border-gray-300">
                <th class="py-3 px-4 font-bold text-gray-800 text-center w-16 relative">No<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 relative">Nama Barang<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-40 relative">Harga<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-28 relative">Satuan<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-24 relative">Stok<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-24 relative text-xs">Terjual<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-40 relative">Tgl Kadaluarsa<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-28">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($obats as $index => $obat)
            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                <td class="py-3 px-4 text-center text-gray-800 font-medium border-r border-gray-100">{{ $index + 1 }}</td>
                <td class="py-3 px-6 text-gray-800 font-medium uppercase border-r border-gray-100">{{ $obat->nama_obat }}</td>
                <td class="py-3 px-6 text-center text-gray-800 font-medium border-r border-gray-100">Rp.{{ number_format($obat->harga_jual, 0, ',', '.') }}</td>
                <td class="py-3 px-6 text-center text-gray-800 font-medium border-r border-gray-100">{{ $obat->satuan->nama_satuan ?? '—' }}</td>
                <td class="py-3 px-6 text-center text-gray-800 font-medium border-r border-gray-100">{{ $obat->total_stok }}</td>
                <td class="py-3 px-6 text-center text-gray-800 border-r border-gray-100">
                    <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded shadow-sm text-xs border border-blue-100">
                        {{ $obat->total_terjual ?? 0 }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center border-r border-gray-100">
                    @if($obat->tanggal_kadaluarsa)
                        <span class="font-medium {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->isPast() ? 'text-red-500 font-bold' : 'text-gray-800' }}">
                            {{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d-m-Y') }}
                        </span>
                    @else
                        <span class="text-gray-400 italic">Kosong</span>
                    @endif
                </td>
                <td class="py-3 px-6 flex justify-center items-center gap-1">
                    <!-- Tombol Edit (buka modal edit) -->
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
                        onclick="openEditModal(this)"
                        class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm"
                        title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>

                    <!-- Tombol Hapus (buka modal konfirmasi) -->
                    <button type="button"
                        onclick="openHapusModal({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}')"
                        class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded transition shadow-sm"
                        title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-6 text-center text-gray-500">Belum ada data obat. Silakan tambah data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


{{-- ============================================================ --}}
{{--  MODAL TAMBAH BARANG                                          --}}
{{-- ============================================================ --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal">
        <!-- Header -->
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center">Tambah Barang</h3>
            <button onclick="closeTambahModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <!-- Body -->
        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <form id="formTambah" action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="kode_obat" id="tambah_kode_obat">
                <input type="hidden" name="harga_beli" value="0">

                <!-- Kategori -->
                <select name="id_kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-600 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm cursor-pointer">
                    <option value="">-- Pilih Kategori (Contoh: Sirup) --</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>

                <!-- Nama Obat -->
                <input type="text" name="nama_obat" required placeholder="Nama Obat (Contoh: OB HERBAL SYR 60ML)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Harga Jual -->
                <input type="number" name="harga_jual" min="0" required placeholder="Harga Jual (Contoh: 19000)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Stok -->
                <input type="number" name="stok" min="0" placeholder="Stok Fisik Awal (Contoh: 100)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Satuan & Kadaluarsa -->
                <div class="grid grid-cols-2 gap-4 items-start">
                    <select name="id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-600 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm">
                        <option value="">-- Satuan (Botol/Strip) --</option>
                        @foreach($satuans as $sat)
                            <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                        @endforeach
                    </select>
                    <div class="flex flex-col">
                        <input type="date" name="expired_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-600 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                        <p class="text-[10px] text-gray-400 mt-1 ml-1">Tanggal Kadaluarsa Stok (Opsional)</p>
                    </div>
                </div>


                @if ($errors->any() && !session('_edit_mode'))
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">{{ $errors->first() }}</div>
                @endif
            </form>
        </div>
        <div class="flex justify-between items-center px-6 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeTambahModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 rounded-lg transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formTambah', 'Data Berhasil Ditambahkan!')" class="px-6 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Tambah</button>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{--  MODAL EDIT BARANG                                            --}}
{{-- ============================================================ --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal">
        <!-- Header -->
        <div class="bg-green-800 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Edit Barang</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-gray-200 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <!-- Body -->
        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="kode_obat" id="edit_kode_obat">
                <input type="hidden" name="harga_beli" id="edit_harga_beli">

                <!-- Kategori -->
                <select name="id_kategori" id="edit_id_kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>

                <!-- Nama Obat -->
                <input type="text" name="nama_obat" id="edit_nama_obat" required placeholder="Nama Obat"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Harga Jual -->
                <input type="number" name="harga_jual" id="edit_harga_jual" min="0" required placeholder="Harga Jual"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Stok -->
                <input type="number" name="stok" id="edit_stok" min="0" placeholder="Stok Fisik Saat Ini"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">

                <!-- Satuan & Kadaluarsa -->
                <div class="grid grid-cols-2 gap-4 items-start">
                    <select name="id_satuan" id="edit_id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white focus:outline-none focus:ring-1 focus:ring-green-500 appearance-none shadow-sm">
                        <option value="">-- Satuan --</option>
                        @foreach($satuans as $sat)
                            <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                        @endforeach
                    </select>
                    <div class="flex flex-col">
                        <input type="date" name="expired_date" id="edit_expired_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-1 focus:ring-green-500">
                        <p class="text-[10px] text-gray-400 mt-1 ml-1">Tanggal Kadaluarsa Stok (Opsional)</p>
                    </div>
                </div>

            </form>
        </div>
        <div class="flex justify-between items-center px-6 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeEditModal()" class="px-5 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formEdit', 'Perubahan Berhasil Disimpan!')" class="px-6 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Simpan</button>
        </div>
    </div>
</div>


{{-- ============================================================ --}}
{{--  MODAL KONFIRMASI HAPUS                                       --}}
{{-- ============================================================ --}}
<div id="modalHapus" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeHapusModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 animate-modal">
        <!-- Pesan -->
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">
                Yakin ingin menghapus <span id="hapus_nama_obat" class="text-green-700"></span>?
            </p>
            <p class="text-sm text-gray-500">Stok obat ini akan ikut terhapus dan tidak bisa dikembalikan.</p>
        </div>
        <!-- Tombol Aksi -->
        <div class="flex gap-3 px-6 pb-6">
            <button type="button" onclick="closeHapusModal()" class="flex-1 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
            <form id="formHapus" action="" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="button" onclick="showSuccessAnimation('formHapus', 'Data Berhasil Dihapus!')" class="w-full py-2.5 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL SUKSES DENGAN ANIMASI CENTANG ===== --}}
<div id="modalSukses" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-72 mx-4 py-8 px-6 text-center sukses-box">
        <!-- Animated Checkmark SVG -->
        <div class="flex justify-center mb-5">
            <svg class="w-24 h-24" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="44" stroke="#16a34a" stroke-width="6"
                    stroke-dasharray="276" stroke-dashoffset="276"
                    class="circle-anim">
                </circle>
                <polyline points="28,52 44,68 73,34" stroke="#16a34a" stroke-width="7"
                    stroke-linecap="round" stroke-linejoin="round"
                    stroke-dasharray="80" stroke-dashoffset="80"
                    class="check-anim">
                </polyline>
            </svg>
        </div>
        <h3 id="sukses_title" class="text-xl font-extrabold text-gray-800 mb-1">Berhasil!</h3>
        <p class="text-sm text-gray-400 mt-1">Sedang memperbarui data...</p>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(-12px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
    .animate-modal { animation: modalIn 0.2s ease-out both; }

    .sukses-box {
        animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
    }
    @keyframes popIn {
        from { opacity: 0; transform: scale(0.7); }
        to   { opacity: 1; transform: scale(1); }
    }
    .circle-anim { animation: drawCircle 0.65s ease forwards; }
    .check-anim { animation: drawCheck 0.45s ease 0.55s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>

@endsection

@push('scripts')
<script>
    /* ===== MODAL TAMBAH ===== */
    function openTambahModal() {
        // Generate kode otomatis
        document.getElementById('tambah_kode_obat').value = 'OBT-' + Date.now();
        document.getElementById('modalTambah').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeTambahModal() {
        document.getElementById('modalTambah').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ===== MODAL EDIT ===== */
    function openEditModal(el) {
        const d = el.dataset;
        const id = d.id;
        const nama = d.nama;
        const idKategori = d.idKategori;
        const hargaJual = d.hargaJual;
        const stok = d.stok;
        const idSatuan = d.idSatuan;
        const kodeObat = d.kodeObat;
        const hargaBeli = d.hargaBeli;
        const expiredDate = d.expiredDate;

        const modal = document.getElementById('modalEdit');
        const form  = document.getElementById('formEdit');

        form.action = '{{ url("obat") }}/' + id;

        document.getElementById('edit_kode_obat').value    = kodeObat;
        document.getElementById('edit_harga_beli').value   = hargaBeli;
        document.getElementById('edit_nama_obat').value    = nama;
        document.getElementById('edit_harga_jual').value   = hargaJual;
        document.getElementById('edit_stok').value         = stok;
        document.getElementById('edit_expired_date').value = expiredDate;

        const selKat = document.getElementById('edit_id_kategori');
        selKat.value = idKategori;

        const selSat = document.getElementById('edit_id_satuan');
        selSat.value = idSatuan;


        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
        document.body.style.overflow = '';
    }


    /* ===== MODAL HAPUS ===== */
    function openHapusModal(id, nama) {
        document.getElementById('hapus_nama_obat').textContent = nama;
        document.getElementById('formHapus').action = '{{ url("obat") }}/' + id;
        document.getElementById('modalHapus').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeHapusModal() {
        document.getElementById('modalHapus').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ===== TUTUP SEMUA MODAL DENGAN ESC ===== */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTambahModal();
            closeEditModal();
            closeHapusModal();
        }
    });

    /* ===== AUTO BUKA TAMBAH MODAL jika ada error validasi dari tambah ===== */
    @if ($errors->any() && old('_form_type') === 'tambah')
        document.addEventListener('DOMContentLoaded', () => openTambahModal());
    @endif

    /* ===== ANIMASI SUKSES SEBELUM SUBMIT ===== */
    function showSuccessAnimation(formId, titleText) {
        const form = document.getElementById(formId);
        
        // Validasi simpel (HTML5 required check)
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

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

        // Submit setelah animasi (1.5 detik)
        setTimeout(() => {
            form.submit();
        }, 1500);
    }
</script>
@endpush

