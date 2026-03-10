<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = \App\Models\Artikel::orderBy('created_at', 'desc')->get();
        return view('artikel.index', compact('artikels'));
    }

    public function create()
    {
        return view('artikel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategori' => 'nullable|string|max:100',
        ]);

        $data = $request->all();
        $data['slug'] = \Illuminate\Support\Str::slug($request->judul) . '-' . time();
        $data['tanggal_publish'] = now();

        if ($request->hasFile('gambar')) {
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('uploads/artikel'), $imageName);
            $data['gambar'] = 'uploads/artikel/' . $imageName;
        }

        \App\Models\Artikel::create($data);

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $artikel = \App\Models\Artikel::findOrFail($id);
        return view('artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $artikel = \App\Models\Artikel::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategori' => 'nullable|string|max:100',
        ]);

        $data = $request->all();
        
        if ($request->judul != $artikel->judul) {
            $data['slug'] = \Illuminate\Support\Str::slug($request->judul) . '-' . time();
        }

        if ($request->hasFile('gambar')) {
            if ($artikel->gambar && file_exists(public_path($artikel->gambar))) {
                unlink(public_path($artikel->gambar));
            }
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('uploads/artikel'), $imageName);
            $data['gambar'] = 'uploads/artikel/' . $imageName;
        }

        $artikel->update($data);

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $artikel = \App\Models\Artikel::findOrFail($id);
        if ($artikel->gambar && file_exists(public_path($artikel->gambar))) {
            unlink(public_path($artikel->gambar));
        }
        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
