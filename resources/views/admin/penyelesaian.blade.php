@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
    
    <div class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm space-y-6">
        
        <div class="w-full bg-[#1e295b] text-white text-center py-4 rounded-xl shadow-sm">
            <h2 class="text-xl font-black tracking-widest uppercase">Update Pengerjaan Tugas</h2>
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
                <div class="rounded-xl bg-slate-100 p-3 text-slate-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-950">{{ $summary['total'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-800 tracking-tight">Total Tugas Saya</p>
                    <p class="text-[11px] text-slate-400">Semua Laboratorium</p>
                </div>
            </div>

            <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-rose-50 p-3 text-rose-500 border border-rose-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-950 text-rose-600">{{ $summary['terkendala'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-800 tracking-tight">Gagal Terinstal</p>
                    <p class="text-[11px] text-slate-400">Terkendala saat instalasi</p>
                </div>
            </div>

            <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-blue-50 p-3 text-blue-500 border border-blue-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.212 9H18.5"></path></svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-950 text-blue-600">{{ $summary['progress'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-800 tracking-tight">Sedang Diproses</p>
                    <p class="text-[11px] text-slate-400">On Progress</p>
                </div>
            </div>

            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm flex items-center gap-4">
                <div class="rounded-xl bg-emerald-50 p-3 text-emerald-500 border border-emerald-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-950 text-emerald-600">{{ $summary['selesai'] ?? 0 }}</div>
                    <p class="text-xs font-bold text-slate-800 tracking-tight">Selesai Terinstal</p>
                    <p class="text-[11px] text-slate-400">Instalasi Sukses</p>
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-2">
            <div class="hidden md:flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 px-6">
                <div class="w-12 text-center">No.</div>
                <div class="w-1/4 pl-6">Software</div>
                <div class="w-1/6 text-center">Laboratorium</div>
                <div class="w-1/5 text-center">Status Progress</div>
                <div class="w-1/5 text-center">Tgl Penugasan</div>
                <div class="flex-1 text-right">Aksi</div>
            </div>

            @forelse($tugas as $index => $item)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col md:flex-row md:items-center transition hover:border-slate-300">
                    
                    <div class="w-full md:w-12 text-left md:text-center text-lg font-black text-slate-900 mb-2 md:mb-0">
                        {{ $index + 1 }}
                    </div>

                    <div class="w-full md:w-1/4 flex items-center gap-4 mb-3 md:mb-0 md:pl-6">
                        <div class="p-3 bg-blue-50/50 text-blue-900 rounded-xl border border-blue-50 shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-950 uppercase tracking-tight">
                                {{ $item->software_id ? $item->software->nama_software : $item->software_lain }}
                            </h4>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">
                                v.{{ $item->software_id ? ($item->versi_requested ?? 'Default') : ($item->versi_lain ?? '-') }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full md:w-1/6 flex md:justify-center mb-3 md:mb-0">
                        <span class="inline-block bg-slate-100 text-slate-700 font-extrabold text-xs px-3 py-1 rounded-full border border-slate-200/50">
                            {{ $item->laboratorium->nama_lab ?? 'LAB ' . ($item->laboratorium->no_lab ?? '-') }}
                        </span>
                    </div>

                    <div class="w-full md:w-1/5 flex md:justify-center mb-3 md:mb-0">
                        @if($item->status_progress == 'terinstal')
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                Terinstal
                            </span>
                        @elseif($item->status_progress == 'gagal_terinstal')
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Gagal Terinstal
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                <svg class="h-3.5 w-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.212 9H18.5"></path></svg>
                                Progress
                            </span>
                        @endif
                    </div>

                    <div class="w-full md:w-1/5 text-left md:text-center text-sm font-bold text-slate-600 mb-4 md:mb-0">
                        {{ $item->tgl_penugasan ? \Carbon\Carbon::parse($item->tgl_penugasan)->format('d F Y') : '-' }}
                    </div>

                    <div class="flex-1 flex flex-row md:flex-col justify-end items-end gap-2">
                        <button type="button" onclick="toggleModal('modalDetail{{ $item->id }}', true)" class="rounded-xl border border-slate-200 bg-white px-4 py-1.5 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 text-center w-24 cursor-pointer transition">
                            Detail
                        </button>
                        <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', true)" class="rounded-xl bg-[#1e295b] px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-opacity-90 text-center w-24 cursor-pointer transition">
                            Update
                        </button>
                    </div>
                </div>

                <div id="modalDetail{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleModal('modalDetail{{ $item->id }}', false)"></div>
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all w-full max-w-xl">
                            <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between bg-slate-50">
                                <div>
                                    <h3 class="text-base font-black text-slate-950">Rincian Lengkap Tugas</h3>
                                    <p class="text-xs text-slate-400">Detail data instruksi pengajuan software lab Anda.</p>
                                </div>
                                <button type="button" onclick="toggleModal('modalDetail{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="px-6 py-5 space-y-4 text-sm text-slate-700">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Software</span>
                                        <span class="font-extrabold text-slate-900">{{ $item->software_id ? $item->software->nama_software : $item->software_lain }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Versi</span>
                                        <span class="font-mono text-slate-600">v.{{ $item->software_id ? ($item->versi_requested ?? 'Default') : ($item->versi_lain ?? '-') }}</span>
                                    </div>
                                </div>
                                <hr class="border-slate-100">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laboratorium Tujuan</span>
                                        <span class="font-bold text-slate-800">{{ $item->laboratorium->nama_lab ?? 'LAB ' . ($item->laboratorium->no_lab ?? '-') }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dosen Pengaju</span>
                                        <span class="font-bold text-slate-800">{{ $item->dosen->name ?? '-' }}</span>
                                    </div>
                                </div>
                                <hr class="border-slate-100">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Kuliah</span>
                                        <span class="font-medium text-slate-800">{{ $item->mata_kuliah ?? '-' }} ({{ $item->kelompok_matkul ?? '-' }})</span>
                                    </div>
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Ditugaskan</span>
                                        <span class="font-medium text-slate-800">{{ $item->tgl_penugasan ? \Carbon\Carbon::parse($item->tgl_penugasan)->format('d F Y') : '-' }}</span>
                                    </div>
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
                                        <span class="text-xs font-semibold text-slate-400 italic">Anda belum mengunggah link dokumentasi.</span>
                                    @endif
                                </div>
                                @if($item->catatan_admin)
                                    <hr class="border-slate-100">
                                    <div>
                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Catatan Kendala Pengerjaan Anda</span>
                                        <p class="text-xs font-medium text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $item->catatan_admin }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex justify-end">
                                <button type="button" onclick="toggleModal('modalDetail{{ $item->id }}', false)" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-slate-800 cursor-pointer">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="modalProgress{{ $item->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleModal('modalProgress{{ $item->id }}', false)"></div>
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative transform overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-2xl transition-all w-full max-w-lg">
                            <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                                <h3 class="text-base font-bold text-slate-950">Update Progress Tugas Anda</h3>
                                <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-50">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <form action="{{ route('admin.instalasi.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="px-6 py-4 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Status Progress *</label>
                                        <select name="status_progress" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-bold text-slate-800 focus:border-blue-500 focus:outline-none" required>
                                            <option value="progress" {{ $item->status_progress == 'progress' ? 'selected' : '' }}>Progress (Sedang Dikerjakan)</option>
                                            <option value="terinstal" {{ $item->status_progress == 'terinstal' ? 'selected' : '' }}>Terinstal (Selesai Sukses)</option>
                                            <option value="gagal_terinstal" {{ $item->status_progress == 'gagal_terinstal' ? 'selected' : '' }}>Gagal Terinstal (Ada Kendala)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Link Dokumentasi Google Drive *</label>
                                        <input type="url" name="dokumentasi" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none" placeholder="https://drive.google.com/..." value="{{ $item->dokumentasi }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Catatan Kendala Lapangan (Opsional)</label>
                                        <textarea name="catatan_admin" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-800 focus:border-blue-500 focus:outline-none" placeholder="Tuliskan jika ada kendala hardware/kompatibilitas OS saat instalasi...">{{ $item->catatan_admin }}</textarea>
                                    </div>
                                </div>
                                <div class="border-t border-slate-100 bg-slate-50 px-6 py-4 flex items-center justify-end gap-2">
                                    <button type="button" onclick="toggleModal('modalProgress{{ $item->id }}', false)" class="rounded-xl px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                                    <button type="submit" class="rounded-xl bg-[#1e295b] px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-opacity-90 cursor-pointer">Simpan Hasil Kerja</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center text-sm font-bold text-slate-400">
                    Belum ada penugasan instalasi software yang dialokasikan ke Anda.
                </div>
            @endforelse
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