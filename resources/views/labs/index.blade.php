@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    {{-- ALERT BERHASIL --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ALERT GAGAL --}}
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800 shadow-sm">
            <svg class="h-5 w-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-950">Manajemen Lab</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">
                    @if(auth()->user()->role === 'supervisor')
                        Monitoring Seluruh Lab
                    @else
                        Lab Kelolaan Saya
                    @endif
                </h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    @if(auth()->user()->role === 'supervisor')
                        Memantau dan menyetujui data ruang laboratorium dari seluruh Admin.
                    @else
                        Mengelola data ruang laboratorium milik akun Anda sendiri.
                    @endif
                </p>
            </div>
            
            {{-- DISESUAIKAN: Tombol hanya muncul jika Supervisor ATAU jika Admin tapi datanya masih kosong --}}
            @if(auth()->user()->role === 'supervisor' || (auth()->user()->role === 'admin' && $labs->isEmpty()))
                <div>
                    <a href="{{ route('labs.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-950/20">
                        Tambah Lab Baru
                    </a>
                </div>
            @endif
        </div>
    </section>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full border-collapse text-left whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-xs font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">No Lab</th>
                        @if(auth()->user()->role === 'supervisor')
                            <th class="px-6 py-4">Dikelola Oleh</th>
                        @endif
                        <th class="px-6 py-4">Level Spesifikasi</th>
                        <th class="px-6 py-4">Jumlah PC</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-semibold text-slate-700">
                    @forelse($labs as $lab)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-950">{{ $lab->no_lab }}</div>
                                <div class="text-xs font-medium text-slate-500">{{ $lab->nama_lab ?? 'Laboratorium' }}</div>
                            </td>

                            @if(auth()->user()->role === 'supervisor')
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-lg bg-blue-50 border border-blue-100 px-2.5 py-1 text-xs font-bold text-black-700">
                                        {{ $lab->admin->nama ?? 'Tidak Diketahui' }}
                                    </span>
                                </td>
                            @endif

                            <td class="px-6 py-4">
                                @if($lab->level == 1 || $lab->level == 'Low')
                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-red-50 px-3 py-1 text-xs font-bold text-red-700">Level 1 (Spek Rendah)</span>
                                @elseif($lab->level == 2 || $lab->level == 'Medium')
                                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Level 2 (Spek Sedang)</span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">Level 3 (Spek Tinggi)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">{{ $lab->jumlah_pc }} Unit</td>
                            
                            <td class="px-6 py-4">
                                @if($lab->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div> Disetujui
                                    </span>
                                @elseif($lab->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-red-200 bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                        <div class="h-1.5 w-1.5 rounded-full bg-red-500"></div> Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                        <div class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></div> Menunggu Tinjauan
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- TOMBOL EDIT --}}
                                    @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'supervisor' && $lab->status === 'approved'))
                                        <a href="{{ route('labs.edit', $lab->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20" title="Edit Lab">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                    @endif

                                    {{-- TOMBOL HAPUS --}}
                                    @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'supervisor' && $lab->status === 'approved'))
                                        @if(auth()->user()->role === 'supervisor' || $lab->status !== 'approved')
                                            <form id="delete-form-{{ $lab->id }}" action="{{ route('labs.destroy', $lab->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="openDeleteModal('delete-form-{{ $lab->id }}')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-400 select-none cursor-not-allowed" title="Admin tidak dapat menghapus data yang telah disetujui.">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                            </span>
                                        @endif
                                    @endif

                                    {{-- AKSI APPROVAL KHUSUS SUPERVISOR --}}
                                    @if(auth()->user()->role === 'supervisor' && ($lab->status === 'pending' || $lab->status === null))
                                        {{-- Tombol Detail sebelum approve/reject --}}
                                        <button type="button" onclick="toggleModal('modalDetailLab{{ $lab->id }}', true)"
                                            class="inline-flex h-8 items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none">
                                            Detail
                                        </button>

                                        <form action="{{ route('labs.updateStatus', $lab->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 text-xs font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-100 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('labs.updateStatus', $lab->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="inline-flex h-8 items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 text-xs font-bold text-red-700 shadow-sm transition hover:bg-red-100 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'supervisor' ? 6 : 5 }}" class="px-6 py-12 text-center text-sm font-medium text-slate-400">Belum ada data ruang laboratorium.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL DETAIL LAB (di luar tabel agar tidak tersembunyi oleh parent hidden) --}}
@if(auth()->user()->role === 'supervisor')
    @foreach($labs as $lab)
        @if($lab->status === 'pending' || $lab->status === null)
            <div id="modalDetailLab{{ $lab->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
                                        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleModal('modalDetailLab{{ $lab->id }}', false)"></div>
                                        <div class="flex min-h-full items-center justify-center p-4">
                                            <div class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden">

                                                {{-- Header --}}
                                                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between bg-amber-50">
                                                    <div>
                                                        <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Menunggu Persetujuan</p>
                                                        <h3 class="text-lg font-black text-slate-950">Detail Lab {{ $lab->no_lab }}</h3>
                                                        <p class="text-sm text-slate-500">{{ $lab->nama_lab ?? 'Laboratorium ICT Terpadu' }}</p>
                                                    </div>
                                                    <button type="button" onclick="toggleModal('modalDetailLab{{ $lab->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 transition">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>

                                                {{-- Body --}}
                                                <div class="px-6 py-5 space-y-4 text-sm text-slate-700">

                                                    {{-- Info dasar --}}
                                                    <div class="grid grid-cols-3 gap-3">
                                                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 text-center">
                                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">No. Lab</span>
                                                            <div class="text-base font-black text-slate-900">{{ $lab->no_lab }}</div>
                                                            <div class="text-[10px] text-slate-500 font-semibold">{{ $lab->nama_lab ?? 'Lab' }}</div>
                                                        </div>
                                                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 text-center">
                                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jumlah PC</span>
                                                            <span class="text-base font-black text-slate-900">{{ $lab->jumlah_pc }} <span class="text-xs font-medium text-slate-400">unit</span></span>
                                                        </div>
                                                        <div class="rounded-xl p-3 text-center border
                                                            @if($lab->level == 1) bg-red-50 border-red-100
                                                            @elseif($lab->level == 2) bg-amber-50 border-amber-100
                                                            @else bg-blue-50 border-blue-100 @endif">
                                                            <span class="block text-[10px] font-bold uppercase tracking-wider mb-1
                                                                @if($lab->level == 1) text-red-400
                                                                @elseif($lab->level == 2) text-amber-500
                                                                @else text-blue-500 @endif">Level Spec</span>
                                                            <span class="text-base font-black
                                                                @if($lab->level == 1) text-red-700
                                                                @elseif($lab->level == 2) text-amber-700
                                                                @else text-blue-700 @endif">
                                                                Level {{ $lab->level }}
                                                                <span class="text-xs font-semibold block">
                                                                    @if($lab->level == 1) Spek Rendah
                                                                    @elseif($lab->level == 2) Spek Sedang
                                                                    @else Spek Tinggi @endif
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    {{-- Admin pengaju --}}
                                                    <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3 flex items-center gap-3">
                                                        <div class="rounded-lg bg-blue-100 p-2 text-blue-600 shrink-0">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                        </div>
                                                        <div>
                                                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Diajukan Oleh (Admin)</span>
                                                            <span class="font-bold text-slate-800">{{ $lab->admin->nama ?? 'Tidak Diketahui' }}</span>
                                                            <span class="text-xs text-slate-400 ml-1">({{ $lab->user_id }})</span>
                                                        </div>
                                                    </div>

                                                    {{-- Spesifikasi hardware --}}
                                                    <div>
                                                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Spesifikasi Hardware</span>
                                                        @php
                                                            $specs = is_array($lab->spesifikasi) ? $lab->spesifikasi : (json_decode($lab->spesifikasi, true) ?? []);
                                                        @endphp
                                                        @if(count($specs) > 0)
                                                            <div class="flex flex-wrap gap-2">
                                                                @foreach($specs as $spec)
                                                                    @php
                                                                        // Kategori warna berdasarkan isi spesifikasi
                                                                        $isVga   = str_contains(strtolower($spec), 'vga') || str_contains(strtolower($spec), 'geforce') || str_contains(strtolower($spec), 'gtx') || str_contains(strtolower($spec), 'gt ') || str_contains(strtolower($spec), 'radeon') || str_contains(strtolower($spec), 'rx ');
                                                                        $isRam   = str_contains(strtolower($spec), 'ram');
                                                                        $isGen   = str_contains(strtolower($spec), 'gen') || str_contains(strtolower($spec), 'core ultra');
                                                                        $isCpu   = str_contains(strtolower($spec), 'cpu') || str_contains(strtolower($spec), 'core') || str_contains(strtolower($spec), 'ryzen') || str_contains(strtolower($spec), 'amd');
                                                                    @endphp
                                                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold border
                                                                        @if($isVga) bg-purple-50 border-purple-200 text-purple-700
                                                                        @elseif($isRam) bg-emerald-50 border-emerald-200 text-emerald-700
                                                                        @elseif($isGen) bg-amber-50 border-amber-200 text-amber-700
                                                                        @elseif($isCpu) bg-blue-50 border-blue-200 text-blue-700
                                                                        @else bg-slate-50 border-slate-200 text-slate-700 @endif">
                                                                        {{ $spec }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <p class="text-xs italic text-slate-400">Tidak ada data spesifikasi hardware yang dicatat.</p>
                                                        @endif
                                                    </div>

                                                    {{-- Tanggal pengajuan --}}
                                                    <div class="flex items-center gap-2 text-xs text-slate-400">
                                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        <span>Diajukan pada: <strong class="text-slate-600">{{ $lab->created_at ? $lab->created_at->format('d F Y, H:i') : '-' }}</strong></span>
                                                    </div>

                                                </div>

                                                {{-- Footer: tombol aksi langsung dari modal --}}
                                                <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between gap-2">
                                                    <button type="button" onclick="toggleModal('modalDetailLab{{ $lab->id }}', false)" class="rounded-xl px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">Tutup</button>
                                                    <div class="flex items-center gap-2">
                                                        <form action="{{ route('labs.updateStatus', $lab->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-bold text-red-700 shadow-sm transition hover:bg-red-100">
                                                                Tolak
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('labs.updateStatus', $lab->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                                                                Setujui Lab Ini
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
            </div>
        @endif
    @endforeach
@endif

{{-- MODAL KONFIRMASI HAPUS --}}
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div id="deleteModalContent" class="w-full max-w-sm scale-95 rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300">
        <div class="flex flex-col items-center gap-4 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50 border-[6px] border-red-50 text-red-500 mb-2">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Hapus Ruang Lab Ini?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Data master ruang laboratorium yang dihapus tidak bisa dikembalikan.</p>
            </div>
            <div class="mt-4 flex w-full gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500/20">Batal</button>
                <button type="button" id="confirmDeleteBtn" class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentFormIdToSubmit = null;
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');

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

    function openDeleteModal(formId) {
        currentFormIdToSubmit = formId;
        deleteModal.classList.remove('opacity-0', 'pointer-events-none');
        deleteModalContent.classList.remove('scale-95');
    }

    function closeDeleteModal() {
        currentFormIdToSubmit = null;
        deleteModal.classList.add('opacity-0', 'pointer-events-none');
        deleteModalContent.classList.add('scale-95');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (currentFormIdToSubmit) {
            document.getElementById(currentFormIdToSubmit).submit();
        }
    });
</script>
@endsection