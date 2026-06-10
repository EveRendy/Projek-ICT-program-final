@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto space-y-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-black tracking-tight text-slate-950 uppercase">Tambah Ruang Lab</h2>
            <p class="text-sm font-medium text-slate-500">Masukkan data laboratorium baru untuk dikelola pada sistem.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">
            <div class="mb-2 font-bold">Terdapat beberapa masalah:</div>
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('labs.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="no_lab" class="mb-2 block text-sm font-semibold text-slate-700">Nomor / Nama Lab</label>
                <input type="text" id="no_lab" name="no_lab" value="{{ old('no_lab') }}" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                       placeholder="Contoh: LAB01">
            </div>

            <div>
                <label for="level" class="mb-2 block text-sm font-semibold text-slate-700">Level Spesifikasi Komputer</label>
                <select id="level" name="level" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <option value="1">Level 1 (Spesifikasi Standar / Rendah)</option>
                    <option value="2">Level 2 (Spesifikasi Menengah)</option>
                    <option value="3">Level 3 (Spesifikasi Tinggi / Multimedia)</option>
                </select>
            </div>

            <div>
                <label for="jumlah_pc" class="mb-2 block text-sm font-semibold text-slate-700">Jumlah PC</label>
                <input type="number" id="jumlah_pc" name="jumlah_pc" value="{{ old('jumlah_pc') }}" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                       placeholder="Contoh: 30">
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-950 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-950/20">
                    Simpan Ruang Lab
                </button>
                <a href="{{ route('labs.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-500/10">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection