<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Persetujuan Pengajuan - Supervisor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid my-5 px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Halaman Persetujuan Pengajuan (Supervisor)</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger py-2">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Dosen Pengaju</th>
                            <th>Mata Kuliah</th>
                            <th>Lab Tujuan</th>
                            <th>Software (Versi)</th>
                            <th>Status Kelayakan</th>
                            <th>Persetujuan SPV</th>
                            <th>Status Eksekusi Admin</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $p)
                        <tr>
                            <td class="ps-3">
                                <strong>{{ $p->dosen->nama }}</strong><br>
                                <small class="text-muted">Tgl: {{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d/m/Y') }}</small>
                            </td>
                            <td>{{ $p->mata_kuliah }} <br><small class="text-muted">Kelas: {{ $p->kelompok_matkul }}</small></td>
                            <td>
                                <strong>{{ $p->laboratorium->no_lab }}</strong><br>
                                <small class="text-muted">Spek Lvl {{ $p->laboratorium->level }}</small>
                            </td>
                            <td>
                                @if($p->software_id)
                                    {{ $p->software->nama_software }} ({{ $p->versi_requested }})
                                @else
                                    <span class="text-warning font-monospace">{{ $p->software_lain }} ({{ $p->versi_lain }}) *</span>
                                @endif
                            </td>
                            <td>
                                @if($p->software_id)
                                    @if($p->software->keterangan > $p->laboratorium->level)
                                        <span class="badge bg-danger">Under Spec (⚠️ Butuh Lvl {{ $p->software->keterangan }})</span>
                                    @else
                                        <span class="badge bg-success">Kompatibel</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Software Luar Master</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status_persetujuan == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($p->status_persetujuan == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status_persetujuan == 'pending')
                                    <span class="text-muted small">Menunggu SPV</span>
                                @elseif($p->status_persetujuan == 'ditolak')
                                    <span class="text-muted small">Batal</span>
                                @else
                                    <small class="d-block"><strong>Teknisi:</strong> {{ $p->admin->nama ?? 'Belum ada' }}</small>
                                    @if($p->status_progress == 'progress')
                                        <span class="badge bg-info text-dark">Sedang Diinstal</span>
                                    @elseif($p->status_progress == 'terinstal')
                                        <span class="badge bg-success">Sukses Terinstal</span>
                                    @else
                                        <span class="badge bg-danger" title="{{ $p->catatan_admin }}">Gagal Terinstal ℹ️</span>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->status_persetujuan == 'pending')
                                    <form action="{{ route('supervisor.pengajuan.setujui', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success px-3">Setujui</button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-danger px-3" data-bs-toggle="modal" data-bs-target="#tolakModal{{ $p->id }}">
                                        Tolak
                                    </button>
                                @else
                                    <span class="text-muted small font-italic">Selesai diproses</span>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="tolakModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Menolak Pengajuan</h5>
                                        <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('supervisor.pengajuan.tolak', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <p class="small text-muted">Berikan alasan penolakan agar dosen pengaju mengetahui kendalanya.</p>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan Penolakan</label>
                                                <textarea name="catatan_spv" class="form-control" rows="3" placeholder="Contoh: Maaf, laboratorium penuh digunakan praktikum s.d akhir semester." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger btn-sm">Kirim & Tolak</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data pengajuan masuk dari dosen.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>