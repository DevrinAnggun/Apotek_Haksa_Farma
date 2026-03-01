<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function index()
    {
        // Jika user sudah login, arahkan langsung ke dashboard yang sesuai (admin/kasir)
        if (Auth::check()) {
            return Auth::user()->role == 'admin' ? redirect()->route('dashboard') : redirect()->route('kasir.pos');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login
     */
    public function login(Request $request)
    {
        // Validasi form login
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba autentikasi menggunakan Auth facet
        if (Auth::attempt($credentials)) {
            // Jika berhasil masuk, buat session aman yang baru
            $request->session()->regenerate();

            // Pengecekan RBAC: Arahkan berdasarkan role (Admin masuk Dashboard, Kasir masuk POS)
            if (Auth::user()->role == 'admin') {
                return redirect()->intended('dashboard')->with('success', 'Selamat datang kembali, Admin!');
            } else {
                return redirect()->intended('kasir/pos')->with('success', 'Login berhasil sebagai Kasir.');
            }
        }

        // Jika salah username / password, kembalikan ke form
        return back()->withErrors([
            'username' => 'Username atau Password salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hancurkan session dan Token CSRF demi keamanan data memori
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
