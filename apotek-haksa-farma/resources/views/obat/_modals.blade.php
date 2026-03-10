{{--  MODAL TAMBAH KATALOG PRODUK (Specific for Katalog Page) --}}
<div id="modalTambahKatalog" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahKatalogModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Tambah Katalog Produk</h3>
            <button onclick="closeTambahKatalogModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-6 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formTambahKatalog" action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="kode_obat" id="tambah_kat_kode_obat">
                <input type="hidden" name="harga_beli" value="0">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat</label>
                            <input type="text" name="nama_obat" required placeholder="Contoh: Panadol Merah 500mg" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 uppercase text-sm font-medium">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                                <select name="id_kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm cursor-pointer uppercase text-xs font-bold">
                                    <option value="" class="normal-case">Pilih Kategori</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Satuan</label>
                                <select name="id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-600 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm text-xs font-bold">
                                    <option value="">Pilih Satuan</option>
                                    @foreach($satuans as $sat)
                                        <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                                    <input type="number" name="harga_jual" required placeholder="0" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Stok Awal</label>
                                <input type="number" name="stok" min="0" placeholder="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Gambar Obat</label>
                            <div class="relative group border-2 border-dashed border-gray-200 rounded-2xl h-44 flex flex-col items-center justify-center transition hover:border-green-400 overflow-hidden">
                                <input type="file" name="gambar" accept="image/*" onchange="previewImageKatalog(this)" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                <div id="preview-kat-placeholder" class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Upload Foto</p>
                                </div>
                                <img id="preview-kat-img" class="absolute inset-0 w-full h-full object-contain p-2 hidden">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Deskripsi Obat</label>
                        <textarea name="deskripsi" rows="2" placeholder="Penjelasan singkat mengenai produk..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kegunaan</label>
                        <textarea name="cara_pakai" rows="2" placeholder="Manfaat obat atau cara pemakaian..." class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-8 py-5 border-t border-gray-100 bg-gray-50 uppercase tracking-widest font-bold">
            <button type="button" onclick="closeTambahKatalogModal()" class="text-sm text-gray-400 hover:text-gray-600 transition">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formTambahKatalog', 'Katalog Berhasil Ditambahkan!')" class="px-10 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl shadow-xl shadow-green-100 transition-all text-sm">Simpan Katalog</button>
        </div>
    </div>
</div>

