@extends('layouts.app') @section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Inventaris & Tracker Lisensi Software</h2>
        @can('is-admin')
            <a href="{{ route('instalasi.create') }}" class="btn btn-primary">Catat Instalasi Baru</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No Lab</th>
                        <th>Software</th>
                        <th>Status Lisensi</th>
                        <th>Masa Aktif</th>
                        <th>Diinstal Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instalasis as $item)
                    <tr>
                        <td>{{ $item->laboratorium->nama_lab ?? $item->no_lab }}</td>
                        <td>{{ $item->software->nama_software ?? $item->id_software }}</td>
                        <td>
                            @if($item->status_lisensi == 'free_license')
                                <span class="badge bg-secondary">Free</span>
                            @elseif($item->status_lisensi == 'license_active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Expired</span>
                            @endif
                        </td>
                        <td>
                            @if($item->tgl_aktif && $item->tgl_expired)
                                {{ $item->tgl_aktif->format('d/m/Y') }} - {{ $item->tgl_expired->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Lifetime/Tidak ada batas</span>
                            @endif
                        </td>
                        <td>{{ $item->teknisi->name ?? 'Teknisi Tidak Diketahui' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection