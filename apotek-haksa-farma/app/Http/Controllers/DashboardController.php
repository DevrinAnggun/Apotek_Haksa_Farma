<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\StokBatch;
use App\Models\Obat;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();
        $totalTransaksiHariIni = Penjualan::has('details')->whereDate('tgl_penjualan', $hariIni)->count();

        $totalRestockBulanIni = Pembelian::whereMonth('tgl_pembelian', Carbon::now()->month)
            ->whereYear('tgl_pembelian', Carbon::now()->year)
            ->sum('total_bayar');

        $obatStokMenipis = Obat::withSum('stokBatches as total_stok_global', 'stok_sisa')
            ->having('total_stok_global', '<=', DB::raw('batas_stok_minimal'))

            ->get()
            ->filter(function($obat) {
                return $obat->total_stok <= $obat->batas_stok_minimal;
            });

        $batasWaktuWarning = Carbon::now()->addMonths(5);

        $obatMendekatiExpired = StokBatch::whereHas('obat', function($q) {
                $q->whereNull('deleted_at');
            })
            ->with('obat')
            ->where('stok_sisa', '>', 0)
            ->whereBetween('tgl_expired', [Carbon::now(), $batasWaktuWarning])
            ->orderBy('tgl_expired', 'asc')
            ->get();
            
        $batasKadaluarsa = Carbon::now()->addMonths(5);
        $jumlahObatKadaluarsa = StokBatch::whereHas('obat', function($q) {
                $q->whereNull('deleted_at');
                $q->whereHas('kategori', function($q2) {
                    $q2->where('nama_kategori', '!=', 'CEK');
                });
            })
            ->where('stok_sisa', '>', 0)
            ->whereDate('tgl_expired', '<=', $batasKadaluarsa)
            ->distinct('id_obat')
            ->count('id_obat');

        $totalDataBarang = Obat::count();
        
        $penjualanValid = Penjualan::has('details');
        
        $totalPenjualan = $penjualanValid->count();
        $totalSemuaPenjualan = $penjualanValid->sum('total_harga');

        $obats = Obat::orderBy('nama_obat', 'asc')->get();

        return view('dashboard.index', compact(
            'totalTransaksiHariIni',
            'totalRestockBulanIni',
            'obatStokMenipis',
            'obatMendekatiExpired',
            'totalDataBarang',
            'totalPenjualan',
            'totalSemuaPenjualan',
            'jumlahObatKadaluarsa',
            'obats'
        ));
    }
}
