@extends('layouts.publik')

@section('title', 'Kontak Kami')
@section('meta_desc', 'Hubungi Apotek Haksa Farma — alamat, WhatsApp, dan lokasi Google Maps.')

@section('content')

{{-- Header --}}
<div class="bg-white border-b border-gray-100 py-10 text-center">
    <h1 class="text-3xl font-extrabold tracking-wide mb-2 text-gray-800 uppercase">Kontak Kami</h1>
    <p class="text-gray-400 text-sm font-medium">Kami siap membantu kebutuhan kesehatan Anda</p>
</div>

<div class="max-w-5xl mx-auto px-4 py-12">

    {{-- ===== KARTU KONTAK UTAMA ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-10">
        <div class="grid grid-cols-1 md:grid-cols-2">

            {{-- Peta Google Maps --}}
            <div class="h-64 md:h-auto min-h-[280px] bg-gray-200 relative">
                @if($settings['kontak_maps'] ?? false)
                <iframe
                    src="{{ $settings['kontak_maps'] }}"
                    width="100%" height="100%" style="border:0; min-height:280px;" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full object-cover">
                </iframe>
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm italic">Peta belum dikonfigurasi</div>
                @endif
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
                            {!! nl2br(e($settings['kontak_alamat'] ?? 'Alamat belum diatur')) !!}
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
                        @php $wa = str_replace(['-', ' '], '', $settings['kontak_telepon'] ?? ''); @endphp
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $wa) }}" class="text-sm text-green-700 hover:underline font-bold">
                            {{ $settings['kontak_telepon'] ?? 'Belum diatur' }}
                        </a>
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
                        <a href="mailto:{{ $settings['kontak_email'] ?? '' }}" class="text-sm text-green-700 hover:underline font-medium">
                            {{ $settings['kontak_email'] ?? 'Belum diatur' }}
                        </a>
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
                $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            @endphp
            @foreach($haris as $hari)
                @php 
                    $key = 'jam_buka_' . strtolower($hari);
                    $waktu = $settings[$key] ?? '08:00 - 20:00 WIB';
                    $isTutup = strtolower($waktu) === 'tutup' || $waktu === '-';
                @endphp
                <div class="flex items-center justify-between px-4 py-2.5 rounded-lg {{ !$isTutup ? 'bg-green-50' : 'bg-red-50' }}">
                    <span class="font-semibold {{ !$isTutup ? 'text-gray-700' : 'text-gray-400' }}">{{ $hari }}</span>
                    <span class="{{ !$isTutup ? 'text-green-700 font-bold' : 'text-red-400 font-medium' }}">
                        {{ $waktu }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
