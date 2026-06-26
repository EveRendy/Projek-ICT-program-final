@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    {{-- ALERT BERHASIL --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm">
            <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ALERT GAGAL --}}
    @if(session('error'))
        <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800 shadow-sm">
            <svg class="h-5 w-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- HEADER --}}
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                    <span>/</span>
                    <span class="text-slate-950">CPU dan VGA</span>
                </nav>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">
                    CPU dan VGA
                </h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Kelola seluruh komponen hardware laboratorium seperti merk CPU, VGA, dan spesifikasinya.
                </p>
            </div>
        </div>
    </section>

    {{-- TABS --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center gap-2 border-b border-slate-200 pb-4 mb-6">
            <button id="tab-cpu" onclick="switchTab('cpu')" class="tab-button inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-bold transition focus:outline-none focus:ring-2 focus:ring-slate-900/20 bg-slate-900 text-white">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                CPU
            </button>
            <button id="tab-vga" onclick="switchTab('vga')" class="tab-button inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-bold transition hover:bg-slate-100 text-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-900/20">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                VGA
            </button>
        </div>

        {{-- CPU TAB CONTENT --}}
        <div id="content-cpu" class="tab-content">
            {{-- FILTER SECTION --}}
            <div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-4 sm:p-5 cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition" onclick="toggleCpuFilter()">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Filter Hardware CPU</h3>
                        <p class="text-xs text-slate-500 font-medium">Pilih merk dan tipe yang ingin ditampilkan</p>
                    </div>
                    <div class="ml-auto flex items-center gap-3">
                        <button onclick="event.stopPropagation(); resetCpuFilters()" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset Filter
                        </button>
                        <svg id="cpuFilterArrow" class="h-6 w-6 text-slate-500 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path></svg>
                    </div>
                </div>
                <div id="cpuFilterContent" class="px-4 sm:px-5 pb-4 sm:pb-5 border-t border-slate-200/50">
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach($cpuBrands as $brand)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/30 p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ strtolower($brand->name) === 'intel' ? 'bg-blue-100 text-blue-700' : (strtolower($brand->name) === 'amd' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700') }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                </div>
                                <h4 class="text-sm font-black text-slate-900">{{ $brand->name }}</h4>
                            </div>
                            <div class="flex flex-wrap gap-2" id="cpu-brand-{{ $brand->id }}-filters">
                                <label class="group flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" class="cpu-brand-filter peer sr-only" data-brand-id="{{ $brand->id }}" checked>
                                    <div class="h-4 w-4 flex items-center justify-center rounded-lg border-2 border-slate-300 peer-checked:border-slate-700 peer-checked:bg-slate-700 transition-all">
                                        <svg class="h-2.5 w-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 transition-colors peer-checked:text-slate-900">Tampilkan Merk Ini</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button onclick="showModal('add', null, 'cpu', 'brand', '', null, '')" class="mb-6 w-full flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Merk CPU Baru
            </button>

            <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2" id="cpu-brands-container">
                @foreach($cpuBrands as $brand)
                <div class="cpu-brand-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition" data-brand-id="{{ $brand->id }}">
                    <div class="flex items-start justify-between mb-5 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ strtolower($brand->name) === 'intel' ? 'bg-blue-100 text-blue-700' : (strtolower($brand->name) === 'amd' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700') }}">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">{{ $brand->name }}</h3>
                                <p class="text-xs font-semibold text-slate-500">Merk CPU</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="showModal('edit', {{ $brand->id }}, '{{ $brand->category }}', '{{ $brand->type }}', '{{ $brand->name }}', null, '')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button onclick="confirmDelete({{ $brand->id }})" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        {{-- CPU Generations --}}
                        <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-2">
                            @foreach($brand->children->where('type', 'generation') as $gen)
                            <div class="group flex items-center justify-between rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm cpu-type-container">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800">{{ $gen->name }}</span>
                                    <span class="text-[10px] text-black">Base Score: {{ $gen->base_score ?? '-' }}</span>
                                </div>
                                <div class="flex gap-1">
                                    <button onclick="showModal('edit', {{ $gen->id }}, '{{ $gen->category }}', '{{ $gen->type }}', '{{ $gen->name }}', {{ $gen->parent_id }}, '', '{{ $gen->base_score }}')" class="text-blue-600 hover:text-blue-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button onclick="confirmDelete({{ $gen->id }})" class="text-red-600 hover:text-red-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- ADD NEW CPU GENERATION BUTTON --}}
                        <button onclick="showModal('add', null, 'cpu', 'generation', '', {{ $brand->id }}, '')" class="w-full mt-4 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none flex items-center justify-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tambah Generasi CPU Baru
                        </button>
                    </div>
                </div>
                @endforeach
                
                {{-- ADD NEW CPU BRAND CARD --}}
                <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-white p-6 shadow-sm hover:border-blue-300 hover:bg-blue-50/30 transition cursor-pointer flex items-center justify-center" onclick="showModal('add', null, 'cpu', 'brand', '', null, '')">
                    <div class="text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-800">Tambah Merk CPU Baru</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- VGA TAB CONTENT --}}
        <div id="content-vga" class="tab-content hidden">
            {{-- FILTER SECTION --}}
            <div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-4 sm:p-5 cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition" onclick="toggleVgaFilter()">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Filter Hardware VGA</h3>
                        <p class="text-xs text-slate-500 font-medium">Pilih merk dan seri yang ingin ditampilkan</p>
                    </div>
                    <div class="ml-auto flex items-center gap-3">
                        <button onclick="event.stopPropagation(); resetVgaFilters()" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset Filter
                        </button>
                        <svg id="vgaFilterArrow" class="h-6 w-6 text-slate-500 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path></svg>
                    </div>
                </div>
                <div id="vgaFilterContent" class="px-4 sm:px-5 pb-4 sm:pb-5 border-t border-slate-200/50">
                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach($vgaBrands as $brand)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/30 p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg 
                                    @if(str_contains(strtolower($brand->name), 'nvidia')) bg-green-100 text-green-700
                                    @elseif(str_contains(strtolower($brand->name), 'amd')) bg-red-100 text-red-700
                                    @elseif(str_contains(strtolower($brand->name), 'intel')) bg-blue-100 text-blue-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <h4 class="text-sm font-black text-slate-900">{{ $brand->name }}</h4>
                            </div>
                            @php
                                $brandSeries = $brand->children->where('type', 'series');
                                $mainGroups = [];
                                foreach($brandSeries as $series) {
                                    $name = $series->name;
                                    // Extract main group name (without numbers)
                                    if (preg_match('/^(GT|GTX|RTX|HD|RX|Radeon R|Arc)/i', $name, $matches)) {
                                        $mainGroup = trim($matches[1]);
                                        // Normalize group names
                                        if ($mainGroup === 'GT') $mainGroup = 'GT Series';
                                        if ($mainGroup === 'GTX') $mainGroup = 'GTX Series';
                                        if ($mainGroup === 'RTX') $mainGroup = 'RTX Series';
                                        if ($mainGroup === 'HD') $mainGroup = 'HD Series';
                                        if ($mainGroup === 'RX') $mainGroup = 'RX Series';
                                        if ($mainGroup === 'Radeon R') $mainGroup = 'Radeon R Series';
                                        if ($mainGroup === 'Arc') $mainGroup = 'Arc Series';
                                        if (!isset($mainGroups[$mainGroup])) {
                                            $mainGroups[$mainGroup] = [];
                                        }
                                        $mainGroups[$mainGroup][] = $series->id;
                                    } else {
                                        if (!isset($mainGroups[$name])) {
                                            $mainGroups[$name] = [];
                                        }
                                        $mainGroups[$name][] = $series->id;
                                    }
                                }
                            @endphp
                            <div class="flex flex-wrap gap-2" id="vga-brand-{{ $brand->id }}-filters">
                                @foreach($mainGroups as $groupName => $seriesIds)
                                <label class="group flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" class="vga-series-filter peer sr-only" data-brand-id="{{ $brand->id }}" data-series-ids="{{ implode(',', $seriesIds) }}" checked>
                                    <div class="h-4 w-4 flex items-center justify-center rounded-lg border-2 border-slate-300 peer-checked:border-slate-700 peer-checked:bg-slate-700 transition-all">
                                        <svg class="h-2.5 w-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 transition-colors peer-checked:text-slate-900">{{ $groupName }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button onclick="showModal('add', null, 'vga', 'brand', '', null, '')" class="mb-6 w-full flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Merk VGA Baru
            </button>

            <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-2" id="vga-brands-container">
                @foreach($vgaBrands as $brand)
                <div class="vga-brand-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition" data-brand-id="{{ $brand->id }}">
                    <div class="flex items-start justify-between mb-5 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl @if(str_contains(strtolower($brand->name), 'nvidia')) bg-green-100 text-green-700 @elseif(str_contains(strtolower($brand->name), 'amd')) bg-red-100 text-red-700 @elseif(str_contains(strtolower($brand->name), 'intel')) bg-blue-100 text-blue-700 @else bg-slate-100 text-slate-700 @endif">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900">{{ $brand->name }}</h3>
                                <p class="text-xs font-semibold text-slate-500">Merk VGA</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="showModal('edit', {{ $brand->id }}, '{{ $brand->category }}', '{{ $brand->type }}', '{{ $brand->name }}', null, '')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button onclick="confirmDelete({{ $brand->id }})" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        {{-- VGA SERIES --}}
                        <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-2">
                            @foreach($brand->children->where('type', 'series') as $series)
                            <div class="group flex items-center justify-between rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-sm vga-series-container" data-series-id="{{ $series->id }}">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800">{{ $series->name }}</span>
                                    <span class="text-[10px] text-black">Base Score: {{ $series->base_score ?? '-' }}</span>
                                </div>
                                <div class="flex gap-1">
                                    <button onclick="showModal('edit', {{ $series->id }}, '{{ $series->category }}', '{{ $series->type }}', '{{ $series->name }}', {{ $series->parent_id }}, '', '{{ $series->base_score }}')" class="text-blue-600 hover:text-blue-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button onclick="confirmDelete({{ $series->id }})" class="text-red-600 hover:text-red-700">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- ADD NEW VGA SERIES BUTTON --}}
                        <button onclick="showModal('add', null, 'vga', 'series', '', {{ $brand->id }}, '')" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none flex items-center justify-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Tambah Seri VGA Baru
                        </button>
                    </div>
                </div>
                @endforeach
                
                {{-- ADD NEW VGA BRAND CARD --}}
                <div class="rounded-2xl border-2 border-dashed border-slate-300 bg-white p-6 shadow-sm hover:border-purple-300 hover:bg-purple-50/30 transition cursor-pointer flex items-center justify-center" onclick="showModal('add', null, 'vga', 'brand', '', null, '')">
                    <div class="text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-800">Tambah Merk VGA Baru</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hardware --}}