{{--  MODAL EDIT BARANG --}}
<div id="modalEdit" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-hidden animate-modal flex flex-col">
        <div class="bg-green-800 px-6 py-4 flex items-center justify-between text-white border-b border-green-900">
            <h3 class="text-xl font-bold tracking-wide w-full text-center uppercase">Edit Obat</h3>
            <button onclick="closeEditModal()" class="absolute right-5 text-gray-200 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="px-8 pt-2 pb-8 overflow-y-auto max-h-[75vh]">
            <form id="formEdit" action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf @method('PUT')
                <input type="hidden" name="kode_obat" id="edit_kode_obat">
                <input type="hidden" name="harga_beli" id="edit_harga_beli">
                
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                    <select name="id_kategori" id="edit_id_kategori" onchange="toggleCekFields('edit')" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm uppercase text-sm font-medium">
                        <option value="" class="normal-case">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" data-nama="{{ $kat->nama_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Obat</label>
                    <input type="text" name="nama_obat" id="edit_nama_obat" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 uppercase text-sm font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Harga Jual</label>
                        <input type="number" name="harga_jual" id="edit_harga_jual" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                    <div class="space-y-1" id="edit_stok_wrapper">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Stok Fisik</label>
                        <input type="number" name="stok" id="edit_stok" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 items-start">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Satuan</label>
                        <select name="id_satuan" id="edit_id_satuan" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 appearance-none shadow-sm text-sm font-medium">
                            <option value="">-- Satuan --</option>
                            @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1 flex flex-col">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kadaluarsa</label>
                        <input type="date" name="expired_date" id="edit_expired_date" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium">
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-gray-50">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Deskripsi Obat</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kegunaan</label>
                        <textarea name="cara_pakai" id="edit_cara_pakai" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Gambar</label>
                        <input type="file" name="gambar" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    </div>
                </div>
            </form>
        </div>
        <div class="flex justify-between items-center px-8 py-5 border-t border-gray-100 bg-gray-50">
            <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition uppercase tracking-wider">Batal</button>
            <button type="button" onclick="showSuccessAnimation('formEdit', 'Perubahan Berhasil Disimpan!')" class="px-8 py-2.5 text-sm font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-lg transition uppercase tracking-wider">Simpan</button>
        </div>
    </div>
</div>

{{--  MODAL KONFIRMASI HAPUS --}}
<div id="modalHapus" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeHapusModal()"></div>
    <div class="relative bg-white rounded-xl shadow-2xl w-80 mx-4 overflow-hidden animate-modal">
        <div class="bg-red-600 py-3 text-center">
            <h4 class="text-white font-bold uppercase tracking-widest text-sm">KONFIRMASI HAPUS</h4>
        </div>
        <div class="px-6 pt-6 pb-4 text-center">
            <p class="text-base font-semibold text-gray-800 mb-2">Hapus Obat ini?</p>
            <p class="text-[11px] text-gray-500 italic leading-relaxed">
                Data yang dihapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 mt-2">
            <button type="button" onclick="closeHapusModal()" class="flex-1 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition uppercase tracking-wider">BATAL</button>
            <form id="formHapus" action="" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="button" onclick="showSuccessAnimation('formHapus', 'Data Berhasil Dihapus!')" class="w-full py-2 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition uppercase tracking-wider">YA, HAPUS</button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div id="modalTambahKat" class="fixed inset-0 z-50 hidden flex items-start sm:items-center justify-center p-4 overflow-y-auto">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeTambahKatModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md my-8 overflow-hidden animate-modal flex flex-col max-h-[90vh]">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between text-white shrink-0">
            <h3 class="text-xl font-bold tracking-wide w-full uppercase text-center">Kategori</h3>
            <button onclick="closeTambahKatModal()" class="absolute right-5 text-gray-100 hover:text-white text-3xl font-light leading-none">&times;</button>
        </div>
        <div class="p-8 overflow-y-auto flex-1 custom-scrollbar">
            <form id="formTambahKat" action="{{ route('kategori.store') }}" method="POST" class="space-y-4 mb-6">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="nama_kategori" required placeholder="Kategori Baru" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 uppercase text-sm font-bold">
                    <button type="button" onclick="showSuccessAnimation('formTambahKat', 'Kategori Ditambahkan!')" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-extrabold shadow-lg transition text-sm">Simpan</button>
                </div>
            </form>
            <div class="border-t border-gray-100 pt-6">
                <div class="space-y-2">
                    @if(isset($kategoris))
                    @foreach($kategoris as $kat)
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100 group hover:border-green-200 transition-all">
                            <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">{{ $kat->nama_kategori }}</span>
                            <form id="formHapusKat{{ $kat->id }}" action="{{ route('kategori.destroy', $kat->id) }}" method="POST">@csrf @method('DELETE')<button type="button" onclick="confirmHapusKat('{{ $kat->id }}', '{{ $kat->nama_kategori }}')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                        </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="flex justify-end px-8 py-5 bg-gray-50 border-t border-gray-100 shrink-0">
            <button type="button" onclick="closeTambahKatModal()" class="px-8 py-2 text-xs font-extrabold bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-md transition uppercase tracking-[0.1em]">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL SUKSES --}}
<div id="modalSukses" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-64 py-8 px-6 text-center animasi-pop">
        <div class="flex justify-center mb-5">
            <svg class="w-20 h-20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="44" stroke="#16a34a" stroke-width="6" stroke-dasharray="276" stroke-dashoffset="276" class="circle-anim"></circle>
                <polyline points="28,52 44,68 73,34" stroke="#16a34a" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="80" stroke-dashoffset="80" class="check-anim"></polyline>
            </svg>
        </div>
        <h3 id="sukses_title" class="text-xl font-extrabold text-gray-800 mb-1">Berhasil!</h3>
    </div>
</div>

