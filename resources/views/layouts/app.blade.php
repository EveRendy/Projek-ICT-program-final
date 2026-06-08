<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Lab - ICT Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#fdfdfd] min-h-screen flex overflow-x-hidden text-gray-800">

    <aside class="w-60 bg-[#f8f9fa] border-r border-gray-200 flex flex-col justify-between fixed h-screen z-10">
        <div class="p-4">
            <div class="flex items-center gap-2.5 px-2 mb-6">
                <div class="w-8 h-8 bg-blue-900 rounded-full flex items-center justify-center text-white font-black text-[10px] border border-blue-400 shadow-sm">
                    ICT
                </div>
                <div>
                    <h2 class="text-[11px] font-extrabold text-gray-700 tracking-tight leading-tight">Laboratorium ICT</h2>
                    <p class="text-[9px] text-gray-400 font-medium uppercase tracking-wider">Terpadu</p>
                </div>
            </div>

            <nav class="space-y-0.5">
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block px-2.5 mb-1.5">MENU</span>
                
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-2.5 px-3 py-1.5 bg-white text-gray-950 rounded-lg shadow-sm text-xs font-semibold border border-gray-100 transition">
                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Home
                </a>

                @if(Auth::user()->role === 'supervisor')
                    <a href="{{ route('softwares.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        List Software
                    </a>
                    <a href="{{ route('supervisor.pengajuan.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Pengajuan
                    </a>
                @endif

                @can('is-admin')
                    <a href="{{ route('admin.tugas.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.213 6H16"></path></svg>
                        Update Pengerjaan
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat
                    </a>
                    <a href="{{ route('instalasi.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        License Tracker
                    </a>
                @endcan

                @can('is-dosen')
                    <a href="{{ route('pengajuan.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Pengajuan Baru
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Status Request
                    </a>
                @endcan
                @if(Auth::user()->role === 'supervisor')
                    <div class="border-t border-gray-200 my-2.5"></div>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        User Manager
                    </a>
                    <a href="{{ route('labs.index') }}" class="flex items-center gap-2.5 px-3 py-1.5 text-gray-600 hover:bg-gray-200/50 hover:text-gray-900 rounded-lg text-xs font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Manajemen Lab
                    </a>
                @endif
            </nav>
        </div>

        <div class="p-3 border-t border-gray-200 bg-gray-50 flex flex-col gap-2">
            <div class="flex items-center gap-2.5 px-1">
                <div class="w-7 h-7 bg-gray-900 rounded-full flex items-center justify-center text-white text-[11px] font-bold">
                    {{ substr(Auth::user()->role ?? 'U', 0, 1) }}
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-[11px] font-bold text-gray-900 truncate capitalize">{{ Auth::user()->role }}</p>
                    <p class="text-[9px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left text-[11px] font-semibold text-red-600 hover:bg-red-50 px-2 py-1.5 rounded-md transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 pl-60 min-h-screen flex flex-col">
        <main class="flex-1">
            @yield('content')
        </main>
    </div>

</body>
</html>