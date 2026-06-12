@extends('layouts.app')

@section('content')
@php
    // Helper untuk mewarnai badge status secara dinamis
    $statusClass = function ($status) {
        return match (strtolower($status)) {
            'disetujui', 'terinstal', 'selesai', 'installed' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'ditolak', 'gagal_terinstal', 'terkendala', 'gagal' => 'bg-red-50 text-red-700 ring-red-100',
            'progress', 'on progress' => 'bg-blue-50 text-blue-700 ring-blue-100',
            default => 'bg-amber-50 text-amber-700 ring-amber-100',
        };
    };
@endphp

<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-950">Riwayat</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Riwayat Pengajuan & Pengerjaan</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Menampilkan seluruh data log rekam jejak instalasi sistem laboratorium.
                </p>
            </div>
            
            <div class="shrink-0 rounded-2xl border border-slate-200 bg-slate-50 px-6 py-4 text-center shadow-sm min-w-[180px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pengerjaan</p>
                <p class="mt-1 text-3xl font-black text-slate-900">{{ $summary['total'] ?? 0 }}</p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Menunggu Instalasi</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $summary['menunggu'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">On Progress</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ $summary['progress'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Selesai (Installed)</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $summary['selesai'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Gagal Terinstal</p>
            <p class="mt-2 text-3xl font-bold text-red-600">{{ $summary['gagal'] ?? 0 }}</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Software / Mata Kuliah</th>
                        <th class="px-6 py-4">Laboratorium</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengajuans as $item)
                        <tr class="transition hover:bg-slate-50/50">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600 font-medium">
                                {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-950">{{ $item->dosen->nama ?? 'Dosen' }}</div>
                                <div class="text-xs text-slate-500">ID: {{ $item->dosen->no_induk ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-950">
                                    {{ $item->software->nama_software ?? $item->software_lain ?? 'Software Tidak Diketahui' }}
                                </div>
                                <div class="text-xs text-slate-500 font-medium">
                                    Mata Kuliah: {{ $item->mata_kuliah ?? '-' }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-800">
                                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $item->laboratorium->nama_lab ?? 'LAB ' . ($item->no_lab ?? '-') }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                @php 
                                    $currentStatus = $item->status_progress ?? $item->status_persetujuan ?? 'pending';
                                @endphp
                                <span class="inline-block rounded-full px-3 py-1 text-xs font-bold ring-1 {{ $statusClass($currentStatus) }} capitalize">
                                    {{ str_replace('_', ' ', $currentStatus) }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <button type="button" onclick="toggleModal('modalDetailHistory{{ $item->id }}', true)" class="rounded-xl border border-slate-200 bg-white px-4 py-1.5 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 text-center cursor-pointer transition">
                                    Detail
                                </button>
                            </td>
                        </tr>

                        <div id="modalDetailHistory{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleModal('modalDetailHistory{{ $item->id }}', false)"></div>
                            <div class="flex min-h-full items-center justify-center p-4">
                                <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all w-full max-w-xl">
                                    
                                    <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between bg-slate-50">
                                        <div>
                                            <h3 class="text-base font-black text-slate-950">Rincian Lengkap Instalasi</h3>
                                            <p class="text-xs text-slate-400">Detail rekam jejak pemasangan software di laboratorium.</p>
                                        </div>
                                        <button type="button" onclick="toggleModal('modalDetailHistory{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    
                                    <div class="px-6 py-5 space-y-4 text-sm text-slate-700">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Software</span>
                                                <span class="font-extrabold text-slate-900">{{ $item->software->nama_software ?? 'ID: '.$item->id_software }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Versi Terinstal</span>
                                                <span class="font-mono text-slate-600 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">v.{{ $item->versi_terinstall ?? '-' }}</span>
                                            </div>
                                        </div>
                                        
                                        <hr class="border-slate-100">
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laboratorium</span>
                                                <span class="font-bold text-slate-800">
                                                    {{ $item->laboratorium->nama_lab ?? 'LAB ' . ($item->no_lab ?? '-') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Lisensi</span>
                                                <span class="inline-block rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-bold text-blue-700 ring-1 ring-blue-100 uppercase tracking-wider">
                                                    {{ $item->status_lisensi ?? 'Free / Default' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <hr class="border-slate-100">
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Aktif</span>
                                                <span class="font-medium text-slate-800">
                                                    {{ $item->tgl_aktif ? \Carbon\Carbon::parse($item->tgl_aktif)->format('d F Y') : '-' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Expired</span>
                                                <span class="font-medium text-slate-800">
                                                    {{ $item->tgl_expired ? \Carbon\Carbon::parse($item->tgl_expired)->format('d F Y') : 'Seumur Hidup (Perpetual)' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <hr class="border-slate-100">
                                        
                                        <div>
                                            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pengerjaan</span>
                                            <span class="inline-block rounded-full px-3 py-0.5 text-xs font-bold ring-1 {{ $statusClass($currentStatus) }} capitalize">
                                                {{ str_replace('_', ' ', $currentStatus) }}
                                            </span>
                                        </div>

                                        <hr class="border-slate-100">

                                        <div>
                                            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Link Dokumentasi Hasil Instalasi</span>
                                            @if($item->dokumentasi)
                                                <a href="{{ $item->dokumentasi }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100 break-all">
                                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    {{ $item->dokumentasi }}
                                                </a>
                                            @else
                                                <span class="text-xs font-semibold text-slate-400 italic">Belum ada link dokumentasi yang diunggah.</span>
                                            @endif
                                        </div>
                                        
                                        <hr class="border-slate-100">
                                        
                                        <div>
                                            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Penanggung Jawab / Eksekutor</span>
                                            <div class="flex items-center gap-2 text-slate-800 font-semibold bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                <span>{{ $item->admin->name ?? $item->diinstal_oleh ?? 'Admin Lab' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex justify-end">
                                        <button type="button" onclick="toggleModal('modalDetailHistory{{ $item->id }}', false)" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-slate-800 cursor-pointer">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm font-semibold text-slate-500">Belum ada rekaman riwayat aktivitas pengerjaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pengajuans->hasPages())
            <div class="border-t border-slate-200 bg-white px-6 py-4">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </section>
</div>

<script>
    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        if (show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
</script>
@endsection