<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan (PDF)</title>
    <style>
        body { font-family: Helvetica, sans-serif; font-size: 11pt; color: #000; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; }
        .title { font-size: 16pt; font-weight: bold; margin: 0; color: #000; }
        .subtitle { font-size: 10pt; color: #000; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; font-size: 11pt; color: #000; }
        th { background-color: #ddd; font-weight: bold; text-align: center; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10pt; font-style: italic; color: #000; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">APOTEK HAKSA FARMA</h1>
        <p class="subtitle">Jl. Purwareja No.82, Dusun Rw. Gembol, Purworejo, Kec. Purwareja Klampok, Kab. Banjarnegara, Jawa Tengah 53474<br>
        {{ $customTitle ?? 'LAPORAN PENJUALAN HARIAN / BULANAN' }}<br>
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">Tanggal</th>
                <th width="150">Nama Barang</th>
                <th width="80">Harga Satuan</th>
                <th width="50">Stok Terkini</th>
                <th width="40">Qty</th>
                <th width="90">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $grandTotal = 0; @endphp
            @forelse($penjualans as $trx)
                @foreach($trx->details as $detail)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($trx->tgl_penjualan)->format('d-m-Y') }}</td>
                    <td>{{ $detail->obat->nama_obat ?? 'Barang Terhapus' }}</td>
                    <td class="text-center">Rp. {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->obat->total_stok ?? 0 }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-center">Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @php $grandTotal += $detail->subtotal; @endphp
                @endforeach
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Belum ada data penjualan pada rentang periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #eee;">
                <td colspan="6" class="text-right" style="font-weight: bold; padding-right: 15px; color: #000;">TOTAL PENDAPATAN :</td>
                <td class="text-center" style="font-weight: bold; color: #000;">Rp. {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div style="margin-top: 50px; text-align: right; font-size: 11pt;">
        Dicetak oleh: {{ auth()->user()->nama ?? 'Admin/Sistem' }}<br>
        Pada: {{ now()->format('d M Y H:i:s') }}
    </div>

    <!-- DOMPdf fitur footer nomor halaman -->
    <script type="text/php">
        if ( isset($pdf) ) {
            $x = 520;
            $y = 820;
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "italic");
            $size = 9;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>
</html>
