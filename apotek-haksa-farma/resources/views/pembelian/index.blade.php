@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2">STOK SUPPLIER</h2>
    <p class="text-sm text-gray-500">Manajemen pengadaan stok barang dari supplier</p>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="dismissAlert('flash-success')" class="ml-4 text-green-700 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif

<!-- Toolbar: Search and Action Buttons -->
<div class="mb-6 flex flex-col sm:flex-row items-center gap-2">
    <!-- Search Bar -->
    <div class="relative w-full sm:w-1/2 md:w-1/3 flex border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
        <input type="text" placeholder="Cari Riwayat....." class="w-full pl-4 pr-2 py-2 focus:outline-none text-sm">
        <button class="px-3 flex items-center bg-gray-50 hover:bg-green-100 transition text-green-600 border-l border-gray-200 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
    </div>

    <!-- Plus Stok Button -->
    <button type="button" onclick="openModalStokMasuk()"
        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition text-center shadow flex items-center justify-center gap-2">
        + Stok Masuk
    </button>

    <!-- PDF Report Button -->
    <a href="{{ route('pembelian.cetak_pdf') }}"
        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition text-center shadow flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        PDF Laporan
    </a>
</div>

{{-- ===== TABEL RIWAYAT STOK MASUK ===== --}}
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max">
        <thead>
            <tr class="border-b border-gray-300">
                <th class="py-3 px-4 font-bold text-gray-800 text-center w-16 relative">No<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center relative">Nama Barang<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center relative w-40">Tgl Terima<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center relative w-44">Tgl Kadaluarsa<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center relative">Supplier<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center w-20 relative">Qty<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center relative w-32">Harga Beli<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center relative w-32">Harga Jual<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-5 font-bold text-gray-800 text-center relative w-40">Subtotal<div class="absolute right-0 top-3 bottom-2 border-r border-gray-200"></div></th>
                <th class="py-3 px-6 font-bold text-gray-800 text-center w-28">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = ($pembelians->currentPage()-1) * $pembelians->perPage() + 1; @endphp
            @forelse($pembelians as $beli)
                @foreach($beli->details as $detail)
                @php 
                    $batch = $detail->obat->stokBatches()->where('id_pembelian', $beli->id)->first();
                @endphp
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition text-sm">
                    <td class="py-3 px-4 text-center text-gray-800 font-medium border-r border-gray-100">
                        {{ $no++ }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-800 font-bold uppercase border-r border-gray-100">
                        {{ $detail->obat->nama_obat ?? '-' }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-800 font-medium border-r border-gray-100">
                        {{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d-m-Y') }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-900 font-bold border-r border-gray-100">
                        {{ \Carbon\Carbon::parse($batch->tgl_expired ?? now())->format('d-m-Y') }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-900 font-bold uppercase border-r border-gray-100">
                        {{ $beli->supplier->nama_suplier ?? '-' }}
                    </td>
                    <td class="py-3 px-5 text-center border-r border-gray-100 font-bold">
                        {{ $detail->qty }}
                    </td>
                    <td class="py-3 px-5 text-center border-r border-gray-100 font-medium">
                        Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-5 text-center border-r border-gray-100 font-bold text-gray-900">
                        Rp{{ number_format($detail->obat->harga_jual ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-5 text-center border-r border-gray-100 font-bold text-gray-900">
                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-6 flex justify-center items-center gap-1">
                        <!-- Tombol Edit -->
                        <button type="button"
                            onclick="openEditRestockModal(this)"
                            data-id-pembelian="{{ $beli->id }}"
                            data-id-detail="{{ $detail->id }}"
                            data-id-obat="{{ $detail->id_obat }}"
                            data-tgl-pembelian="{{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('Y-m-d') }}"
                            data-nama-suplier="{{ $beli->supplier->nama_suplier ?? '' }}"
                            data-tgl-expired="{{ \Carbon\Carbon::parse($batch->tgl_expired ?? now())->format('Y-m-d') }}"
                            data-qty="{{ $detail->qty }}"
                            data-harga-beli="{{ $detail->harga_beli }}"
                            class="bg-green-600 hover:bg-green-700 text-white p-1.5 rounded transition shadow-sm"
                            title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>

                        <!-- Tombol Hapus -->
                        <button type="button"
                            onclick="confirmDeletePembelian('{{ $beli->id }}')"
                            class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded transition shadow-sm"
                            title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>

                        {{-- Form Hapus (Hidden) --}}
                        <form id="form-delete-pembelian-{{ $beli->id }}" action="{{ route('pembelian.destroy', $beli->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            @empty
            <tr>
                <td colspan="10" class="py-12 text-center text-gray-400 italic">Belum ada riwayat pengadaan stok dari supplier.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Section -->
<div class="mt-8 mb-10 flex flex-col sm:flex-row items-center justify-between px-2 gap-4">
    <div class="text-xs text-gray-400 italic">
        * Menampilkan riwayat stok masuk per item barang.
    </div>
    <div class="flex gap-2">
        @if($pembelians->onFirstPage())
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                &#9664; Back
            </span>
        @else
            <a href="{{ $pembelians->previousPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                &#9664; Back
            </a>
        @endif

        @if($pembelians->hasMorePages())
            <a href="{{ $pembelians->nextPageUrl() }}" class="px-5 py-2 bg-green-700 hover:bg-green-800 text-white rounded-lg text-xs font-bold uppercase tracking-widest transition shadow-md">
                Next &#9654;
            </a>
        @else
            <span class="px-5 py-2 bg-gray-100 text-gray-400 rounded-lg text-xs font-bold uppercase tracking-widest cursor-not-allowed">
                Next &#9654;
            </span>
        @endif
    </div>
</div>

{{-- Modals extracted to partials at the end of file --}}

{{-- ===== MODAL SUKSES DENGAN ANIMASI CENTANG ===== --}}
<div id="modalSukses" class="fixed inset-0 z-[110] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-72 mx-4 py-8 px-6 text-center sukses-box">
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

    .sukses-box { animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
    @keyframes popIn { from { opacity: 0; transform: scale(0.7); } to { opacity: 1; transform: scale(1); } }
    .circle-anim { animation: drawCircle 0.65s ease forwards; }
    .check-anim { animation: drawCheck 0.45s ease 0.55s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>

@include('pembelian.partials.modal_edit')
@include('pembelian.partials.modal_add_stock')

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div id="modalHapusPembelian" class="fixed inset-0 z-[110] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeletePembelian()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-80 mx-4 overflow-hidden">
        <div class="bg-green-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">Konfirmasi Hapus</h4>
        </div>
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">
                Hapus Riwayat ini?
            </p>
            <p class="text-[11px] text-gray-500 leading-relaxed italic">
                * PERHATIAN: Penghapusan rincian ini akan menghapus batch stok terkait secara permanen.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 mt-2">
            <button type="button" onclick="closeDeletePembelian()" class="flex-1 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition uppercase tracking-wider">Batal</button>
            <button type="button" onclick="executeDeletePembelian()" class="flex-1 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition uppercase tracking-wider">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    /* ===== MODAL STOK MASUK ===== */
    function openModalStokMasuk() {
        const now = Date.now();
        document.getElementById('restock_no_faktur').value = 'INV-RESTOCK-' + now;
        document.getElementById('restock_no_batch').value = 'BATCH-TEMP-' + now;
        document.getElementById('formStokMasuk').reset();
        document.getElementById('restock_no_faktur').value = 'INV-RESTOCK-' + now;
        document.getElementById('restock_no_batch').value = 'BATCH-TEMP-' + now;
        document.getElementById('modalStokMasuk').classList.remove('hidden');
        document.getElementById('modalStokMasuk').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModalStokMasuk() {
        document.getElementById('modalStokMasuk').classList.add('hidden');
        document.getElementById('modalStokMasuk').classList.remove('flex');
        document.body.style.overflow = '';
    }

    /* ===== LOGIKA EDIT RIWAYAT ===== */
    function openEditRestockModal(btn) {
        const idPembelian = btn.getAttribute('data-id-pembelian');
        const idObat = btn.getAttribute('data-id-obat');
        const tglBeli = btn.getAttribute('data-tgl-pembelian');
        const supplier = btn.getAttribute('data-nama-suplier');
        const tglExp = btn.getAttribute('data-tgl-expired');
        const qty = btn.getAttribute('data-qty');
        const hargaBeli = btn.getAttribute('data-harga-beli');

        // Set Form Action
        document.getElementById('formEditStok').action = '/pembelian/' + idPembelian;

        // Populate Fields
        document.getElementById('edit_id_obat').value = idObat;
        document.getElementById('edit_tgl_pembelian').value = tglBeli;
        document.getElementById('edit_nama_suplier').value = supplier;
        document.getElementById('edit_tgl_expired').value = tglExp;
        document.getElementById('edit_qty').value = qty;
        document.getElementById('edit_harga_beli').value = hargaBeli;
        document.getElementById('edit_tambah_stok').value = '';

        // Show Modal
        document.getElementById('modalEditStok').classList.remove('hidden');
        document.getElementById('modalEditStok').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('modalEditStok').classList.add('hidden');
        document.getElementById('modalEditStok').classList.remove('flex');
        document.body.style.overflow = '';
    }

    /* ===== LOGIKA HAPUS RIWAYAT ===== */
    let activeDeletePembelianId = null;

    function confirmDeletePembelian(id) {
        activeDeletePembelianId = id;
        const modal = document.getElementById('modalHapusPembelian');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeletePembelian() {
        const modal = document.getElementById('modalHapusPembelian');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        activeDeletePembelianId = null;
    }

    function executeDeletePembelian() {
        if (activeDeletePembelianId) {
            showSuccessAnimation('form-delete-pembelian-' + activeDeletePembelianId, 'Riwayat Berhasil Dihapus!');
        }
    }

    /* ===== ANIMASI SUKSES ===== */
    function showSuccessAnimation(formId, message) {
        const modal = document.getElementById('modalSukses');
        const title = document.getElementById('sukses_title');
        title.innerText = message;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { document.getElementById(formId).submit(); }, 1200);
    }
</script>
@endpush
