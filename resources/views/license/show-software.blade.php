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
                    <a href="{{ route('license.show-lab', $laboratorium->id) }}" class="transition hover:text-blue-700">{{ $laboratorium->no_lab }}</a>
                    <span>/</span>
                    <span class="text-slate-950">{{ $software->nama_software }}</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">{{ $laboratorium->no_lab }} - {{ $software->nama_software }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Kelola lisensi {{ $software->nama_software }} di setiap PC.
                    @if($software->category)
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-300 bg-blue-100 px-3 py-0.5 text-xs font-bold text-blue-800 ml-2">
                        {{ $software->category }}
                        </span>
                    @endif
                </p>
                @if($laboratorium->admin)
                <p class="mt-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Admin: <span class="font-bold">{{ $laboratorium->admin->nama }}</span>
                </p>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('license.show-lab', $laboratorium->id) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali
                </a>
                <button type="button" onclick="toggleModal('modal-tambah-lisensi', true)" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Lisensi
                </button>
            </div>
        </div>
    </section>

    @if(session('success'))
        <div class="flex items-center justify-between gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm animate-fadeIn">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="flex flex-col gap-2 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-800 shadow-sm animate-fadeIn">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Ada kesalahan!</span>
                </div>
            </div>
            <ul class="list-disc pl-7 font-medium text-xs text-rose-700 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        @foreach($pcNumbers as $pcNumber)
            @php
                $license = $licensesByPc->get($pcNumber);
            @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-950">
                                {{ $pcNumber }}
                                @if($pcNumber === 'PC Dosen')
                                    <span class="ml-1 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 uppercase tracking-wider">Khusus Dosen</span>
                                @endif
                            </h3>
                            <p class="text-xs text-slate-500">{{ $software->nama_software }}</p>
                        </div>
                    </div>
                    @if($license)
                        <div class="flex flex-col gap-2 items-end">
                            @php
                                $now = \Illuminate\Support\Carbon::now()->startOfDay();
                                $expiry = $license->expiry_date->startOfDay();
                                $daysUntilExpiry = $now->diffInDays($expiry, false);
                            @endphp
                            
                            <!-- Status Expiry -->
                            @if($license->license_type !== 'paid')
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    Aktif (Permanen)
                                </span>
                            @else
                                @if($license->is_expired)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-red-300 bg-red-100 px-3 py-1 text-xs font-bold text-red-800">
                                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                        Kadaluarsa
                                    </span>
                                @elseif($license->is_expiring_soon)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800 relative overflow-hidden">
                                        <span class="absolute inset-0 bg-amber-200 opacity-20 animate-pulse"></span>
                                        <span class="relative flex items-center gap-1.5">
                                            <span class="h-2 w-2 rounded-full bg-amber-500 animate-ping"></span>
                                            Akan Habis ({{ $daysUntilExpiry }} hari)
                                        </span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Aktif ({{ $daysUntilExpiry }} hari)
                                    </span>
                                @endif
                            @endif
                            
                            <!-- Status License Type -->
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-{{ $license->license_type_color }}-300 bg-{{ $license->license_type_color }}-100 px-3 py-1 text-xs font-bold text-{{ $license->license_type_color }}-800">
                                {{ $license->license_type_label }}
                            </span>
                        </div>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-300 bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                            Belum Ada Lisensi
                        </span>
                    @endif
                </div>

                @if($license)
                    <div class="space-y-3">
                        @if($license->license_type !== 'free' && $license->license_type !== 'opensource')
                            <div class="grid grid-cols-2 gap-4">
                                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Masa Aktif</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $license->active_date ? $license->active_date->format('d M Y') : '-' }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Kadaluarsa</p>
                                    <p class="text-sm font-bold {{ $license->is_expiring_soon || $license->is_expired ? 'text-rose-700' : 'text-slate-800' }}">{{ $license->expiry_date ? $license->expiry_date->format('d M Y') : '-' }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($license->license_account)
                            <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Akun Lisensi</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $license->license_account }}</p>
                            </div>
                        @endif
                        
                        @if($license->license_password)
                            <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Password</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $license->license_password }}</p>
                            </div>
                        @endif
                        
                        @if($license->unique_code)
                            <div class="rounded-xl bg-slate-50 border border-slate-200 p-3">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Kode Unik</p>
                                <p class="text-sm font-semibold text-slate-800 font-mono">{{ $license->unique_code }}</p>
                            </div>
                        @endif

                        <div class="flex gap-2 mt-4 pt-4 border-t border-slate-100">
                            <button type="button" onclick="toggleModal('modal-edit-lisensi-{{ $license->id }}', true)" class="flex-1 inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </button>
                            <button type="button" onclick="openDeleteModal({{ $license->id }})" class="inline-flex items-center justify-center rounded-xl border border-red-300 bg-white px-4 py-2 text-sm font-bold text-red-700 shadow-sm transition hover:bg-red-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                @else
                    <button type="button" onclick="openAddForPc('{{ $pcNumber }}')" class="w-full inline-flex items-center justify-center rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-100">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Lisensi
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah Lisensi -->
<div id="modal-tambah-lisensi" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-tambah-lisensi', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden">
            <div class="border-b border-slate-100 bg-blue-50 px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Tambah</p>
                    <h3 class="text-lg font-black text-slate-950">Tambah Lisensi {{ $software->nama_software }}</h3>
                </div>
                <button type="button" onclick="toggleModal('modal-tambah-lisensi', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('license.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="laboratorium_id" value="{{ $laboratorium->id }}">
                <input type="hidden" name="software_id" value="{{ $software->id }}">
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Pilih PC <span class="text-rose-500">*</span></label>
                    <x-custom-select
                        name="pc_number"
                        id="pc_number"
                        label="-- Pilih PC --"
                        :options="array_merge([['value' => 'all', 'label' => 'Semua PC']], collect($pcNumbers)->map(fn($pc) => ['value' => $pc, 'label' => $pc])->toArray())"
                        :selected="old('pc_number')"
                        required="true"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Tipe Lisensi <span class="text-rose-500">*</span></label>
                    <x-custom-select
                        name="license_type"
                        id="add_license_type"
                        label="-- Pilih Tipe --"
                        :options="[
                            ['value' => 'paid', 'label' => 'Berbayar'],
                            ['value' => 'free', 'label' => 'Gratis'],
                        ]"
                        :selected="old('license_type')"
                        required="true"
                    />
                </div>

                <div id="add_date_fields" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Masa Aktif <span class="text-rose-500">*</span></label>
                        <input type="date" name="active_date" id="add_active_date" value="{{ old('active_date', date('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kadaluarsa <span class="text-rose-500">*</span></label>
                        <input type="date" name="expiry_date" id="add_expiry_date" value="{{ old('expiry_date') }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Akun Lisensi</label>
                    <input type="text" name="license_account" id="license_account" value="{{ old('license_account') }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Username atau email">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password</label>
                    <input type="text" name="license_password" id="license_password" value="{{ old('license_password') }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Password lisensi">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kode Unik (Opsional)</label>
                    <input type="text" name="unique_code" id="unique_code" value="{{ old('unique_code') }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono" placeholder="Kode unik atau serial number">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modal-tambah-lisensi', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Lisensi -->
