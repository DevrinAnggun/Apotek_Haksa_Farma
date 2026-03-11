@extends('layouts.admin')

@section('content')
{{-- Header --}}
<div class="mb-8 text-center flex flex-col items-center">
    <h2 class="text-3xl font-extrabold text-black tracking-wide uppercase mb-2">DATA KADALUARSA</h2>
</div>

{{-- Alert Success --}}
@if(session('success'))
    <div id="flash-success" class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-green-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="dismissAlert('flash-success')" class="text-green-500 hover:text-green-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

@if(session('error'))
    <div id="flash-error" class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <div class="bg-red-500 rounded-full p-1 mr-3 shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        <button onclick="dismissAlert('flash-error')" class="text-red-500 hover:text-red-700 transition font-bold text-xl leading-none">&times;</button>
    </div>
@endif

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row items-center justify-start mb-6 gap-3">
    {{-- Search --}}
    <div class="flex w-full sm:w-1/2 md:w-1/3 border border-gray-400 rounded-lg overflow-hidden focus-within:ring-1 focus-within:ring-green-600 bg-white shadow-sm">
        <input type="text" id="searchKadaluarsa" placeholder="Cari Barang....."
            class="w-full pl-4 pr-2 py-2 text-sm focus:outline-none"
            oninput="filterTable(this.value)">
        <div class="px-3 flex items-center bg-gray-50 border-l border-gray-200">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    {{-- Export Button --}}
    <a href="{{ route('kadaluarsa.pdf') }}" target="_blank"
        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Kadaluarsa
    </a>
</div>

{{-- Tabel Data Kadaluarsa --}}
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-max border border-gray-400 shadow-sm rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-4 px-4 font-bold text-gray-800 text-center w-14 border border-gray-300 uppercase text-xs tracking-wider">No</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-left border border-gray-300 uppercase text-xs tracking-wider">Nama Obat</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-32 border border-gray-300 uppercase text-xs tracking-wider">Stok Sisa</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-40 border border-gray-300 uppercase text-xs tracking-wider">Tgl Kadaluarsa</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-24 border border-gray-300 uppercase text-xs tracking-wider">Terjual</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-32 border border-gray-300 uppercase text-xs tracking-wider">Status</th>
                <th class="py-4 px-5 font-bold text-gray-800 text-center w-28 border border-gray-300 uppercase text-xs tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody id="kadaluarsaTableBody">
            @forelse($kadaluarsas as $index => $item)
            @php
                $now     = \Carbon\Carbon::now();
                $expired = \Carbon\Carbon::parse($item->earliest_expired);
                $diffDays = (int)$now->diffInDays($expired, false); // negatif = sudah expired

                if ($diffDays <= 0) {
                    $statusLabel = 'Kadaluarsa';
                    $statusClass = 'bg-red-100 text-red-700 border border-red-200';
                    $rowClass    = 'bg-red-50';
                } else {
                    $hLabel      = 'H-' . $diffDays;
                    $statusLabel = $hLabel;
                    $statusClass = $diffDays <= 7
                        ? 'bg-red-100 text-red-700 border border-red-200'
                        : 'bg-orange-100 text-orange-700 border border-orange-200';
                    $rowClass    = $diffDays <= 7 ? 'bg-red-50' : 'bg-orange-50';
                }
            @endphp
            <tr class="hover:bg-gray-100 transition {{ \Carbon\Carbon::parse($item->earliest_expired)->isPast() ? 'bg-red-50' : (\Carbon\Carbon::parse($item->earliest_expired)->diffInDays(now()) <= 7 ? 'bg-red-50' : 'bg-orange-50') }}">
                <td class="py-3 px-4 text-center font-medium text-gray-800 border border-gray-300">{{ ($kadaluarsas->currentPage()-1) * $kadaluarsas->perPage() + $index + 1 }}</td>
                <td class="py-3 px-5 font-semibold text-gray-800 uppercase border border-gray-300 text-left">
                    {{ $item->obat->nama_obat ?? '—' }}
                    <span class="text-xs font-normal text-gray-400 block normal-case">{{ $item->obat->kategori->nama_kategori ?? '—' }}</span>
                </td>
                <td class="py-3 px-5 text-center font-bold border border-gray-300 {{ $item->total_sisa <= 0 ? 'text-red-500' : 'text-gray-800' }}">
                    {{ number_format($item->total_sisa, 0, ',', '.') }}
                </td>
                <td class="py-3 px-5 text-center border border-gray-300">
                    <span class="font-semibold {{ $diffDays < 0 ? 'text-red-600' : 'text-gray-800' }}">
                        @if($item->obat->kategori && strtoupper($item->obat->kategori->nama_kategori) === 'CEK')
                            <span class="text-gray-400 font-normal">-</span>
                        @else
                            {{ $expired->format('d-m-Y') }}
                        @endif
                    </span>
                </td>
                <td class="py-3 px-5 text-center border border-gray-300">
                    <span class="bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded shadow-sm text-xs border border-blue-100">
                        {{ $item->obat->total_terjual ?? 0 }}
                    </span>
                </td>
                <td class="py-3 px-5 text-center border border-gray-300">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="py-3 px-5 border border-gray-300">
                    <div class="flex items-center justify-center gap-1">
                        {{-- Tombol Lihat Detail (trigger modal) --}}
                        <button type="button" title="Lihat Detail"
                            data-nama="{{ $item->obat->nama_obat ?? '-' }}"
                            data-kategori="{{ $item->obat->kategori->nama_kategori ?? '-' }}"
                            data-stok="{{ number_format($item->total_sisa, 0) }}"
                            data-tgl="{{ $expired->format('d/m/Y') }}"
                            class="btn-detail bg-blue-600 hover:bg-blue-700 text-white p-1.5 rounded transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>

                        {{-- Form hapus tersembunyi (Sekarang per Obat id) --}}
                        <form id="formHapus-{{ $item->id_obat }}"
                            action="{{ route('kadaluarsa.destroy', $item->id_obat) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                        {{-- Tombol Hapus (trigger modal konfirmasi) --}}
                        <button type="button" title="Bersihkan Data Kadaluarsa"
                            data-form="formHapus-{{ $item->id_obat }}"
                            data-nama="{{ $item->obat->nama_obat ?? '' }}"
                            class="btn-hapus bg-red-600 hover:bg-red-700 text-white p-1.5 rounded transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-10 text-center border border-gray-300">
                    <div class="flex flex-col items-center gap-2">
                        <p class="text-gray-400 text-sm">Tidak ada obat yang kadaluarsa atau mendekati H-7.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="mt-6">
    {{ $kadaluarsas->links() }}
