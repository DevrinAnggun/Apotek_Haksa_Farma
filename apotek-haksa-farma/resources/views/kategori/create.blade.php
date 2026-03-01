<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - Apotek</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .form-group { margin-bottom: 20px; }
        .form-control { padding: 8px; width: 300px; display: block; margin-top: 5px; }
        .btn { padding: 8px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        a { text-decoration: none; color: #007bff; margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>

    <h2>Form Tambah Kategori</h2>
    <a href="{{ route('kategori.index') }}">&leftarrow; Kembali ke Daftar Kategori</a>

    <!-- Menampilkan Error Validasi Laravel -->
    @if ($errors->any())
        <div class="alert-error">
            <strong>Opps! Ada kesalahan input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama_kategori"><strong>Nama Kategori</strong></label>
            <!-- value=old() ini adalah best practice agar jika gagal validasi, ketikan user sebelumnya tidak hilang -->
            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Contoh: Obat Bebas" value="{{ old('nama_kategori') }}">
        </div>

        <button type="submit" class="btn">Simpan Data</button>
    </form>

</body>
</html>
