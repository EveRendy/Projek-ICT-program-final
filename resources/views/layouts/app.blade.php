<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lab - ICT Terpadu</title>
    
    {{-- TAG FAVICON UNTUK LOGO DI TAB BROWSER --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('img/ict.jpg.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
@php
    $user = Auth::user();
    $role = $user->role ?? 'user';
    $baseItem = 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-blue-500/20';
    $inactiveItem = 'text-slate-600 hover:bg-slate-100 hover:text-slate-950';
    $activeItem = 'border border-blue-100 bg-blue-50 text-blue-950 shadow-sm';

    $menuItems = [
        ['label' => 'Home', 'route' => 'dashboard', 'href' => route('dashboard'), 'icon' => 'home'],
    ];

    if ($role === 'supervisor') {
        $menuItems = array_merge($menuItems, [
            ['label' => 'List Software', 'route' => 'softwares.*', 'href' => route('softwares.index'), 'icon' => 'list'],
            ['label' => 'Pengajuan', 'route' => 'supervisor.pengajuan.*', 'href' => route('supervisor.pengajuan.index'), 'icon' => 'request'],
            ['label' => 'Update Pengerjaan', 'route' => 'admin.tugas.*', 'href' => route('admin.tugas.index'), 'icon' => 'refresh'],
            ['label' => 'Riwayat', 'route' => 'riwayat.index', 'href' => route('riwayat.index'), 'icon' => 'history'],
            ['label' => 'License Tracker', 'route' => 'instalasi.*', 'href' => route('instalasi.index'), 'icon' => 'shield'],
            ['label' => 'User Manager', 'route' => 'users.*', 'href' => route('users.index'), 'icon' => 'users'],
            ['label' => 'Manajemen Lab', 'route' => 'labs.*', 'href' => route('labs.index'), 'icon' => 'building'],
        ]);
    } elseif ($role === 'admin') {
        $menuItems = array_merge($menuItems, [
            ['label' => 'List Software', 'route' => 'softwares.*', 'href' => route('softwares.index'), 'icon' => 'list'],
            ['label' => 'Riwayat', 'route' => 'riwayat.index', 'href' => route('riwayat.index'), 'icon' => 'history'],
            
            // 1. KITA UBAH BARIS INI AGAR MENGARAH KE DATA PROGRESS (SAMA SEPERTI SUPERVISOR)
            ['label' => 'Update Pengerjaan', 'route' => 'admin.tugas.*', 'href' => route('admin.tugas.index'), 'icon' => 'refresh'],
            
            // 2. KITA TAMBAHKAN MENU BARU KHUSUS UNTUK DATA YANG SUDAH SELESAI/GAGAL (Tugas yang sudah lewat)
            ['label' => 'Riwayat Penyelesaian', 'route' => 'admin.penyelesaian.*', 'href' => route('admin.penyelesaian.index'), 'icon' => 'status'],
            
            ['label' => 'License Tracker', 'route' => 'instalasi.*', 'href' => route('instalasi.index'), 'icon' => 'shield'],
        ]);
   } elseif ($role === 'dosen') {
        $menuItems = array_merge($menuItems, [
            ['label' => 'List Software', 'route' => 'softwares.*', 'href' => route('softwares.index'), 'icon' => 'list'],
            
            // Mengarah ke form input langsung
            ['label' => 'Pengajuan', 'route' => 'pengajuan.index', 'href' => route('pengajuan.create'), 'icon' => 'request'],
            
            // Mengarah ke halaman list tracking approval dari SPV
            ['label' => 'Status Pengajuan', 'route' => 'pengajuan.status', 'href' => route('pengajuan.status'), 'icon' => 'status'],
            
            // Mengarah ke tabel horizontal riwayat pengajuan milik dosen
            ['label' => 'Riwayat', 'route' => 'riwayat.index', 'href' => route('riwayat.index'), 'icon' => 'history'],
        ]);
    }

    $renderIcon = function ($icon) {
        $class = 'h-5 w-5 shrink-0';
        return match ($icon) {
            'home' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10"></path></svg>',
            'list' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path></svg>',
            'request' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6M7 3h7l5 5v13H7z"></path></svg>',
            'refresh' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"></path></svg>',
            'history' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2M3 12a9 9 0 109-9M3 4v5h5"></path></svg>',
            'shield' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4v5c0 5-3.4 8.7-8 9-4.6-.3-8-4-8-9V7l8-4zM9 12l2 2 4-4"></path></svg>',
            'users' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 20a8 8 0 0116 0"></path></svg>',
            'building' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1M9 11h1M9 15h1M14 7h1M14 11h1M14 15h1M3 21h18"></path></svg>',
            'status' => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7M4 4h16v16H4z"></path></svg>',
            default => '<svg class="'.$class.'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>',
        };
    };
@endphp

<div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
    <aside class="fixed inset-y-0 left-0 z-30 hidden w-[280px] border-r border-slate-200 bg-white lg:flex lg:flex-col">
        <div class="flex h-full flex-col">
            <div class="border-b border-slate-100 p-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/ict.jpg.png') }}" alt="Logo Lab ICT" class="h-11 w-11 rounded-xl object-contain bg-white p-0.5 border border-slate-100 shadow-sm">
                    <div>
                        <p class="text-sm font-black tracking-tight text-slate-950">Laboratorium ICT</p>
                        <p class="text-xs font-medium text-slate-500">Request Instalasi Software</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 space-y-1.5 overflow-y-auto p-4">
                <p class="px-3 pb-2 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Menu</p>
                @foreach($menuItems as $item)
                    @php
                        $isActive = $item['route'] ? request()->routeIs($item['route']) : false;
                    @endphp
                    <a href="{{ $item['href'] }}" class="{{ $baseItem }} {{ $isActive ? $activeItem : $inactiveItem }}">
                        <span class="{{ $isActive ? 'text-blue-700' : 'text-slate-500 group-hover:text-slate-900' }}">{!! $renderIcon($item['icon']) !!}</span>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-slate-100 p-4">
                <details class="relative">
                    <summary class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 transition hover:bg-slate-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-950 text-white shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 20a8 8 0 0116 0"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold capitalize text-slate-950">{{ $user->nama ?? $role }}</p>
                            <p class="truncate text-xs font-medium capitalize text-slate-500">{{ $role }}</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"></path></svg>
                    </summary>
                    <div class="mt-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
                        <p class="truncate px-3 py-2 text-xs text-slate-500">{{ $user->email }}</p>
                        <form action="{{ route('logout') }}" method="POST" class="px-1 pb-1">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-rose-600 px-3 py-2 text-sm font-bold text-white transition hover:bg-rose-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </aside>

    <div class="min-w-0 lg:col-start-2">
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <details class="relative lg:hidden">
                    <summary class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 shadow-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        Menu
                    </summary>
                    <div class="absolute left-0 top-12 w-72 rounded-2xl border border-slate-200 bg-white p-3 shadow-xl">
                        @foreach($menuItems as $item)
                            @php
                                $isActive = $item['route'] ? request()->routeIs($item['route']) : false;
                            @endphp
                            <a href="{{ $item['href'] }}" class="{{ $baseItem }} {{ $isActive ? $activeItem : $inactiveItem }}">
                                <span>{!! $renderIcon($item['icon']) !!}</span>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </details>

                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Dashboard</p>
                    <h1 class="truncate text-lg font-black text-slate-950">Sistem Request Instalasi Software</h1>
                </div>
            </div>
        </header>

        <main class="min-h-[calc(100vh-4rem)]">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>