@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-2xl font-black tracking-tight text-slate-950 mb-6">Edit Data Lab</h2>

        {{-- Menampilkan Alert Error Validasi --}}
        @if ($errors->any())
            <div class="mb-6 flex gap-3 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-800 shadow-sm">
                <svg class="h-5 w-5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <span class="font-semibold block mb-1">Terdapat kesalahan:</span>
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('labs.update', $lab->id) }}" method="POST" class="flex flex-col gap-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor / Nama Lab</label>
                <input type="text" name="no_lab" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition" value="{{ $lab->no_lab }}" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Level Spesifikasi Komputer</label>
                <select name="level" class="block w-full appearance-none rounded-xl border border-slate-300 bg-white px-4 py-2.5 pr-10 text-sm text-slate-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20fill=%22none%22%20viewBox=%220%200%2024%2024%22%20stroke-width=%222%22%20stroke=%22%2364748b%22%3E%3Cpath%20stroke-linecap=%22round%22%20stroke-linejoin=%22round%22%20d=%22M19.5%208.25l-7.5%207.5-7.5-7.5%22/%3E%3C/svg%3E')] bg-[position:right_1rem_center] bg-no-repeat bg-[length:1em_1em]" required>
                    <option value="1" {{ $lab->level == 1 ? 'selected' : '' }}>Level 1 (Spesifikasi Rendah)</option>
                    <option value="2" {{ $lab->level == 2 ? 'selected' : '' }}>Level 2 (Spesifikasi Menengah)</option>
                    <option value="3" {{ $lab->level == 3 ? 'selected' : '' }}>Level 3 (Spesifikasi Tinggi)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah PC</label>
                <input type="number" name="jumlah_pc" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition" value="{{ $lab->jumlah_pc }}" required>
            </div>
            
            <div class="mt-2 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    Simpan Perubahan
                </button>
                <a href="{{ route('labs.index') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection