@extends('layouts.admin')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-3">
    <h2 class="text-2xl font-extrabold text-gray-800 tracking-wide uppercase">Point of Sale — Kasir</h2>
    <a href="{{ route('laporan.penjualan') }}" class="text-sm text-green-700 hover:underline flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Riwayat Penjualan
    </a>
</div>

{{-- Alert Error --}}
@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex justify-between items-center" id="alert-error">
    <span>⚠️ {{ session('error') }}</span>
    <button onclick="dismissAlert('alert-error')" class="text-red-400 hover:text-red-600 font-bold text-lg leading-none">&times;</button>
</div>
@endif

<div class="flex flex-col lg:flex-row gap-5">

    {{-- ======================= PANEL KIRI: Daftar Obat ======================= --}}
    <div class="flex-1 bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-700 text-base">Daftar Obat Tersedia</h3>
            <span class="text-xs text-gray-400">{{ $obats->count() }} item</span>
        </div>

        {{-- Search Bar --}}
        <div class="px-5 pt-4 pb-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="searchObat" placeholder="Cari nama atau kode obat..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    oninput="filterObat(this.value)">
            </div>
        </div>

        {{-- Tabel Obat --}}
        <div class="flex-1 overflow-y-auto px-5 pb-4" style="max-height: 65vh;">
            <table class="w-full text-sm border-collapse mt-2">
                <thead class="sticky top-0 bg-white z-10">
                    <tr class="border-b border-gray-200 text-gray-500 text-xs uppercase">
                        <th class="py-2 px-2 text-left font-semibold">Kode</th>
                        <th class="py-2 px-2 text-left font-semibold">Nama Obat</th>
                        <th class="py-2 px-2 text-center font-semibold">Stok</th>
                        <th class="py-2 px-2 text-right font-semibold">Harga</th>
                        <th class="py-2 px-2 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="obatTableBody">
                    @forelse($obats as $obat)
                    @php $stok = $obat->total_stok; @endphp
                    <tr class="border-b border-gray-100 hover:bg-green-50 transition obat-row"
                        data-nama="{{ strtolower($obat->nama_obat) }}"
                        data-kode="{{ strtolower($obat->kode_obat) }}">
                        <td class="py-2.5 px-2 text-gray-500 font-mono text-xs">{{ $obat->kode_obat }}</td>
                        <td class="py-2.5 px-2 font-medium text-gray-800">{{ $obat->nama_obat }}
                            <span class="text-xs text-gray-400 block">{{ $obat->kategori->nama_kategori ?? '-' }} · {{ $obat->satuan->nama_satuan ?? '-' }}</span>
                        </td>
                        <td class="py-2.5 px-2 text-center">
                            <span class="font-bold {{ $stok <= 0 ? 'text-red-500' : ($stok < 5 ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $stok }}
                            </span>
                        </td>
                        <td class="py-2.5 px-2 text-right font-semibold text-gray-700">
                            Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="py-2.5 px-2 text-center">
                            @if($stok > 0)
                            <button type="button"
                                onclick="tambahKeKeranjang({{ $obat->id }}, '{{ addslashes($obat->nama_obat) }}', {{ $obat->harga_jual }}, {{ $stok }})"
                                class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                                + Tambah
                            </button>
                            @else
                            <span class="text-xs text-red-400 font-medium">Habis</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 italic">Belum ada data obat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ======================= PANEL KANAN: Keranjang & Pembayaran ======================= --}}
    <div class="w-full lg:w-96 flex flex-col gap-4">

        {{-- Keranjang Belanja --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-700 text-base">Keranjang Belanja</h3>
                <button type="button" onclick="kosongkanKeranjang()" class="text-xs text-red-500 hover:text-red-700 hover:underline font-medium">Kosongkan</button>
            </div>

            <div id="keranjangList" class="px-3 py-3 flex flex-col gap-2 overflow-y-auto" style="max-height: 38vh; min-height: 80px;">
                <p id="emptyCart" class="text-center text-gray-400 text-sm italic py-6">Keranjang masih kosong.<br>Klik "+ Tambah" untuk menambah obat.</p>
            </div>
        </div>

        {{-- Panel Pembayaran --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm px-5 py-5">
            <h3 class="font-bold text-gray-700 text-base mb-4 border-b pb-2">Pembayaran</h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 font-medium">Total Belanja</span>
                    <span id="displayTotal" class="font-extrabold text-lg text-gray-800">Rp 0</span>
                </div>

                <div>
                    <label class="block text-gray-500 font-medium mb-1">Uang Bayar (Rp)</label>
                    <input type="number" id="nominal_bayar_input" placeholder="Masukkan nominal..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                        oninput="hitungKembalian()" min="0">
                </div>

                <div class="flex justify-between items-center bg-green-50 rounded-lg px-3 py-2">
                    <span class="text-gray-600 font-medium">Kembalian</span>
                    <span id="displayKembalian" class="font-extrabold text-base text-green-700">Rp 0</span>
                </div>
            </div>

            <form id="formPOS" action="{{ route('kasir.store') }}" method="POST" class="mt-5">
                @csrf
                <input type="hidden" name="nominal_bayar" id="hidden_nominal_bayar">
                <div id="hiddenItems"></div>

                <button type="button" onclick="submitTransaksi()"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md tracking-wide uppercase">
                    Proses Transaksi
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    let keranjang = []; // Array of { id_obat, nama, harga_jual, qty, stok_max }

    // ==============================
    // Tambah item ke keranjang
    // ==============================
    function tambahKeKeranjang(idObat, nama, hargaJual, stokMax) {
        const existing = keranjang.find(item => item.id_obat === idObat);
        if (existing) {
            if (existing.qty >= stokMax) {
                alert(`Stok obat "${nama}" hanya tersedia ${stokMax} unit.`);
                return;
            }
            existing.qty++;
        } else {
            keranjang.push({ id_obat: idObat, nama, harga_jual: hargaJual, qty: 1, stok_max: stokMax });
        }
        renderKeranjang();
    }

    // ==============================
    // Render ulang tampilan keranjang
    // ==============================
    function renderKeranjang() {
        const list = document.getElementById('keranjangList');
        const emptyNote = document.getElementById('emptyCart');

        if (keranjang.length === 0) {
            list.innerHTML = '<p id="emptyCart" class="text-center text-gray-400 text-sm italic py-6">Keranjang masih kosong.<br>Klik "+ Tambah" untuk menambah obat.</p>';
            updateTotals();
            return;
        }

        let html = '';
        keranjang.forEach((item, idx) => {
            const subtotal = item.qty * item.harga_jual;
            html += `
            <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">${item.nama}</p>
                    <p class="text-xs text-gray-500">Rp${numberFormat(item.harga_jual)} × ${item.qty} = <span class="font-bold text-gray-700">Rp${numberFormat(subtotal)}</span></p>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" onclick="ubahQty(${idx}, -1)" class="w-6 h-6 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold text-sm flex items-center justify-center transition">−</button>
                    <span class="w-7 text-center text-sm font-bold text-gray-800">${item.qty}</span>
                    <button type="button" onclick="ubahQty(${idx}, 1)" class="w-6 h-6 rounded-md bg-green-100 hover:bg-green-200 text-green-700 font-bold text-sm flex items-center justify-center transition">+</button>
                    <button type="button" onclick="hapusItem(${idx})" class="w-6 h-6 rounded-md bg-red-50 hover:bg-red-100 text-red-500 font-bold text-sm flex items-center justify-center transition ml-1">×</button>
                </div>
            </div>`;
        });

        list.innerHTML = html;
        updateTotals();
    }

    // ==============================
    // Ubah qty dari keranjang
    // ==============================
    function ubahQty(idx, delta) {
        const item = keranjang[idx];
        const newQty = item.qty + delta;
        if (newQty <= 0) {
            keranjang.splice(idx, 1);
        } else if (newQty > item.stok_max) {
            alert(`Stok obat "${item.nama}" hanya tersedia ${item.stok_max} unit.`);
            return;
        } else {
            item.qty = newQty;
        }
        renderKeranjang();
    }

    // ==============================
    // Hapus item dari keranjang
    // ==============================
    function hapusItem(idx) {
        keranjang.splice(idx, 1);
        renderKeranjang();
    }

    // ==============================
    // Kosongkan seluruh keranjang
    // ==============================
    function kosongkanKeranjang() {
        if (keranjang.length === 0) return;
        if (confirm('Kosongkan seluruh keranjang belanja?')) {
            keranjang = [];
            renderKeranjang();
        }
    }

    // ==============================
    // Update total & kembalian
    // ==============================
    function updateTotals() {
        const total = keranjang.reduce((sum, item) => sum + (item.qty * item.harga_jual), 0);
        document.getElementById('displayTotal').textContent = 'Rp ' + numberFormat(total);
        hitungKembalian();
    }

    function hitungKembalian() {
        const total = keranjang.reduce((sum, item) => sum + (item.qty * item.harga_jual), 0);
        const bayar = parseInt(document.getElementById('nominal_bayar_input').value) || 0;
        const kembalian = bayar - total;
        const el = document.getElementById('displayKembalian');

        if (bayar === 0) {
            el.textContent = 'Rp 0';
            el.className = 'font-extrabold text-base text-green-700';
        } else if (kembalian < 0) {
            el.textContent = '− Rp ' + numberFormat(Math.abs(kembalian));
            el.className = 'font-extrabold text-base text-red-600';
        } else {
            el.textContent = 'Rp ' + numberFormat(kembalian);
            el.className = 'font-extrabold text-base text-green-700';
        }
    }

    // ==============================
    // Submit Transaksi
    // ==============================
    function submitTransaksi() {
        if (keranjang.length === 0) {
            alert('Keranjang masih kosong! Tambahkan obat terlebih dahulu.');
            return;
        }

        const total = keranjang.reduce((sum, item) => sum + (item.qty * item.harga_jual), 0);
        const bayar = parseInt(document.getElementById('nominal_bayar_input').value) || 0;

        if (bayar <= 0) {
            alert('Masukkan nominal uang bayar terlebih dahulu.');
            document.getElementById('nominal_bayar_input').focus();
            return;
        }
        if (bayar < total) {
            alert(`Uang bayar kurang! Total belanja: Rp ${numberFormat(total)}, Uang bayar: Rp ${numberFormat(bayar)}`);
            return;
        }

        // Isi hidden inputs untuk form submission
        document.getElementById('hidden_nominal_bayar').value = bayar;

        const hiddenContainer = document.getElementById('hiddenItems');
        hiddenContainer.innerHTML = '';
        keranjang.forEach((item, idx) => {
            hiddenContainer.innerHTML += `
                <input type="hidden" name="items[${idx}][id_obat]" value="${item.id_obat}">
                <input type="hidden" name="items[${idx}][qty]" value="${item.qty}">
                <input type="hidden" name="items[${idx}][harga_jual]" value="${item.harga_jual}">
            `;
        });

        // Tampilkan modal sukses dengan animasi centang
        const modal = document.getElementById('modalSukses');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Restart animasi SVG setiap kali modal dibuka
        const circle = modal.querySelector('.circle-anim');
        const check  = modal.querySelector('.check-anim');
        circle.style.animation = 'none';
        check.style.animation  = 'none';
        // Force reflow
        circle.getBoundingClientRect();
        check.getBoundingClientRect();
        circle.style.animation = '';
        check.style.animation  = '';

        // Submit form setelah animasi selesai (1.8 detik)
        setTimeout(() => {
            document.getElementById('formPOS').submit();
        }, 1800);
    }

    // ==============================
    // Filter pencarian obat
    // ==============================
    function filterObat(keyword) {
        const q = keyword.toLowerCase().trim();
        document.querySelectorAll('.obat-row').forEach(row => {
            const nama = row.dataset.nama || '';
            const kode = row.dataset.kode || '';
            row.style.display = (nama.includes(q) || kode.includes(q)) ? '' : 'none';
        });
    }

    // ==============================
    // Helper format angka
    // ==============================
    function numberFormat(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>

{{-- ===== MODAL TRANSAKSI BERHASIL ===== --}}
<div id="modalSukses" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
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
        <h3 class="text-xl font-extrabold text-gray-800 mb-1">Transaksi Berhasil!</h3>
        <p class="text-sm text-gray-400 mt-1">Menyimpan data transaksi...</p>
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
    .circle-anim {
        animation: drawCircle 0.65s ease forwards;
    }
    .check-anim {
        animation: drawCheck 0.45s ease 0.55s forwards;
    }
    @keyframes drawCircle {
        to { stroke-dashoffset: 0; }
    }
    @keyframes drawCheck {
        to { stroke-dashoffset: 0; }
    }
</style>

@endsection
