@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">
    
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-black tracking-tight text-slate-950 sm:text-2xl">Update Pengerjaan Tugas</h2>
            <p class="text-sm font-medium text-slate-500">Kelola status dan progress instalasi seluruh software laboratorium.</p>
        </div>
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

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm flex items-center gap-4">
            <div class="rounded-xl bg-slate-50 p-3 border border-slate-100 text-slate-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-950">{{ $countTotal ?? 0 }}</div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tugas</p>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm flex items-center gap-4">
            <div class="rounded-xl bg-amber-50 p-3 border border-amber-100 text-amber-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-950">{{ $countPending ?? 0 }}</div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Dikerjakan</p>
            </div>
        </div>

        <div class="rounded-2xl border border-blue-200 bg-white p-5 shadow-sm flex items-center gap-4">
            <div class="rounded-xl bg-blue-50 p-3 border border-blue-100 text-blue-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.212 9H18.5"></path></svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-950">{{ $countProgress ?? 0 }}</div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sedang Diproses</p>
            </div>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm flex items-center gap-4">
            <div class="rounded-xl bg-emerald-50 p-3 border border-emerald-100 text-emerald-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-950">{{ $countSuccess ?? 0 }}</div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Selesai Terinstal</p>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 text-center w-12">No</th>
                        <th class="px-6 py-4">Software</th>
                        <th class="px-6 py-4">Laboratorium</th>
                        <th class="px-6 py-4 text-center">Status Progress</th>
                        <th class="px-6 py-4">Tgl Pengajuan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @forelse($tugas as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-6 py-4 text-center text-slate-400 font-normal">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                @if($item->software_id)
                                    <div class="font-bold text-slate-900">{{ $item->software->nama_software }}</div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5">v{{ $item->versi_requested ?? 'Default' }}</div>
                                @else
                                    <div class="font-bold text-slate-900">{{ $item->software_lain }}</div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5">v{{ $item->versi_lain ?? '-' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-semibold">
                                {{ $item->laboratorium->nama_lab ?? 'LAB' . ($item->laboratorium->no_lab ?? '-') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status_progress == 'terinstal')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Terinstal
                                    </span>
                                @elseif($item->status_progress == 'gagal_terinstal')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Gagal Terinstal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-normal">
                                {{ $item->created_at ? $item->created_at->format('d F Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', true)" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none">
                                        Update Progress
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div id="modalProgress{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('modalProgress{{ $item->id }}', false)"></div>
                            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
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
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Status Progress *</label>
                                                <select name="status_progress" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-blue-500 focus:outline-none" required>
                                                    <option value="progress" {{ $item->status_progress == 'progress' ? 'selected' : '' }}>Progress (Sedang Dikerjakan)</option>
                                                    <option value="terinstal" {{ $item->status_progress == 'terinstal' ? 'selected' : '' }}>Terinstal (Selesai)</option>
                                                    <option value="gagal_terinstal" {{ $item->status_progress == 'gagal_terinstal' ? 'selected' : '' }}>Gagal Terinstal</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Link Dokumentasi Google Drive *</label>
                                                <input type="url" name="dokumentasi" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none" placeholder="https://drive.google.com/..." value="{{ $item->dokumentasi }}" required>
                                            </div>
                                        </div>
                                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-2">
                                            <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                            <button type="submit" class="rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-slate-400">
                                <span>Belum ada tugas pengerjaan instalasi yang dialokasikan ke Anda.</span>
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
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
</script>
@endsection