@foreach($licensesByPc as $license)
<div id="modal-edit-lisensi-{{ $license->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-edit-lisensi-{{ $license->id }}', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative z-10 w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden">
            <div class="border-b border-slate-100 bg-amber-50 px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-amber-600">Edit</p>
                    <h3 class="text-lg font-black text-slate-950">Edit Lisensi {{ $software->nama_software }} - {{ $license->pc_number }}</h3>
                </div>
                <button type="button" onclick="toggleModal('modal-edit-lisensi-{{ $license->id }}', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('license.update', $license->id) }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Tipe Lisensi <span class="text-rose-500">*</span></label>
                    <x-custom-select
                        name="license_type"
                        id="license_type_edit_{{ $license->id }}"
                        label="-- Pilih Tipe --"
                        :options="[
                            ['value' => 'paid', 'label' => 'Berbayar'],
                            ['value' => 'free', 'label' => 'Gratis'],
                        ]"
                        :selected="$license->license_type === 'opensource' ? 'free' : $license->license_type"
                        required="true"
                    />
                </div>
                
                <div id="edit_date_fields_{{ $license->id }}" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Masa Aktif <span class="text-rose-500">*</span></label>
                        <input type="date" name="active_date" id="edit_active_date_{{ $license->id }}" value="{{ $license->active_date ? $license->active_date->format('Y-m-d') : '' }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kadaluarsa <span class="text-rose-500">*</span></label>
                        <input type="date" name="expiry_date" id="edit_expiry_date_{{ $license->id }}" value="{{ $license->expiry_date ? $license->expiry_date->format('Y-m-d') : '' }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Akun Lisensi</label>
                    <input type="text" name="license_account" value="{{ $license->license_account }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Username atau email">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Password</label>
                    <input type="text" name="license_password" value="{{ $license->license_password }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Password lisensi">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kode Unik (Opsional)</label>
                    <input type="text" name="unique_code" value="{{ $license->unique_code }}" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-medium text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 font-mono" placeholder="Kode unik atau serial number">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button" onclick="toggleModal('modal-edit-lisensi-{{ $license->id }}', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Delete -->
