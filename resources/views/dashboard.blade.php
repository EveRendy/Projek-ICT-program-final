@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1200px] mx-auto min-h-[85vh] flex flex-col justify-between">
    
    @if(Auth::user()->role === 'supervisor')
        <div class="bg-gray-50 border border-gray-100 rounded-xl p-5 flex justify-between items-center mb-6 relative overflow-hidden shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-gray-950 tracking-tight">REQUEST INSTALASI SOFTWARE</h1>
                <p class="text-sm text-gray-600 font-medium mt-0.5">Laboratorium ICT Terpadu</p>
                <p class="text-[10px] text-gray-400 mt-2 font-mono bg-gray-200/50 inline-block px-1.5 py-0.5 rounded">
                    ID: {{ Auth::user()->no_induk }}
                </p>
            </div>
            <div class="hidden sm:block w-20 h-20 text-gray-300">
                <svg viewBox="0 0 24 24" fill="none" class="w-full h-full" stroke="currentColor" stroke-width="1.2">
                    <rect x="2" y="3" width="20" height="12" rx="2"></rect>
                    <path d="M12 15v4M8 19h8M2 11h20"></path>
                </svg>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            
            <div class="border border-gray-200 rounded-xl p-3.5 flex items-start gap-3 bg-white shadow-xs">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg border border-blue-100 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Total Pengajuan</p>
                    <p class="text-xl font-bold text-gray-900 my-0.5">{{ $totalPengajuan ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400">Semua Waktu</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl p-3.5 flex items-start gap-3 bg-white shadow-xs">
                <div class="p-2 bg-amber-50 text-amber-500 rounded-lg border border-amber-100 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Menunggu Instalasi</p>
                    <p class="text-xl font-bold text-gray-900 my-0.5">{{ $menungguInstalasi ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400">Perlu dikerjakan</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl p-3.5 flex items-start gap-3 bg-white shadow-xs">
                <div class="p-2 bg-emerald-50 text-emerald-500 rounded-lg border border-emerald-100 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.213 6H16"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Sedang Diinstal</p>
                    <p class="text-xl font-bold text-gray-900 my-0.5">{{ $sedangDiinstal ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400">Sedang Berlangsung</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl p-3.5 flex items-start gap-3 bg-white shadow-xs">
                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg border border-purple-100 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400">Selesai</p>
                    <p class="text-xl font-bold text-gray-900 my-0.5">{{ $selesai ?? 0 }}</p>
                    <p class="text-[10px] text-gray-400">Instalasi selesai</p>
                </div>
            </div>
        </div>
    @endif
    <div class="text-center my-auto py-12">
        <h2 class="text-2xl font-normal text-gray-800 tracking-tight">
            Selamat Datang <span class="capitalize font-bold text-gray-950">{{ Auth::user()->role }}</span> !
        </h2>
        @if(Auth::user()->role !== 'supervisor')
            <p class="text-xs text-gray-400 mt-1.5 font-medium tracking-wide">
                Nomor Induk: {{ Auth::user()->no_induk }} &nbsp;|&nbsp; Email: {{ Auth::user()->email }}
            </p>
        @endif
    </div>
    @if(Auth::user()->role === 'supervisor')
        <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex items-center gap-4 shadow-xs max-w-2xl mx-auto w-full mt-auto">
            <div class="p-3 bg-blue-900 text-white rounded-xl shadow-md shadow-blue-100">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Hari Ini</p>
                <p class="text-xl font-black text-blue-950 my-px">{{ $pengajuanHariIni ?? 0 }}</p>
                <p class="text-[10px] text-blue-600 font-semibold">Pengajuan Baru</p>
            </div>
        </div>
    @endif
    </div>
@endsection