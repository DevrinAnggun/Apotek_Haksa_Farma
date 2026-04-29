<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return Auth::user()->role == 'admin' ? redirect()->route('dashboard') : redirect()->route('kasir.pos');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'admin') {
                return redirect()->intended('dashboard')->with('success', 'Selamat datang kembali, Admin!');
            } else {
                return redirect()->intended('kasir/pos')->with('success', 'Login berhasil sebagai Kasir.');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau Password salah.',
        ])->onlyInput('username');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
