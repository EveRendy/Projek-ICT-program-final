@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-0 bg-white p-4">
                <h3>Selamat Datang di Dashboard</h3>
                <p class="text-muted">Nomor Induk: {{ Auth::user()->no_induk }} | Email: {{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        
        @if(Auth::user()->role === 'supervisor')
            <div class="col-md-4 mb-3">
                <div class="card border-start border-primary border-4 shadow-sm p-3 h-100">
                    <h5>Manajemen User</h5>
                    <p class="text-muted small">Kelola data Admin (Pengurus Lab) dan Dosen.</p>
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm w-100 mt-auto">Buka Data User</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start border-primary border-4 shadow-sm p-3 h-100">
                    <h5>Persetujuan Pengajuan</h5>
                    <p class="text-muted small">Periksa pengajuan software dari dosen dan delegasikan penugasan admin.</p>
                    <a href="{{ route('supervisor.pengajuan.index') }}" class="btn btn-primary btn-sm w-100 mt-auto">Buka Menu Approval</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start border-success border-4 shadow-sm p-3 h-100">
                    <h5>Manajemen Lab</h5>
                    <p class="text-muted small">Kelola data laboratorium, level spesifikasi, dan jumlah unit PC.</p>
                    <a href="{{ route('labs.index') }}" class="btn btn-success btn-sm w-100 mt-auto">Buka Data Lab</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start border-warning border-4 shadow-sm p-3 h-100">
                    <h5>Master Software</h5>
                    <p class="text-muted small">Kelola repositori aplikasi resmi universitas, daftar versi, dan level bebannya.</p>
                    <a href="{{ route('softwares.index') }}" class="btn btn-warning btn-sm text-dark w-100 mt-auto">Buka Master Software</a>
                </div>
            </div>
        @endif

        @can('is-admin')
            <div class="col-md-4 mb-3">
                <div class="card border-start border-info border-4 shadow-sm p-3 h-100">
                    <h5>Daftar Tugas Instalasi</h5>
                    <p class="text-muted small">Ada request software dari dosen yang perlu kamu eksekusi di lab.</p>
                    <a href="{{ route('admin.tugas.index') }}" class="btn btn-info text-white btn-sm w-100 mt-auto">Lihat Request Baru</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start border-secondary border-4 shadow-sm p-3 h-100">
                    <h5>Riwayat Instalasi Selesai</h5>
                    <p class="text-muted small">Daftar software yang sudah sukses diinstal di laboratorium.</p>
                    <a href="#" class="btn btn-secondary btn-sm w-100 mt-auto">Lihat Riwayat</a>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center shadow-sm border-info h-100 p-2">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-info">Tracker Lisensi</h5>
                        <p class="card-text small text-muted">Kelola inventaris software yang telah terinstal serta pantau masa aktif lisensinya.</p>
                        <a href="{{ route('instalasi.index') }}" class="btn btn-info text-white btn-sm w-100 mt-auto">Kelola Lisensi Lab</a>
                    </div>
                </div>
            </div>
        @endcan

        @can('is-dosen')
            <div class="col-md-6 mb-3">
                <div class="card border-start border-primary border-4 shadow-sm p-3 h-100">
                    <h5>Pengajuan Instalasi</h5>
                    <p class="text-muted small">Ajukan software perkuliahan baru ke laboratorium komputer terkait.</p>
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-primary btn-sm w-100 mt-auto">Buka Pengajuan</a>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card border-start border-dark border-4 shadow-sm p-3 h-100">
                    <h5>Status Pengajuan Saya</h5>
                    <p class="text-muted small">Pantau apakah request kamu sudah diinstal oleh admin lab atau belum.</p>
                    <a href="#" class="btn btn-dark btn-sm w-100 mt-auto">Cek Status Request</a>
                </div>
            </div>
        @endcan

    </div>
</div>
@endsection