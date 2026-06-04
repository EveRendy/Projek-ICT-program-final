<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Software</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Master Software</h2>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Dashboard</a>
                <a href="{{ route('softwares.create') }}" class="btn btn-primary">Tambah Software Baru</a>
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
                            <th class="ps-3">ID Software</th>
                            <th>Nama Software</th>
                            <th>Daftar Versi Tersedia</th>
                            <th>Min. Spek Lab</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($softwares as $item)
                        <tr>
                            <td class="ps-3"><strong>{{ $item->id_software }}</strong></td>
                            <td>{{ $item->nama_software }}</td>
                            <td>
                                @foreach($item->versi as $v)
                                    <span class="badge bg-info text-dark me-1">{{ $v }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($item->keterangan == 1)
                                    <span class="badge bg-danger">Level 1 (Low)</span>
                                @elseif($item->keterangan == 2)
                                    <span class="badge bg-warning text-dark">Level 2 (Medium)</span>
                                @else
                                    <span class="badge bg-success">Level 3 (High)</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('softwares.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                
                                <form action="{{ route('softwares.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus software ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data software yang terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>