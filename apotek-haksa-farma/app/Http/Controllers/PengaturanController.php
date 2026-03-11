<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Pengaturan::pluck('value', 'key');
        return view('publik.kontak_admin', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            \App\Models\Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
