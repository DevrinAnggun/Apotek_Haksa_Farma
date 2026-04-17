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
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .nav-active { background-color: #15803d; color: #fff; }
        .nav-item { transition: all 0.2s; }

        /* ── HERO SLIDER ── */
        .hero-slider {
            position: relative;
            overflow: hidden;
            width: 100%;
        }
        .hero-slider .slide {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }
        .hero-slider .slide.active {
            opacity: 1;
            position: relative;
        }
        .hero-slider .slide img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            display: block;
        }
        .hero-slider .slide .slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
            display: flex;
            align-items: center;
        }
        .slider-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 20;
        }
        .slider-dots .dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.45);
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid rgba(255,255,255,0.6);
        }
        .slider-dots .dot.active {
            background: #fff;
            width: 28px;
            border-radius: 5px;
        }
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 20;
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #fff;
            transition: all 0.2s;
        }
        .slider-arrow:hover { background: rgba(255,255,255,0.35); }
        .slider-arrow.prev { left: 16px; }
        .slider-arrow.next { right: 16px; }

        @media (max-width: 768px) {
            .hero-slider .slide img { height: 280px; }
        }
    </style>
    @stack('styles')
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
                    class="nav-item px-4 py-2 rounded-full text-sm font-semibold {{ (request()->routeIs('publik.katalog') || request()->routeIs('home')) ? 'nav-active' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                    Katalog Produk
                </a>
                <a href="{{ route('publik.artikel') }}"
                    class="nav-item px-4 py-2 rounded-full text-sm font-semibold {{ request()->routeIs('publik.artikel*') ? 'nav-active' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                    Artikel
                </a>
                <a href="{{ route('publik.kontak') }}"
                    class="nav-item px-4 py-2 rounded-full text-sm font-semibold {{ request()->routeIs('publik.kontak') ? 'nav-active' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                    Kontak Kami
                </a>
            </nav>
        </div>
    </header>

    {{-- ===== HERO SLIDER (hanya muncul jika child view set section 'show_slider') ===== --}}
    @hasSection('show_slider')
    <div class="hero-slider" id="heroSlider">
        {{-- Slide 1: Peresmian Apotek --}}
        <div class="slide active">
            <img src="{{ asset('images/slider/slide1.jpg') }}" alt="Peresmian Apotek Haksa Farma">
            <div class="slide-overlay">
                <div class="max-w-6xl mx-auto px-6 w-full">
                    <div class="max-w-lg">
                        <h2 class="text-white text-3xl md:text-4xl font-extrabold leading-tight mb-3 drop-shadow-lg">Selamat Datang di<br>Apotek Haksa Farma</h2>
                        <p class="text-white/80 text-sm md:text-base font-medium mb-5 leading-relaxed">Melayani kebutuhan obat-obatan dan alat kesehatan berkualitas dengan harga terjangkau.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 2: Tampak Depan Apotek --}}
        <div class="slide">
            <img src="{{ asset('images/slider/slide2.png') }}" alt="Apotek Haksa Farma Tampak Depan">
            <div class="slide-overlay">
                <div class="max-w-6xl mx-auto px-6 w-full">
                    <div class="max-w-lg">
                        <h2 class="text-white text-3xl md:text-4xl font-extrabold leading-tight mb-3 drop-shadow-lg">Apotek & Toko<br>Grosir dan Eceran</h2>
                        <p class="text-white/80 text-sm md:text-base font-medium mb-5 leading-relaxed">Buka setiap hari untuk melayani kebutuhan kesehatan masyarakat.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 3: Foto Apotek --}}
        <div class="slide">
            <img src="{{ asset('images/haksa2.jpg') }}" alt="Apotek Haksa Farma">
            <div class="slide-overlay">
                <div class="max-w-6xl mx-auto px-6 w-full">
                    <div class="max-w-lg">
                        <h2 class="text-white text-3xl md:text-4xl font-extrabold leading-tight mb-3 drop-shadow-lg">Informasi Kesehatan<br>Terpercaya & Terkini</h2>
                        <p class="text-white/80 text-sm md:text-base font-medium mb-5 leading-relaxed">Baca artikel seputar tips sehat, panduan penggunaan obat, dan banyak lagi.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Arrows --}}
        <button class="slider-arrow prev" onclick="changeSlide(-1)" aria-label="Previous">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button class="slider-arrow next" onclick="changeSlide(1)" aria-label="Next">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>

        {{-- Dots --}}
        <div class="slider-dots">
            <div class="dot active" onclick="goToSlide(0)"></div>
            <div class="dot" onclick="goToSlide(1)"></div>
            <div class="dot" onclick="goToSlide(2)"></div>
        </div>
    </div>
    @endif

    {{-- ===== KONTEN UTAMA (langsung nyambung ke bawah slider) ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-green-900 text-gray-200 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            {{-- Kolom 1 --}}
            <div>
                <p class="font-bold text-white mb-3 text-sm">Contact Us</p>
                <div class="flex items-start gap-2 mb-2">
                    <svg class="w-4 h-4 text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    <div>
                        <a href="https://wa.me/6285385984906" target="_blank" class="text-xs font-semibold text-white hover:text-green-400 transition">Apotek Haksa Farma</a>
                        <p class="text-[10px] text-gray-400 leading-none mt-1">WhatsApp Only</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">08.00 – 20.00 WIB</p>
                    </div>
                </div>
                <div class="flex gap-4 mt-4">
                    <a href="https://www.instagram.com/apotek.haksafarma?igsh=bG9kaW1haWQ2dGc1" target="_blank" class="text-xs text-gray-400 hover:text-pink-500 transition flex items-center gap-1.5 font-medium">
                        <span>📷</span> Instagram
                    </a>
                    <a href="https://wa.me/6285385984906" target="_blank" class="text-xs text-gray-400 hover:text-green-500 transition flex items-center gap-1.5 font-medium">
                        <span>💬</span> WhatsApp
                    </a>
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
                <form action="{{ route('publik.katalog') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Cari obat..." class="flex-1 px-3 py-2 rounded-lg bg-white text-gray-800 text-xs focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-xs transition shadow-md">→</button>
                </form>
            </div>
        </div>
        <div class="border-t border-green-800 py-4 text-center text-xs text-green-300/50">
            © {{ date('Y') }} Apotek Haksa Farma. All rights reserved.
        </div>
    </footer>

    <script>
    // ── Hero Slider ──
    let currentSlide = 0;
    let sliderInterval;
    const slides = document.querySelectorAll('#heroSlider .slide');
    const dots   = document.querySelectorAll('.slider-dots .dot');

    function goToSlide(n) {
        if (!slides.length) return;
        slides.forEach((s, i) => {
            s.classList.remove('active');
            s.style.position = 'absolute';
        });
        dots.forEach(d => d.classList.remove('active'));
        slides[n].classList.add('active');
        slides[n].style.position = 'relative';
        if (dots[n]) dots[n].classList.add('active');
        currentSlide = n;
    }

    function changeSlide(dir) {
        let next = (currentSlide + dir + slides.length) % slides.length;
        goToSlide(next);
        resetTimer();
    }

    function resetTimer() {
        clearInterval(sliderInterval);
        sliderInterval = setInterval(() => changeSlide(1), 5000);
    }

    if (slides.length > 0) {
        goToSlide(0);
        sliderInterval = setInterval(() => changeSlide(1), 5000);
    }
    </script>

    @stack('scripts')
</body>
</html>
