<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab-Install Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <div class="navbar-nav ms-auto align-items-center">
                <span class="nav-link text-white me-3 mb-0">Halo, {{ $user->nama }} (<strong>{{ ucfirst($role) }}</strong>)</span>
                
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger text-white">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm border-0 bg-white p-4">
                    <h3>Selamat Datang di Dashboard</h3>
                    <p class="text-muted">Nomor Induk: {{ $user->no_induk }} | Email: {{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            
            @if($role == 'supervisor')
                <div class="col-md-4 mb-3">
                    <div class="card border-start border-primary border-4 shadow-sm p-3">
                        <h5>Manajemen User</h5>
                        <p class="text-muted small">Kelola data Admin (Pengurus Lab) dan Dosen.</p>
                        <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm w-100">Buka Data User</a>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-start border-success border-4 shadow-sm p-3">
                        <h5>Laporan Global</h5>
                        <p class="text-muted small">Melihat seluruh statistik request instalasi software di lab.</p>
                        <a href="#" class="btn btn-success btn-sm w-100">Lihat Laporan</a>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-start border-warning border-4 shadow-sm p-3">
                        <h5>Log Aktivitas</h5>
                        <p class="text-muted small">Memantau riwayat sistem dan perubahan data.</p>
                        <a href="#" class="btn btn-warning btn-sm w-100">Lihat Log</a>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-start border-success border-4 shadow-sm p-3">
                        <h5>Manajemen Lab</h5>
                        <p class="text-muted small">Kelola data laboratorium, level spesifikasi, dan jumlah unit PC.</p>
                        <a href="{{ route('labs.index') }}" class="btn btn-success btn-sm w-100">Buka Data Lab</a>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-start border-warning border-4 shadow-sm p-3">
                        <h5>Master Software</h5>
                        <p class="text-muted small">Kelola repositori aplikasi resmi universitas, daftar versi, dan level bebannya.</p>
                        <a href="{{ route('softwares.index') }}" class="btn btn-warning btn-sm text-dark w-100">Buka Master Software</a>
                    </div>
                </div>

            @elseif($role == 'admin')
                <div class="col-md-6 mb-3">
                    <div class="card border-start border-info border-4 shadow-sm p-3">
                        <h5>Daftar Tugas Instalasi (Pending)</h5>
                        <p class="text-muted small">Ada request software dari dosen yang perlu kamu eksekusi di lab.</p>
                        <a href="#" class="btn btn-info text-white btn-sm w-100">Lihat Request Baru</a>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-start border-secondary border-4 shadow-sm p-3">
                        <h5>Riwayat Instalasi Selesai</h5>
                        <p class="text-muted small">Daftar software yang sudah sukses kamu instal di komputer laboratorium.</p>
                        <a href="#" class="btn btn-secondary btn-sm w-100">Lihat Riwayat</a>
                    </div>
                </div>

            @elseif($role == 'dosen')
                <div class="col-md-6 mb-3">
                    <div class="card border-start border-danger border-4 shadow-sm p-3">
                        <h5>Buat Pengajuan Baru</h5>
                        <p class="text-muted small">Butuh software tertentu untuk perkuliahan? Ajukan permohonan di sini.</p>
                        <a href="#" class="btn btn-danger btn-sm w-100">Form Request Software</a>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-start border-dark border-4 shadow-sm p-3">
                        <h5>Status Pengajuan Saya</h5>
                        <p class="text-muted small">Pantau apakah request kamu sudah diinstal oleh admin lab atau belum.</p>
                        <a href="#" class="btn btn-dark btn-sm w-100">Cek Status Request</a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>