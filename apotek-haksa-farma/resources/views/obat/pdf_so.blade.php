<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stock Opname - {{ $monthName }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #000; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; color: #000; font-size: 18pt; }
        .header p { margin: 5px 0; font-size: 10pt; color: #000; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main-table th { background-color: #eee; color: #000; font-weight: bold; text-transform: uppercase; font-size: 8pt; border: 1px solid #000; padding: 8px; }
        table.main-table td { border: 1px solid #000; padding: 8px; font-size: 9pt; color: #000; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .positive { color: #000; font-weight: bold; }
        .negative { color: #000; font-weight: bold; }
        
        .footer { margin-top: 40px; }
        .signature-box { float: right; width: 200px; text-align: center; }
        .signature-space { height: 60px; }
        
        .summary-box { margin-top: 20px; border: 1px solid #000; border-radius: 8px; padding: 15px; background-color: #fff; }
        .summary-box h4 { margin: 0 0 10px 0; color: #000; font-size: 10pt; text-transform: uppercase; }
        .summary-box p { margin: 0; font-size: 9pt; color: #000; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="header">
        <h2>APOTEK HAKSA FARMA</h2>
        <p>Laporan Rekapitulasi Selisih Stock Opname</p>
        <p>Periode Bulan: <strong>{{ $monthName }}</strong></p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="20">No</th>
                <th>Nama Obat</th>
                <th width="40">Satuan</th>
                <th width="50">Awal</th>
                <th width="50">Msk</th>
                <th width="50">Klr</th>
                <th width="50">Sis</th>
                <th width="50" style="background-color: #eee;">Fisik</th>
                <th width="50">Selisih</th>
                <th width="80">Harga Modal</th>
                <th width="90">Selisih (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotalSelisih = 0; @endphp
            @foreach($obats as $idx => $o)
            @php 
                $nominal = $o->selisih * $o->harga_beli; 
                $grandTotalSelisih += $nominal;
            @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="font-bold uppercase" style="font-size: 8pt;">{{ $o->nama_obat }}</td>
                <td class="text-center">{{ $o->satuan->nama_satuan ?? '-' }}</td>
                <td class="text-center">{{ number_format($o->stok_awal) }}</td>
                <td class="text-center">{{ number_format($o->masuk_bulan_ini) }}</td>
                <td class="text-center">{{ number_format($o->terjual_bulan_ini) }}</td>
                <td class="text-center font-bold">{{ number_format($o->expected_stok) }}</td>
                <td class="text-center font-bold">{{ number_format($o->total_so) }}</td>
                <td class="text-center {{ $o->selisih != 0 ? 'font-bold' : '' }}">
                    {{ $o->selisih > 0 ? '+' : '' }}{{ number_format($o->selisih) }}
                </td>
                <td class="text-right">Rp{{ number_format($o->harga_beli, 0, ',', '.') }}</td>
                <td class="text-right font-bold">
                    {{ $nominal == 0 ? '-' : ($nominal > 0 ? '+' : '-') . ' Rp' . number_format(abs($nominal), 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee;">
                <td colspan="10" class="text-right font-bold" style="padding: 10px;">TOTAL SELISIH KEUANGAN :</td>
                <td class="text-right font-bold" style="padding: 10px; font-size: 10pt;">
                    {{ $grandTotalSelisih == 0 ? 'Rp 0' : ($grandTotalSelisih > 0 ? '+' : '-') . ' Rp' . number_format(abs($grandTotalSelisih), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <h4>Catatan Penting</h4>
        <p>
            - <strong>Stok Sistem</strong> dihitung dari: (Stok Awal Bulan + Total Penerimaan - Total Penjualan).<br>
            - <strong>Stok Fisik</strong> adalah total akumulasi input pengecekan pada menu Stock Opname harian.<br>
            - <strong>Selisih (-)</strong> mengindikasikan kemungkinan barang hilang atau rusak yang belum tercatat.<br>
            - <strong>Selisih (+)</strong> mengindikasikan adanya barang masuk yang belum terdata di sistem pembelian.
        </p>
    </div>

    <div class="footer">
        <p style="font-size: 8pt; color: #000; float: left;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        <div class="signature-box">
            <p>Penanggung Jawab,</p>
            <div class="signature-space"></div>
            <p><strong>( ____________________ )</strong></p>
        </div>
    </div>
</body>
</html>
