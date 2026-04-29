<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password_baru' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->username = $request->username;

        if ($request->filled('password_baru')) {
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil dan Password berhasil diperbarui!');
    }
}
