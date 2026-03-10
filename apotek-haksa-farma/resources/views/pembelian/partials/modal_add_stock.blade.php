{{-- ===== MODAL STOK MASUK (SUPPLIER) ===== --}}
<div id="modalStokMasuk" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeModalStokMasuk()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col"
        x-data="{ 
            openObat: false, 
            searchObat: '', 
            selectedObatId: '', 
            selectedObatName: '-- Pilih Barang / Obat --',
            obats: [
                @foreach($obats as $obat)
                    { id: '{{ $obat->id }}', name: '{{ strtoupper($obat->nama_obat) }}' },
                @endforeach
            ],
            selectObat(id, name) {
                this.selectedObatId = id;
                this.selectedObatName = name;
                this.openObat = false;
                this.searchObat = '';
            },
            resetForm() {
                this.selectedObatId = '';
                this.selectedObatName = '-- Pilih Barang / Obat --';
                this.searchObat = '';
                this.openObat = false;
            }
        }"
        @reset-restock.window="resetForm()">
        <!-- Header -->
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between text-white text-center">
            <h3 class="font-bold text-xl uppercase tracking-widest w-full">Penerimaan Stok (Supplier)</h3>
            <button onclick="closeModalStokMasuk()" class="absolute right-5 text-green-100 hover:text-white transition text-3xl font-light">&times;</button>
        </div>

        <form action="{{ route('pembelian.store') }}" method="POST" id="formStokMasuk" onsubmit="event.preventDefault(); showSuccessAnimation('formStokMasuk', 'Stok Berhasil Ditambahkan!');">
            @csrf
            {{-- Hidden Fields for System Requirements --}}
            <input type="hidden" name="no_faktur" id="restock_no_faktur">
            <input type="hidden" name="items[0][no_batch]" id="restock_no_batch">

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                <!-- Data Header -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Terima -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Tanggal Terima</label>
                        <input type="date" name="tgl_pembelian" required value="{{ date('Y-m-d') }}"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Nama Supplier -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Nama Supplier</label>
                        <input list="supplier_list" name="nama_suplier" required placeholder="Ketik nama supplier..."
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                        <datalist id="supplier_list">
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->nama_suplier }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <!-- Nama Barang (Searchable Dropdown) -->
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Barang</label>
                    <button type="button" @click="openObat = !openObat" 
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm flex justify-between items-center focus:ring-2 focus:ring-green-500 transition font-bold uppercase text-gray-800 shadow-sm">
                        <span x-text="selectedObatName"></span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="openObat" @click.away="openObat = false" x-transition
                        class="absolute z-[110] mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden animate-modal">
                        <div class="p-2 border-b border-gray-100 bg-gray-50">
                            <input type="text" x-model="searchObat" placeholder="Cari nama obat..." 
                                class="w-full px-3 py-2 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-green-500 outline-none uppercase font-bold">
                        </div>
                        <ul class="max-h-60 overflow-y-auto py-1">
                            <template x-for="obat in obats.filter(o => o.name.includes(searchObat.toUpperCase()))" :key="obat.id">
                                <li @click="selectObat(obat.id, obat.name)" 
                                    class="px-4 py-2.5 text-xs font-bold uppercase text-gray-700 hover:bg-green-50 hover:text-green-700 cursor-pointer transition flex items-center justify-between">
                                    <span x-text="obat.name"></span>
                                    <svg x-show="selectedObatId == obat.id" class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </li>
                            </template>
                            <li x-show="obats.filter(o => o.name.includes(searchObat.toUpperCase())).length === 0" class="px-4 py-3 text-xs text-gray-400 italic text-center">
                                Obat tidak ditemukan...
                            </li>
                        </ul>
                    </div>
                    {{-- Hidden Input for original logic --}}
                    <input type="hidden" name="items[0][id_obat]" x-model="selectedObatId" required>
                </div>

                <!-- Detail Barang -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Kadaluarsa -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Tanggal Kadaluarsa</label>
                        <input type="date" name="items[0][tgl_expired]" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Barang Masuk (Qty) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Qty Masuk</label>
                        <input type="number" name="items[0][qty]" min="1" required placeholder="0"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-green-600 shadow-sm">
                    </div>
                </div>

                <!-- Harga Beli & Jual -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Harga Beli -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Harga Beli Per Item</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="items[0][harga_beli]" min="0" required placeholder="0"
                                class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-gray-800 shadow-sm">
                        </div>
                    </div>
                    <!-- Harga Jual -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Harga Jual Per Item</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="items[0][harga_jual]" min="0" required placeholder="0"
                                class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-gray-800 shadow-sm">
                        </div>
                    </div>
                </div>

                <p class="text-[10px] text-gray-400 italic text-center pt-2">
                    * Penambahan stok ini akan otomatis memperbarui data stok utama dan laporan kadaluarsa.
                </p>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t border-gray-100">
                <button type="button" onclick="closeModalStokMasuk()" class="px-5 py-2.5 text-gray-500 hover:text-gray-700 font-bold transition text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-extrabold py-3 px-8 rounded-xl transition shadow-lg text-xs flex items-center gap-2 uppercase tracking-widest active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Stok Masuk
                </button>
            </div>
        </form>
    </div>
</div>
