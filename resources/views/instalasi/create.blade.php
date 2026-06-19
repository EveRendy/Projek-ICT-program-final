@extends('layouts.app')

@section('content')
<div class="mx-auto w-full max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
            <span>/</span>
            <a href="{{ route('instalasi.index') }}" class="transition hover:text-blue-700">Tracker Lisensi</a>
            <span>/</span>
            <span class="text-slate-950">Tambah Lisensi</span>
        </nav>
        <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Catat Instalasi Software Baru</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">Tambahkan data lisensi software baru pada laboratorium.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 mt-6">
        <form action="{{ route('instalasi.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Pilih Laboratorium</label>
                <x-custom-select
                    name="no_lab"
                    label="-- Pilih Lab --"
                    :selected="old('no_lab')"
                    :options="array_merge(
                        [['value' => '', 'label' => '-- Pilih Lab --']],
                        $laboratoriums->map(fn($l) => ['value' => $l->no_lab, 'label' => $l->no_lab . ' - ' . $l->nama_lab])->toArray()
                    )" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Pilih Software</label>
                <x-custom-select
                    name="id_software"
                    label="-- Pilih Software --"
                    :selected="old('id_software')"
                    :options="array_merge(
                        [['value' => '', 'label' => '-- Pilih Software --']],
                        $softwares->map(fn($sw) => ['value' => $sw->id_software, 'label' => $sw->nama_software])->toArray()
                    )" />
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Status Lisensi</label>
                <x-custom-select
                    name="status_lisensi"
                    label="Pilih Status"
                    :selected="old('status_lisensi', 'free_license')"
                    :options="[
                        ['value' => 'free_license',     'label' => 'Lisensi Gratis / Open Source'],
                        ['value' => 'license_active',   'label' => 'Lisensi Aktif (Berbayar)'],
                        ['value' => 'license_expired',  'label' => 'Lisensi Kedaluwarsa'],
                    ]" />
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Tanggal Aktif (Opsional)</label>
                    <input type="date" name="tgl_aktif" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer shadow-sm">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Tanggal Expired (Opsional)</label>
                    <input type="date" name="tgl_expired" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer shadow-sm">
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('instalasi.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    Simpan Catatan Instalasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection