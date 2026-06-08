@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Daftar Tugas Instalasi Software</h2>
            <p class="text-muted small mb-0">Berikut adalah daftar software yang didelegasikan oleh Supervisor untuk kamu instal.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Dosen Pengaju</th>
                        <th>Laboratorium</th>
                        <th>Software & Versi</th>
                        <th>Tanggal Penugasan</th>
                        <th>Status Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugas as $index => $t)
                    <tr>
                        <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $t->dosen->nama }}</strong><br>
                            <small class="text-muted">{{ $t->mata_kuliah }} (Kelas: {{ $t->kelompok_matkul }})</small>
                        </td>
                        <td>
                            <span class="badge bg-dark">Lab: {{ $t->laboratorium->no_lab }}</span>
                        </td>
                        <td>
                            @if($t->software_id)
                                <strong>{{ $t->software->nama_software }}</strong> <span class="text-muted">({{ $t->versi_requested }})</span>
                            @else
                                <strong class="text-warning">{{ $t->software_lain }}</strong> <span class="text-muted">({{ $t->versi_lain }}) *</span>
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($t->tgl_penugasan)->format('d M Y') }}
                        </td>
                        <td>
                            @if($t->status_progress == 'progress')
                                <span class="badge bg-info text-dark">⏳ Sedang Dikerjakan</span>
                            @elseif($t->status_progress == 'terinstal')
                                <span class="badge bg-success">✅ Selesai Terinstal</span>
                            @else
                                <span class="badge bg-danger" title="{{ $t->catatan_admin }}">❌ Gagal / Tertunda</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            🎉 Bagus! Belum ada tugas instalasi baru untukmu saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection