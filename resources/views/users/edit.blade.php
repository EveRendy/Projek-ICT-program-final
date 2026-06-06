<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5" style="max-width: 600px;">
        <div class="card shadow border-0 p-4">
            <h4 class="mb-4">Edit Data User</h4>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-3">
                    <label class="form-label">No Induk</label>
                    <input type="text" name="no_induk" class="form-control" value="{{ $user->no_induk }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password (Kosongkan jika tidak diganti)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin (Pengurus Lab)</option>
                        <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role Akses</label>
                    <select name="role" id="role_select" class="form-select" onchange="toggleLabDropdown()" required>
                        <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Lab (Teknisi)</option>
                    </select>
                </div>

                <!-- DROPDOWN LAB EDIT -->
                <div class="mb-3 d-none" id="lab_assignment_container">
                    <label class="form-label text-primary"><strong>Tugaskan di Laboratorium (Khusus Admin)</strong></label>
                    <select name="laboratorium_id" class="form-select">
                        <option value="">-- Pilih Ruang Lab Tanggung Jawab --</option>
                        @foreach($laboratoriums as $lab)
                            <option value="{{ $lab->id }}" {{ (isset($currentLabId) && $currentLabId == $lab->id) ? 'selected' : '' }}>
                                {{ $lab->no_lab }} (Level {{ $lab->level }})
                                @if($lab->user_id && $lab->user_id != $user->id)
                                    - (Akan merebut tugas dari: {{ $lab->admin->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <script>
                    function toggleLabDropdown() {
                        const roleSelect = document.getElementById('role_select');
                        const labContainer = document.getElementById('lab_assignment_container');

                        if (roleSelect.value === 'admin') {
                            labContainer.classList.remove('d-none');
                        } else {
                            labContainer.classList.add('d-none');
                        }
                    }
                    window.onload = toggleLabDropdown;
                </script>
                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">Perbarui User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>