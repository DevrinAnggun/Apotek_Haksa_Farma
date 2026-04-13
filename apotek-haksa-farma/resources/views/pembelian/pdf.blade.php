<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Masuk Supplier (PDF)</title>
    <style>
        body { font-family: Helvetica, sans-serif; font-size: 10pt; color: #000; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .title { font-size: 16pt; font-weight: bold; margin: 0; color: #000; }
        .subtitle { font-size: 9pt; color: #000; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; color: #000; }
        th { background-color: #f1f1f1; font-weight: bold; text-align: center; font-size: 9pt; color: #000; }
        td { font-size: 9pt; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8pt; font-style: italic; color: #000; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">APOTEK HAKSA FARMA</h1>
        <p class="subtitle">Jl. Purwareja No.82, Dusun Rw. Gembol, Purworejo, Kec. Purwareja Klampok, Kab. Banjarnegara, Jawa Tengah 53474<br>
        LAPORAN STOK MASUK (SUPPLIER)
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">Tgl Terima</th>
                <th width="120">Supplier</th>
                <th width="150">Nama Barang</th>
                <th width="90">Tgl Kadaluarsa</th>
                <th width="40">Qty</th>
                <th width="90">Harga Beli</th>
                <th width="100">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($pembelians as $pembelian)
                @foreach($pembelian->details as $detail)
                @php 
                    $batch = $detail->obat->stokBatches()->where('id_pembelian', $pembelian->id)->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d/m/Y') }}</td>
                    <td>{{ $pembelian->supplier->nama_suplier ?? '-' }}</td>
                    <td><span class="font-bold">{{ $detail->obat->nama_obat ?? 'Barang Terhapus' }}</span></td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($batch->tgl_expired ?? now())->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-center">Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-center">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Belum ada riwayat stok masuk pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="7" class="text-right font-bold" style="color: #000;">TOTAL PENGADAAN :</td>
                <td class="text-center font-bold" style="color: #000;">Rp{{ number_format($totalPembelian, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div style="margin-top: 40px; text-align: right;">
        <p>Banjarnegara, {{ now()->format('d M Y') }}</p>
        <br><br><br>
        <p class="font-bold">( {{ auth()->user()->nama ?? 'Admin Apotek' }} )</p>
        <p style="font-size: 9pt;">Dicetak pada: {{ now()->format('H:i:s') }}</p>
    </div>

    <!-- Halaman -->
    <script type="text/php">
        if ( isset($pdf) ) {
            $x = 750;
            $y = 560;
            $text = "Hal {PAGE_NUM} / {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "italic");
            $size = 8;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>
