<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <div class="card shadow border-0 p-4">
            <h4 class="mb-4">Tambah User Baru</h4>
            
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">No Induk (NIM / NIDN)</label>
                    <input type="text" name="no_induk" class="form-control" value="{{ old('no_induk') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" id="role_select" class="form-select" onchange="toggleLabDropdown()" required>
                        <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="lab_assignment_container">
                    <label class="form-label text-primary"><strong>Tugaskan di Laboratorium (Khusus Admin)</strong></label>
                    <select name="laboratorium_id" class="form-select">
                        <option value="">-- Pilih Ruang Lab Tanggung Jawab --</option>
                        @foreach($laboratoriums as $lab)
                            <option value="{{ $lab->id }}" {{ old('laboratorium_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->no_lab }} (Level {{ $lab->level }}) 
                                {{ $lab->admin ? '- Saat ini dijaga: '.$lab->admin->name : '(Belum ada admin)' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text text-muted small">Memilih lab yang sudah ada penjaganya akan menggantikan posisi admin lama di lab tersebut.</div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleLabDropdown() {
            const roleSelect = document.getElementById('role_select');
            const labContainer = document.getElementById('lab_assignment_container');

            if (roleSelect && roleSelect.value === 'admin') {
                labContainer.classList.remove('d-none');
            } else {
                labContainer.classList.add('d-none');
            }
        }
        
        // Jalankan saat halaman selesai dimuat sepenuhnya
        document.addEventListener('DOMContentLoaded', function() {
            toggleLabDropdown();
        });
    </script>
</body>
</html>