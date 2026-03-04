<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Apotek Haksa Farma')</title>
    <meta name="description" content="@yield('meta_desc', 'Apotek Haksa Farma - Katalog Obat, Artikel Kesehatan, dan Informasi Kontak.')">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        green: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .nav-active { background-color: #15803d; color: #fff; }
        .nav-item { transition: all 0.2s; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    {{-- ===== NAVBAR ===== --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('publik.katalog') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Apotek Haksa Farma" class="h-10 object-contain">
                <span class="font-bold text-green-700 text-sm hidden sm:block leading-tight">Apotek<br>Haksa Farma</span>
            </a>

            {{-- Nav Menu --}}
            <nav class="flex items-center gap-1">
                <a href="{{ route('publik.katalog') }}"
                    class="nav-item px-4 py-2 rounded-full text-sm font-semibold {{ request()->routeIs('publik.katalog') ? 'nav-active' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                    Katalog Produk
                </a>
                <a href="{{ route('publik.artikel') }}"
                    class="nav-item px-4 py-2 rounded-full text-sm font-semibold {{ request()->routeIs('publik.artikel') ? 'nav-active' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                    Artikel
                </a>
                <a href="{{ route('publik.kontak') }}"
                    class="nav-item px-4 py-2 rounded-full text-sm font-semibold {{ request()->routeIs('publik.kontak') ? 'nav-active' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                    Kontak Kami
                </a>
            </nav>
        </div>
    </header>

    {{-- ===== KONTEN UTAMA ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-gray-800 text-gray-300 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            {{-- Kolom 1 --}}
            <div>
                <p class="font-bold text-white mb-3 text-sm">Contact Us</p>
                <div class="flex items-start gap-2 mb-2">
                    <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    <div>
                        <p class="text-xs font-semibold text-white">Apotek Haksa Farma</p>
                        <p class="text-xs text-gray-400">WhatsApp Only</p>
                        <p class="text-xs text-gray-400">08.00 – 20.00 WIB</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-3">
                    <span class="text-xs text-gray-400">📷 Instagram</span>
                    <span class="text-xs text-gray-400">💬 WhatsApp</span>
                </div>
            </div>

            {{-- Kolom 2 --}}
            <div>
                <p class="font-bold text-white mb-3 text-sm">Tentang Kami</p>
                <ul class="space-y-1.5 text-xs text-gray-400">
                    <li><a href="{{ route('publik.katalog') }}" class="hover:text-green-400 transition">Katalog Produk</a></li>
                    <li><a href="{{ route('publik.artikel') }}" class="hover:text-green-400 transition">Artikel</a></li>
                    <li><a href="{{ route('publik.kontak') }}"  class="hover:text-green-400 transition">Kontak Kami</a></li>
                </ul>
            </div>

            {{-- Kolom 3 --}}
            <div>
                <p class="font-bold text-white mb-3 text-sm">Haksa Farma</p>
                <ul class="space-y-1.5 text-xs text-gray-400">
                    <li><a href="{{ route('publik.kontak') }}" class="hover:text-green-400 transition">Kontak Kami</a></li>
                </ul>
            </div>

            {{-- Kolom 4 --}}
            <div>
                <p class="font-bold text-white mb-3 text-sm">Dapatkan Penawaran Terbaru</p>
                <p class="text-xs text-gray-400 mb-3">Tetap update soal katalog informasi terbaru di Haksa Farma</p>
                <div class="flex gap-2">
                    <input type="text" placeholder="Search you want..." class="flex-1 px-3 py-2 rounded-lg bg-white text-gray-800 text-xs focus:outline-none">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-xs transition">→</button>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 py-4 text-center text-xs text-gray-500">
            © {{ date('Y') }} Apotek Haksa Farma. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
