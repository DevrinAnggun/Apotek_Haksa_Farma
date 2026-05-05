<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Apotek Haksa Farma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        green: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg border border-green-100 p-6">
        <div class="text-center mb-5">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Apotek Haksa Farma" class="h-20 mx-auto mb-3 object-contain">
            <h2 class="text-xl font-bold text-green-700 tracking-tight uppercase">Apotek Haksa Farma</h2>
        </div>

        @if($errors->any() || session('error'))
            <div id="login-error" class="bg-red-50 border-l-4 border-red-500 text-red-800 p-3 rounded-lg mb-4 shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-red-500 rounded-full p-1 mr-2 flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <span class="text-xs font-bold">
                        @if($errors->has('username') && $errors->first('username') == 'Username atau Password salah.')
                            Gagal login. Username atau Password salah.
                        @elseif($errors->any())
                            Gagal login. Pastikan Username dan Password telah diisi.
                        @else
                            {{ session('error') }}
                        @endif
                    </span>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'" class="text-red-500 hover:text-red-700 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        @if(session('success'))
            <div id="login-success" class="bg-green-50 border-l-4 border-green-500 text-green-800 p-3 rounded-lg mb-4 shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-green-500 rounded-full p-1 mr-2 flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-1 text-sm">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 @error('username') border-red-500 @enderror text-sm" 
                    placeholder="Username">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-1 text-sm">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-green-500 @error('password') border-red-500 @enderror pr-11 text-sm" 
                        placeholder="••••••••">
                    <button type="button" id="togglePassword"
                        onclick="togglePasswordVisibility()"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-green-600 focus:outline-none transition-colors duration-200"
                        title="Lihat / Sembunyikan Password">
                        <!-- Eye icon (show) -->
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye-off icon (hide) -->
                        <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white font-extrabold py-2 px-4 rounded-lg focus:outline-none shadow-md transition duration-200 uppercase tracking-widest text-xs">
                Masuk
            </button>
        </form>
    </div>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
