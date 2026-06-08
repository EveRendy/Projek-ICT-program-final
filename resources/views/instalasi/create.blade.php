@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Catat Instalasi Software Baru</h2>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form action="{{ route('instalasi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Pilih Laboratorium</label>
                    <select name="no_lab" class="form-control" required>
                        <option value="">-- Pilih Lab --</option>
                        @foreach($laboratoriums as $lab)
                            <option value="{{ $lab->no_lab }}">{{ $lab->no_lab }} - {{ $lab->nama_lab }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Pilih Software</label>
                    <select name="id_software" class="form-control" required>
                        <option value="">-- Pilih Software --</option>
                        @foreach($softwares as $sw)
                            <option value="{{ $sw->id_software }}">{{ $sw->nama_software }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Status Lisensi</label>
                    <select name="status_lisensi" class="form-control" required>
                        <option value="free_license">Free License / Open Source</option>
                        <option value="license_active">License Active (Berbayar)</option>
                        <option value="license_expired">License Expired</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Aktif (Opsional)</label>
                        <input type="date" name="tgl_aktif" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tanggal Expired (Opsional)</label>
                        <input type="date" name="tgl_expired" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Catatan Instalasi</button>
                <a href="{{ route('instalasi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection