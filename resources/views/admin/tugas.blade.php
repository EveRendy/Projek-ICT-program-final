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
                    <x-custom-select
                        name="lab"
                        label="Semua Laboratorium"
                        :selected="request('lab')"
                        :autosubmit="true"
                        class="w-full sm:w-64"
                        :options="array_merge(
                            [['value' => '', 'label' => 'Semua Laboratorium']],
                            collect($laboratoriums ?? [])->map(fn($l) => ['value' => $l->no_lab, 'label' => $l->no_lab . ($l->nama_lab ? ' : ' . $l->nama_lab : '')])->toArray()
                        )" />
                </form>
            </div>
        @else
            <div>
                <p class="text-sm font-medium text-slate-500 border-b border-slate-100 pb-4">
                    Kelola status dan progress instalasi pekerjaan yang ditugaskan kepada Anda.
                </p>
            </div>
        @endif

        {{-- Card Summary HANYA untuk Supervisor: hanya Total Tugas & Sedang Diproses --}}
        @if(Auth::user()->role === 'supervisor')
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-slate-50 p-3 border border-slate-100 text-slate-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-950">{{ $summary['total'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Tugas</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ request('lab') ? 'Berdasarkan Lab' : 'Semua Laboratorium' }}</p>
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
                    <p class="text-[10px] text-slate-400 mt-0.5">Sedang Diproses</p>
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
                                                <div class="text-xs text-slate-400 font-mono mt-0.5">v{{ $item->versi_requested ?? 'Bawaan' }}</div>
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
                                
                                {{-- PERBAIKAN: Menampilkan Laboratorium dari Array lab_ids --}}
                                <td class="px-6 py-4 text-center text-slate-600 font-semibold">
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 border border-slate-200">
                                        @php
                                            // Memastikan formatnya adalah array, lalu mencocokkan ID dengan data Master Laboratorium
                                            $labIdsArray = is_string($item->lab_ids) ? json_decode($item->lab_ids, true) : ($item->lab_ids ?? []);
                                            $labNames = collect($laboratoriums)
                                                ->whereIn('id', $labIdsArray)
                                                ->map(fn($l) => $l->no_lab . ($l->nama_lab ? ' : ' . $l->nama_lab : ''))
                                                ->implode(', ');
                                        @endphp
                                        {{ $labNames ?: '-' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($item->status_verifikasi == 'menunggu')
                                        {{-- Foto sudah dikirim, menunggu review supervisor --}}
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Verif. SPV
                                        </span>
                                    @elseif($item->status_verifikasi == 'ditolak')
                                        {{-- Foto ditolak supervisor --}}
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Foto Ditolak SPV
                                        </span>
                                    @elseif($item->status_progress == 'terinstal')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Terinstal
                                        </span>
                                    @elseif($item->status_progress == 'gagal_terinstal')
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Gagal Terinstal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                            <svg class="h-3 w-3 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Dalam Proses
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-bold">
                                    {{ $item->tgl_penugasan ? \Carbon\Carbon::parse($item->tgl_penugasan)->format('d F Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center justify-center gap-1.5 w-full max-w-[100px] mx-auto">
                                        <button type="button" onclick="toggleModal('modalDetail{{ $item->id }}', true)" class="inline-flex w-full items-center justify-center rounded-full border border-slate-300 bg-white px-3 py-1.5 text-[11px] font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none">
                                            Detail
                                        </button>

                                        {{-- Tombol Tinjau khusus Supervisor — muncul jika ada foto menunggu verifikasi --}}
                                        @if(Auth::user()->role === 'supervisor' && $item->status_verifikasi === 'menunggu')
                                            <button type="button" onclick="toggleModal('modalTinjau{{ $item->id }}', true)"
                                                class="inline-flex w-full items-center justify-center rounded-full bg-amber-500 px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none animate-pulse">
                                                Tinjau
                                            </button>
                                        @elseif($item->status_verifikasi == 'menunggu')
                                            <span class="inline-flex w-full items-center justify-center rounded-full bg-amber-100 px-3 py-1.5 text-[11px] font-bold text-amber-700 cursor-not-allowed">
                                                Menunggu Supervisor
                                            </span>
                                        @elseif($item->status_verifikasi == 'ditolak')
                                            <button type="button" onclick="toggleModal('modalFotoBukti{{ $item->id }}', true)" class="inline-flex w-full items-center justify-center rounded-full bg-rose-600 px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none">
                                                Unggah Ulang
                                            </button>
                                        @else
                                            <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', true)" class="inline-flex w-full items-center justify-center rounded-full bg-[#1e293b] px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none">
                                                Update
                                            </button>
                                        @endif
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
    
    {{-- MODAL UPDATE PROGRESS (khusus laporan gagal / kendala) --}}
    <div id="modalProgress{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalProgress{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-10">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-950">Perbarui Pengerjaan Instalasi</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pilih tindakan: unggah foto bukti atau laporkan kendala.</p>
                    </div>
                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-3">
                    {{-- Notifikasi penolakan foto jika ada --}}
                    @if($item->status_verifikasi == 'ditolak' && $item->catatan_penolakan_foto)
                        <div class="rounded-xl bg-rose-50 border border-rose-200 p-4">
                            <p class="text-xs font-bold text-rose-700 uppercase tracking-wide mb-1">Foto Ditolak Supervisor</p>
                            <p class="text-sm text-rose-800">{{ $item->catatan_penolakan_foto }}</p>
                        </div>
                    @endif

                    {{-- Pilihan tindakan --}}
                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false); toggleModal('modalFotoBukti{{ $item->id }}', true)"
                        class="w-full flex items-center gap-4 rounded-2xl border-2 border-blue-200 bg-blue-50 p-4 text-left hover:border-blue-400 hover:bg-blue-100 transition group">
                        <div class="rounded-xl bg-blue-600 p-2.5 text-white shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-900 text-sm">Unggah Foto Bukti Instalasi</p>
                            <p class="text-xs text-slate-500 mt-0.5">Kirim foto sebagai bukti instalasi selesai untuk diverifikasi supervisor.</p>
                        </div>
                    </button>

                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false); toggleModal('modalGagal{{ $item->id }}', true)"
                        class="w-full flex items-center gap-4 rounded-2xl border-2 border-rose-200 bg-rose-50 p-4 text-left hover:border-rose-400 hover:bg-rose-100 transition group">
                        <div class="rounded-xl bg-rose-600 p-2.5 text-white shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-900 text-sm">Laporkan Kendala / Gagal Terinstal</p>
                            <p class="text-xs text-slate-500 mt-0.5">Tandai instalasi sebagai gagal dan tuliskan alasan kendalanya.</p>
                        </div>
                    </button>
                </div>
                <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-3 flex justify-end">
                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD FOTO BUKTI --}}
    <div id="modalFotoBukti{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalFotoBukti{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-10">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-950">Unggah Foto Bukti Instalasi</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Foto akan dikompres otomatis lalu dikirim ke supervisor untuk diverifikasi.</p>
                    </div>
                    <button type="button" onclick="toggleModal('modalFotoBukti{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.foto.upload', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-5 space-y-4">

                        {{-- Catatan penolakan sebelumnya --}}
                        @if($item->status_verifikasi == 'ditolak' && $item->catatan_penolakan_foto)
                            <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5">
                                <p class="text-xs font-bold text-rose-700 uppercase tracking-wide mb-1">Alasan Penolakan Foto Sebelumnya</p>
                                <p class="text-sm text-rose-800">{{ $item->catatan_penolakan_foto }}</p>
                            </div>
                        @endif

                        {{-- Upload area foto --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Foto Bukti Instalasi <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="file" name="foto_bukti" id="fotoInput{{ $item->id }}" accept="image/jpeg,image/png,image/jpg"
                                    class="w-full rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-600 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-blue-700 focus:outline-none focus:border-blue-400 cursor-pointer transition"
                                    onchange="previewFoto(this, '{{ $item->id }}')" required>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG/PNG. Maks 10MB. Foto akan dikompres otomatis sebelum disimpan.</p>
                            {{-- Preview foto --}}
                            <div id="fotoPreview{{ $item->id }}" class="hidden mt-3">
                                <img id="fotoPreviewImg{{ $item->id }}" src="" alt="Preview" class="w-full max-h-48 object-contain rounded-xl border border-slate-200 bg-slate-50">
                            </div>
                        </div>

                        {{-- Link dokumentasi opsional --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Link Dokumentasi Google Drive <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <input type="url" name="dokumentasi" value="{{ $item->dokumentasi }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none" placeholder="https://drive.google.com/...">
                        </div>

                        {{-- Catatan admin opsional --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Catatan Tambahan <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <textarea name="catatan_admin" rows="2" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none" placeholder="Catatan atau keterangan tambahan tentang proses instalasi...">{{ $item->catatan_admin }}</textarea>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleModal('modalFotoBukti{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 0l-3 3m3-3l3 3M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1"></path></svg>
                            Kirim ke Supervisor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL LAPORAN GAGAL / KENDALA --}}
    <div id="modalGagal{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalGagal{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-10">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-950">Laporkan Kendala Instalasi</h3>
                    <button type="button" onclick="toggleModal('modalGagal{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.instalasi.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_progress" value="gagal_terinstal">
                    <div class="px-6 py-5 space-y-4">
                        <div class="rounded-xl bg-rose-50 border border-rose-100 p-3.5 text-xs text-rose-700 font-medium">
                            Tandai tugas ini sebagai <strong>Gagal Terinstal</strong>. Supervisor akan diberitahu dan pengajuan bisa ditinjau ulang.
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Alasan / Deskripsi Kendala <span class="text-rose-500">*</span></label>
                            <textarea name="catatan_admin" rows="4" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none" placeholder="Jelaskan secara detail mengapa instalasi gagal (misal: spesifikasi PC tidak memadai, lisensi error, konflik software, dll)." required>{{ $item->catatan_admin }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Link Dokumentasi <span class="text-slate-400 font-normal">(opsional)</span></label>
                            <input type="url" name="dokumentasi" value="{{ $item->dokumentasi }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none" placeholder="https://drive.google.com/...">
                        </div>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleModal('modalGagal{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">Laporkan Gagal</button>
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
                    if ($item->status_verifikasi == 'ditolak') {
                        $bannerClass = 'bg-rose-50 border-rose-100 text-rose-700';
                        $iconColor = 'text-rose-600';
                        $statusTitle = 'Foto Bukti Ditolak SPV';
                    } elseif ($item->status_verifikasi == 'menunggu') {
                        $bannerClass = 'bg-amber-50 border-amber-100 text-amber-700';
                        $iconColor = 'text-amber-600';
                        $statusTitle = 'Menunggu Verifikasi SPV';
                    } elseif ($item->status_progress == 'terinstal') {
                        $bannerClass = 'bg-emerald-50 border-emerald-100 text-emerald-700';
                        $iconColor = 'text-emerald-600';
                        $statusTitle = 'Instalasi Selesai';
                    } elseif ($item->status_progress == 'gagal_terinstal') {
                        $bannerClass = 'bg-rose-50 border-rose-100 text-rose-700';
                        $iconColor = 'text-rose-600';
                        $statusTitle = 'Gagal Terinstal';
                    } else {
                        $bannerClass = 'bg-blue-50 border-blue-100 text-blue-700';
                        $iconColor = 'text-blue-600';
                        $statusTitle = 'Sedang Diproses';
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
                        <span>Status: {{ $statusTitle }}</span>
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

                        {{-- PERBAIKAN: Row 3: Laboratorium (DI MODAL) --}}
                        <div class="grid grid-cols-12 p-3.5 items-center gap-3 hover:bg-slate-50/40 transition">
                            <div class="col-span-5 text-slate-500 font-medium text-xs sm:text-sm">
                                Laboratorium
                            </div>
                            <div class="col-span-7 text-slate-900 font-bold text-xs sm:text-sm">
                                @php
                                    $labIdsArrayModal = is_string($item->lab_ids) ? json_decode($item->lab_ids, true) : ($item->lab_ids ?? []);
                                    $labNamesModal = collect($laboratoriums)
                                        ->whereIn('id', $labIdsArrayModal)
                                        ->map(fn($l) => $l->no_lab . ($l->nama_lab ? ' : ' . $l->nama_lab : ''))
                                        ->implode(', ');
                                @endphp
                                {{ $labNamesModal ?: '-' }}
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
                                @if($item->status_verifikasi == 'ditolak')
                                    <span class="text-rose-600 font-extrabold uppercase">Foto Ditolak SPV</span>
                                @elseif($item->status_verifikasi == 'menunggu')
                                    <span class="text-amber-600 font-extrabold uppercase">Menunggu Verifikasi SPV</span>
                                @elseif($item->status_progress == 'terinstal')
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

                        {{-- Row 8.5: Alasan Penolakan Foto (Hanya Muncul Jika Foto Ditolak) --}}
                        @if($item->status_verifikasi == 'ditolak' && $item->catatan_penolakan_foto)
                            <div class="grid grid-cols-12 p-3.5 items-start gap-3 bg-rose-50/40 hover:bg-rose-50/60 transition">
                                <div class="col-span-5 text-rose-600 font-bold text-xs sm:text-sm">
                                    Alasan Penolakan Foto
                                </div>
                                <div class="col-span-7 text-rose-700 font-semibold text-xs sm:text-sm leading-relaxed">
                                    {{ $item->catatan_penolakan_foto }}
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
                                        Buka Google Drive
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
    {{-- MODAL TINJAU FOTO BUKTI — khusus Supervisor, muncul di halaman Update Pengerjaan --}}
    @if(Auth::user()->role === 'supervisor' && $item->status_verifikasi === 'menunggu' && $item->foto_bukti)
    <div id="modalTinjau{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modalTinjau{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden">

                {{-- Header --}}
                <div class="border-b border-amber-100 bg-amber-50 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-600 flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Menunggu Tinjauan Anda
                        </p>
                        <h3 class="text-base font-black text-slate-950 mt-0.5">Tinjau Foto Bukti Instalasi</h3>
                    </div>
                    <button type="button" onclick="toggleModal('modalTinjau{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 space-y-4 text-sm">

                    {{-- Info ringkas pengajuan --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 border border-slate-100 px-3 py-2.5">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Software</span>
                            <span class="font-extrabold text-slate-900 uppercase text-xs">
                                {{ $item->software_id ? $item->software->nama_software : $item->software_lain }}
                            </span>
                            <span class="block text-[10px] font-mono text-slate-400">v{{ $item->versi_requested ?? $item->versi_lain ?? '-' }}</span>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-100 px-3 py-2.5">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Laboratorium</span>
                            <span class="font-bold text-slate-800 text-xs">
                                @php
                                    $labIdsTinjau = is_string($item->lab_ids) ? json_decode($item->lab_ids, true) : ($item->lab_ids ?? []);
                                    $labNamesTinjau = collect($laboratoriums)->whereIn('id', $labIdsTinjau)->map(fn($l) => $l->nama_lab ?? $l->no_lab)->implode(', ');
                                @endphp
                                {{ $labNamesTinjau ?: '-' }}
                            </span>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-100 px-3 py-2.5">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Admin Pelaksana</span>
                            <span class="font-bold text-slate-800 text-xs">
                                @php $adminUser = \App\Models\User::where('no_induk', $item->tugaskan_admin)->first(); @endphp
                                {{ $adminUser->nama ?? $item->tugaskan_admin }}
                            </span>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-100 px-3 py-2.5">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Tgl Ditugaskan</span>
                            <span class="font-bold text-slate-800 text-xs">
                                {{ $item->tgl_penugasan ? \Carbon\Carbon::parse($item->tgl_penugasan)->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Catatan admin --}}
                    @if($item->catatan_admin)
                    <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Admin</span>
                        <p class="text-xs text-slate-700 leading-relaxed">{{ $item->catatan_admin }}</p>
                    </div>
                    @endif

                    {{-- Foto bukti --}}
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Foto Bukti Instalasi</span>
                        <button type="button" onclick="toggleModal('modalFotoFullTinjau{{ $item->id }}', true)" class="block w-full group">
                            <img src="{{ asset('storage/' . $item->foto_bukti) }}"
                                 alt="Foto Bukti"
                                 class="w-full max-h-52 object-cover rounded-xl border border-slate-200 shadow-sm group-hover:opacity-90 transition cursor-zoom-in">
                            <p class="text-center text-[11px] font-semibold text-slate-400 mt-1.5">Klik untuk perbesar</p>
                        </button>
                    </div>

                    {{-- Link dokumentasi --}}
                    @if($item->dokumentasi)
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <a href="{{ $item->dokumentasi }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">
                            Buka Link Dokumentasi Google Drive
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Footer: tombol Setujui & Tolak --}}
                <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between gap-3">
                    <button type="button" onclick="toggleModal('modalTinjau{{ $item->id }}', false)"
                        class="rounded-xl px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Tutup</button>
                    <div class="flex items-center gap-2">
                        <button type="button"
                            onclick="toggleModal('modalTinjau{{ $item->id }}', false); toggleModal('modalTolakFotoTugas{{ $item->id }}', true)"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-bold text-rose-700 shadow-sm transition hover:bg-rose-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak
                        </button>
                        <form action="{{ route('supervisor.foto.approve', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal foto full dari tinjau --}}
    <div id="modalFotoFullTinjau{{ $item->id }}" class="fixed inset-0 z-[60] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/85 backdrop-blur-sm" onclick="toggleModal('modalFotoFullTinjau{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative z-10 max-w-3xl w-full">
                <button type="button" onclick="toggleModal('modalFotoFullTinjau{{ $item->id }}', false)"
                    class="absolute -top-10 right-0 rounded-xl p-1.5 text-white hover:bg-white/20 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <img src="{{ asset('storage/' . $item->foto_bukti) }}" alt="Foto Bukti Instalasi"
                     class="w-full rounded-2xl shadow-2xl border border-white/20">
            </div>
        </div>
    </div>

    {{-- Modal Tolak Foto dari halaman tugas --}}
    <div id="modalTolakFotoTugas{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modalTolakFotoTugas{{ $item->id }}', false)"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative z-10 w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-950">Tolak Foto Bukti</h3>
                    <button type="button" onclick="toggleModal('modalTolakFotoTugas{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('supervisor.foto.tolak', $item->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="px-6 py-5 space-y-4">
                        <p class="text-sm text-slate-600">Tuliskan alasan penolakan. Admin akan diminta untuk mengunggah ulang foto bukti.</p>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                                Alasan Penolakan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="catatan_penolakan_foto" rows="4" required
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-rose-400 focus:outline-none"
                                placeholder="Contoh: Foto tidak jelas, tidak terlihat nama software terinstal, foto bukan dari lab yang diminta..."></textarea>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                        <button type="button" onclick="toggleModal('modalTolakFotoTugas{{ $item->id }}', false)"
                            class="rounded-xl px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                        <button type="submit"
                            class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
                            Tolak & Kirim Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

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

    // Fungsi preview foto sebelum upload
    window.previewFoto = function(input, itemId) {
        const preview = document.getElementById('fotoPreview' + itemId);
        const previewImg = document.getElementById('fotoPreviewImg' + itemId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    };
</script>
@endsection