@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Pengaturan Kontak & Toko</h1>
    <p class="text-gray-500 text-sm mt-1">Sesuaikan informasi yang muncul di halaman publik pelanggan.</p>
</div>

@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <form action="{{ route('pengaturan.update') }}" method="POST" class="p-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
            
            {{-- Informasi Kontak --}}
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Informasi Kontak</h3>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Nomor Telepon / WhatsApp</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </span>
                        <input type="text" name="kontak_telepon" value="{{ $settings['kontak_telepon'] ?? '' }}"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                            placeholder="Contoh: 0812-3456-7890">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Email Apotek</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        <input type="email" name="kontak_email" value="{{ $settings['kontak_email'] ?? '' }}"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                            placeholder="Contoh: haksafarma@gmail.com">
                    </div>
                </div>

                {{-- Jam Operasional Mingguan --}}
                <div class="pt-4">
                    <h4 class="text-sm font-extrabold text-green-700 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Jam Operasional Mingguan
                    </h4>
                    <div class="space-y-3">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <div class="flex items-center gap-4">
                            <span class="w-20 text-xs font-bold text-gray-500 uppercase">{{ $hari }}</span>
                            <input type="text" name="jam_buka_{{ strtolower($hari) }}" value="{{ $settings['jam_buka_'.strtolower($hari)] ?? '08:00 - 20:00 WIB' }}"
                                class="flex-1 px-4 py-2 text-xs rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-green-500 transition"
                                placeholder="Contoh: 08:00 - 20:00 WIB atau Tutup">
                        </div>
                        @endforeach
                        <p class="text-[10px] text-gray-400 italic mt-2">*Tulis "Tutup" jika hari tersebut libur.</p>
                    </div>
                </div>
            </div>

            {{-- Lokasi & Peta --}}
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Lokasi Fisik</h3>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Alamat Lengkap</label>
                    <textarea name="kontak_alamat" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition" placeholder="Tuliskan alamat lengkap apotek...">{{ $settings['kontak_alamat'] ?? '' }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Embed Google Maps (Iframe URL)</label>
                    <input type="text" name="kontak_maps" value="{{ $settings['kontak_maps'] ?? '' }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                        placeholder="Tempelkan URL dari src iframe Google Maps">
                    <p class="text-[10px] text-gray-400 mt-1 italic leading-tight">Buka Google Maps > Share > Embed a map > Copy URL di dalam atribut 'src'.</p>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-end pt-8 border-t border-gray-100 mt-10">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-10 rounded-xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-0.5">
                Simpan Perubahan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
