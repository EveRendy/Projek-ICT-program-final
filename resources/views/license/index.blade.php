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
                    <span class="text-slate-950">Pelacak Lisensi</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Pilih Laboratorium</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Pilih laboratorium untuk melihat dan mengelola lisensi software di setiap PC.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Dashboard
            </a>
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

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($laboratoriums as $lab)
            @if($lab->admin)
                <a href="{{ route('license.show-lab', $lab->id) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-blue-300 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Laboratorium</p>
                            <h3 class="text-lg font-black text-slate-950">{{ $lab->no_lab }}</h3>
                            <p class="text-sm text-slate-600 mt-1">{{ $lab->nama_lab ?? 'Laboratorium ICT' }}</p>
                            <p class="text-xs text-slate-400 mt-2">Jumlah PC: {{ $lab->jumlah_pc }}</p>
                            @if($lab->admin)
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="font-bold">{{ $lab->admin->nama }}</span>
                            </p>
                            @endif
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1M9 11h1M9 15h1M14 7h1M14 11h1M14 15h1M3 21h18"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-sm font-bold text-blue-700">
                        <span>Lihat PC</span>
                        <svg class="h-4 w-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            @else
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm opacity-60 cursor-not-allowed">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Laboratorium</p>
                            <h3 class="text-lg font-black text-slate-950">{{ $lab->no_lab }}</h3>
                            <p class="text-sm text-slate-600 mt-1">{{ $lab->nama_lab ?? 'Laboratorium ICT' }}</p>
                            <p class="text-xs text-slate-400 mt-2">Jumlah PC: {{ $lab->jumlah_pc }}</p>
                            <p class="text-xs text-orange-500 mt-1 flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Belum memiliki admin
                            </p>
                        </div>
                        <div class="h-12 w-12 rounded-xl bg-slate-200 flex items-center justify-center text-slate-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1M9 11h1M9 15h1M14 7h1M14 11h1M14 15h1M3 21h18"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-sm font-bold text-slate-500">
                        <span>Lihat PC</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection
