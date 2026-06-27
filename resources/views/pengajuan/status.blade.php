@extends('layouts.app')

@section('content')
<div class="w-full px-4 py-4 sm:px-6 lg:px-8">
    
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col">
                <span class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Dashboard / Status Pengajuan</span>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Status Pengajuan Software</h2>
                <p class="mt-2 text-sm text-slate-500">Menampilkan data pelacakan rekam jejak persetujuan serta proses instalasi sistem laboratorium Anda.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('pengajuan.create') }}" 
                   class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Pengajuan
                </a>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        {{-- Table for large screens --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-700">
                    <tr>
                        <th scope="col" class="w-[35%] px-6 py-4 font-bold">SOFTWARE</th>
                        <th scope="col" class="w-[25%] px-6 py-4 font-bold">TANGGAL PENGAJUAN</th>
                        <th scope="col" class="w-[20%] px-6 py-4 text-center font-bold">STATUS</th>
                        <th scope="col" class="w-[20%] px-6 py-4 text-center font-bold">AKSI</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-slate-100 bg-white">
                    @if($pengajuans->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-32 text-center">
                                <h5 class="mb-1 text-base font-semibold text-slate-900">Belum ada riwayat pengajuan</h5>
                                <p class="text-sm text-slate-500">Anda belum pernah membuat permohonan instalasi software.</p>
                            </td>
                        </tr>
                    @else
                        @foreach($pengajuans as $p)
                            <tr class="transition-colors hover:bg-slate-50">
                                
                                <td class="px-6 py-4">
                                    <span class="block font-semibold text-slate-900">
                                        {{ $p->software_id ? $p->software->nama_software : $p->software_lain }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        v.{{ $p->software_id ? $p->versi_requested : ($p->versi_lain ?? 'Asli') }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 font-medium text-slate-600">
                                    {{ \Carbon\Carbon::parse($p->tgl_pengajuan)->translatedFormat('d F Y') }}
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if($p->status_persetujuan == 'pending')
                                        <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-600">
                                            Menunggu
                                        </span>
                                    @elseif($p->status_persetujuan == 'ditolak')
                                        <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-600">
                                            Ditolak
                                        </span>
                                    @elseif($p->status_progress === 'terinstal' && $p->status_verifikasi === 'disetujui')
                                        <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            Terinstal
                                        </span>
                                    @elseif($p->status_progress === 'gagal_terinstal' && $p->status_verifikasi === 'disetujui')
                                        <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-600">
                                            Gagal Terinstal
                                        </span>
                                    @elseif($p->status_verifikasi === 'menunggu')
                                        <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Verif.
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                            Diproses
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('pengajuan.showStatus', $p->id) }}" 
                                       class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Cards for small screens --}}
        <div class="sm:hidden p-4 space-y-4">
            @if($pengajuans->isEmpty())
                <div class="text-center py-10">
                    <h5 class="mb-1 text-base font-semibold text-slate-900">Belum ada riwayat pengajuan</h5>
                    <p class="text-sm text-slate-500">Anda belum pernah membuat permohonan instalasi software.</p>
                </div>
            @else
                @foreach($pengajuans as $p)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:bg-slate-50">
                        <div class="mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">SOFTWARE</span>
                            <div class="mt-1">
                                <span class="block font-semibold text-slate-900">
                                    {{ $p->software_id ? $p->software->nama_software : $p->software_lain }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    v.{{ $p->software_id ? $p->versi_requested : ($p->versi_lain ?? 'Asli') }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">TANGGAL PENGAJUAN</span>
                            <div class="mt-1 font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($p->tgl_pengajuan)->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">STATUS</span>
                            <div class="mt-1">
                                @if($p->status_persetujuan == 'pending')
                                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-600">
                                        Menunggu
                                    </span>
                                @elseif($p->status_persetujuan == 'ditolak')
                                    <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-600">
                                        Ditolak
                                    </span>
                                @elseif($p->status_progress === 'terinstal' && $p->status_verifikasi === 'disetujui')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        Terinstal
                                    </span>
                                @elseif($p->status_progress === 'gagal_terinstal' && $p->status_verifikasi === 'disetujui')
                                    <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-600">
                                        Gagal Terinstal
                                    </span>
                                @elseif($p->status_verifikasi === 'menunggu')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Verif.
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Diproses
                                    </span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('pengajuan.showStatus', $p->id) }}" 
                           class="w-full inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-slate-200">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
        
        <div class="flex flex-col gap-3 border-t border-slate-100 bg-white px-6 py-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>Menampilkan {{ $pengajuans->count() }} status pengajuan</p>
            <div class="flex items-center gap-1">
                <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400">Sebelumnya</button>
                <button type="button" class="rounded-lg bg-blue-950 px-3 py-1.5 font-bold text-white">1</button>
                <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400">Berikutnya</button>
            </div>
        </div>
        
    </div>
</div>
@endsection