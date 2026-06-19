@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
            <span>/</span>
            <a href="{{ route('softwares.index') }}" class="transition hover:text-blue-700">Daftar Software</a>
            <span>/</span>
            <span class="text-slate-950">Edit</span>
        </nav>
        <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Edit Software</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">Perbarui data master software tanpa mengubah alur penyimpanan yang sudah berjalan.</p>
    </section>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-bold">Periksa kembali input berikut:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <form action="{{ route('softwares.update', $software->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">ID Software</label>
                <input type="text" name="id_software" class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-900 outline-none cursor-not-allowed" value="{{ $software->id_software }}" readonly>
                <p class="mt-2 text-xs font-medium text-slate-500">ID Software tidak dapat diubah setelah dibuat</p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Nama Software</label>
                <input type="text" name="nama_software" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10" value="{{ $software->nama_software }}" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Versi</label>
                <input type="text" name="versi_raw" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10" value="{{ $versiRaw }}" required>
                <p class="mt-2 text-xs font-medium text-slate-500">Pisahkan beberapa versi dengan tanda koma.</p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-bold text-slate-700">Level Kompatibilitas Spek Lab</label>
                <x-custom-select
                    name="keterangan"
                    label="Pilih Level"
                    :selected="old('keterangan', $software->keterangan)"
                    :options="[
                        ['value' => '1', 'label' => 'Level 1 — PC Spek Standar / Rendah'],
                        ['value' => '2', 'label' => 'Level 2 — PC Spek Menengah'],
                        ['value' => '3', 'label' => 'Level 3 — PC Spek Tinggi / Multimedia'],
                    ]" />
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('softwares.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    Perbarui Software
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
