@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $isSupervisor = $user && $user->role === 'supervisor';
    
    // HANYA SUPERVISOR DAN ADMIN YANG BISA CETAK
    $canPrint = $user && in_array($user->role, ['supervisor', 'admin']);

    $levelMeta = function ($level) {
        return match ((int) $level) {
            1 => ['label' => 'Level 1', 'desc' => 'Spek Rendah', 'class' => 'bg-red-50 text-red-700 ring-red-100'],
            2 => ['label' => 'Level 2', 'desc' => 'Spek Sedang', 'class' => 'bg-amber-50 text-amber-700 ring-amber-100'],
            default => ['label' => 'Level 3', 'desc' => 'Spek Tinggi', 'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
        };
    };
@endphp

<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-950">Daftar Software</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Daftar Software</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Daftar master software yang tersedia untuk kebutuhan instalasi laboratorium ICT.
                </p>
            </div>

            @if($isSupervisor)
                <a href="{{ route('softwares.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"></path></svg>
                    Tambah Software
                </a>
            @endif
        </div>
    </section>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4 sm:p-5">
            <form method="GET" action="{{ route('softwares.index') }}" class="grid gap-3 lg:grid-cols-[1fr_180px_260px_auto]">
                
                {{-- 1. Input Search --}}
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"></path></svg>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari software" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                </div>

                {{-- 2. Dropdown Kategori --}}
                <x-custom-select
                    name="kategori"
                    label="Semua Kategori"
                    :selected="request('kategori')"
                    :autosubmit="true"
                    :options="[
                        ['value' => '',  'label' => 'Semua Kategori'],
                        ['value' => '1', 'label' => 'Level 1 (Spek Rendah)'],
                        ['value' => '2', 'label' => 'Level 2 (Spek Sedang)'],
                        ['value' => '3', 'label' => 'Level 3 (Spek Tinggi)'],
                    ]" />

                {{-- 3. Dropdown Filter Laboratorium & Tombol Cetak PDF --}}
                <div class="flex items-center gap-2">
                    <x-custom-select
                        name="laboratorium"
                        label="Semua Laboratorium"
                        :selected="request('laboratorium')"
                        :autosubmit="true"
                        :options="array_merge(
                            [['value' => '', 'label' => 'Semua Laboratorium']],
                            $laboratoriums->map(fn($l) => ['value' => $l->no_lab, 'label' => $l->no_lab])->toArray()
                        )" />

                    {{-- Proteksi Tombol Cetak dari Sisi UI --}}
                    @if($canPrint)
                        @if(request()->filled('laboratorium'))
                            <a href="{{ route('cetak.laporan.lab', request('laboratorium')) }}" target="_blank" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-600 text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/30" title="Cetak PDF Lab {{ request('laboratorium') }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                            </a>
                        @else
                            <button type="button" disabled class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 border border-slate-200 text-slate-400 cursor-not-allowed" title="Silakan pilih laboratorium terlebih dahulu">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                            </button>
                        @endif
                    @endif
                </div>

                {{-- 4. Blok Tombol Cari & Reset --}}
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-900">
                        Cari
                    </button>
                    @if(request('search') || request('kategori') || request('laboratorium'))
                        <a href="{{ route('softwares.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
                            Atur Ulang
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Software</th>
                        <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Versi</th>
                        <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Kategori</th>
                        <th class="px-5 py-4 text-left text-xs font-black uppercase tracking-[0.14em] text-slate-500">Status Instalasi</th>
                        @if($isSupervisor)
                            <th class="px-5 py-4 text-right text-xs font-black uppercase tracking-[0.14em] text-slate-500">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($softwares as $item)
                        @php $meta = $levelMeta($item->keterangan); @endphp
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-950 text-sm font-black text-white shadow-sm">
                                        {{ substr($item->nama_software, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-slate-950">{{ $item->nama_software }}</p>
                                            @if($item->instalasis->isEmpty())
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-md">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    Belum Terinstal
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 text-xs font-medium text-slate-500">{{ $item->id_software }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-5 py-4">
                                <div class="flex max-w-xs flex-wrap gap-1.5">
                                    @if(request()->filled('laboratorium'))
                                        @forelse($item->instalasis as $inst)
                                            <span class="rounded-full bg-blue-50 border border-blue-200 px-2.5 py-1 text-xs font-bold text-blue-700">
                                                v{{ $inst->versi_terinstall }}
                                            </span>
                                        @empty
                                            <span class="text-xs italic text-slate-400">Belum terinstal</span>
                                        @endforelse
                                    @else
                                        @foreach($item->versi as $v)
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">{{ $v }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-bold ring-1 {{ $meta['class'] }}">{{ $meta['label'] }} · {{ $meta['desc'] }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 ring-1 ring-slate-200">
                                    @if($item->instalasis && $item->instalasis->count() > 0)
                                        Terpasang di: {{ implode(', ', $item->instalasis->pluck('no_lab')->unique()->toArray()) }}
                                    @else
                                        Belum Terinstal di Lab Manapun
                                    @endif
                                </span>
                            </td>
                            @if($isSupervisor)
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('softwares.edit', $item->id) }}"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                            title="Edit {{ $item->nama_software }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>

                        <form id="delete-form-{{ $item->id }}" action="{{ route('softwares.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openDeleteModal('delete-form-{{ $item->id }}')"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20"
                                title="Hapus {{ $item->nama_software }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isSupervisor ? 5 : 4 }}" class="px-5 py-16">
                                <div class="mx-auto max-w-sm text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path></svg>
                                    </div>
                                    <h3 class="mt-4 text-lg font-black text-slate-950">Belum ada software / Tidak ditemukan</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">Data master software kosong atau kata kunci pencarian tidak cocok.</p>
                                    @if($isSupervisor)
                                        <a href="{{ route('softwares.create') }}" class="mt-5 inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-900">Tambah Software</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <p>Menampilkan {{ $softwares->firstItem() ?? 0 }} - {{ $softwares->lastItem() ?? 0 }} dari {{ $softwares->total() }} software</p>
            <div class="flex items-center gap-1">
                @if ($softwares->onFirstPage())
                    <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400 bg-slate-50">Sebelumnya</button>
                @else
                    <a href="{{ $softwares->previousPageUrl() }}" class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-700 transition hover:bg-slate-50">Sebelumnya</a>
                @endif

                <span class="rounded-lg bg-blue-950 px-3 py-1.5 font-bold text-white">{{ $softwares->currentPage() }}</span>

                @if ($softwares->hasMorePages())
                    <a href="{{ $softwares->nextPageUrl() }}" class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-700 transition hover:bg-slate-50">Berikutnya</a>
                @else
                    <button type="button" disabled class="rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-400 bg-slate-50">Berikutnya</button>
                @endif
            </div>
        </div>
    </section>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div id="deleteModalContent" class="w-full max-w-sm scale-95 rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300">
        <div class="flex flex-col items-center gap-4 text-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50 border-[6px] border-red-50 text-red-500 mb-2">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Hapus Software Ini?</h3>
                <p class="mt-2 text-sm text-slate-500 leading-relaxed">Data master software yang sudah dihapus tidak dapat dikembalikan lagi ke sistem. Pastikan keputusan Anda sudah benar.</p>
            </div>
            <div class="mt-4 flex w-full gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                    Batal
                </button>
                <button type="button" id="confirmDeleteBtn" class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentFormIdToSubmit = null;
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');

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