<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_suplier' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

}