<div id="hardwareModal" class="fixed inset-0 z-50 overflow-y-auto opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="hardwareModalContent" class="relative z-10 w-full max-w-md scale-95 rounded-3xl border border-slate-200 bg-white shadow-2xl overflow-hidden transition-transform duration-300">
            {{-- Header --}}
            <div class="border-b border-slate-100 px-6 py-4 bg-slate-50">
                <div class="flex items-center justify-between">
                    <h3 id="modalTitle" class="text-lg font-black text-slate-950">Tambah Hardware</h3>
                    <button type="button" onclick="hideModal()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            
            {{-- Body --}}
            <div class="px-6 py-5">
                <form id="hardwareForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="modalMethod" value="POST">
                    
                    {{-- Category --}}
                    <div id="categorySelectContainer">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <div id="categoryDisplay" class="hidden">
                            <div class="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 bg-slate-50" id="categoryDisplayText"></div>
                            <input type="hidden" id="modalCategory" name="category">
                        </div>
                        <div id="categoryDropdown">
                            <x-custom-select
                                id="modalCategorySelect"
                                name="category"
                                label="Pilih Kategori"
                                :options="[
                                    ['value' => 'cpu', 'label' => 'CPU'],
                                    ['value' => 'vga', 'label' => 'VGA']
                                ]"
                                selected=""
                                required="true"
                                onchange="handleTypeChange"
                            />
                        </div>
                    </div>
                    
                    {{-- Type --}}
                    <div id="typeSelectContainer">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tipe</label>
                        <div id="typeDisplay" class="hidden">
                            <div class="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 bg-slate-50" id="typeDisplayText"></div>
                            <input type="hidden" id="modalType" name="type">
                        </div>
                        <div id="typeDropdown">
                            <x-custom-select
                                id="modalTypeSelect"
                                name="type"
                                label="Pilih Tipe"
                                :options="[
                                    ['value' => 'brand', 'label' => 'Brand'],
                                    ['value' => 'generation', 'label' => 'Generasi (CPU)'],
                                    ['value' => 'series', 'label' => 'Seri (VGA)']
                                ]"
                                selected=""
                                required="true"
                                onchange="handleTypeChange"
                                :options="[['value' => 'brand', 'label' => 'Brand'], ['value' => 'generation', 'label' => 'Generasi (CPU)'], ['value' => 'series', 'label' => 'Seri (VGA)']]"
                                selected=""
                                required="true"
                                onchange="handleTypeChange"
                            />
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama</label>
                        <input type="text" id="modalName" name="name" required class="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 shadow-sm transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Masukkan nama hardware">
                    </div>
                    
                    <div id="baseScoreField">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Base Score (1-100)</label>
                        <input type="number" id="modalBaseScore" name="base_score" min="1" max="100" class="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 shadow-sm transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Skor Performa">
                    </div>
                    
                    <div id="vramField" class="hidden">
                        <label class="block text-sm font-bold text-slate-700 mb-2">VRAM</label>
                        <input type="text" id="modalVram" name="vram" class="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 placeholder-slate-400 shadow-sm transition hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Contoh: 8GB">
                    </div>
                    
                    <input type="hidden" name="parent_id" id="modalParentId">
                </form>
            </div>
            
            <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between gap-2">
                <button type="button" onclick="hideModal()" class="rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500/20">Batal</button>
                <button type="submit" form="hardwareForm" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/20">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div id="deleteModalContent" class="relative z-10 w-full max-w-sm scale-95 rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <div class="flex flex-col items-center gap-4 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50 border-[6px] border-red-50 text-red-500 mb-2">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77-1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Hapus Hardware Ini?</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">Data hardware yang dihapus tidak bisa dikembalikan dan semua komponen turunannya juga akan dihapus.</p>
                </div>
                <div class="mt-4 flex w-full gap-3">
                    <button type="button" onclick="hideDeleteModal()" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500/20">Batal</button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const cpuTab = document.getElementById('tab-cpu');
        const vgaTab = document.getElementById('tab-vga');
        const cpuContent = document.getElementById('content-cpu');
        const vgaContent = document.getElementById('content-vga');
        
        localStorage.setItem('activeHardwareTab', tab);
        
        if (tab === 'cpu') {
            cpuTab.classList.remove('text-slate-600', 'hover:bg-slate-100');
            cpuTab.classList.add('bg-slate-900', 'text-white');
            vgaTab.classList.remove('bg-slate-900', 'text-white');
            vgaTab.classList.add('text-slate-600', 'hover:bg-slate-100');
            cpuContent.classList.remove('hidden');
            vgaContent.classList.add('hidden');
        } else {
            vgaTab.classList.remove('text-slate-600', 'hover:bg-slate-100');
            vgaTab.classList.add('bg-slate-900', 'text-white');
            cpuTab.classList.remove('bg-slate-900', 'text-white');
            cpuTab.classList.add('text-slate-600', 'hover:bg-slate-100');
            vgaContent.classList.remove('hidden');
            cpuContent.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedTab = localStorage.getItem('activeHardwareTab');
        if (savedTab) {
            switchTab(savedTab);
        }
        document.querySelectorAll('.vga-series-filter').forEach(checkbox => {
            checkbox.addEventListener('change', applyVgaFilters);
        });
    });

    function applyVgaFilters() {
        let selectedSeriesIds = [];
        document.querySelectorAll('.vga-series-filter:checked').forEach(cb => {
            const ids = cb.dataset.seriesIds.split(',');
            selectedSeriesIds = selectedSeriesIds.concat(ids);
        });

        document.querySelectorAll('.vga-brand-card').forEach(card => {
            const brandId = card.dataset.brandId;
            const hasSelectedSeries = Array.from(document.querySelectorAll('.vga-series-filter:checked')).some(cb => cb.dataset.brandId === brandId);
            
            if (hasSelectedSeries) {
                card.classList.remove('hidden');
                card.querySelectorAll('.vga-series-container').forEach(seriesContainer => {
                    const seriesId = seriesContainer.dataset.seriesId;
                    if (selectedSeriesIds.includes(seriesId)) {
                        seriesContainer.classList.remove('hidden');
                    } else {
                        seriesContainer.classList.add('hidden');
                    }
                });
                const allSeriesHidden = Array.from(card.querySelectorAll('.vga-series-container')).every(el => el.classList.contains('hidden'));
                if (allSeriesHidden) card.classList.add('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }

    function setCustomSelect(uid, value, label) {
        const valInput = document.getElementById('val_' + uid);
        const lblSpan = document.getElementById('lbl_' + uid);
        const menu = document.getElementById('menu_' + uid);
        
        if (valInput) valInput.value = value;
        if (lblSpan) lblSpan.textContent = label || value;
        
        if (menu) {
            menu.querySelectorAll('[onclick]').forEach(el => {
                const isActive = el.getAttribute('onclick').includes("'" + value + "'");
                el.classList.toggle('bg-blue-50', isActive);
                el.classList.toggle('text-blue-700', isActive);
                el.classList.toggle('font-bold', isActive);
                el.classList.toggle('text-slate-700', !isActive);
                el.classList.toggle('font-semibold', !isActive);
            });
        }
    }

    function handleTypeChange(value, selectedElement) {
        const vramField = document.getElementById('vramField');
        const baseScoreField = document.getElementById('baseScoreField');
        const modalBaseScore = document.getElementById('modalBaseScore');
        let category = document.getElementById('val_modalCategorySelect')?.value || document.getElementById('modalCategory')?.value;
        
        if (value === 'series' && category === 'vga') {
            vramField.classList.remove('hidden');
        } else {
            vramField.classList.add('hidden');
        }

        if (value === 'brand') {
            baseScoreField.classList.add('hidden');
            modalBaseScore.value = '';
        } else {
            baseScoreField.classList.remove('hidden');
        }
    }

    const hardwareModal = document.getElementById('hardwareModal');
    const hardwareModalContent = document.getElementById('hardwareModalContent');
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');

    function showModal(mode, id = null, category = '', type = '', name = '', parentId = '', vram = '', base_score = '') {
        const modalTitle = document.getElementById('modalTitle');
        const modalMethod = document.getElementById('modalMethod');
        const modalName = document.getElementById('modalName');
        const modalBaseScore = document.getElementById('modalBaseScore');
        const modalVram = document.getElementById('modalVram');
        const modalParentId = document.getElementById('modalParentId');
        const form = document.getElementById('hardwareForm');

        const categoryDisplay = document.getElementById('categoryDisplay');
        const categoryDropdown = document.getElementById('categoryDropdown');
        const typeDisplay = document.getElementById('typeDisplay');
        const typeDropdown = document.getElementById('typeDropdown');

        if (mode === 'edit') {
            modalTitle.textContent = 'Edit Hardware';
            modalMethod.value = 'PUT';
            form.action = '/hardware/' + id;
            modalName.value = name;
            modalBaseScore.value = base_score;
            modalParentId.value = parentId;
            modalVram.value = vram;
            
            const categoryLabel = category === 'cpu' ? 'CPU' : 'VGA';
            document.getElementById('categoryDisplayText').textContent = categoryLabel;
            document.getElementById('modalCategory').value = category;
            categoryDisplay.classList.remove('hidden');
            categoryDropdown.classList.add('hidden');
            
            const typeLabels = {'brand': 'Brand', 'generation': 'Generasi (CPU)', 'series': 'Seri (VGA)'};
            document.getElementById('typeDisplayText').textContent = typeLabels[type];
            document.getElementById('modalType').value = type;
            typeDisplay.classList.remove('hidden');
            typeDropdown.classList.add('hidden');
            document.getElementById('vramField').classList.add('hidden');
            
            if (type === 'brand') {
                document.getElementById('baseScoreField').classList.add('hidden');
            } else {
                document.getElementById('baseScoreField').classList.remove('hidden');
            }
        } else {
            modalTitle.textContent = 'Tambah Hardware';
            modalMethod.value = 'POST';
            form.action = '/hardware';
            modalName.value = name;
            modalParentId.value = parentId;
            modalVram.value = vram;
            
            if (category && type) {
                // If both category and type are provided, display as text
                const categoryLabel = category === 'cpu' ? 'CPU' : 'VGA';
                document.getElementById('categoryDisplayText').textContent = categoryLabel;
                document.getElementById('modalCategory').value = category;
                categoryDisplay.classList.remove('hidden');
                categoryDropdown.classList.add('hidden');
                // Remove name from dropdown input to avoid duplicate
                const categorySelectInput = document.getElementById('val_modalCategorySelect');
                if (categorySelectInput) categorySelectInput.removeAttribute('name');
                
                const typeLabels = {
                    'brand': 'Brand',
                    'generation': 'Generasi (CPU)',
                    'series': 'Seri (VGA)'
                };
                document.getElementById('typeDisplayText').textContent = typeLabels[type];
                document.getElementById('modalType').value = type;
                typeDisplay.classList.remove('hidden');
                typeDropdown.classList.add('hidden');
                // Remove name from dropdown input to avoid duplicate
                const typeSelectInput = document.getElementById('val_modalTypeSelect');
                if (typeSelectInput) typeSelectInput.removeAttribute('name');
                
                if (type === 'series' && category === 'vga') {
                    document.getElementById('vramField').classList.remove('hidden');
                } else {
                    document.getElementById('vramField').classList.add('hidden');
                }
                
                if (type === 'brand') {
                    document.getElementById('baseScoreField').classList.add('hidden');
                } else {
                    document.getElementById('baseScoreField').classList.remove('hidden');
                }
            } else {
                // Show dropdowns for selection
                categoryDisplay.classList.add('hidden');
                categoryDropdown.classList.remove('hidden');
                // Restore name to dropdown input
                const categorySelectInput = document.getElementById('val_modalCategorySelect');
                if (categorySelectInput) categorySelectInput.setAttribute('name', 'category');
                
                typeDisplay.classList.add('hidden');
                typeDropdown.classList.remove('hidden');
                // Restore name to dropdown input
                const typeSelectInput = document.getElementById('val_modalTypeSelect');
                if (typeSelectInput) typeSelectInput.setAttribute('name', 'type');
                
                // Reset selects
                setCustomSelect('modalCategorySelect', '', 'Pilih Kategori');
                setCustomSelect('modalTypeSelect', '', 'Pilih Tipe');
                document.getElementById('vramField').classList.add('hidden');
                document.getElementById('baseScoreField').classList.remove('hidden');
            }
        }

        hardwareModal.classList.remove('opacity-0', 'pointer-events-none');
        hardwareModalContent.classList.remove('scale-95');
        document.body.style.overflow = 'hidden';
    }

    function hideModal() {
        hardwareModal.classList.add('opacity-0', 'pointer-events-none');
        hardwareModalContent.classList.add('scale-95');
        document.body.style.overflow = '';
    }

    function confirmDelete(id) {
        const form = document.getElementById('deleteForm');
        form.action = '/hardware/' + id;
        deleteModal.classList.remove('opacity-0', 'pointer-events-none');
        deleteModalContent.classList.remove('scale-95');
        document.body.style.overflow = 'hidden';
    }

    function hideDeleteModal() {
        deleteModal.classList.add('opacity-0', 'pointer-events-none');
        deleteModalContent.classList.add('scale-95');
        document.body.style.overflow = '';
    }
</script>
@endsection