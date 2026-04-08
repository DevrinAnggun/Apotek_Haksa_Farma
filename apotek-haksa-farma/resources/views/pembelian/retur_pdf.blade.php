<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Retur Pembelian (PDF)</title>
    <style>
        body { font-family: Helvetica, sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .title { font-size: 16pt; font-weight: bold; margin: 0; }
        .subtitle { font-size: 9pt; color: #555; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #777; padding: 5px; text-align: left; }
        th { background-color: #f1f1f1; font-weight: bold; text-align: center; font-size: 9pt; }
        td { font-size: 9pt; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8pt; font-style: italic; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">APOTEK HAKSA FARMA</h1>
        <p class="subtitle">Jl. Purwareja No.82, Dusun Rw. Gembol, Purworejo, Kec. Purwareja Klampok, Kab. Banjarnegara, Jawa Tengah 53474<br>
        LAPORAN RETUR OBAT KADALUARSA (SUPPLIER)<br>
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="70">Tgl Retur</th>
                <th width="100">Supplier</th>
                <th width="130">Nama Barang</th>
                <th width="30">Qty</th>
                <th width="130">Alasan</th>
                <th width="80">Foto Bukti</th>
                <th width="100">Total Potongan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($returs as $retur)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($retur->tgl_retur)->format('d/m/Y') }}</td>
                    <td>{{ $retur->pembelian->supplier->nama_suplier ?? '-' }}</td>
                    <td><span class="font-bold">{{ $retur->obat->nama_obat ?? 'Barang Terhapus' }}</span></td>
                    <td class="text-center">{{ $retur->qty_retur }}</td>
                    <td>{{ $retur->alasan }}</td>
                    <td class="text-center">
                        @if($retur->foto && file_exists(public_path($retur->foto)))
                            <img src="{{ public_path($retur->foto) }}" style="max-width: 70px; max-height: 70px;" alt="">
                        @else
                            <span style="color: #999; font-style: italic;">Tidak ada</span>
                        @endif
                    </td>
                    <td class="text-center">Rp{{ number_format($retur->nominal_potongan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Belum ada riwayat retur pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="7" class="text-right font-bold">TOTAL POTONGAN :</td>
                <td class="text-center font-bold" style="color: #166534;">Rp{{ number_format($totalPotongan, 0, ',', '.') }}</td>
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
