<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengajuan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Riwayat Pengajuan Instalasi Software</h2>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Dashboard</a>
                <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">Buat Pengajuan Baru</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Tgl Ajuan</th>
                            <th>Mata Kuliah</th>
                            <th>Lab</th>
                            <th>Software (Versi)</th>
                            <th>Status Approval</th>
                            <th>Status Instalasi</th>
                            <th>Bukti Dokumentasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $p)
                        <tr>
                            <td class="ps-3">{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d/m/Y') }}</td>
                            <td>{{ $p->mata_kuliah }} <br><small class="text-muted">Kelompok: {{ $p->kelompok_matkul }}</small></td>
                            <td><strong>{{ $p->laboratorium->no_lab }}</strong></td>
                            <td>
                                @if($p->software_id)
                                    {{ $p->software->nama_software }} ({{ $p->versi_requested }})
                                @else
                                    <span class="text-warning">{{ $p->software_lain }} ({{ $p->versi_lain }}) *</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status_persetujuan == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($p->status_persetujuan == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger" title="{{ $p->catatan_spv }}">Ditolak ℹ️</span>
                                @endif
                            </td>
                            <td>
                                @if(!$p->status_progress)
                                    <span class="text-muted small">-</span>
                                @elseif($p->status_progress == 'progress')
                                    <span class="badge bg-info text-dark">Dalam Proses</span>
                                @elseif($p->status_progress == 'terinstal')
                                    <span class="badge bg-success">Selesai Terinstal</span>
                                @else
                                    <span class="badge bg-danger" title="{{ $p->catatan_admin }}">Gagal ℹ️</span>
                                @endif
                            </td>
                            <td>
                                @if($p->dokumentasi)
                                    <a href="{{ $p->dokumentasi }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2 small">Buka GDrive</a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Kamu belum pernah membuat pengajuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>