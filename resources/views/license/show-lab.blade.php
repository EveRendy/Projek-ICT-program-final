@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700 flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                        Dashboard
                    </a>
                    <span>/</span>
                    <a href="{{ route('license.index') }}" class="transition hover:text-blue-700">Pelacak Lisensi</a>
                    <span>/</span>
                    <span class="text-slate-950">{{ $laboratorium->no_lab }}</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">{{ $laboratorium->no_lab }} - {{ $laboratorium->nama_lab ?? 'Laboratorium ICT' }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Pilih aplikasi untuk melihat lisensi di setiap PC.
                </p>
                @if($laboratorium->admin)
                <p class="mt-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Admin: <span class="font-bold">{{ $laboratorium->admin->nama }}</span>
                </p>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('license.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali ke Pilih Lab
                </a>
                <button type="button" onclick="toggleModal('modal-tambah-lisensi-manual', true)" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Lisensi Manual
                </button>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" id="filterForm" class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Cari Software</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Cari nama software...">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Tipe Lisensi</label>
                <x-custom-select
                    name="license_type"
                    label="Semua Tipe"
                    :options="[
                        ['value' => '', 'label' => 'Semua Tipe'],
                        ['value' => 'paid', 'label' => 'Berbayar'],
                        ['value' => 'free', 'label' => 'Gratis'],
                    ]"
                    :selected="request('license_type')"
                />
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Filter
                </button>
                <a href="{{ route('license.show-lab', $laboratorium->id) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Reset
                </a>
            </div>
        </form>
    </section>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($softwares as $software)
            @php
                $counts = $softwareLicenseCounts[$software->id] ?? ['paid' => 0, 'free' => 0, 'opensource' => 0];
                $totalLicenses = array_sum($counts);
            @endphp
            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-blue-300 hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-950">{{ $software->nama_software }}</h3>
                            <p class="text-xs text-slate-500">{{ $software->id_software }}</p>
                            @if($software->category)
                                <p class="text-xs font-semibold text-blue-600 mt-1">{{ $software->category }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-300 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                            {{ $totalLicenses }} PC
                        </span>
                        <button type="button" onclick="openDeleteSoftwareModal({{ $laboratorium->id }}, {{ $software->id }})" class="inline-flex items-center justify-center rounded-xl border border-rose-300 bg-white p-1.5 text-rose-600 shadow-sm transition hover:bg-rose-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                
                <!-- Tampilkan status lisensi -->
                @if($totalLicenses > 0)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($counts['paid'] > 0)
                            <span class="inline-flex items-center gap-1 rounded-full border border-amber-300 bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Berbayar: {{ $counts['paid'] }}
                            </span>
                        @endif
                        @if($counts['free'] > 0)
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-300 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Gratis: {{ $counts['free'] }}
                            </span>
                        @endif
                    </div>
                @else
                    <p class="mt-4 text-xs text-slate-400">Belum ada lisensi</p>
                @endif
                
                <a href="{{ route('license.show-software', [$laboratorium->id, $software->id]) }}" class="mt-4 flex items-center gap-2 text-sm font-bold text-blue-700 group-hover:translate-x-1 transition">
                    <span>Lihat Lisensi</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        @endforeach
    </div>

<!-- Modal Delete Software -->
<div id="modal-delete-software" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-delete-software', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative z-10 w-full max-w-sm rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden">
            <div class="border-b border-slate-100 bg-rose-50 px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Hapus</p>
                    <h3 class="text-lg font-black text-slate-950">Hapus Semua Lisensi Software Ini?</h3>
                </div>
                <button type="button" onclick="toggleModal('modal-delete-software', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-slate-600">Semua lisensi untuk software ini di laboratorium ini akan dihapus dan tidak bisa dikembalikan.</p>
            </div>
            <form id="form-delete-software" method="POST" class="px-6 pb-5 flex justify-end gap-2">
                @csrf
                @method('DELETE')
                <button type="button" onclick="toggleModal('modal-delete-software', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">Hapus</button>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Modal Tambah Lisensi Manual -->
<div id="modal-tambah-lisensi-manual" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-tambah-lisensi-manual', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl transform rounded-3xl bg-white shadow-2xl transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-lg font-black text-slate-950">Tambah Lisensi Manual di {{ $laboratorium->no_lab }}</h3>
                <button type="button" onclick="toggleModal('modal-tambah-lisensi-manual', false)" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('license.store') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="laboratorium_id" value="{{ $laboratorium->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Pilih Software <span class="text-red-500">*</span></label>
                        <select name="software_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" required>
                            <option value="">-- Pilih Software --</option>
                            @foreach($allSoftware as $sw)
                                <option value="{{ $sw->id }}">{{ $sw->nama_software }} ({{ $sw->id_software }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nomor PC <span class="text-red-500">*</span></label>
                        <input type="hidden" name="pc_number" value="all">
                        <div class="w-full rounded-xl border border-slate-300 bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-600 flex items-center gap-2 cursor-not-allowed">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                            Terapkan ke Semua PC (Otomatis)
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Tipe Lisensi <span class="text-red-500">*</span></label>
                        <x-custom-select
                            name="license_type"
                            id="manual_license_type"
                            label="-- Pilih Tipe --"
                            :options="[
                                ['value' => 'paid', 'label' => 'Berbayar'],
                                ['value' => 'free', 'label' => 'Gratis'],
                            ]"
                            :selected="'paid'"
                            required="true"
                        />
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Akun Lisensi (Opsional)</label>
                        <input type="text" name="license_account" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Email / Username lisensi">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Password Lisensi (Opsional)</label>
                        <input type="text" name="license_password" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Password lisensi (jika ada)">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kode Unik / Serial (Opsional)</label>
                        <input type="text" name="unique_code" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Serial number / Product key">
                    </div>

                    <div id="manual_date_fields" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Tanggal Aktif <span class="text-red-500">*</span></label>
                            <input type="date" name="active_date" id="manual_active_date" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" value="{{ date('Y-m-d') }}">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Tanggal Berakhir <span class="text-red-500">*</span></label>
                            <input type="date" name="expiry_date" id="manual_expiry_date" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" value="{{ date('Y-m-d', strtotime('+1 year')) }}">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 rounded-2xl bg-slate-50 p-4">
                    <button type="button" onclick="toggleModal('modal-tambah-lisensi-manual', false)" class="rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm border border-slate-200 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                        Simpan Lisensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(modalID, show) {
    const modal = document.getElementById(modalID);
    if (!modal) return;
    if (show) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function toggleDateFieldsManual() {
    const hiddenInput = document.getElementById('val_manual_license_type');
    const dateFields = document.getElementById('manual_date_fields');
    const activeInput = document.getElementById('manual_active_date');
    const expiryInput = document.getElementById('manual_expiry_date');
    
    if (!dateFields || !hiddenInput) return;
    
    const value = hiddenInput.value;
    
    if (value === 'free' || value === 'opensource') {
        dateFields.classList.add('hidden');
        if (activeInput) activeInput.removeAttribute('required');
        if (expiryInput) expiryInput.removeAttribute('required');
    } else {
        dateFields.classList.remove('hidden');
        if (activeInput) activeInput.setAttribute('required', 'required');
        if (expiryInput) expiryInput.setAttribute('required', 'required');
    }
}

window.openDeleteSoftwareModal = function(labId, softwareId) {
    const form = document.getElementById('form-delete-software');
    form.action = `/license/${labId}/software/${softwareId}/destroy`;
    toggleModal('modal-delete-software', true);
};

document.addEventListener('DOMContentLoaded', function() {
    // Listen for changes on the hidden input of the license_type custom-select
    const manualInput = document.getElementById('val_manual_license_type');
    if (manualInput) {
        manualInput.addEventListener('change', function() {
            toggleDateFieldsManual();
        });

        // Also observe attribute changes in case change event doesn't fire
        const observer = new MutationObserver(function() {
            toggleDateFieldsManual();
        });
        observer.observe(manualInput, { attributes: true, attributeFilter: ['value'] });
    }

    // Set initial state: default is 'paid', so show date fields
    toggleDateFieldsManual();
});
</script>
@endsection
