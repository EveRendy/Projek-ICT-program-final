@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-950">Pengajuan</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Persetujuan Pengajuan Instalasi (Supervisor)</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Tinjau, setujui, atau tolak pengajuan instalasi software baru dari dosen.
                </p>
            </div>
            <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900">
            + Tambah Pengajuan Baru
            </a>
        </div>
    </section>

    @if(session('success'))
        <div class="flex items-center justify-between gap-3 rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-sm font-semibold text-emerald-950 shadow-sm animate-fadeIn">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center justify-between gap-3 rounded-xl border border-rose-100 bg-rose-50 p-4 text-sm font-semibold text-rose-950 shadow-sm animate-fadeIn">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="flex flex-col gap-2 rounded-xl border border-rose-100 bg-rose-50 p-4 text-sm font-semibold text-rose-950 shadow-sm animate-fadeIn">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Ada kesalahan validasi pengeditan:</span>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <ul class="list-disc pl-7 mt-1 font-medium text-xs text-rose-800 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- VERIFIKASI FOTO DIHAPUS DARI SINI (Pindah ke Update Pengerjaan) --}}

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 text-center w-12">No</th>
                        <th class="px-6 py-4">Dosen</th>
                        <th class="px-6 py-4">Mata Kuliah</th>
                        <th class="px-6 py-4">Laboratorium</th>
                        <th class="px-6 py-4">Software Diminta</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi Persetujuan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @forelse($tugas as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                            
                            <td class="px-6 py-4 text-slate-900 font-semibold">
                                {{ $item->dosen->name ?? $item->dosen->nama ?? 'Nama tidak ditemukan' }}
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $item->mata_kuliah }}</div>
                                <div class="text-xs text-slate-400 font-normal mt-0.5">Kelompok: {{ $item->kelompok_matkul }}</div>
                            </td>
                            
                            <td class="px-6 py-4 text-slate-600">
                                @php
                                    // Memaksa data menjadi array jika bentuknya masih string JSON
                                    $rawLabIds = $item->lab_ids;
                                    $labIdsArray = is_string($rawLabIds) ? json_decode($rawLabIds, true) : $rawLabIds;
                                    
                                    $labs = \App\Models\Laboratorium::whereIn('id', $labIdsArray ?? [])->get();
                                @endphp

                                @if($labs && $labs->count() > 0)
                                    {{ $labs->map(function($lab) { return $lab->no_lab . ($lab->nama_lab ? ' : ' . $lab->nama_lab : ''); })->implode(', ') }}
                                @else
                                    <span class="italic text-slate-400">Tidak ada lab</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($item->software_id)
                                    <span class="text-slate-900 font-semibold">{{ $item->software->nama_software }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md font-mono ml-1">v{{ $item->versi_requested ?? 'Bawaan' }}</span>
                                @else
                                    <span class="text-slate-900 font-semibold">{{ $item->software_lain }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md font-mono ml-1">v{{ $item->versi_lain ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Persetujuan
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="openApproveModal({{ $item->id }})" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none">
                                        Setujui
                                    </button>

                                    <button type="button" onclick="toggleModal('modalEdit{{ $item->id }}', true)" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none">
                                        Edit
                                    </button>

                                    <button type="button" onclick="toggleModal('modalTolak{{ $item->id }}', true)" class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none">
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div id="modalTolak{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalTolak{{ $item->id }}', false)"></div>
                            
                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                    
                                    <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                                        <h3 class="text-base font-bold text-slate-950">Tolak Pengajuan Instalasi</h3>
                                        <button type="button" onclick="toggleModal('modalTolak{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('supervisor.pengajuan.tolak', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="px-6 py-4 space-y-4">
                                            <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-100 text-xs font-medium text-slate-600 space-y-1">
                                                <p>Dosen Pengaju: <strong class="text-slate-950">{{ $item->dosen->name ?? $item->dosen->nama ?? 'Nama tidak ditemukan' }}</strong></p>
                                                <p>Mata Kuliah: <strong class="text-slate-950">{{ $item->mata_kuliah }}</strong></p>
                                                <p>Software: <strong class="text-slate-950">{{ $item->software->nama_software ?? $item->software_lain }}</strong></p>
                                            </div>

                                            <div>
                                                <label for="catatan_spv_{{ $item->id }}" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                                <textarea name="catatan_spv" id="catatan_spv_{{ $item->id }}" rows="4"
                                                          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none"
                                                          placeholder="Tuliskan alasan mengapa pengajuan ini ditolak..." required></textarea>
                                            </div>
                                        </div>

                                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                                            <button type="button" onclick="toggleModal('modalTolak{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">
                                                Batal
                                            </button>
                                            <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
                                                Tolak Pengajuan
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        {{-- MODAL EDIT PENGAJUAN SEBELUM DISETUJUI --}}
                        <div id="modalEdit{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleModal('modalEdit{{ $item->id }}', false)"></div>
                            <div class="flex min-h-full items-center justify-center p-4">
                                <div class="relative z-10 w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

                                    {{-- Header --}}
                                    <div class="flex items-center justify-between border-b border-slate-100 bg-blue-50 px-6 py-4">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Koreksi Data Pengajuan</p>
                                            <h3 class="text-base font-black text-slate-950">Edit Sebelum Disetujui</h3>
                                        </div>
                                        <button type="button" onclick="toggleModal('modalEdit{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('supervisor.pengajuan.edit', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="px-6 py-5 space-y-4">

                                            {{-- Info dosen (read-only) --}}
                                            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3.5 text-xs text-slate-600 space-y-1">
                                                <p>Dosen Pengaju: <strong class="text-slate-900">{{ $item->dosen->nama ?? $item->dosen->name ?? '-' }}</strong></p>
                                                <p class="text-[11px] text-blue-600 font-semibold">⚠️ Anda mengedit data ini sebagai Supervisor. Perubahan akan tersimpan sebelum disetujui.</p>
                                            </div>

                                            {{-- Mata Kuliah --}}
                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Mata Kuliah <span class="text-rose-500">*</span></label>
                                                <input type="text" name="mata_kuliah" value="{{ old('mata_kuliah', $item->mata_kuliah) }}"
                                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-200"
                                                    placeholder="Nama mata kuliah..." required>
                                            </div>

                                            {{-- Kelompok --}}
                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kelompok / Kelas <span class="text-rose-500">*</span></label>
                                                <input type="text" name="kelompok_matkul" value="{{ old('kelompok_matkul', $item->kelompok_matkul) }}"
                                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-200"
                                                    placeholder="Contoh: A, B, TI-3A..." required>
                                            </div>

                                            {{-- Laboratorium (single radio) --}}
                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Laboratorium <span class="text-rose-500">*</span></label>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                    @foreach($list_laboratorium as $lab)
                                                        @php
                                                            $currentLabIds = is_array($item->lab_ids) ? $item->lab_ids : [];
                                                            $isChecked = in_array((string)$lab->id, array_map('strval', $currentLabIds));
                                                        @endphp
                                                        <label class="flex items-center gap-2 rounded-xl border p-2.5 cursor-pointer transition border-slate-200 bg-white hover:bg-slate-50 [&:has(:checked)]:border-blue-400 [&:has(:checked)]:bg-blue-50 [&:has(:checked)]:ring-2 [&:has(:checked)]:ring-blue-150">
                                                            <input type="radio" name="lab_id" value="{{ $lab->id }}"
                                                                class="h-4 w-4 accent-blue-600 cursor-pointer"
                                                                {{ $isChecked ? 'checked' : '' }} required>
                                                            <span class="text-xs font-bold text-slate-700">{{ $lab->no_lab }}</span>
                                                            <span class="text-[10px] text-slate-500 font-medium">{{ $lab->nama_lab ?? '' }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- Software (hanya bisa diedit jika 'software lainnya') --}}
                                            @if(is_null($item->software_id))
                                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 space-y-3">
                                                <p class="text-xs font-bold text-amber-700 uppercase tracking-wider">✏️ Software Lainnya (Bisa Diedit)</p>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Software</label>
                                                    <input type="text" name="software_lain" value="{{ old('software_lain', $item->software_lain) }}"
                                                        class="w-full rounded-xl border border-amber-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-amber-400 focus:outline-none focus:ring-1 focus:ring-amber-200"
                                                        placeholder="Nama software yang diminta dosen...">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Versi</label>
                                                    <input type="text" name="versi_lain" value="{{ old('versi_lain', $item->versi_lain) }}"
                                                        class="w-full rounded-xl border border-amber-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-amber-400 focus:outline-none focus:ring-1 focus:ring-amber-200"
                                                        placeholder="Contoh: 1.0, 2024, Latest...">
                                                </div>
                                            </div>
                                            @else
                                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 space-y-3">
                                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Software Master: <strong class="text-slate-800">{{ $item->software->nama_software ?? '-' }}</strong></p>
                                                
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Pilih Versi Software</label>
                                                    <select name="versi_requested" id="versi_requested_{{ $item->id }}" onchange="toggleVersiLainEdit({{ $item->id }})"
                                                        class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                                        @php
                                                            $versionsList = $item->software->versi ?? [];
                                                            $isCustomVersion = !empty($item->versi_lain) || (!empty($item->versi_requested) && !in_array($item->versi_requested, $versionsList));
                                                        @endphp
                                                        @foreach($versionsList as $v)
                                                            <option value="{{ $v }}" {{ (!$isCustomVersion && $item->versi_requested === $v) ? 'selected' : '' }}>{{ $v }}</option>
                                                        @endforeach
                                                        <option value="lainnya" {{ $isCustomVersion ? 'selected' : '' }}>Lainnya (Isi Manual)</option>
                                                    </select>
                                                </div>

                                                <div id="container_versi_lain_edit_{{ $item->id }}" class="{{ $isCustomVersion ? '' : 'hidden' }}">
                                                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Masukkan Versi Baru</label>
                                                    <input type="text" name="versi_lain" id="versi_lain_edit_{{ $item->id }}" value="{{ $item->versi_lain ?? ($isCustomVersion ? $item->versi_requested : '') }}"
                                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-200"
                                                        placeholder="Contoh: v2.0">
                                                </div>
                                            </div>
                                            @endif

                                        </div>

                                        {{-- Footer Aksi --}}
                                        <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                                            <button type="button" onclick="toggleModal('modalEdit{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">
                                                Batal
                                            </button>
                                            <div class="flex gap-2">
                                                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm font-medium text-slate-400">
                                <span>Tidak ada pengajuan baru yang membutuhkan persetujuan Anda saat ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===================================================================
         SECTION: PENGAJUAN SUDAH DISETUJUI TAPI BELUM PUNYA ADMIN
    =================================================================== --}}
    @if(isset($tugasTanpaAdmin) && $tugasTanpaAdmin->count() > 0)
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-xl bg-slate-200 p-2 text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Perlu Penugasan Admin</h3>
                    <p class="text-xs text-slate-600 mt-0.5">{{ $tugasTanpaAdmin->count() }} pengajuan sudah disetujui tapi belum ada admin yang ditugaskan</p>
                </div>
            </div>
        </div>

        {{-- TOMBOL BULK ASSIGN SEMUA PENGAJUAN --}}
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
            @php
                $allAdmins = \App\Models\User::where('role', 'admin')->get();
            @endphp
            <form action="{{ route('supervisor.bulk.assign.admin') }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Tugaskan Admin ke Semua Pengajuan Tertahan</label>
                        @php $allAdmins = \App\Models\User::where('role', 'admin')->get(); @endphp
                        <x-custom-select
                            name="admin_id"
                            label="-- Pilih Admin --"
                            :selected="''"
                            :options="array_merge(
                                [['value' => '', 'label' => '-- Pilih Admin --']],
                                $allAdmins->map(fn($a) => ['value' => $a->no_induk, 'label' => $a->nama . ' (' . $a->no_induk . ')'])->toArray()
                            )" />
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none shrink-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Tugaskan Semua
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 text-center w-12">No</th>
                        <th class="px-6 py-4">Dosen</th>
                        <th class="px-6 py-4">Mata Kuliah</th>
                        <th class="px-6 py-4">Laboratorium</th>
                        <th class="px-6 py-4">Software Diminta</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @foreach($tugasTanpaAdmin as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>

                            <td class="px-6 py-4 text-slate-900 font-semibold">
                                {{ $item->dosen->name ?? $item->dosen->nama ?? 'Nama tidak ditemukan' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $item->mata_kuliah }}</div>
                                <div class="text-xs text-slate-400 font-normal mt-0.5">Kelompok: {{ $item->kelompok_matkul }}</div>
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                @php
                                    $rawLabIds = $item->lab_ids;
                                    $labIdsArray = is_string($rawLabIds) ? json_decode($rawLabIds, true) : $rawLabIds;
                                    $labs = \App\Models\Laboratorium::whereIn('id', $labIdsArray ?? [])->get();
                                @endphp

                                @if($labs && $labs->count() > 0)
                                    {{ $labs->map(function($lab) { return $lab->no_lab . ($lab->nama_lab ? ' : ' . $lab->nama_lab : ''); })->implode(', ') }}
                                @else
                                    <span class="italic text-slate-400">Tidak ada lab</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($item->software_id)
                                    <span class="text-slate-900 font-semibold">{{ $item->software->nama_software }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md font-mono ml-1">v{{ $item->versi_requested ?? 'Bawaan' }}</span>
                                @else
                                    <span class="text-slate-900 font-semibold">{{ $item->software_lain }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md font-mono ml-1">v{{ $item->versi_lain ?? '-' }}</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-800">
                                    Disetujui
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="toggleModal('modalAssignAdmin{{ $item->id }}', true)" class="inline-flex items-center justify-center rounded-xl bg-slate-800 px-3 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none">
                                        Tugaskan Admin
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL TUGASKAN ADMIN (untuk pengajuan tanpa admin) --}}
                        <div id="modalAssignAdmin{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalAssignAdmin{{ $item->id }}', false)"></div>

                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                    <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                                        <h3 class="text-base font-bold text-slate-950">Tugaskan Admin ke Pengajuan</h3>
                                        <button type="button" onclick="toggleModal('modalAssignAdmin{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('pengajuan.assign.admin', $item->id) }}" method="POST">
                                        @csrf

                                        <div class="px-6 py-4 space-y-4">
                                            <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-100 text-xs font-medium text-slate-600 space-y-1">
                                                <p>Dosen Pengaju: <strong class="text-slate-950">{{ $item->dosen->name ?? $item->dosen->nama ?? 'Nama tidak ditemukan' }}</strong></p>
                                                <p>Mata Kuliah: <strong class="text-slate-950">{{ $item->mata_kuliah }} ({{ $item->kelompok_matkul }})</strong></p>
                                                <p>Software: <strong class="text-slate-950">{{ $item->software_id ? $item->software->nama_software : $item->software_lain }}</strong></p>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Pilih Admin <span class="text-rose-500">*</span></label>
                                                @php $admins = \App\Models\User::where('role', 'admin')->get(); @endphp
                                                <x-custom-select
                                                    name="admin_id"
                                                    label="-- Pilih Admin --"
                                                    :selected="''"
                                                    :options="array_merge(
                                                        [['value' => '', 'label' => '-- Pilih Admin --']],
                                                        $admins->map(fn($a) => ['value' => $a->no_induk, 'label' => $a->nama . ' (' . $a->no_induk . ')'])->toArray()
                                                    )" />
                                            </div>
                                        </div>

                                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                                            <button type="button" onclick="toggleModal('modalAssignAdmin{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">
                                                Batal
                                            </button>
                                            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-700">
                                                Tugaskan Admin
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    {{-- END SECTION PENGAJUAN TANPA ADMIN --}}

</div>

<div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeApproveModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-slate-200 bg-white text-left shadow-2xl">
            <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-600">Konfirmasi</p>
                    <h3 class="text-lg font-black text-slate-950">Setujui Pengajuan Ini?</h3>
                </div>
                <button type="button" onclick="closeApproveModal()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="px-6 py-5 text-sm text-slate-600 space-y-2">
                <p>Setelah disetujui, pengajuan akan otomatis masuk ke daftar pengerjaan admin/teknisi.</p>
                <p class="text-xs font-semibold text-blue-600 bg-blue-50 p-2 rounded-lg border border-blue-100">
                    ℹ️ Jika software tidak terdaftar di master, software akan otomatis ditambahkan ke list software.
                </p>
            </div>
            <form id="approveForm" method="POST" class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                @csrf
                @method('PATCH')
                <button type="button" onclick="closeApproveModal()" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">Ya, Setujui</button>
            </form>
        </div>
    </div>
</div>

<script>
    const approveForm = document.getElementById('approveForm');

    function openApproveModal(id) {
        approveForm.action = '{{ route('supervisor.pengajuan.setujui', ':id') }}'.replace(':id', id);
        document.getElementById('approveModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        if (show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function toggleVersiLainEdit(id) {
        const select = document.getElementById('versi_requested_' + id);
        const container = document.getElementById('container_versi_lain_edit_' + id);
        const input = document.getElementById('versi_lain_edit_' + id);
        if (select && container) {
            if (select.value === 'lainnya') {
                container.classList.remove('hidden');
                if (input) input.required = true;
            } else {
                container.classList.add('hidden');
                if (input) {
                    input.required = false;
                    input.value = '';
                }
            }
        }
    }
</script>
@endsection