<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Laboratorium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Ruang Laboratorium</h2>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Dashboard</a>
                <a href="{{ route('labs.create') }}" class="btn btn-primary">Tambah Lab Baru</a>
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
                            <th class="ps-3">No Lab</th>
                            <th>Level Spek</th>
                            <th>Jumlah PC</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($labs as $lab)
                        <tr>
                            <td class="ps-3"><strong>{{ $lab->no_lab }}</strong></td>
                            <td>
                                @if($lab->level == 1)
                                    <span class="badge bg-danger">Level 1 (Low Spec)</span>
                                @elseif($lab->level == 2)
                                    <span class="badge bg-warning text-dark">Level 2 (Medium Spec)</span>
                                @else
                                    <span class="badge bg-success">Level 3 (High Spec)</span>
                                @endif
                            </td>
                            <td>{{ $lab->jumlah_pc }} Unit</td>
                            <td class="text-center">
                                <a href="{{ route('labs.edit', $lab->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                
                                <form action="{{ route('labs.destroy', $lab->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hakin ingin menghapus ruang lab ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data ruang laboratorium.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>