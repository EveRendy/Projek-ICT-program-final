@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto space-y-6">
    
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-black tracking-tight text-slate-950 uppercase">Edit Lisensi</h2>
            <p class="text-sm font-medium text-slate-500">Perbarui informasi lisensi software pada laboratorium.</p>
        </div>
        <div>
            <a href="{{ route('instalasi.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-rose-800">Terdapat kesalahan pengisian:</h3>
                    <ul class="mt-1 list-inside list-disc text-sm font-medium text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        
        <form action="{{ route('instalasi.update', $instalasi->id ?? $instalasi->id_instalasi) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilih Software *</label>
                <x-custom-select
                    name="id_software"
                    label="-- Pilih Software --"
                    :selected="$instalasi->id_software"
                    :options="array_merge(
                        [['value' => '', 'label' => '-- Pilih Software --']],
                        $softwares->map(fn($sw) => ['value' => $sw->id_software, 'label' => $sw->nama_software])->toArray()
                    )" />
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Laboratorium *</label>
                <x-custom-select
                    name="no_lab"
                    label="-- Pilih Laboratorium --"
                    :selected="$instalasi->no_lab"
                    :options="array_merge(
                        [['value' => '', 'label' => '-- Pilih Laboratorium --']],
                        $laboratoriums->map(fn($l) => ['value' => $l->no_lab, 'label' => $l->nama_lab . ' (' . $l->no_lab . ')'])->toArray()
                    )" />
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Lisensi *</label>
                <x-custom-select
                    name="status_lisensi"
                    label="Pilih Status"
                    :selected="$instalasi->status_lisensi"
                    :options="[
                        ['value' => 'license_active',  'label' => 'Lisensi Aktif (Berbayar/Subs)'],
                        ['value' => 'free_license',    'label' => 'Lisensi Gratis (Open Source)'],
                        ['value' => 'license_expired', 'label' => 'Lisensi Kedaluwarsa'],
                    ]" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Aktif</label>
                    <input type="date" name="tgl_aktif" 
                           value="{{ $instalasi->tgl_aktif ? $instalasi->tgl_aktif->format('Y-m-d') : '' }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Expired</label>
                    <input type="date" name="tgl_expired" 
                           value="{{ $instalasi->tgl_expired ? $instalasi->tgl_expired->format('Y-m-d') : '' }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5 mt-2">
                <button type="submit" class="w-full sm:w-auto rounded-xl bg-blue-950 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-900 shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection