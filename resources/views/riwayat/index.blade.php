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
                    Menampilkan seluruh data rekam jejak instalasi sistem laboratorium.
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

    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <form id="filterForm" action="{{ url()->current() }}" method="GET" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="grid flex-1 grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pemohon, mata kuliah..." class="w-full pl-10 pr-4 py-2 text-sm rounded-xl border border-slate-200 text-slate-900 bg-white placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition">
                </div>

                <div class="relative dropdown-container">
                    <input type="hidden" name="laboratorium" id="selectedLaboratorium" value="{{ request('laboratorium') }}">
                    <button type="button" onclick="toggleDropdown(event, 'dropdownLab')" class="w-full flex items-center justify-between px-4 py-2 text-sm rounded-xl border border-slate-200 text-slate-700 bg-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition text-left cursor-pointer">
                        <span id="labelLab">
                            @if(request()->filled('laboratorium') && isset($list_laboratorium))
                                @php 
                                    $selectedLab = $list_laboratorium->firstWhere('id', request('laboratorium')); 
                                @endphp
                                {{ $selectedLab ? ($selectedLab->nama_lab ?? $selectedLab->nama ?? $selectedLab->nama_laboratorium ?? 'LAB ' . $selectedLab->id) : 'Semua Laboratorium' }}
                            @else
                                Semua Laboratorium
                            @endif
                        </span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="dropdownLab" class="hidden absolute left-0 z-30 mt-1.5 w-full rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl max-h-60 overflow-y-auto">
                        <div onclick="selectOption('selectedLaboratorium', 'labelLab', '', 'Semua Laboratorium')" class="cursor-pointer rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium transition">
                            Semua Laboratorium
                        </div>
                        @if(isset($list_laboratorium))
                            @foreach($list_laboratorium as $lab)
                                @php
                                    $labName = $lab->nama_lab ?? $lab->nama ?? $lab->nama_laboratorium ?? 'LAB ' . $lab->id;
                                @endphp
                                <div onclick="selectOption('selectedLaboratorium', 'labelLab', '{{ $lab->id }}', '{{ $labName }}')" class="cursor-pointer rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium transition {{ request('laboratorium') == $lab->id ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
                                    {{ $labName }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="relative dropdown-container">
                    <input type="hidden" name="software" id="selectedSoftware" value="{{ request('software') }}">
                    <button type="button" onclick="toggleDropdown(event, 'dropdownSoftware')" class="w-full flex items-center justify-between px-4 py-2 text-sm rounded-xl border border-slate-200 text-slate-700 bg-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition text-left cursor-pointer">
                        <span id="labelSoftware">
                            @if(request()->filled('software') && isset($list_software))
                                @php 
                                    $selectedSoft = $list_software->firstWhere('id', request('software')); 
                                @endphp
                                {{ $selectedSoft ? ($selectedSoft->nama_software ?? $selectedSoft->nama ?? $selectedSoft->nama_program ?? 'Software ' . $selectedSoft->id) : 'Semua Software' }}
                            @else
                                Semua Software
                            @endif
                        </span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="dropdownSoftware" class="hidden absolute left-0 z-30 mt-1.5 w-full rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl max-h-60 overflow-y-auto">
                        <div onclick="selectOption('selectedSoftware', 'labelSoftware', '', 'Semua Software')" class="cursor-pointer rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium transition">
                            Semua Software
                        </div>
                        @if(isset($list_software))
                            @foreach($list_software as $soft)
                                @php
                                    $softName = $soft->nama_software ?? $soft->nama ?? $soft->nama_program ?? 'Software ' . $soft->id;
                                @endphp
                                <div onclick="selectOption('selectedSoftware', 'labelSoftware', '{{ $soft->id }}', '{{ $softName }}')" class="cursor-pointer rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium transition {{ request('software') == $soft->id ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
                                    {{ $softName }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="w-full md:w-auto rounded-xl bg-blue-950 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-blue-900 transition cursor-pointer">
                    Cari
                </button>
                @if(request()->filled('search') || request()->filled('laboratorium') || request()->filled('software'))
                    <a href="{{ url()->current() }}" class="w-full md:w-auto rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-bold text-slate-700 text-center hover:bg-slate-100 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4">Tanggal</th>
                        @if($role === 'supervisor' || $role === 'admin')
                            <th class="px-6 py-4">Pemohon</th>
                        @endif
                        <th class="px-6 py-4">Software / Mata Kuliah</th>
                        <th class="px-6 py-4">Laboratorium</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengajuans as $item)
                        @php
                            // Logika pintar untuk membaca nama laboratorium agar tidak muncul "LAB -"
                            $labDisplay = '-';
                            if (is_string($item->laboratorium) && !empty($item->laboratorium)) {
                                $labDisplay = $item->laboratorium;
                            } elseif (optional($item->laboratorium)->nama_lab) {
                                $labDisplay = $item->laboratorium->nama_lab;
                            } elseif (!empty($item->nama_lab)) {
                                $labDisplay = $item->nama_lab;
                            } elseif (!empty($item->no_lab)) {
                                $labDisplay = 'LAB ' . $item->no_lab;
                            } elseif (!empty($item->laboratorium_id)) {
                                $labDisplay = 'LAB ' . $item->laboratorium_id;
                            } else {
                                $labDisplay = 'Belum Ditentukan';
                            }
                        @endphp
                        
                        <tr class="transition hover:bg-slate-50/50">
                            <td class="whitespace-nowrap px-6 py-4 text-slate-600 font-medium">
                                {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            
                            @if($role === 'supervisor' || $role === 'admin')
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-950">{{ $item->dosen->nama ?? 'Dosen' }}</div>
                                    <div class="text-xs text-slate-500">ID: {{ $item->dosen->no_induk ?? '-' }}</div>
                                </td>
                            @endif

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
                                    {{ $labDisplay }}
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
                                                <span class="font-extrabold text-slate-900">
                                                    {{ $item->software->nama_software ?? $item->software_lain ?? 'Software Tidak Diketahui' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Versi yang Diminta</span>
                                                <span class="font-mono text-slate-600 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                                    v.{{ $item->versi_requested ?? $item->versi_lain ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <hr class="border-slate-100">
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laboratorium</span>
                                                <span class="font-bold text-slate-800">
                                                    {{ $labDisplay }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Kuliah / Kelompok</span>
                                                <span class="font-medium text-slate-800">
                                                    {{ $item->mata_kuliah ?? '-' }} ({{ $item->kelompok_matkul ?? '-' }})
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <hr class="border-slate-100">
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</span>
                                                <span class="font-medium text-slate-800">
                                                    {{ $item->tgl_pengajuan ? $item->tgl_pengajuan->format('d F Y') : '-' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Penugasan</span>
                                                <span class="font-medium text-slate-800">
                                                    {{ $item->tgl_penugasan ? $item->tgl_penugasan->format('d F Y') : 'Belum Ditugaskan' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($item->catatan_spv || $item->catatan_admin)
                                            <hr class="border-slate-100">
                                            <div class="space-y-2">
                                                @if($item->catatan_spv)
                                                    <div>
                                                        <span class="block text-[11px] font-bold text-red-500 uppercase tracking-wider">Catatan Supervisor</span>
                                                        <p class="text-xs text-slate-600 italic bg-red-50/50 p-2 rounded-lg border border-red-100">{{ $item->catatan_spv }}</p>
                                                    </div>
                                                @endif
                                                @if($item->catatan_admin)
                                                    <div>
                                                        <span class="block text-[11px] font-bold text-amber-600 uppercase tracking-wider">Catatan Admin / Kendala</span>
                                                        <p class="text-xs text-slate-600 italic bg-amber-50/50 p-2 rounded-lg border border-amber-100">{{ $item->catatan_admin }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

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
                                                <span>{{ $item->admin->nama ?? $item->admin->name ?? 'Belum Ditugaskan' }}</span>
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
                            <td colspan="{{ $role === 'supervisor' || $role === 'admin' ? 6 : 5 }}" class="px-6 py-10 text-center">
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

    // Fungsi Pengendali Komponen Custom Dropdown Modern
    function toggleDropdown(event, id) {
        event.stopPropagation();
        const dropdown = document.getElementById(id);
        const allDropdowns = ['dropdownLab', 'dropdownSoftware'];
        
        allDropdowns.forEach(dId => {
            if(dId !== id) {
                const element = document.getElementById(dId);
                if (element) element.classList.add('hidden');
            }
        });

        if (dropdown) dropdown.classList.toggle('hidden');
    }

    function selectOption(inputId, labelId, value, labelText) {
        document.getElementById(inputId).value = value;
        document.getElementById(labelId).innerText = labelText;
        document.getElementById('filterForm').submit();
    }

    // Tutup dropdown otomatis jika klik di luar area filter
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-container')) {
            const dropdownLab = document.getElementById('dropdownLab');
            const dropdownSoftware = document.getElementById('dropdownSoftware');
            if (dropdownLab) dropdownLab.classList.add('hidden');
            if (dropdownSoftware) dropdownSoftware.classList.add('hidden');
        }
    });
</script>
@endsection