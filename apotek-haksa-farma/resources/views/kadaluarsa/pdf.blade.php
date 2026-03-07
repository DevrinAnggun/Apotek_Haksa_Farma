<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Kadaluarsa (PDF)</title>
    <style>
        body { font-family: Helvetica, sans-serif; font-size: 10pt; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .title { font-size: 16pt; font-weight: bold; margin: 0; }
        .subtitle { font-size: 9pt; color: #555; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #777; padding: 5px; text-align: left; color: #000; }
        th { background-color: #eee; font-weight: bold; text-align: center; font-size: 9pt; }
        td { font-size: 9pt; color: #000; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-red { background-color: #f3f3f3; color: #000; }
        .bg-orange { background-color: #ffffff; color: #000; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8pt; font-style: italic; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">APOTEK HAKSA FARMA</h1>
        <p class="subtitle">Jl. Purwareja No.82, Dusun Rw. Gembol, Purworejo, Kec. Purwareja Klampok, Kab. Banjarnegara, Jawa Tengah 53474<br>
        LAPORAN DATA OBAT KADALUARSA (H-7)<br>
        Per Tanggal: {{ date('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Obat</th>
                <th width="100">Kategori</th>
                <th width="70">Stok Sisa</th>
                <th width="100">Tgl Kadaluarsa</th>
                <th width="60">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($kadaluarsas as $item)
                @php
                    $now = \Carbon\Carbon::now();
                    $expired = \Carbon\Carbon::parse($item->earliest_expired);
                    $diffDays = (int)$now->diffInDays($expired, false);
                    
                    $statusText = $diffDays <= 0 ? 'Kadaluarsa' : 'H-' . $diffDays;
                    $rowStyle = $diffDays <= 0 ? 'bg-red' : ($diffDays <= 7 ? 'bg-orange' : '');
                @endphp
                <tr class="{{ $rowStyle }}">
                    <td class="text-center">{{ $no++ }}</td>
                    <td><span class="font-bold uppercase">{{ $item->obat->nama_obat ?? 'Barang Terhapus' }}</span></td>
                    <td>{{ $item->obat->kategori->nama_kategori ?? '-' }}</td>
                    <td class="text-center font-bold">{{ number_format($item->total_sisa, 0, ',', '.') }}</td>
                    <td class="text-center font-bold">{{ $expired->format('d/m/Y') }}</td>
                    <td class="text-center font-bold">{{ $statusText }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data obat yang kadaluarsa atau mendekati H-7.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 40px; text-align: right;">
        <p>Banjarnegara, {{ date('d M Y') }}</p>
        <br><br><br>
        <p class="font-bold">( {{ auth()->user()->nama ?? 'Admin Apotek' }} )</p>
        <p style="font-size: 9pt;">Dicetak pada: {{ date('H:i:s') }}</p>
    </div>

</body>
</html>
