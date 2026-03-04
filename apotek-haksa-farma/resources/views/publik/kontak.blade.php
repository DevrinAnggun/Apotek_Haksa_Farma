@extends('layouts.publik')

@section('title', 'Kontak Kami')
@section('meta_desc', 'Hubungi Apotek Haksa Farma — alamat, WhatsApp, dan lokasi Google Maps.')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-br from-green-700 to-green-900 py-14 text-center text-white">
    <h1 class="text-3xl font-extrabold tracking-wide mb-2">Kontak Kami</h1>
    <p class="text-green-200 text-sm">Kami siap membantu kebutuhan kesehatan Anda</p>
</div>

<div class="max-w-5xl mx-auto px-4 py-12">

    {{-- ===== KARTU KONTAK UTAMA ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-10">
        <div class="grid grid-cols-1 md:grid-cols-2">

            {{-- Peta Google Maps --}}
            <div class="h-64 md:h-auto min-h-[280px] bg-gray-200 relative">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.234817!2d109.6960!3d-7.4024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMjQnMDUuNC"
                    width="100%" height="100%" style="border:0; min-height:280px;" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full object-cover">
                </iframe>
            </div>

            {{-- Info Kontak --}}
            <div class="p-8 flex flex-col justify-center gap-6">
                {{-- Alamat --}}
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 mb-1">Alamat</p>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Jl. Purwareja No.82, Dusun Rw. Gembol, Purworejo,<br>
                            Kec. Purwareja Klampok,<br>
                            Kab. Banjarnegara, Jawa Tengah 53474
                        </p>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 mb-1">WhatsApp</p>
                        <a href="https://wa.me/6208xxxxx" class="text-sm text-green-700 hover:underline font-medium">08xxxxxxxxx</a>
                        <p class="text-xs text-gray-400 mt-0.5">Hanya WhatsApp (tidak ada telepon)</p>
                        <p class="text-xs text-gray-400">Senin – Sabtu, 08:00 – 20:00 WIB</p>
                    </div>
                </div>

                {{-- Instagram --}}
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 mb-1">Instagram</p>
                        <a href="#" class="text-sm text-green-700 hover:underline font-medium">@haksafarma</a>
                        <p class="text-xs text-gray-400 mt-0.5">Ikuti kami untuk info promo terbaru</p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 mb-1">Email</p>
                        <a href="mailto:info@haksafarma.com" class="text-sm text-green-700 hover:underline font-medium">info@haksafarma.com</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== JAM OPERASIONAL ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-10">
        <h2 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Jam Operasional
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            @php
                $jadwals = [
                    ['hari' => 'Senin',   'buka' => '08:00', 'tutup' => '20:00', 'aktif' => true],
                    ['hari' => 'Selasa',  'buka' => '08:00', 'tutup' => '20:00', 'aktif' => true],
                    ['hari' => 'Rabu',    'buka' => '08:00', 'tutup' => '20:00', 'aktif' => true],
                    ['hari' => 'Kamis',   'buka' => '08:00', 'tutup' => '20:00', 'aktif' => true],
                    ['hari' => 'Jumat',   'buka' => '08:00', 'tutup' => '20:00', 'aktif' => true],
                    ['hari' => 'Sabtu',   'buka' => '08:00', 'tutup' => '17:00', 'aktif' => true],
                    ['hari' => 'Minggu',  'buka' => '-',     'tutup' => '-',     'aktif' => false],
                ];
            @endphp
            @foreach($jadwals as $j)
            <div class="flex items-center justify-between px-4 py-2.5 rounded-lg {{ $j['aktif'] ? 'bg-green-50' : 'bg-red-50' }}">
                <span class="font-semibold {{ $j['aktif'] ? 'text-gray-700' : 'text-gray-400' }}">{{ $j['hari'] }}</span>
                <span class="{{ $j['aktif'] ? 'text-green-700 font-bold' : 'text-red-400 font-medium' }}">
                    {{ $j['aktif'] ? $j['buka'].' – '.$j['tutup'].' WIB' : 'Tutup' }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
