<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Apotek Haksa Farma</title>
    <!-- Tailwind CSS (CDN for quick MVP prototyping) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        green: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f7fafc; }
        .sidebar-active { background-color: #dcfce7; color: #15803d; border-left: 4px solid #16a34a; font-weight: 600; }
        .sidebar-link { transition: all 0.2s ease-in-out; }
        .sidebar-link:hover:not(.sidebar-active) { background-color: #f3f4f6; color: #16a34a; }
    </style>
</head>
<body class="flex flex-col h-screen overflow-hidden text-sm">

    <!-- Top Navbar (Style = User Page Header) -->
    <header class="flex items-center justify-between px-6 py-3 bg-white shadow-sm z-10 border-b border-gray-100">
        <!-- Logo + Nama -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Apotek Haksa Farma" class="h-10 object-contain">
            <span class="font-bold text-green-700 text-sm leading-tight hidden sm:block">Apotek<br>Haksa Farma</span>
        </div>
        <!-- Profile Dropdown Component -->
        <div class="relative inline-block text-left" id="profile-menu-container">
            <!-- Toggle Button -->
            <button onclick="toggleDropdown()" class="flex items-center gap-2 text-gray-700 hover:text-green-700 focus:outline-none transition py-1.5 px-3 rounded-full hover:bg-green-50 border border-gray-200 hover:border-green-300">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="font-semibold text-sm">Hi, {{ auth()->user()->nama ?? 'Admin' }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <!-- Dropdown Panel -->
            <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-xl py-2 ring-1 ring-black ring-opacity-5 z-50 transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                
                <!-- Label Info (Dari Mockup) -->
                <div class="px-4 py-2 border-b border-gray-100 flex items-center mb-1">
                    <span class="font-bold text-gray-800 uppercase tracking-wider">Haksa Farma {{ auth()->user()->role == 'admin' ? 'Admin' : 'Kasir' }}</span>
                </div>
                
                <!-- Opsi Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-red-600 font-medium hover:bg-red-50 transition">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>

                <!-- Opsi Edit Password (buka modal) -->
                <button onclick="openProfileModal()" class="w-full text-left flex items-center px-4 py-2.5 text-gray-700 font-medium hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Edit Username / Password
                </button>

            </div>
        </div>
    </header>

    <!-- Main Content Layout -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg border-r border-gray-100 hidden lg:flex flex-col flex-shrink-0 relative z-0">
            <div class="flex-1 py-6 space-y-1">
                
                <!-- Dashboard -->
                <!-- The URL segment checking applies 'sidebar-active' to the current page -->
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->is('dashboard*') || request()->is('/') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <!-- Data Master & Stok -->
                <a href="{{ route('obat.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->is('obat*') || request()->is('kategori*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Data Obat
                </a>

                <!-- Data Supplier -->
                <a href="{{ route('supplier.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->is('supplier*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Data Supplier
                </a>

                <!-- Stok Supplier (Pembelian) -->
                <a href="{{ route('pembelian.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->is('pembelian*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2-2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Stok Supplier
                </a>

                <!-- Penjualan (Riwayat) -->
                <a href="{{ route('laporan.penjualan') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->is('laporan*') || request()->is('kasir*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Penjualan
                </a>

                <!-- Data Kadaluarsa -->
                <a href="{{ route('kadaluarsa.index') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->is('kadaluarsa*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kadaluarsa Obat
                </a>

                <!-- Halaman User (Publik) -->
                <a href="{{ route('publik.katalog') }}" target="_blank" class="sidebar-link flex items-center px-6 py-3 text-gray-600 {{ request()->is('publik*') ? 'sidebar-active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    Halaman User
                </a>
            </div>
        </aside>


        <!-- Main Workspace Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white p-6 md:p-10">
            <div class="max-w-full mx-auto">
                <!-- Blade Template Injection Point -->
                @yield('content')
            </div>
        </main>

    </div>

    <!-- ===== MODAL EDIT PROFIL & PASSWORD ===== -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" onclick="closeProfileModal()"></div>

        <!-- Modal Box -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 animate-modal">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Edit Profil &amp; Password</h3>
                </div>
                <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 overflow-y-auto max-h-[75vh]">

                @if(session('success'))
                    <div id="modal-success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 text-sm">
                        {{ session('success') }}
                        <button onclick="dismissAlert('modal-success-alert')" class="absolute top-0 bottom-0 right-0 px-4">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div id="modal-error-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 text-sm">
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')

                    <!-- Nama (disabled) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-1.5 text-sm">Nama Pengguna <span class="text-gray-400 font-normal">(Tidak Bisa Diubah)</span></label>
                        <input type="text" value="{{ auth()->user()->nama }}" class="w-full px-4 py-2.5 border rounded-lg bg-gray-100 cursor-not-allowed text-gray-500 text-sm" disabled>
                    </div>

                    <!-- Username -->
                    <div class="mb-4">
                        <label for="modal_username" class="block text-gray-700 font-semibold mb-1.5 text-sm">Username Baru <span class="text-gray-400 font-normal">(Untuk Login)</span></label>
                        <input type="text" name="username" id="modal_username" value="{{ old('username', auth()->user()->username) }}" required
                            class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm transition">
                        <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah username.</p>
                    </div>

                    <hr class="my-4 border-gray-100">

                    <p class="text-sm font-bold text-gray-700 mb-3">Ganti Password</p>

                    <!-- Password Baru -->
                    <div class="mb-4">
                        <label for="modal_password_baru" class="block text-gray-700 font-semibold mb-1.5 text-sm">Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_baru" id="modal_password_baru"
                                class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 pr-11 text-sm transition"
                                placeholder="Kosongkan jika tidak ingin ganti password">
                            <button type="button" onclick="toggleModalPassword('modal_password_baru', 'eye1', 'eyeoff1')"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-green-600 transition">
                                <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeoff1" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Minimal 6 karakter.</p>
                    </div>

                    <!-- Ulangi Password Baru -->
                    <div class="mb-2">
                        <label for="modal_password_confirm" class="block text-gray-700 font-semibold mb-1.5 text-sm">Ulangi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_baru_confirmation" id="modal_password_confirm"
                                class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 pr-11 text-sm transition"
                                placeholder="Ulangi password baru">
                            <button type="button" onclick="toggleModalPassword('modal_password_confirm', 'eye2', 'eyeoff2')"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-green-600 transition">
                                <svg id="eye2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeoff2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                <button type="button" onclick="closeProfileModal()" class="px-5 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-200 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" form="profileForm" class="px-6 py-2 text-sm font-bold bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-modal { animation: modalIn 0.2s ease-out both; }
    </style>

    <!-- Scripts -->
    <script>
        // Dismiss alert notification function
        function dismissAlert(elementId) {
            const el = document.getElementById(elementId);
            if(el) {
                el.style.opacity = '0';
                setTimeout(() => el.style.display = 'none', 300);
            }
        }

        // Toggle Profile Dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if(dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                // timeout to allow display:block to apply before animating opacity
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'scale-95');
                    dropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                dropdown.classList.remove('opacity-100', 'scale-100');
                dropdown.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200); // match duration-200
            }
        }

        // Tutup dropdown jika user klik di sembarang tempat (luar panel)
        document.addEventListener('click', function(event) {
            const container = document.getElementById('profile-menu-container');
            const dropdown = document.getElementById('profileDropdown');
            if (container && !container.contains(event.target)) {
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('opacity-100', 'scale-100');
                    dropdown.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                    }, 200);
                }
            }
        });
    </script>
    <script>
        /* ===== MODAL PROFIL ===== */
        function openProfileModal() {
            // tutup dropdown dulu
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('opacity-100', 'scale-100');
                dropdown.classList.add('opacity-0', 'scale-95');
                setTimeout(() => dropdown.classList.add('hidden'), 200);
            }
            document.getElementById('profileModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeProfileModal();
        });

        // Auto buka modal jika ada error validasi (dari server)
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                openProfileModal();
            });
        @endif

        /* ===== TOGGLE SHOW/HIDE PASSWORD DI MODAL ===== */
        function toggleModalPassword(inputId, eyeId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);

            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
