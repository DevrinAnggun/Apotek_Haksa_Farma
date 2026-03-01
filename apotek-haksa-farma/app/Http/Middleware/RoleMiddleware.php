<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roles  (admin,kasir)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Ambil data role user dari database (lewat session auth)
        $userRole = auth()->user()->role;

        // 3. Cek apakah role user saat ini ada di dalam daftar parameter role yang diizinkan ($roles)
        if (!in_array($userRole, $roles)) {
            // Jika tidak diizinkan, arahkan ke halaman 403 atau kembali ke home
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // 4. Jika role cocok, izinkan akses ke Controller/Halaman
        return $next($request);
    }
}
