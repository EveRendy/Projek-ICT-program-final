@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">
    
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-black tracking-tight text-slate-950 sm:text-2xl">Persetujuan Pengajuan Instalasi (Supervisor)</h2>
            <p class="text-sm font-medium text-slate-500">Tinjau, setujui, atau tolak pengajuan instalasi software baru dari dosen.</p>
        </div>
        <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900">
            + Tambah Pengajuan Baru
        </a>
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
                                                <p>Dosen Pengaju: <strong class="text-slate-950">{{ $item->dosen->name ?? 'N/A' }}</strong></p>
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
            <div class="px-6 py-5 text-sm text-slate-600">
                Setelah disetujui, pengajuan akan otomatis masuk ke daftar pengerjaan admin/teknisi.
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