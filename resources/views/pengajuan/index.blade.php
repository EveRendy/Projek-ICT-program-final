@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8 font-sans">
    
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-950">Riwayat Pengajuan</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Riwayat Pengajuan</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Pantau status persetujuan laboratorium dan progres instalasi software praktikum Anda secara berkala.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center justify-center gap-3 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/30">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Buat Pengajuan Baru</span>
                </a>
            </div>
        </div>
    </section>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 flex items-center gap-2 shadow-sm">
            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Tgl Ajuan</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Mata Kuliah</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Laboratorium</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Software (Versi)</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Status Approval</th>
                        <th class="px-6 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Status Instalasi</th>
                        <th class="px-6 py-4 text-center text-xs font-black uppercase tracking-[0.14em] text-slate-500">Bukti Dokumentasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($pengajuans as $p)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-600">
                                {{ \Carbon\Carbon::parse($p->tgl_pengajuan)->translatedFormat('d M Y') }}
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-950">{{ $p->mata_kuliah }}</div>
                                <div class="mt-1 flex items-center gap-1 text-xs font-medium text-slate-400">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Kelompok: {{ $p->kelompok_matkul }}
                                </div>
                            </td>
                            
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-mono font-bold text-slate-700">
                                    {{ $p->laboratorium->no_lab }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($p->software_id)
                                    <div class="font-bold text-slate-950">{{ $p->software->nama_software }}</div>
                                    <div class="mt-0.5 text-xs font-medium text-slate-500">Versi: {{ $p->versi_requested }}</div>
                                @else
                                    <div class="flex items-center gap-1.5 font-bold text-slate-700">
                                        {{ $p->software_lain }}
                                        <span class="rounded bg-amber-50 px-1.5 py-0.5 text-[10px] font-bold text-amber-700 ring-1 ring-amber-200">Custom</span>
                                    </div>
                                    <div class="mt-0.5 text-xs font-medium text-slate-500">Versi: {{ $p->versi_lain }}</div>
                                @endif
                            </td>
                            
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($p->status_persetujuan == 'pending')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @elseif($p->status_persetujuan == 'disetujui')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                    </span>
                                @else
                                    <div class="group relative inline-block">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 ring-1 ring-rose-200 cursor-help">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Ditolak
                                        </span>
                                        @if($p->catatan_spv)
                                            <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 w-48 -translate-x-1/2 rounded-xl bg-slate-950 p-2.5 text-center text-xs font-medium text-white opacity-0 shadow-xl transition-opacity group-hover:opacity-100">
                                                {{ $p->catatan_spv }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            
                            <td class="whitespace-nowrap px-6 py-4">
                                @if(!$p->status_progress)
                                    <span class="text-xs font-medium text-slate-400">—</span>
                                @elseif($p->status_progress == 'progress')
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-100">
                                        Dalam Proses
                                    </span>
                                @elseif($p->status_progress == 'terinstal')
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800">
                                        Selesai Terinstal
                                    </span>
                                @else
                                    <div class="group relative inline-block">
                                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-bold text-red-800 cursor-help">
                                            Gagal
                                        </span>
                                        @if($p->catatan_admin)
                                            <div class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 w-48 -translate-x-1/2 rounded-xl bg-slate-950 p-2.5 text-center text-xs font-medium text-white opacity-0 shadow-xl transition-opacity group-hover:opacity-100">
                                                {{ $p->catatan_admin }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                @if($p->dokumentasi)
                                    <a href="{{ $p->dokumentasi }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50 shadow-sm">
                                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Buka GDrive
                                    </a>
                                @else
                                    <span class="text-xs font-medium italic text-slate-400">Belum ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16">
                                <div class="mx-auto max-w-sm text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 shadow-inner">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    <h3 class="mt-4 text-lg font-black text-slate-950">Belum ada riwayat pengajuan</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">Kamu belum pernah membuat permohonan instalasi software.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>Menampilkan {{ $pengajuans->count() }} data pengajuan praktikum</p>
            <div class="flex items-center gap-1">
                <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400">Sebelumnya</button>
                <button type="button" class="rounded-lg bg-blue-950 px-3 py-1.5 font-bold text-white">1</button>
                <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400">Berikutnya</button>
            </div>
        </div>
    </section>
</div>
@endsection