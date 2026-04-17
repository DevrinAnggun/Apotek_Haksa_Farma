<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\StokBatch;
use App\Models\Supplier; // Import Supplier
use App\Models\Pembelian; // Import Pembelian
use App\Models\DetailPembelian; // Import DetailPembelian
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $monthName = Carbon::create($year, $month)->translatedFormat('F Y');

        $query = Obat::with(['kategori', 'satuan', 'stokBatches'])
            ->with(['penjualanDetails' => function($q) use ($month, $year) {
                $q->whereHas('penjualan', function($q2) use ($month, $year) {
                    $q2->whereMonth('tgl_penjualan', $month)->whereYear('tgl_penjualan', $year);
                })->with('penjualan:id,tgl_penjualan');
            }])
            ->with(['pembelianDetails' => function($q) use ($month, $year) {
                $q->whereHas('pembelian', function($q2) use ($month, $year) {
                    $q2->whereMonth('tgl_pembelian', $month)->whereYear('tgl_pembelian', $year);
                });
            }])
            ->with(['returPembelians' => function($q) use ($month, $year) {
                $q->whereMonth('tgl_retur', $month)->whereYear('tgl_retur', $year);
            }])
            ->with(['stockOpnames' => function($q) use ($month, $year) {
                $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
            }]);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%");
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        $obats = $query->paginate(10)->withQueryString();

        $obats->getCollection()->transform(function ($obat) use ($daysInMonth, $month, $year) {
            $obat->current_stok = $obat->stokBatches->sum('stok_sisa');
            $obat->masuk_bulan_ini = $obat->pembelianDetails->sum('qty');
            $obat->retur_bulan_ini = $obat->returPembelians->sum('qty_retur');
            $obat->terjual_bulan_ini = $obat->penjualanDetails->sum('qty');
            
            // Perkiraan Stok Awal
            $isCurrentMonth = ($month == date('m') && $year == date('Y'));
            if ($isCurrentMonth) {
                $obat->stok_awal = $obat->current_stok - $obat->masuk_bulan_ini + $obat->retur_bulan_ini + $obat->terjual_bulan_ini;
            } else {
                $obat->stok_awal = 0; 
            }

            // Stok Sistem Akhir (Expected)
            $obat->expected_stok = $obat->stok_awal + $obat->masuk_bulan_ini - $obat->retur_bulan_ini - $obat->terjual_bulan_ini;
            
            // Stok Fisik Akhir (SO terakhir)
            $soTerakhir = $obat->stockOpnames->sortByDesc('tanggal')->first();
            $obat->total_so = $soTerakhir ? $soTerakhir->jumlah : 0;
            $obat->last_so_date = $soTerakhir ? Carbon::parse($soTerakhir->tanggal)->format('Y-m-d') : '';
            $obat->last_so_id = $soTerakhir ? $soTerakhir->id : null;
            
            // Selisih
            $obat->selisih = $obat->total_so - $obat->expected_stok;

            $dailySO = [];
            for($i = 1; $i <= $daysInMonth; $i++) {
                $dailySO[$i] = 0;
            }
            foreach($obat->stockOpnames as $so) {
                $day = Carbon::parse($so->tanggal)->format('j');
                $dailySO[(int)$day] = $so->jumlah;
            }
            $obat->daily_so = $dailySO;
            
            return $obat;
        });

        $kategoris = Kategori::all();
        $satuans   = Satuan::all();
        
        return view('obat.index', compact('obats', 'kategoris', 'satuans', 'daysInMonth', 'monthName', 'month', 'year'));
    }

    public function katalogAdmin(Request $request)
    {
        $query = Obat::whereHas('kategori', function($q) {
                        $q->where('nama_kategori', '!=', 'CEK');
                     })
                     ->whereHas('stokBatches', function($q) {
                        $q->where('tgl_expired', '>=', date('Y-m-d'));
                     })
                     ->with(['kategori', 'satuan'])
                     ->withSum('penjualanDetails as total_terjual', 'qty');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%");
            });
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }

        $obats = $query->paginate(12)->withQueryString();

        // Exclude 'CEK' from filter options
        $kategoris = Kategori::where('nama_kategori', '!=', 'CEK')->get();
        $satuans   = Satuan::all();
        
        return view('publik.katalog_admin', compact('obats', 'kategoris', 'satuans'));
    }

    public function create()
    {
        return redirect()->route('obat.index');
    }

    public function store(Request $request)
    {
        $isCek = \App\Models\Kategori::where('id', $request->id_kategori)->where('nama_kategori', 'CEK')->exists();
        
        // Validasi Relasi dan Nilai Numerik
        $request->validate([
            'kode_obat'   => 'required|string|unique:obats,kode_obat',
            'nama_obat'   => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategoris,id',
            'id_satuan'   => $isCek ? 'nullable|exists:satuans,id' : 'required|exists:satuans,id',
            'harga_beli'  => $isCek ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'harga_jual'  => $isCek ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'stok_awal'   => 'nullable|integer|min:0',
            'barang_datang'=> 'nullable|integer|min:0',
            'expired_date'=> 'nullable|date',
            'deskripsi'   => 'nullable|string',
            'cara_pakai'  => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $obatData = $request->except(['stok_awal', 'barang_datang', 'expired_date', 'gambar']);
        
        // Default values for CEK category if empty
        if ($isCek) {
            if (!$request->filled('id_satuan')) {
                $obatData['id_satuan'] = \App\Models\Satuan::first()->id;
            }
            if (!$request->filled('harga_beli')) {
                $obatData['harga_beli'] = 0;
            }
            if (!$request->filled('harga_jual')) {
                $obatData['harga_jual'] = 0;
            }
        }
        $obatData['batas_stok_minimal'] = 5;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/obat'), $filename);
            $obatData['gambar'] = 'images/obat/' . $filename;
        }

        $obat = Obat::create($obatData);

        $stokAwal = $request->input('stok_awal', 0);
        $barangDatang = $request->input('barang_datang', 0);
        $sisaStok = $stokAwal + $barangDatang;

        // Inject initial stok directly to StokBatch table if user filled it
        if ($sisaStok > 0) {
            $expiredDate = $request->filled('expired_date') ? $request->expired_date : Carbon::now()->addYears(2)->format('Y-m-d');
            StokBatch::create([
                'id_obat' => $obat->id,
                'no_batch' => 'BATCH-INIT-' . time(),
                'tgl_expired' => $expiredDate, 
                'stok_awal' => $stokAwal,
                'stok_sisa' => $sisaStok,
            ]);
            
            // If there's barang datang, we might want to log it if needed, but for now we just add it to initial batch.
        }

        return redirect()->back()->with('success', 'Data Obat berhasil ditambahkan!');
    }

    public function edit(Obat $obat)
    {
        return redirect()->route('obat.index');
    }

    public function update(Request $request, Obat $obat)
    {
        $isCek = \App\Models\Kategori::where('id', $request->id_kategori)->where('nama_kategori', 'CEK')->exists();

        $request->validate([
            'kode_obat'   => 'required|string|unique:obats,kode_obat,'.$obat->id,
            'nama_obat'   => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategoris,id',
            'id_satuan'   => $isCek ? 'nullable|exists:satuans,id' : 'required|exists:satuans,id',
            'harga_beli'  => $isCek ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'harga_jual'  => $isCek ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'stok_awal'   => 'nullable|integer|min:0',
            'barang_datang'=> 'nullable|integer|min:0',
            'expired_date'=> 'nullable|date',
            'deskripsi'   => 'nullable|string',
            'cara_pakai'  => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $obatData = $request->except(['stok_awal', 'barang_datang', 'expired_date', 'gambar']);

        // Default values for CEK category if empty
        if ($isCek) {
            if (!$request->filled('id_satuan')) {
                $obatData['id_satuan'] = \App\Models\Satuan::first()->id;
            }
            if (!$request->filled('harga_beli')) {
                $obatData['harga_beli'] = 0;
            }
            if (!$request->filled('harga_jual')) {
                $obatData['harga_jual'] = 0;
            }
        }
        $obatData['batas_stok_minimal'] = 5;

        if ($request->hasFile('gambar')) {
            if ($obat->gambar && file_exists(public_path($obat->gambar))) {
                unlink(public_path($obat->gambar));
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/obat'), $filename);
            $obatData['gambar'] = 'images/obat/' . $filename;
        }

        $obat->update($obatData);

        // Handle direct edit stok
        $stokAwal = $request->input('stok_awal', 0);
        $barangDatang = $request->input('barang_datang', 0);
        $sisaStok = $stokAwal + $barangDatang;

        if ($request->filled('stok_awal') || $request->filled('barang_datang')) {
            // Find existing generic batch or get any first batch
            $batch = StokBatch::where('id_obat', $obat->id)->orderBy('created_at', 'asc')->first();
            $expiredDate = $request->filled('expired_date') ? $request->expired_date : Carbon::now()->addYears(2)->format('Y-m-d');
            
            if ($batch) {
                // Update existing batch
                $batch->update([
                    'stok_sisa' => $sisaStok,
                    'stok_awal' => $stokAwal, // Maybe we shouldn't overwrite existing stok_awal actually, but to be consistent with form we will
                    'tgl_expired' => $expiredDate,
                ]);
            } else if ($sisaStok > 0) {
                // Generate a new one if completely empty and user put > 0
                StokBatch::create([
                    'id_obat' => $obat->id,
                    'no_batch' => 'BATCH-ADJ-' . time(),
                    'tgl_expired' => $expiredDate,
                    'stok_awal' => $stokAwal,
                    'stok_sisa' => $sisaStok,
                ]);
            }
        } else if ($request->filled('expired_date')) {
             $batch = StokBatch::where('id_obat', $obat->id)->orderBy('created_at', 'asc')->first();
             if ($batch) {
                 $batch->update(['tgl_expired' => $request->expired_date]);
             }
        }

        return redirect()->back()->with('success', 'Data Obat berhasil diperbarui!');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();
        return redirect()->back()->with('success', 'Data Obat berhasil dihapus!');
    }

    public function saveStockOpname(Request $request)
    {
        $id_obat = $request->id_obat;
        $tanggal = $request->tanggal; // Expected Y-m-d
        $jumlah = $request->jumlah;

        \App\Models\StockOpname::updateOrCreate(
            ['id_obat' => $id_obat, 'tanggal' => $tanggal],
            ['jumlah' => $jumlah]
        );

        return response()->json(['success' => true]);
    }

    public function updateSODate(Request $request)
    {
        $id_so = $request->id_so;
        $tanggal = $request->tanggal;
        
        $so = \App\Models\StockOpname::find($id_so);
        if ($so) {
            // Cek apakah tanggal baru bertabrakan dengan record obat yang sama
            $exists = \App\Models\StockOpname::where('id_obat', $so->id_obat)
                        ->where('tanggal', $tanggal)
                        ->where('id', '!=', $id_so)
                        ->exists();
            
            if($exists) {
                return response()->json(['success' => false, 'message' => 'Sudah ada catatan di tanggal tersebut.'], 422);
            }

            $so->update(['tanggal' => $tanggal]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function getSOData(Request $request, $id)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;

        $obat = Obat::with(['stockOpnames' => function($q) use ($month, $year) {
                $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
            }])
            ->with(['penjualanDetails' => function($q) use ($month, $year) {
                $q->whereHas('penjualan', function($q2) use ($month, $year) {
                    $q2->whereMonth('tgl_penjualan', $month)->whereYear('tgl_penjualan', $year);
                });
            }])->findOrFail($id);
        
        $dailySO = [];
        for($i = 1; $i <= $daysInMonth; $i++) {
            $dailySO[$i] = 0;
        }
        foreach($obat->stockOpnames as $so) {
            $day = \Carbon\Carbon::parse($so->tanggal)->format('j');
            $dailySO[(int)$day] = $so->jumlah;
        }

        return response()->json([
            'daily_so' => $dailySO,
            'terjual_bulan_ini' => $obat->penjualanDetails->sum('qty'),
            'daysInMonth' => $daysInMonth
        ]);
    }

    public function cetakStokOpname(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $monthName = \Carbon\Carbon::create($year, $month)->translatedFormat('F Y');

        $obats = Obat::whereHas('kategori', function($q) {
                $q->where('nama_kategori', '!=', 'CEK');
            })
            ->with(['kategori', 'satuan', 'stokBatches'])
            ->with(['penjualanDetails' => function($q) use ($month, $year) {
                $q->whereHas('penjualan', function($q2) use ($month, $year) {
                    $q2->whereMonth('tgl_penjualan', $month)->whereYear('tgl_penjualan', $year);
                });
            }])
            ->with(['pembelianDetails' => function($q) use ($month, $year) {
                $q->whereHas('pembelian', function($q2) use ($month, $year) {
                    $q2->whereMonth('tgl_pembelian', $month)->whereYear('tgl_pembelian', $year);
                });
            }])
            ->with(['returPembelians' => function($q) use ($month, $year) {
                $q->whereMonth('tgl_retur', $month)->whereYear('tgl_retur', $year);
            }])
            ->with(['stockOpnames' => function($q) use ($month, $year) {
                $q->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
            }])
            ->get();

        foreach ($obats as $obat) {
            $obat->current_stok = $obat->stokBatches->sum('stok_sisa');
            $obat->masuk_bulan_ini = $obat->pembelianDetails->sum('qty');
            $obat->retur_bulan_ini = $obat->returPembelians->sum('qty_retur');
            $obat->terjual_bulan_ini = $obat->penjualanDetails->sum('qty');
            
            // Perkiraan Stok Awal
            $isCurrentMonth = ($month == date('m') && $year == date('Y'));
            if ($isCurrentMonth) {
                $obat->stok_awal = $obat->current_stok - $obat->masuk_bulan_ini + $obat->retur_bulan_ini + $obat->terjual_bulan_ini;
            } else {
                $obat->stok_awal = 0; // fallback untuk bulan lalu
            }

            // Stok Sistem Akhir (Expected)
            $obat->expected_stok = $obat->stok_awal + $obat->masuk_bulan_ini - $obat->retur_bulan_ini - $obat->terjual_bulan_ini;
            
            // Stok Fisik Akhir (Hasil SO terakhir di bulan itu)
            $soTerakhir = $obat->stockOpnames->sortByDesc('tanggal')->first();
            $obat->total_so = $soTerakhir ? $soTerakhir->jumlah : 0;
            
            // Selisih
            $obat->selisih = $obat->total_so - $obat->expected_stok;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('obat.pdf_so', compact('obats', 'monthName'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("Laporan_Stock_Opname_{$month}_{$year}.pdf");
    }
}
