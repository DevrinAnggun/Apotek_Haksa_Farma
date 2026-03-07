{{-- ===== MODAL STOK MASUK (SUPPLIER) ===== --}}
<div id="modalStokMasuk" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeModalStokMasuk()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col">
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

                <!-- Nama Barang -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Barang</label>
                    <select name="items[0][id_obat]" id="restock_id_obat" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold uppercase text-gray-800 appearance-none shadow-sm cursor-pointer">
                        <option value="">-- Pilih Barang / Obat --</option>
                        @foreach($obats as $obat)
                            @if(($obat->kategori->nama_kategori ?? '') !== 'CEK')
                                <option value="{{ $obat->id }}">{{ $obat->nama_obat }}</option>
                            @endif
                        @endforeach
                    </select>
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
