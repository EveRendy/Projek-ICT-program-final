@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">
    
    <div class="bg-[#1e293b] text-white text-center py-4 rounded-2xl font-black text-xl shadow-sm uppercase tracking-widest">
        Update Pengerjaan Tugas
    </div>

    @if(session('success'))
        <div class="flex items-center justify-between gap-3 rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-sm font-semibold text-emerald-950 shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5 sm:p-6 lg:p-8 space-y-6 sm:space-y-8">
        
        @if(Auth::user()->role === 'supervisor')
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-slate-100 p-4 sm:p-5 bg-slate-50/30">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Filter Data Tugas</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pilih laboratorium untuk melihat daftar pengerjaan.</p>
                </div>
                
                <form action="{{ url()->current() }}" method="GET" class="w-full sm:w-auto shrink-0">
                    <select name="lab_id" onchange="this.form.submit()" class="w-full sm:w-64 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer shadow-sm hover:bg-slate-50">
                        <option value="">Semua Laboratorium</option>
                        @foreach($laboratoriums ?? [] as $lab)
                            <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama_lab ?? $lab->no_lab }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @else
            <div>
                <p class="text-sm font-medium text-slate-500 border-b border-slate-100 pb-4">
                    Kelola status dan progress instalasi pekerjaan yang ditugaskan kepada Anda.
                </p>
            </div>
        @endif

        {{-- Menampilkan Card Summary HANYA untuk Supervisor --}}
        @if(Auth::user()->role === 'supervisor')
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-slate-50 p-3 border border-slate-100 text-slate-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-950">{{ $summary['total'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Tugas</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ request('lab_id') ? 'Berdasarkan Lab' : 'Semua Laboratorium' }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-rose-50 p-3 border border-rose-100 text-rose-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-950">{{ $summary['terkendala'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Gagal Terinstal</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Terkendala saat instalasi</p>
                </div>
            </div>

            <div class="rounded-2xl border border-blue-200 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-blue-50 p-3 border border-blue-100 text-blue-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-950">{{ $summary['progress'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Sedang Diproses</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">On Progress</p>
                </div>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-emerald-50 p-3 border border-emerald-100 text-emerald-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-950">{{ $summary['selesai'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Selesai Terinstal</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Instalasi Sukses</p>
                </div>
            </div>
        </div>
        @endif
        {{-- Akhir dari Card Summary --}}

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                            <th class="px-6 py-4 text-center w-12">No</th>
                            <th class="px-6 py-4">Software</th>
                            <th class="px-6 py-4 text-center">Laboratorium</th>
                            <th class="px-6 py-4 text-center">Status Progress</th>
                            <th class="px-6 py-4">Tgl Penugasan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @forelse($tugas as $index => $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    @if($item->software_id)
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-lg border border-slate-200 bg-white flex items-center justify-center shrink-0">
                                                <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 uppercase">{{ $item->software->nama_software }}</div>
                                                <div class="text-xs text-slate-400 font-mono mt-0.5">v{{ $item->versi_requested ?? 'Default' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-lg border border-slate-200 bg-white flex items-center justify-center shrink-0">
                                                <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 uppercase">{{ $item->software_lain }}</div>
                                                <div class="text-xs text-slate-400 font-mono mt-0.5">v{{ $item->versi_lain ?? '-' }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600 font-semibold">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 border border-slate-200">
                                        {{ $item->laboratorium->nama_lab ?? $item->laboratorium->no_lab ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status_progress == 'terinstal')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Terinstal
                                        </span>
                                    @elseif($item->status_progress == 'gagal_terinstal')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Gagal Terinstal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                            <svg class="h-3 w-3 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Progress
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-bold">
                                    {{ $item->tgl_penugasan ? \Carbon\Carbon::parse($item->tgl_penugasan)->format('d F Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center justify-center gap-1.5 w-full max-w-[80px] mx-auto">
                                        <button type="button" onclick="toggleModal('modalDetail{{ $item->id }}', true)" class="inline-flex w-full items-center justify-center rounded-full border border-slate-300 bg-white px-3 py-1.5 text-[11px] font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none">
                                            Detail
                                        </button>
                                        
                                        <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', true)" class="inline-flex w-full items-center justify-center rounded-full bg-[#1e293b] px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none">
                                            Update
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <span>Belum ada tugas pengerjaan instalasi yang dialokasikan berdasarkan filter ini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
</div>

@foreach($tugas as $item)
    
    {{-- MODAL UPDATE PROGRESS --}}
    <div id="modalProgress{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalProgress{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-10">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-950">Update Progress Instalasi</h3>
                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.instalasi.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Status Progress *</label>
                            <select name="status_progress" onchange="toggleAlasanField(this.value, '{{ $item->id }}')" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-blue-500 focus:outline-none" required>
                                <option value="progress" {{ $item->status_progress == 'progress' ? 'selected' : '' }}>Progress (Sedang Dikerjakan)</option>
                                <option value="terinstal" {{ $item->status_progress == 'terinstal' ? 'selected' : '' }}>Terinstal (Selesai)</option>
                                <option value="gagal_terinstal" {{ $item->status_progress == 'gagal_terinstal' ? 'selected' : '' }}>Gagal Terinstal</option>
                            </select>
                        </div>
                        
                        <div id="alasan_container_{{ $item->id }}" class="{{ $item->status_progress == 'gagal_terinstal' ? 'block' : 'hidden' }}">
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Alasan Kendala / Gagal *</label>
                            <textarea name="catatan_admin" id="alasan_input_{{ $item->id }}" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none" placeholder="Jelaskan alasan spesifik mengapa instalasi gagal (misal: spesifikasi PC tidak memadai, lisensi error, dll)." {{ $item->status_progress == 'gagal_terinstal' ? 'required' : '' }}>{{ $item->catatan_admin ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Link Dokumentasi Google Drive</label>
                            <input type="url" name="dokumentasi" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none" placeholder="https://drive.google.com/..." value="{{ $item->dokumentasi }}">
                        </div>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                        <button type="submit" class="rounded-xl bg-[#1e293b] px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL DATA (BERDASARKAN FIGMA PROTOTYPE - TANPA IKON BARIS) --}}
    <div id="modalDetail{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalDetail{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl z-10">
                
                {{-- Penentuan Warna Tema Banner Berdasarkan Status --}}
                @php
                    if ($item->status_progress == 'terinstal') {
                        $bannerClass = 'bg-emerald-50 border-emerald-100 text-emerald-700';
                        $iconColor = 'text-emerald-600';
                    } elseif ($item->status_progress == 'gagal_terinstal') {
                        $bannerClass = 'bg-rose-50 border-rose-100 text-rose-700';
                        $iconColor = 'text-rose-600';
                    } else {
                        $bannerClass = 'bg-blue-50 border-blue-100 text-blue-700';
                        $iconColor = 'text-blue-600';
                    }
                @endphp

                {{-- Header Modal & Close Button --}}
                <div class="px-6 pt-5 pb-3 flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Informasi Tugas</span>
                    <button type="button" onclick="toggleModal('modalDetail{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="px-6 pb-6 space-y-5">
                    {{-- Banner Atas Status --}}
                    <div class="flex items-center justify-center gap-2.5 rounded-xl py-3.5 px-4 font-extrabold text-base border {{ $bannerClass }} transition">
                        <svg class="h-5 w-5 {{ $iconColor }} shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Detail Pengajuan</span>
                    </div>

                    {{-- Layout List Berbaris Tanpa Ikon Sisi Kiri --}}
                    <div class="border border-slate-200/80 rounded-2xl overflow-hidden divide-y divide-slate-100 bg-white shadow-sm">
                        
                        {{-- Row 1: Nama Software --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Nama Software
                            </div>
                            <div class="col-span-7 text-slate-900 font-semibold text-xs sm:text-sm uppercase">
                                {{ $item->software_id ? $item->software->nama_software : $item->software_lain }}
                            </div>
                        </div>

                        {{-- Row 2: Versi --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Versi
                            </div>
                            <div class="col-span-7 text-slate-600 font-mono text-xs sm:text-sm">
                                v{{ $item->versi_requested ?? $item->versi_lain ?? '-' }}
                            </div>
                        </div>

                        {{-- Row 3: Laboratorium --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Laboratorium
                            </div>
                            <div class="col-span-7 text-slate-900 font-bold text-xs sm:text-sm">
                                {{ $item->laboratorium->nama_lab ?? $item->laboratorium->no_lab ?? '-' }}
                            </div>
                        </div>

                        {{-- Row 4: Waktu Pengajuan --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Waktu Penugasan
                            </div>
                            <div class="col-span-7 text-slate-700 font-semibold text-xs sm:text-sm">
                                {{ $item->tgl_penugasan ? \Carbon\Carbon::parse($item->tgl_penugasan)->format('d F Y') : '-' }}
                            </div>
                        </div>

                        {{-- Row 5: Mata Kuliah --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Mata Kuliah
                            </div>
                            <div class="col-span-7 text-slate-700 font-medium text-xs sm:text-sm">
                                {{ $item->mata_kuliah ?? '-' }}
                            </div>
                        </div>

                        {{-- Row 6: Kelompok --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Kelompok
                            </div>
                            <div class="col-span-7 text-slate-700 font-medium text-xs sm:text-sm">
                                {{ $item->kelompok_matkul ?? '-' }}
                            </div>
                        </div>

                        {{-- Row 7: Status Progress --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Status Progress
                            </div>
                            <div class="col-span-7 text-xs sm:text-sm">
                                @if($item->status_progress == 'terinstal')
                                    <span class="text-emerald-600 font-extrabold uppercase">Selesai (Terinstal)</span>
                                @elseif($item->status_progress == 'gagal_terinstal')
                                    <span class="text-rose-600 font-extrabold uppercase">Gagal Terinstal</span>
                                @else
                                    <span class="text-blue-600 font-extrabold uppercase">Sedang Diproses</span>
                                @endif
                            </div>
                        </div>

                        {{-- Row 8: Alasan Gagal (Hanya Muncul Jika Status Gagal) --}}
                        @if($item->status_progress == 'gagal_terinstal')
                            <div class="grid grid-cols-12 p-3.5 items-start gap-3 bg-rose-50/40 hover:bg-rose-50/60 transition">
                                <div class="col-span-5 text-rose-600 font-bold text-xs sm:text-sm">
                                    Alasan Gagal
                                </div>
                                <div class="col-span-7 text-rose-700 font-semibold text-xs sm:text-sm leading-relaxed">
                                    {{ $item->catatan_admin ?? 'Tidak ada keterangan kendala.' }}
                                </div>
                            </div>
                        @endif

                        {{-- Row 9: Link Dokumentasi --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Link Dokumentasi
                            </div>
                            <div class="col-span-7 text-xs sm:text-sm">
                                @if($item->dokumentasi)
                                    <a href="{{ $item->dokumentasi }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-bold underline transition">
                                        Buka GDrive 
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic font-normal">Belum melampirkan dokumentasi</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end">
                    <button type="button" onclick="toggleModal('modalDetail{{ $item->id }}', false)" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 shadow-sm hover:bg-slate-50 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    // Fungsi untuk membuka & menutup Modal
    window.toggleModal = function(modalId, show) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        if (show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    // Fungsi untuk memunculkan input Catatan Admin (Alasan Gagal) secara dinamis
    window.toggleAlasanField = function(statusValue, itemId) {
        const container = document.getElementById('alasan_container_' + itemId);
        const input = document.getElementById('alasan_input_' + itemId);
        
        if (statusValue === 'gagal_terinstal') {
            container.classList.remove('hidden');
            container.classList.add('block');
            input.required = true;
        } else {
            container.classList.remove('block');
            container.classList.add('hidden');
            input.required = false;
            input.value = '';
        }
    };
</script>
@endsection