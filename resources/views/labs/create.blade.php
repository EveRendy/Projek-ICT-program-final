@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 uppercase">
                    @if(auth()->user()->role === 'supervisor')
                        Form Tambah Ruang Lab (Supervisor)
                    @else
                        Form Pengajuan Ruang Lab
                    @endif
                </h2>
                <p class="text-sm font-medium text-slate-500 mt-1">
                    @if(auth()->user()->role === 'supervisor')
                        Input spesifikasi teknis laboratorium baru yang akan langsung diaktifkan ke dalam sistem.
                    @else
                        Isi spesifikasi teknis untuk diajukan kepada Supervisor.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 shadow-sm flex gap-3 items-start">
        <svg class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
        </svg>
        <div>
            <span class="font-bold">Informasi Alur Pengisian:</span>
            @if(auth()->user()->role === 'supervisor')
                Anda masuk sebagai <span class="font-black underline">Supervisor</span>. Data laboratorium yang Anda buat akan langsung berstatus Aktif/Disetujui.
            @else
                Pengajuan lab akan disimpan sebagai draf menunggu tinjauan Supervisor. Setelah disetujui, data lab beserta spesifikasi hardware akan aktif di sistem.
            @endif
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
        <form action="{{ route('labs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="no_lab" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Lab <span class="text-red-500">*</span></label>
                    <input type="text" id="no_lab" name="no_lab" value="{{ old('no_lab') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                        placeholder="Contoh: LAB01"
                        oninput="this.value = this.value.replace(/\s/g, '').toUpperCase()"
                        style="text-transform: uppercase; letter-spacing: 0.5px;">
                    <p class="mt-1 text-xs text-slate-400">Otomatis kapital & tanpa spasi. Contoh: <strong>LAB01</strong>, <strong>LAB-TI</strong></p>
                </div>
                <div>
                    <label for="nama_lab" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lab <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_lab" name="nama_lab" value="{{ old('nama_lab') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                        placeholder="Contoh: Laboratorium Teknik Informatika">
                </div>
                <div>
                    <label for="jumlah_pc" class="mb-2 block text-sm font-semibold text-slate-700">Jumlah PC</label>
                    <input type="number" id="jumlah_pc" name="jumlah_pc" value="{{ old('jumlah_pc') }}" required min="1"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                        placeholder="Contoh: 30">
                </div>
            </div>

            @include('labs.partials.hardware-form', ['hardware' => $hardware, 'selectedSpecs' => [], 'labLevel' => ''])

            <hr class="border-slate-100">

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-950 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-4 focus:ring-blue-950/20">
                    @if(auth()->user()->role === 'supervisor')
                        Simpan Master Lab Baru
                    @else
                        Kirim Pengajuan ke Supervisor
                    @endif
                </button>
                <a href="{{ route('labs.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
