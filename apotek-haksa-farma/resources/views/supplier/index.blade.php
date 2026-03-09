@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2">DATA SUPPLIER</h2>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="dismissAlert('flash-success')" class="ml-4 text-green-700 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif

<!-- Toolbar: Search and Add Action -->
<div class="mb-6 flex flex-col sm:flex-row items-center gap-2">
    <!-- Search Bar -->
    <div class="relative w-full sm:w-1/2 md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
        <input type="text" id="searchSupplier" placeholder="Cari Supplier....." class="w-full pl-4 pr-2 py-2 focus:outline-none text-sm" onkeyup="filterTable()">
        <div class="px-3 flex items-center bg-gray-50 text-green-600 border-l border-gray-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <!-- Tombol Tambah Supplier -->
    <button type="button" onclick="openTambahModal()"
        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition text-center shadow">
        + Supplier
    </button>
</div>

<!-- Table Data -->
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max">
        <thead>
            <tr class="border-b border-gray-300">
                <th class="py-3 px-4 font-bold text-gray-800 text-center w-16 relative">No<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 relative">Nama Supplier<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 relative">Kontak / No. Telp<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 relative">Alamat<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-28">Aksi</th>
            </tr>
        </thead>
        <tbody id="supplierTableBody">
            @forelse($suppliers as $index => $supplier)
            <tr class="border-b border-gray-200 hover:bg-gray-50 transition supplier-row">
                <td class="py-3 px-4 text-center text-gray-800 font-medium border-r border-gray-100">{{ $index + 1 }}</td>
                <td class="py-3 px-6 text-gray-800 font-bold uppercase border-r border-gray-100">{{ $supplier->nama_suplier }}</td>
                <td class="py-3 px-6 text-gray-800 font-medium border-r border-gray-100">{{ $supplier->kontak ?? '-' }}</td>
                <td class="py-3 px-6 text-gray-800 border-r border-gray-100 italic text-xs">{{ $supplier->alamat ?? '-' }}</td>
                <td class="py-3 px-6">
                    <div class="flex justify-center items-center gap-1">
                        <!-- Tombol Edit -->
                        <button type="button" 
                            data-id="{{ $supplier->id }}"
                            data-nama="{{ $supplier->nama_suplier }}"
                            data-kontak="{{ $supplier->kontak }}"
                            data-alamat="{{ $supplier->alamat }}"
                            onclick="openEditModal(this)"
                            class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-12 text-center text-gray-400 italic">Belum ada data supplier.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL TAMBAH SUPPLIER --}}
<div id="modalTambah" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden animate-modal">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold uppercase tracking-wide w-full text-center">Tambah Supplier</h3>
            <button onclick="closeTambahModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="p-6">
            <form id="formTambah" action="{{ route('supplier.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Nama Supplier</label>
                    <input type="text" name="nama_suplier" required placeholder="Contoh: PT. KIMIA FARMA" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 uppercase text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Kontak / No HP</label>
                    <input type="text" name="kontak" placeholder="Contoh: 08123456789" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap supplier..." 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 text-sm"></textarea>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-6 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeTambahModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 rounded-lg transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formTambah', 'Supplier Berhasil Ditambahkan!')" class="px-6 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Simpan</button>
        </div>
    </div>
</div>

{{-- MODAL EDIT SUPPLIER --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden animate-modal">
        <div class="bg-green-800 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold uppercase tracking-wide w-full text-center">Edit Supplier</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="p-6">
            <form id="formEdit" action="" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Nama Supplier</label>
                    <input type="text" name="nama_suplier" id="edit_nama" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 uppercase text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Kontak / No HP</label>
                    <input type="text" name="kontak" id="edit_kontak"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Alamat</label>
                    <textarea name="alamat" id="edit_alamat" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 text-sm"></textarea>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-6 py-4 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeEditModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:bg-gray-200 rounded-lg transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formEdit', 'Perubahan Berhasil Disimpan!')" class="px-6 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">Simpan</button>
        </div>
    </div>
</div>

{{-- MODAL SUKSES DENGAN ANIMASI CENTANG --}}
<div id="modalSukses" class="fixed inset-0 z-[100] hidden items-center justify-center">
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
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-modal { animation: modalIn 0.2s ease-out both; }
    .sukses-box { animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
    @keyframes popIn { from { opacity: 0; transform: scale(0.7); } to { opacity: 1; transform: scale(1); } }
    .circle-anim { animation: drawCircle 0.65s ease forwards; }
    .check-anim { animation: drawCheck 0.45s ease 0.55s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>

<script>
    function dismissAlert(id) {
        const el = document.getElementById(id);
        if(el) el.style.display = 'none';
    }

    function openTambahModal() {
        document.getElementById('modalTambah').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTambahModal() {
        document.getElementById('modalTambah').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openEditModal(el) {
        const d = el.dataset;
        document.getElementById('edit_nama').value = d.nama;
        document.getElementById('edit_kontak').value = d.kontak;
        document.getElementById('edit_alamat').value = d.alamat;
        document.getElementById('formEdit').action = '/supplier/' + d.id;
        
        document.getElementById('modalEdit').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('modalEdit').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function filterTable() {
        const input = document.getElementById('searchSupplier');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('.supplier-row');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    function showSuccessAnimation(formId, message) {
        const form = document.getElementById(formId);
        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const modal = document.getElementById('modalSukses');
        document.getElementById('sukses_title').innerText = message;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            form.submit();
        }, 1100);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTambahModal();
            closeEditModal();
        }
    });
</script>
@endsection