<style>
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-modal { animation: modalIn 0.2s ease-out both; }
    .animasi-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; }
    @keyframes pop { from { opacity: 0; transform: scale(0.7); } to { opacity: 1; transform: scale(1); } }
    .circle-anim { animation: drawCircle 0.6s ease forwards; }
    .check-anim { animation: drawCheck 0.4s ease 0.5s forwards; }
    @keyframes drawCircle { to { stroke-dashoffset: 0; } }
    @keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>

@push('scripts')
<script>
    function previewImageKatalog(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-kat-img').src = e.target.result;
                document.getElementById('preview-kat-img').classList.remove('hidden');
                document.getElementById('preview-kat-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function openTambahKatalogModal() {
        document.getElementById('tambah_kat_kode_obat').value = 'KAT-' + Date.now();
        document.getElementById('modalTambahKatalog').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeTambahKatalogModal() {
        document.getElementById('modalTambahKatalog').classList.add('hidden');
        document.body.style.overflow = '';
    }
    function previewImg(input, prefix) {}
    function openTambahModal() {
        document.getElementById('tambah_kode_obat').value = 'OBT-' + Date.now();
        document.getElementById('modalTambah').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        toggleCekFields('tambah');
    }
    function closeTambahModal() { document.getElementById('modalTambah').classList.add('hidden'); document.body.style.overflow = ''; }
    function toggleCekFields(prefix) {
        const sel = document.getElementById(prefix + '_id_kategori');
        if (!sel) return;
        const selectedOption = sel.options[sel.selectedIndex];
        const isCek = selectedOption && selectedOption.getAttribute('data-nama') === 'CEK';
        const stokWrapper = document.getElementById(prefix + '_stok_wrapper');
        const expWrapper = document.getElementById(prefix + '_expired_wrapper');
        if (isCek) {
            if (stokWrapper) stokWrapper.classList.add('hidden');
            if (expWrapper) expWrapper.classList.add('hidden');
            const stokInput = document.getElementById(prefix === 'tambah' ? 'tambah_field_stok' : 'edit_stok');
            if (stokInput) stokInput.value = 9999;
        } else {
            if (stokWrapper) stokWrapper.classList.remove('hidden');
            if (expWrapper) expWrapper.classList.remove('hidden');
        }
    }
    function openEditModal(el) {
        const d = el.dataset;
        const form = document.getElementById('formEdit');
        form.action = '{{ url("obat") }}/' + d.id;
        document.getElementById('edit_kode_obat').value = d.kodeObat;
        document.getElementById('edit_harga_beli').value = d.hargaBeli;
        document.getElementById('edit_nama_obat').value = d.nama;
        document.getElementById('edit_harga_jual').value = d.hargaJual;
        document.getElementById('edit_stok').value = d.stok;
        document.getElementById('edit_expired_date').value = d.expiredDate;
        document.getElementById('edit_id_kategori').value = d.idKategori;
        document.getElementById('edit_id_satuan').value = d.idSatuan;
        document.getElementById('edit_deskripsi').value = d.deskripsi || '';
        document.getElementById('edit_cara_pakai').value = d.caraPakai || '';
        toggleCekFields('edit');
        document.getElementById('modalEdit').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); document.body.style.overflow = ''; }
    function openHapusModal(id, nama) {
        document.getElementById('formHapus').action = '{{ url("obat") }}/' + id;
        document.getElementById('modalHapus').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeHapusModal() { document.getElementById('modalHapus').classList.add('hidden'); document.body.style.overflow = ''; }
    function openTambahKatModal() { document.getElementById('modalTambahKat').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeTambahKatModal() { document.getElementById('modalTambahKat').classList.add('hidden'); document.body.style.overflow = ''; }
    function confirmHapusKat(id, nama) {
        if(confirm('Hapus kategori ' + nama + '?')) document.getElementById('formHapusKat' + id).submit();
    }
    function showSuccessAnimation(formId, titleText) {
        const form = document.getElementById(formId);
        if (!form.checkValidity()) { form.reportValidity(); return; }
        document.getElementById('sukses_title').textContent = titleText;
        document.getElementById('modalSukses').classList.remove('hidden');
        document.getElementById('modalSukses').classList.add('flex');
        setTimeout(() => form.submit(), 1200);
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeTambahModal(); closeEditModal(); closeHapusModal(); closeTambahKatModal(); closeTambahKatalogModal(); }
    });
</script>
@endpush
