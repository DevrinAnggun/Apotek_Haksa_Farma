<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman form edit profil & password.
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Proses perbarui username dan/atau password.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password_baru' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        // Update Username
        $user->username = $request->username;

        // Jika form password baru diisi, maka update password
        if ($request->filled('password_baru')) {
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil dan Password berhasil diperbarui!');
    }
}
