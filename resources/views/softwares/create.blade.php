<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 550px;">
        <div class="card shadow border-0 p-4">
            <h4 class="mb-4">Tambah Software Baru</h4>

            <form action="{{ route('softwares.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ID Software</label>
                    <input type="text" name="id_software" class="form-control" placeholder="Contoh: ADB01" value="{{ old('id_software') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Software</label>
                    <input type="text" name="nama_software" class="form-control" placeholder="Contoh: Adobe Photoshop" value="{{ old('nama_software') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Versi (Pisahkan dengan tanda koma)</label>
                    <input type="text" name="versi_raw" class="form-control" placeholder="Contoh: CC 2022, CC 2023, CC 2024" value="{{ old('versi_raw') }}" required>
                    <div class="form-text small text-muted">Gunakan tanda koma ( , ) untuk memasukkan lebih dari satu versi.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Level Kompatibilitas Spek Lab</label>
                    <select name="keterangan" class="form-select" required>
                        <option value="1">Level 1 (Bisa berjalan di PC Spek Standar / Low)</option>
                        <option value="2">Level 2 (Membutuhkan PC Spek Menengah)</option>
                        <option value="3">Level 3 (Wajib PC Spek Tinggi / Multimedia / Berat)</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100 mb-2">Simpan Master Software</button>
                    <a href="{{ route('softwares.index') }}" class="btn btn-secondary w-100 btn-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>