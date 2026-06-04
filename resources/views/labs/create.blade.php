<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Laboratorium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 500px;">
        <div class="card shadow border-0 p-4">
            <h4 class="mb-4">Tambah Ruang Lab</h4>

            @if ($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('labs.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nomor / Nama Lab</label>
                    <input type="text" name="no_lab" class="form-control" placeholder="Contoh: LAB01" value="{{ old('no_lab') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Level Spesifikasi Komputer</label>
                    <select name="level" class="form-select" required>
                        <option value="1">Level 1 (Spesifikasi Standar / Rendah)</option>
                        <option value="2">Level 2 (Spesifikasi Menengah)</option>
                        <option value="3">Level 3 (Spesifikasi Tinggi / Multimedia)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah PC</label>
                    <input type="number" name="jumlah_pc" class="form-control" placeholder="Contoh: 30" value="{{ old('jumlah_pc') }}" required>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100 mb-2">Simpan Ruang Lab</button>
                    <a href="{{ route('labs.index') }}" class="btn btn-secondary w-100 btn-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>