</div>

{{-- ========== MODAL DETAIL OBAT ========== --}}
<div id="modalDetail" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="tutupDetail()"></div>

    {{-- Modal Box --}}
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        {{-- Header Modal --}}
        <div class="bg-blue-700 px-6 py-4 relative flex items-center justify-center">
            <h3 class="text-white font-bold text-lg">Detail Obat</h3>
            <button onclick="tutupDetail()" class="absolute right-5 text-white hover:text-blue-200 text-2xl font-light leading-none">&times;</button>
        </div>

        {{-- Konten --}}
        <div class="p-6">
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-5 font-semibold text-gray-700 w-44">Nama Barang</td>
                            <td class="py-3 px-2 text-gray-700 w-4 text-center">:</td>
                            <td id="d-nama" class="py-3 px-5 font-bold text-gray-900 uppercase"></td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-5 font-semibold text-gray-700">Kategori</td>
                            <td class="py-3 px-2 text-gray-700 text-center">:</td>
                            <td id="d-kategori" class="py-3 px-5 font-bold text-gray-900 uppercase"></td>
                        </tr>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-5 font-semibold text-gray-700">Stok</td>
                            <td class="py-3 px-2 text-gray-700 text-center">:</td>
                            <td id="d-stok" class="py-3 px-5 font-bold text-gray-900"></td>
                        </tr>
                        <tr>
                            <td class="py-3 px-5 font-semibold text-gray-700">Tanggal Kadaluarsa</td>
                            <td class="py-3 px-2 text-gray-700 text-center">:</td>
                            <td id="d-tgl" class="py-3 px-5 font-bold text-gray-900"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>


{{-- ========== MODAL KONFIRMASI HAPUS ========== --}}
<div id="modalHapus" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="tutupHapus()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-[320px] mx-4 overflow-hidden animate-modal">
        <div class="bg-red-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">KONFIRMASI HAPUS</h4>
        </div>
        <div class="px-6 pt-8 pb-4 text-center">
            <p class="text-base font-bold text-gray-800 mb-2 leading-relaxed">
                Yakin ingin menghapus <span id="hapusNamaObat" class="text-red-600"></span>?
            </p>
            <p class="text-[11px] text-gray-500 italic leading-relaxed">
                Data yang dihapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-8 mt-4">
            <button type="button" onclick="tutupHapus()"
                class="flex-1 py-2.5 text-xs font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition uppercase tracking-widest">Batal</button>
            <button type="button" id="btnKonfirmasiHapus" onclick="konfirmasiHapus()"
                class="flex-1 py-2.5 text-xs font-extrabold bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-lg transition uppercase tracking-widest">Ya, Hapus</button>
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

<script>
function filterTable(keyword) {
    const q = keyword.toLowerCase().trim();
    document.querySelectorAll('.kadaluarsa-row').forEach(row => {
        const nama = row.dataset.nama || '';
        row.style.display = nama.includes(q) ? '' : 'none';
    });
}

function tutupDetail() {
    document.getElementById('modalDetail').classList.add('hidden');
    document.body.style.overflow = '';
}

function tutupDetail() {
    document.getElementById('modalDetail').classList.add('hidden');
    document.body.style.overflow = '';
}

let activeFormHapus = null;
function tutupHapus() {
    document.getElementById('modalHapus').classList.add('hidden');
    document.body.style.overflow = '';
    activeFormHapus = null;
}
function konfirmasiHapus() {
    if (activeFormHapus) {
        showSuccessAnimation(activeFormHapus.id, 'Data Berhasil Dihapus!');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Tombol Lihat → modal detail
    document.querySelectorAll('.btn-detail').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('d-nama').textContent      = this.dataset.nama;
            document.getElementById('d-kategori').textContent = this.dataset.kategori;
            document.getElementById('d-stok').textContent     = this.dataset.stok;
            document.getElementById('d-tgl').textContent      = this.dataset.tgl;
            document.getElementById('modalDetail').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    // Tombol Hapus → modal konfirmasi
    document.querySelectorAll('.btn-hapus').forEach(function(btn) {
        btn.addEventListener('click', function() {
            activeFormHapus = document.getElementById(this.dataset.form);
            document.getElementById('hapusNamaObat').textContent = this.dataset.nama || '';
            document.getElementById('modalHapus').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    // Escape tutup semua modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { 
            tutupDetail(); 
            tutupHapus(); 
        }
    });
});

/* ===== ANIMASI SUKSES SEBELUM SUBMIT ===== */
function showSuccessAnimation(formId, titleText) {
    const form = document.getElementById(formId);
    
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

    setTimeout(() => {
        form.submit();
    }, 1500);
}
</script>
@endsection