<div id="modal-delete" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-delete', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative z-10 w-full max-w-sm rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden">
            <div class="border-b border-slate-100 bg-rose-50 px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Hapus</p>
                    <h3 class="text-lg font-black text-slate-950">Hapus Lisensi?</h3>
                </div>
                <button type="button" onclick="toggleModal('modal-delete', false)" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-200 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-slate-600">Data lisensi yang dihapus tidak bisa dikembalikan.</p>
            </div>
            <form id="form-delete" method="POST" data-base-action="{{ url('license') }}" class="px-6 pb-5 flex justify-end gap-2">
                @csrf
                @method('DELETE')
                <button type="button" onclick="toggleModal('modal-delete', false)" class="rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-100 transition">Batal</button>
                <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
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

function toggleDateFields(prefix) {
    let dateFields, activeInput, expiryInput, hiddenInput;
    
    if (prefix === 'add') {
        hiddenInput = document.getElementById('val_add_license_type');
        dateFields = document.getElementById('add_date_fields');
        activeInput = document.getElementById('add_active_date');
        expiryInput = document.getElementById('add_expiry_date');
    } else {
        hiddenInput = document.getElementById('val_license_type_edit_' + prefix);
        dateFields = document.getElementById('edit_date_fields_' + prefix);
        activeInput = document.getElementById('edit_active_date_' + prefix);
        expiryInput = document.getElementById('edit_expiry_date_' + prefix);
    }
    
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

function openDeleteModal(licenseId) {
    const form = document.getElementById('form-delete');
    const baseAction = form.getAttribute('data-base-action');
    form.action = baseAction + '/' + licenseId + '/destroy';
    toggleModal('modal-delete', true);
}

function openAddForPc(pcNumber) {
    pickCustomSelect('pc_number', pcNumber, pcNumber, false);
    toggleModal('modal-tambah-lisensi', true);
    // Sync date fields visibility after opening
    setTimeout(function() { toggleDateFields('add'); }, 50);
}

document.addEventListener('DOMContentLoaded', function() {
    // --- Modal Tambah Lisensi ---
    const addInput = document.getElementById('val_add_license_type');
    if (addInput) {
        addInput.addEventListener('change', function() {
            toggleDateFields('add');
        });
    }

    // --- Modal Edit Lisensi (per license) ---
    @foreach($licensesByPc as $license)
    (function() {
        const editInput = document.getElementById('val_license_type_edit_{{ $license->id }}');
        if (editInput) {
            editInput.addEventListener('change', function() {
                toggleDateFields('{{ $license->id }}');
            });
            // Set initial state based on current license type
            toggleDateFields('{{ $license->id }}');
        }
    })();
    @endforeach

    // Set initial state for add modal
    toggleDateFields('add');
});
</script>
@endsection
