@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <div class="mb-3">
        <a href="{{ route('pengajuan.status') }}" class="btn btn-sm btn-light border rounded-pill px-3 text-secondary fw-medium">
            ← Kembali ke Status Pengajuan
        </a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-primary small fw-bold text-uppercase">Spesifikasi Permohonan</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">
                                {{ $pengajuan->software_id ? $pengajuan->software->nama_software : $pengajuan->software_lain }}
                            </h3>
                        </div>
                        <div>
                            @if($pengajuan->status_persetujuan == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">PENDING</span>
                            @elseif($pengajuan->status_persetujuan == 'disetujui')
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold">DISETUJUI</span>
                            @else
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold">DITOLAK</span>
                            @endif
                        </div>
                    </div>

                    <hr class="opacity-25 my-3">

                    <div class="row g-3 text-dark small">
                        <div class="col-6">
                            <span class="text-muted d-block mb-1">Mata Kuliah</span>
                            <strong class="text-dark">{{ $pengajuan->mata_kuliah }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block mb-1">Kelompok / Kelas</span>
                            <strong class="text-dark">{{ $pengajuan->kelompok_matkul }}</strong>
                        </div>
                        <div class="col-12">
                            <span class="text-muted d-block mb-1">Laboratorium Dituju</span>
                            <strong class="text-dark">Ruang {{ $pengajuan->laboratorium->no_lab ?? '-' }} — {{ $pengajuan->laboratorium->nama_lab }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block mb-1">Versi Yang Diminta</span>
                            <span class="badge bg-light text-secondary border px-2 py-1 fw-bold">
                                v.{{ $pengajuan->software_id ? $pengajuan->versi_requested : ($pengajuan->versi_lain ?? 'Asli') }}
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block mb-1">Tanggal Kirim Berkas</span>
                            <span class="fw-medium text-secondary">{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">Catatan Verifikasi Laboratorium</h5>

                    @if($pengajuan->status_persetujuan == 'ditolak')
                        <div class="alert alert-danger border-0 rounded-3 p-3 mb-0">
                            <h6 class="fw-bold text-danger mb-1">Alasan Penolakan Supervisor:</h6>
                            <p class="small text-dark mb-3">{{ $pengajuan->catatan_spv ?? 'Mohon maaf, spesifikasi hardware pada lab yang dituju belum memenuhi batas minimum sistem software.' }}</p>
                            
                            <div class="bg-white p-3 rounded-3 border small text-muted">
                                <strong class="text-warning">💡 Rekomendasi Ruang Alternatif:</strong><br>
                                Disarankan untuk mengajukan pemindahan instalasi ke ruang laboratorium komputer pusat dengan spesifikasi RAM dan core prosesor yang lebih memadai untuk modul kuliah ini.
                            </div>
                        </div>
                    @endif

                    @if($pengajuan->status_persetujuan == 'disetujui')
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-success mb-2">✓ Tanggapan Deployment Admin</h6>
                            
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Catatan Progres Lapangan:</small>
                                <p class="small text-secondary bg-white p-2 rounded border mb-0">
                                    "{{ $pengajuan->catatan_admin ?? 'Master file software sudah dikonfirmasi. Tim teknisi kami sedang melakukan instalasi berkala pada komputer client di laboratorium.' }}"
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($pengajuan->status_persetujuan == 'pending')
                        <div class="text-center py-5 text-muted">
                            <p class="small mb-0 px-2">Berkas permohonan instalasi Anda telah masuk ke sistem dan saat ini sedang berada dalam antrean pemeriksaan berkas kurikulum oleh <strong>Supervisor Laboratorium ICT</strong>.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</div>
@endsection