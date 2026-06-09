@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $role = $user->role ?? 'user';

    $statusClass = function ($status) {
        return match ($status) {
            'disetujui', 'terinstal' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'ditolak', 'gagal_terinstal' => 'bg-red-50 text-red-700 ring-red-100',
            'progress' => 'bg-blue-50 text-blue-700 ring-blue-100',
            default => 'bg-amber-50 text-amber-700 ring-amber-100',
        };
    };

    $maxPengajuan = max($totalPengajuan ?? 0, $menungguInstalasi ?? 0, $pengajuanDisetujui ?? 0, $pengajuanDitolak ?? 0, 1);
    $chartRows = [
        ['label' => 'Pending', 'value' => $menungguInstalasi ?? 0, 'color' => 'bg-amber-500'],
        ['label' => 'Disetujui', 'value' => $pengajuanDisetujui ?? 0, 'color' => 'bg-emerald-500'],
        ['label' => 'Ditolak', 'value' => $pengajuanDitolak ?? 0, 'color' => 'bg-red-500'],
        ['label' => 'Progress', 'value' => $sedangDiinstal ?? 0, 'color' => 'bg-blue-500'],
    ];
@endphp

<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1fr_320px] lg:items-center">
            <div>
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-blue-700">
                    {{ ucfirst($role) }} Workspace
                </div>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">
                    Selamat datang, {{ $user->nama ?? ucfirst($role) }}
                </h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Kelola pengajuan instalasi software laboratorium dengan tampilan ringkas, jelas, dan mudah dipantau.
                </p>
                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">Role: {{ ucfirst($role) }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">ID: {{ $user->no_induk }}</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-sm font-bold text-slate-500">Ringkasan Hari Ini</p>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <p class="text-4xl font-black tracking-tight text-blue-950">{{ $pengajuanHariIni ?? 0 }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-600">Pengajuan baru</p>
                    </div>
                    <div class="rounded-2xl bg-blue-950 p-4 text-white">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5M9 17a3 3 0 006 0"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($role === 'supervisor')
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-slate-500">Total Pengajuan</p>
                <p class="mt-3 text-3xl font-black text-slate-950">{{ $totalPengajuan ?? 0 }}</p>
                <p class="mt-3 text-sm text-slate-500">Semua request dosen</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">Pengajuan Menunggu</p>
                <p class="mt-3 text-3xl font-black text-amber-950">{{ $menungguInstalasi ?? 0 }}</p>
                <p class="mt-3 text-sm text-amber-700">Butuh persetujuan</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">Pengajuan Disetujui</p>
                <p class="mt-3 text-3xl font-black text-emerald-950">{{ $pengajuanDisetujui ?? 0 }}</p>
                <p class="mt-3 text-sm text-emerald-700">Sudah masuk proses</p>
            </article>
            <article class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-blue-700">Sedang Diinstal</p>
                <p class="mt-3 text-3xl font-black text-blue-950">{{ $sedangDiinstal ?? 0 }}</p>
                <p class="mt-3 text-sm text-blue-700">Pekerjaan berjalan</p>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950">Grafik Statistik Pengajuan</h3>
                        <p class="mt-1 text-sm text-slate-500">Distribusi status pengajuan saat ini.</p>
                    </div>
                </div>
                <div class="mt-6 space-y-5">
                    @foreach($chartRows as $row)
                        @php $width = ($row['value'] / $maxPengajuan) * 100; @endphp
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-semibold text-slate-700">{{ $row['label'] }}</span>
                                <span class="font-bold text-slate-950">{{ $row['value'] }}</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $row['color'] }}" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-slate-950">Aktivitas Terbaru</h3>
                <div class="mt-5 space-y-3">
                    @forelse($aktivitasTerbaru as $item)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-slate-950">{{ $item->mata_kuliah }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->dosen->nama ?? 'Dosen' }} - {{ $item->laboratorium->no_lab ?? 'Lab' }}</p>
                                </div>
                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-bold ring-1 {{ $statusClass($item->status_persetujuan) }}">{{ $item->status_persetujuan }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </div>
        </section>
    @elseif($role === 'admin')
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-slate-500">Total Instalasi</p>
                <p class="mt-3 text-3xl font-black text-slate-950">{{ $adminTotalInstalasi ?? 0 }}</p>
                <p class="mt-3 text-sm text-slate-500">Ditugaskan ke Anda</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">Instalasi Selesai</p>
                <p class="mt-3 text-3xl font-black text-emerald-950">{{ $adminInstalasiSelesai ?? 0 }}</p>
                <p class="mt-3 text-sm text-emerald-700">Status terinstal</p>
            </article>
            <article class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-blue-700">Instalasi Berjalan</p>
                <p class="mt-3 text-3xl font-black text-blue-950">{{ $adminInstalasiBerjalan ?? 0 }}</p>
                <p class="mt-3 text-sm text-blue-700">Sedang diproses</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">Instalasi Pending</p>
                <p class="mt-3 text-3xl font-black text-amber-950">{{ $adminInstalasiPending ?? 0 }}</p>
                <p class="mt-3 text-sm text-amber-700">Menunggu update</p>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_1fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-slate-950">Progress Instalasi Terbaru</h3>
                <div class="mt-5 space-y-3">
                    @forelse($tugasTerbaru as $item)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-950">{{ $item->software->nama_software ?? $item->software_lain ?? 'Software' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->laboratorium->no_lab ?? 'Lab' }} - {{ $item->mata_kuliah }}</p>
                                </div>
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold ring-1 {{ $statusClass($item->status_progress ?? 'pending') }}">{{ $item->status_progress ?? 'pending' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">Belum ada tugas instalasi.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-slate-950">Ringkasan Sistem</h3>
                <div class="mt-5 grid gap-3">
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
                        <span class="text-sm font-semibold text-slate-600">Master software</span>
                        <span class="text-sm font-black text-slate-950">{{ $totalSoftware ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
                        <span class="text-sm font-semibold text-slate-600">Total lab</span>
                        <span class="text-sm font-black text-slate-950">{{ $totalLaboratorium ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
                        <span class="text-sm font-semibold text-slate-600">Catatan lisensi</span>
                        <span class="text-sm font-black text-slate-950">{{ $totalInstalasi ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-bold text-slate-500">Total Pengajuan</p>
                <p class="mt-3 text-3xl font-black text-slate-950">{{ $dosenTotalPengajuan ?? 0 }}</p>
                <p class="mt-3 text-sm text-slate-500">Pengajuan Anda</p>
            </article>
            <article class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-emerald-700">Disetujui</p>
                <p class="mt-3 text-3xl font-black text-emerald-950">{{ $dosenPengajuanDisetujui ?? 0 }}</p>
                <p class="mt-3 text-sm text-emerald-700">Siap diproses admin</p>
            </article>
            <article class="rounded-2xl border border-amber-100 bg-amber-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-amber-700">Pending</p>
                <p class="mt-3 text-3xl font-black text-amber-950">{{ $dosenPengajuanPending ?? 0 }}</p>
                <p class="mt-3 text-sm text-amber-700">Menunggu supervisor</p>
            </article>
            <article class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm">
                <p class="text-sm font-bold text-red-700">Ditolak</p>
                <p class="mt-3 text-3xl font-black text-red-950">{{ $dosenPengajuanDitolak ?? 0 }}</p>
                <p class="mt-3 text-sm text-red-700">Perlu revisi</p>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_0.85fr]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-950">Status Pengajuan Terbaru</h3>
                        <p class="mt-1 text-sm text-slate-500">Pantau perkembangan request software Anda.</p>
                    </div>
                    <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900">Buat Pengajuan</a>
                </div>
                <div class="mt-5 space-y-3">
                    @forelse($pengajuanTerbaruDosen as $item)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-950">{{ $item->software->nama_software ?? $item->software_lain ?? 'Software' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $item->mata_kuliah }} - {{ $item->laboratorium->no_lab ?? 'Lab' }}</p>
                                </div>
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold ring-1 {{ $statusClass($item->status_persetujuan) }}">{{ $item->status_persetujuan }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">Anda belum membuat pengajuan.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-slate-950">Panduan Singkat</h3>
                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm font-bold text-slate-950">1. Pilih software</p>
                        <p class="mt-1 text-sm text-slate-500">Gunakan daftar master atau isi software lain.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm font-bold text-slate-950">2. Pilih laboratorium</p>
                        <p class="mt-1 text-sm text-slate-500">Sistem menampilkan peringatan kompatibilitas.</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-sm font-bold text-slate-950">3. Pantau status</p>
                        <p class="mt-1 text-sm text-slate-500">Cek status pengajuan setelah dikirim.</p>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection
