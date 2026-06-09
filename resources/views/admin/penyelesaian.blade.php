@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">
    
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-black tracking-tight text-slate-950 sm:text-2xl">Daftar Tugas Instalasi Saya</h2>
            <p class="text-sm font-medium text-slate-500">Kelola status dan progress instalasi software laboratorium.</p>
        </div>
    </div>

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

    @if($errors->any())
        <div class="rounded-xl border border-rose-100 bg-rose-50 p-4 text-sm font-semibold text-rose-950 shadow-sm">
            <div class="flex items-start gap-2">
                <svg class="h-5 w-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

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
                        <th class="px-6 py-4">Status Progress</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @forelse($tugas as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-slate-900 font-semibold">{{ $item->dosen->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $item->mata_kuliah }}</div>
                                <div class="text-xs text-slate-400 font-normal mt-0.5">Kelompok: {{ $item->kelompok_matkul }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $item->laboratorium->nama_lab ?? 'Lab ' . ($item->laboratorium->no_lab ?? '-') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->software_id)
                                    <span class="text-slate-900 font-semibold">{{ $item->software->nama_software }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md font-mono ml-1">v{{ $item->versi_requested ?? 'Default' }}</span>
                                @else
                                    <span class="text-slate-900 font-semibold">{{ $item->software_lain }}</span>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded-md font-mono ml-1">v{{ $item->versi_lain ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status_progress == 'terinstal')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200/60 bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Terinstal
                                    </span>
                                @elseif($item->status_progress == 'gagal_terinstal')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200/60 bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Gagal Terinstal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200/60 bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Progress
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', true)" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                        Update Progress
                                    </button>

                                    @if($item->dokumentasi)
                                        <a href="{{ $item->dokumentasi }}" target="_blank" class="inline-flex items-center justify-center rounded-xl border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 shadow-sm transition hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                            Lihat Bukti
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div id="modalProgress{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalProgress{{ $item->id }}', false)"></div>
                            
                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg animate-scaleIn">
                                    
                                    <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                                        <h3 class="text-base font-bold text-slate-950">Update Progress Instalasi</h3>
                                        <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <form action="{{ route('admin.updateProgressTugas', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="px-6 py-4 space-y-4">
                                            <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-100 text-xs font-medium text-slate-600 space-y-1">
                                                <p>Mata Kuliah: <strong class="text-slate-950">{{ $item->mata_kuliah }}</strong></p>
                                                <p>Lokasi Ruang: <strong class="text-slate-950">{{ $item->laboratorium->nama_lab ?? 'Lab ' . ($item->laboratorium->no_lab ?? '-') }}</strong></p>
                                            </div>

                                            <div>
                                                <label for="status_progress_{{ $item->id }}" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Status Progress <span class="text-rose-500">*</span></label>
                                                <select name="status_progress" id="status_progress_{{ $item->id }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10" required>
                                                    <option value="progress" {{ old('status_progress', $item->status_progress) == 'progress' ? 'selected' : '' }}>Progress (Sedang Dikerjakan)</option>
                                                    <option value="terinstal" {{ old('status_progress', $item->status_progress) == 'terinstal' ? 'selected' : '' }}>Terinstal (Selesai)</option>
                                                    <option value="gagal_terinstal" {{ old('status_progress', $item->status_progress) == 'gagal_terinstal' ? 'selected' : '' }}>Gagal Terinstal</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="dokumentasi_{{ $item->id }}" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Link Google Drive Dokumentasi <span class="text-rose-500">*</span></label>
                                                <input type="url" name="dokumentasi" id="dokumentasi_{{ $item->id }}" 
                                                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                                       placeholder="https://drive.google.com/drive/folders/..."
                                                       value="{{ old('dokumentasi', $item->dokumentasi) }}" required>
                                                <p class="text-[11px] text-slate-400 font-medium mt-1">Sediakan tautan folder/file Google Drive bukti instalasi di lapangan.</p>
                                            </div>

                                            <div>
                                                <label for="catatan_admin_{{ $item->id }}" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Catatan Admin</label>
                                                <textarea name="catatan_admin" id="catatan_admin_{{ $item->id }}" rows="3"
                                                          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                                          placeholder="Tulis alasan jika status Gagal Terinstal, atau detail tambahan lainnya...">{{ old('catatan_admin', $item->catatan_admin) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                                            <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition focus:outline-none">
                                                Batal
                                            </button>
                                            <button type="submit" class="rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-950/20">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm font-medium text-slate-400">
                                <svg class="h-8 w-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <span>Belum ada tugas instalasi yang ditugaskan kepada Anda.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        if (show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Lock background scroll
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Unlock background scroll
        }
    }
</script>
@endsection