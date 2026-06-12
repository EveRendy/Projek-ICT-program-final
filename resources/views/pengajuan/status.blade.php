@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <div class="card shadow-sm bg-white" style="border-radius: 20px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 25px;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <span class="text-muted small fw-medium text-uppercase d-block mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Dashboard / Status Pengajuan</span>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Status Pengajuan Software</h2>
                <p class="text-muted small mt-2 mb-0" style="font-size: 0.87rem; line-height: 1.5;">Menampilkan data pelacakan rekam jejak persetujuan serta proses instalasi sistem laboratorium Anda.</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm bg-white overflow-hidden" style="border-radius: 20px; border: 1px solid #e2e8f0;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="width: 100%; table-layout: fixed;">
                    
                    <thead class="text-uppercase small border-bottom" 
                           style="background-color: #f1f5f9; border-color: #cbd5e1; font-size: 0.8rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3 ps-4 text-start" style="width: 35%; font-weight: 700 !important; color: #334155;">SOFTWARE</th>
                            <th class="py-3 text-start" style="width: 25%; font-weight: 700 !important; color: #334155;">TANGGAL PENGAJUAN</th>
                            <th class="py-3 text-center" style="width: 20%; font-weight: 700 !important; color: #334155;">STATUS</th>
                            <th class="py-3 text-center" style="width: 20%; font-weight: 700 !important; color: #334155;">AKSI</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @if($pengajuans->isEmpty())
                            <tr style="height: 420px;">
                                <td colspan="4" class="text-center align-middle" style="border-bottom: none;">
                                    <h5 class="fw-bold text-dark mb-1" style="font-weight: 610 !important; font-size: 1rem;">Belum ada riwayat pengajuan</h5>
                                    <p class="text-muted small mb-0">Anda belum pernah membuat permohonan instalasi software.</p>
                                </td>
                            </tr>
                        @else
                            @foreach($pengajuans as $p)
                                <tr class="border-bottom" style="border-color: #f1f5f9;">
                                    <td class="py-3 ps-4 text-start">
                                        <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">
                                            {{ $p->software_id ? $p->software->nama_software : $p->software_lain }}
                                        </span>
                                        <small class="text-muted">v.{{ $p->software_id ? $p->versi_requested : ($p->versi_lain ?? 'Asli') }}</small>
                                    </td>
                                    
                                    <td class="py-3 text-start text-secondary small fw-medium">
                                        {{ \Carbon\Carbon::parse($p->tgl_pengajuan)->translatedFormat('d F Y') }}
                                    </td>
                                    
                                    <td class="py-3 text-center">
                                        @if($p->status_persetujuan == 'pending')
                                            <span class="badge rounded-pill px-3 py-2 text-warning bg-warning bg-opacity-10 border border-warning border-opacity-10 fw-bold" style="font-size: 0.75rem;">
                                                Menunggu
                                            </span>
                                        @elseif($p->status_persetujuan == 'disetujui')
                                            <span class="badge rounded-pill px-3 py-2 text-success bg-success bg-opacity-10 border border-success border-opacity-10 fw-bold" style="font-size: 0.75rem;">
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-3 py-2 text-danger bg-danger bg-opacity-10 border border-danger border-opacity-10 fw-bold" style="font-size: 0.75rem;">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-3 text-center">
                                        <a href="{{ route('pengajuan.showStatus', $p->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" style="font-size: 0.75rem;">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
            </div>
        <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>Menampilkan {{ $pengajuans->count() }} status pengajuan</p>
            <div class="flex items-center gap-1">
                <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400">Sebelumnya</button>
                <button type="button" class="rounded-lg bg-blue-950 px-3 py-1.5 font-bold text-white">1</button>
                <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400">Berikutnya</button>
            </div>
</div>
@endsection