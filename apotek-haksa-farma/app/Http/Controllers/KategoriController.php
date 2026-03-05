<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return redirect()->route('obat.index');
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Request
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama Kategori wajib diisi.',
            'nama_kategori.unique' => 'Kategori ini sudah ada di database.',
        ]);

        // 2. Simpan Data
        Kategori::create($request->all());

        return redirect()->route('obat.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        // Validasi dan abaikan validasi unique untuk nama yang sama (milik id ini sendiri)
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update($request->all());

        return redirect()->route('obat.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('obat.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
