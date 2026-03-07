{{-- ===== MODAL EDIT STOK MASUK ===== --}}
<div id="modalEditStok" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal flex flex-col">
        <!-- Header -->
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between text-white text-center">
            <h3 class="font-bold text-xl uppercase tracking-widest w-full">Edit Penerimaan Stok</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-green-100 hover:text-white transition text-3xl font-light">&times;</button>
        </div>

        <form action="" method="POST" id="formEditStok" onsubmit="event.preventDefault(); showSuccessAnimation('formEditStok', 'Data Berhasil Diperbarui!');">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                <!-- Data Header -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal Terima -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Tanggal Terima</label>
                        <input type="date" name="tgl_pembelian" id="edit_tgl_pembelian" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Nama Supplier -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Nama Supplier</label>
                        <input list="supplier_list" name="nama_suplier" id="edit_nama_suplier" required placeholder="Ketik nama supplier..."
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                </div>

                <!-- Nama Barang -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Barang</label>
                    <select name="id_obat" id="edit_id_obat" required class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold uppercase text-gray-800 appearance-none shadow-sm cursor-pointer">
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
                        <input type="date" name="tgl_expired" id="edit_tgl_expired" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-medium shadow-sm">
                    </div>
                    <!-- Barang Masuk (Qty) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Qty Masuk</label>
                        <input type="number" name="qty" id="edit_qty" min="1" required placeholder="0"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-green-600 shadow-sm">
                    </div>
                </div>

                <!-- Harga Beli -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide text-left">Harga Beli Per Item</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-400 font-bold">Rp</span>
                        <input type="number" name="harga_beli" id="edit_harga_beli" min="0" required placeholder="0"
                            class="w-full bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition font-bold text-gray-800 shadow-sm">
                    </div>
                </div>

                <!-- Tambah Stok Baru (Optional) -->
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mt-2">
                    <label class="block text-xs font-bold text-blue-600 mb-2 uppercase tracking-widest text-left flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Stok Baru (Opsional)
                    </label>
                    <input type="number" name="tambah_stok" id="edit_tambah_stok" min="0" placeholder="0"
                        class="w-full bg-white border border-blue-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition font-bold text-blue-700 shadow-sm"
                        title="Isi jika ada stok masuk baru untuk item ini tanpa merubah data awal">
                    <p class="text-[10px] text-blue-400 italic mt-1 font-medium">* Stok ini akan ditambahkan ke jumlah yang sudah ada.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-between px-6 py-5 bg-gray-50 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 text-gray-500 hover:text-gray-700 font-bold transition text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-extrabold py-3 px-8 rounded-xl transition shadow-lg text-xs flex items-center gap-2 uppercase tracking-widest active:scale-95">
                    Update Riwayat
                </button>
            </div>
        </form>
    </div>
</div>
