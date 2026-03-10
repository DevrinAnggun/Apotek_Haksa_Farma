@extends('layouts.admin')

@section('content')
<!-- Header title area -->
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2 flex items-center gap-3">
        STOK SUPPLIER
    </h2>
</div>

@if(session('success'))
    <div id="flash-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="dismissAlert('flash-success')" class="ml-4 text-green-700 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
    </div>
@endif

{{-- Flash Error Message --}}
@if(session('error'))
    <div id="flash-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-5 text-sm flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="dismissAlert('flash-error')" class="ml-4 text-red-700 hover:text-red-900 font-bold text-lg leading-none">&times;</button>
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
    <table class="w-full text-left border-collapse min-w-max border border-gray-400 shadow-sm rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-4 px-4 font-bold text-gray-800 text-center w-16 border border-gray-300 uppercase text-xs tracking-wider">No</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Nama Barang</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-40">Tgl Terima</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-44">Tgl Kadaluarsa</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider">Supplier</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-20">Qty</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-32">Harga Beli</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-32">Harga Jual</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-40">Subtotal</th>
                <th class="py-4 px-6 font-bold text-gray-800 text-center border border-gray-300 uppercase text-xs tracking-wider w-28">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = ($pembelians->currentPage()-1) * $pembelians->perPage() + 1; @endphp
            @forelse($pembelians as $beli)
                @foreach($beli->details as $detail)
                @php 
                    $batch = $detail->obat->stokBatches()->where('id_pembelian', $beli->id)->first();
                @endphp
                <tr class="hover:bg-gray-50 transition text-sm">
                    <td class="py-3 px-4 text-center text-gray-800 font-medium border border-gray-300">
                        {{ $no++ }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-800 font-bold uppercase border border-gray-300">
                        {{ $detail->obat->nama_obat ?? '-' }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-800 font-medium border border-gray-300">
                        {{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('d-m-Y') }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-900 font-bold border border-gray-300">
                        {{ \Carbon\Carbon::parse($batch->tgl_expired ?? now())->format('d-m-Y') }}
                    </td>
                    <td class="py-3 px-5 text-center text-gray-900 font-bold uppercase border border-gray-300">
                        {{ $beli->supplier->nama_suplier ?? '-' }}
                    </td>
                    <td class="py-3 px-5 text-center border border-gray-300 font-bold">
                        {{ $detail->qty }}
                    </td>
                    <td class="py-3 px-5 text-center border border-gray-300 font-medium">
                        Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-5 text-center border border-gray-300 font-bold text-gray-900">
                        Rp{{ number_format($detail->obat->harga_jual ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-5 text-center border border-gray-300 font-bold text-gray-900">
                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-6 border border-gray-300">
                        <div class="flex justify-center items-center gap-1">
                        <!-- Tombol Riwayat -->
                        <button type="button"
                            onclick="openRiwayatModal('{{ $detail->id }}', '{{ $detail->obat->nama_obat ?? '' }}')"
                            class="bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded transition shadow-sm"
                            title="Riwayat Penambahan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>

                        <!-- Tombol Edit -->
                        <button type="button"
                            onclick="openEditRestockModal(this)"
                            data-id-pembelian="{{ $beli->id }}"
                            data-id-detail="{{ $detail->id }}"
                            data-id-obat="{{ $detail->id_obat }}"
                            data-nama-obat="{{ $detail->obat->nama_obat ?? '' }}"
                            data-tgl-pembelian="{{ \Carbon\Carbon::parse($beli->tgl_pembelian)->format('Y-m-d') }}"
                            data-nama-suplier="{{ $beli->supplier->nama_suplier ?? '' }}"
                            data-tgl-expired="{{ \Carbon\Carbon::parse($batch->tgl_expired ?? now())->format('Y-m-d') }}"
                            data-qty="{{ $detail->qty }}"
                            data-harga-beli="{{ $detail->harga_beli }}"
                            data-harga-jual="{{ $detail->obat->harga_jual ?? 0 }}"
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
                    </td>
                </tr>
                @endforeach
            @empty
            <tr>
                <td colspan="10" class="py-12 text-center text-gray-400 italic border border-gray-300">Belum ada riwayat pengadaan stok dari supplier.</td>
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
<div id="modalSukses" class="fixed inset-0 z-[150] hidden items-center justify-center">
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

    </div>
</div>

{{-- ===== MODAL RIWAYAT STOK MASUK ===== --}}
<div id="modalRiwayatStok" class="fixed inset-0 z-[100] hidden flex items-center justify-center font-sans">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeRiwayatModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col">
        <!-- Header -->
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between text-white text-center">
            <h3 class="font-bold text-lg uppercase tracking-widest w-full">Riwayat Stok: <span id="riwayat_nama_obat">--</span></h3>
            <button onclick="closeRiwayatModal()" class="absolute right-5 text-blue-100 hover:text-white transition text-3xl font-light">&times;</button>
        </div>

        <div class="p-4 overflow-y-auto max-h-[60vh]">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="p-2 font-bold text-gray-700 text-center">Tgl & Jam</th>
                        <th class="p-2 font-bold text-gray-700 text-center">Jumlah</th>
                        <th class="p-2 font-bold text-gray-700 text-center">Harga Beli</th>
                        <th class="p-2 font-bold text-gray-700 text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="riwayat_body">
                    <!-- Data injected via JS -->
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="button" onclick="closeRiwayatModal()" class="px-5 py-2 text-gray-500 hover:text-gray-700 font-bold transition text-xs uppercase tracking-widest">Tutup</button>
        </div>
    </div>
</div>

{{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
<div id="modalHapusPembelian" class="fixed inset-0 z-[110] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeletePembelian()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-80 mx-4 overflow-hidden">
        <div class="bg-red-600 py-3 text-center">
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
            <button type="button" onclick="executeDeletePembelian()" class="flex-1 py-2 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition uppercase tracking-wider">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Single Form for Deletion --}}
<form id="form-delete-pembelian" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

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
        
        // Reset Alpine searchable dropdown state
        window.dispatchEvent(new CustomEvent('reset-restock'));
        
        document.getElementById('modalStokMasuk').classList.remove('hidden');
        document.getElementById('modalStokMasuk').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModalStokMasuk() {
        document.getElementById('modalStokMasuk').classList.add('hidden');
        document.getElementById('modalStokMasuk').classList.remove('flex');
        document.body.style.overflow = '';
    }

    /* ===== LOGIKA RIWAYAT STOK ===== */
    function openRiwayatModal(idDetail, namaObat) {
        document.getElementById('riwayat_nama_obat').innerText = namaObat;
        const body = document.getElementById('riwayat_body');
        body.innerHTML = '<tr><td colspan="4" class="p-4 text-center italic text-gray-400">Memuat data...</td></tr>';
        
        document.getElementById('modalRiwayatStok').classList.remove('hidden');
        document.getElementById('modalRiwayatStok').classList.add('flex');
        document.body.style.overflow = 'hidden';

        fetch(`/pembelian/riwayat/${idDetail}`)
            .then(response => response.json())
            .then(data => {
                body.innerHTML = '';
                if (data.length === 0) {
                    body.innerHTML = '<tr><td colspan="4" class="p-5 text-center text-gray-400">Tidak ada riwayat penambahan.</td></tr>';
                    return;
                }
                data.forEach(item => {
                    const dateObj = new Date(item.created_at);
                    const formattedDate = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    const formattedTime = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    
                    body.innerHTML += `
                        <tr class="border-b border-gray-100 text-center">
                            <td class="p-3 text-gray-600">${formattedDate} <br> <span class="text-[10px] text-gray-400">${formattedTime} WIB</span></td>
                            <td class="p-3 font-bold text-green-600">+${item.qty_masuk}</td>
                            <td class="p-3 text-gray-700 font-medium">Rp${new Intl.NumberFormat('id-ID').format(item.harga_beli)}</td>
                            <td class="p-3 text-gray-500 italic text-[11px]">${item.keterangan || '-'}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => {
                body.innerHTML = '<tr><td colspan="4" class="p-4 text-center text-red-500">Gagal memuat data.</td></tr>';
                console.error('Error fetching riwayat:', error);
            });
    }

    function closeRiwayatModal() {
        document.getElementById('modalRiwayatStok').classList.add('hidden');
        document.getElementById('modalRiwayatStok').classList.remove('flex');
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
        const hargaJual = btn.getAttribute('data-harga-jual');

        // Set Form Action
        document.getElementById('formEditStok').action = '/pembelian/' + idPembelian;

        // Populate Fields
        document.getElementById('edit_id_obat').value = idObat;
        
        // Notify Alpine to update its internal state for the searchable dropdown
        window.dispatchEvent(new CustomEvent('set-edit-obat', { detail: { id: idObat } }));
        
        document.getElementById('edit_tgl_pembelian').value = tglBeli;
        document.getElementById('edit_nama_suplier').value = supplier;
        document.getElementById('edit_tgl_expired').value = tglExp;
        document.getElementById('edit_qty').value = qty;
        document.getElementById('edit_harga_beli').value = hargaBeli;
        document.getElementById('edit_harga_jual').value = hargaJual;
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
            const form = document.getElementById('form-delete-pembelian');
            form.action = '/pembelian/' + activeDeletePembelianId;
            closeDeletePembelian(); // ID sekarang tetap aman karena form sudah diset actionnya
            showSuccessAnimation('form-delete-pembelian', 'Riwayat Berhasil Dihapus!');
        }
    }

    /* ===== ANIMASI SUKSES ===== */
    function showSuccessAnimation(formId, message) {
        const modal = document.getElementById('modalSukses');
        const title = document.getElementById('sukses_title');
        title.innerText = message;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Restart animasi centang
        const circle = modal.querySelector('.circle-anim');
        const check  = modal.querySelector('.check-anim');
        if(circle && check) {
            circle.style.animation = 'none';
            check.style.animation  = 'none';
            circle.offsetHeight; // trigger reflow
            circle.style.animation = '';
            check.style.animation  = '';
        }

        setTimeout(() => { document.getElementById(formId).submit(); }, 800);
    }
</script>
@endpush
