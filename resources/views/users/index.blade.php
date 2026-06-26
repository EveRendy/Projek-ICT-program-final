    @extends('layouts.app')

    @section('content')
    <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
                        <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
                        <span>/</span>
                        <span class="text-slate-950">Manajemen User</span>
                    </nav>
                    <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Manajemen User</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola hak akses, data induk, dan informasi akun pengguna aplikasi.
                    </p>
                </div>
                <div>
                    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                        Tambah User Baru
                    </a>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengguna</p>
                <p class="mt-2 text-2xl font-black text-slate-950">
                    {{ $users->total() }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-500">Supervisor</p>
                <p class="mt-2 text-2xl font-black text-slate-950">
                    {{ \App\Models\User::where('role', 'supervisor')->count() }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Admin</p>
                <p class="mt-2 text-2xl font-black text-slate-950">
                    {{ \App\Models\User::where('role', 'admin')->count() }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-500">Dosen</p>
                <p class="mt-2 text-2xl font-black text-slate-950">
                    {{ \App\Models\User::where('role', 'dosen')->count() }}
                </p>
            </div>
        </div>

        {{-- FORM PENCARIAN & CUSTOM DROPDOWN --}}
        <form action="" method="GET" class="flex w-full flex-col gap-3 md:flex-row md:items-center">
            {{-- Input Pencarian Teks --}}
            {{-- MENGHAPUS BATAS MAKSIMAL AGAR MELAR MENGISI SELURUH SISA RUANG --}}
            <div class="relative flex-1 w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no induk..." class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm font-semibold text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            
            {{-- Custom UI Dropdown Filter Role --}}
            <div class="relative w-full md:w-48 z-20">
                {{-- Input Hidden untuk Mengirim Data ke Backend Laravel --}}
                <input type="hidden" name="role" id="hiddenRoleInput" value="{{ request('role') }}">
                
                {{-- Tombol Pemicu Dropdown --}}
                <button type="button" id="dropdownToggleBtn" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white pl-4 pr-3.5 py-2.5 text-left text-sm font-semibold text-slate-900 shadow-sm transition focus:border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <span id="dropdownSelectedLabel">
                        @if(request('role') == 'admin') Admin
                        @elseif(request('role') == 'dosen') Dosen
                        @else Semua Peran
                        @endif
                    </span>
                    <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" id="dropdownChevron" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                    </svg>
                </button>

                {{-- List Menu Pilihan Dropdown --}}
                <div id="dropdownMenu" class="absolute left-0 mt-2 hidden w-full rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl transition-all">
                    <div class="flex flex-col gap-0.5">
                        <button type="button" data-value="" class="dropdown-item w-full rounded-xl px-3 py-2 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none {{ request('role') == '' ? 'bg-slate-100 text-slate-900 font-bold' : '' }}">
                            Semua Peran
                        </button>
                        <button type="button" data-value="admin" class="dropdown-item w-full rounded-xl px-3 py-2 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none {{ request('role') == 'admin' ? 'bg-slate-100 text-slate-900 font-bold' : '' }}">
                            Admin
                        </button>
                        <button type="button" data-value="dosen" class="dropdown-item w-full rounded-xl px-3 py-2 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none {{ request('role') == 'dosen' ? 'bg-slate-100 text-slate-900 font-bold' : '' }}">
                            Dosen
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    Cari
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ url()->current() }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                        Atur Ulang
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            {{-- Table for large screens --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-400 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4">No Induk</th>
                            <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                            <th scope="col" class="px-6 py-4">Email</th>
                            <th scope="col" class="px-6 py-4">No HP</th>
                            <th scope="col" class="px-6 py-4">Hak Akses / Peran</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $item)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-700">
                                    <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 border border-slate-200">
                                        {{ $item->no_induk ?? $item->username ?? '-' }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 font-semibold text-slate-950">
                                    @if($item->nama)
                                        {{ $item->nama }}
                                    @else
                                        <span class="italic text-slate-400 font-medium">— Belum diisi —</span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ $item->email }}
                                </td>
                                
                                <td class="px-6 py-4 font-medium text-slate-500">
                                    {{ $item->no_hp ?? '-' }}
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-1">
                                        @if($item->role === 'supervisor')
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 shadow-sm">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                                Supervisor
                                            </span>
                                        @elseif($item->role === 'dosen')
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                                Dosen
                                            </span>
                                            @if($item->is_first_login)
                                                <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 mt-0.5">
                                                    ⏳ Profil Belum Dilengkapi
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-100 bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 shadow-sm">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>
                                                Admin
                                            </span>
                                            
                                            @if($item->laboratoriums && $item->laboratoriums->count() > 0)
                                                <span class="text-[11px] font-medium text-slate-500 mt-0.5">
                                                    Tugas: <strong class="text-slate-700 font-semibold">{{ $item->laboratoriums->pluck('no_lab')->implode(', ') }}</strong>
                                                </span>
                                            @else
                                                <span class="text-[11px] font-medium text-red-500 italic mt-0.5">
                                                    Belum ada lab
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Button Edit --}}
                                        <a href="{{ route('users.edit', $item->no_induk) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20" title="Edit User">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        {{-- Button Hapus --}}
                                        <form id="delete-form-{{ $item->no_induk }}" action="{{ route('users.destroy', $item->no_induk) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openDeleteModal('delete-form-{{ $item->no_induk }}')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20" title="Hapus User">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <p class="text-sm font-bold text-slate-900">Belum Ada Data User</p>
                                        <p class="text-xs text-slate-500">Silakan tambahkan data pengguna baru sistem terlebih dahulu atau sesuaikan kata kunci dan filter pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Cards for small screens --}}
            <div class="sm:hidden p-4 space-y-4">
                @forelse($users as $item)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:bg-slate-50/70 transition">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex-1">
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">No Induk</div>
                                <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 border border-slate-200 font-mono text-xs font-bold text-slate-700">
                                    {{ $item->no_induk ?? $item->username ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Lengkap</div>
                            <div class="font-semibold text-slate-950">
                                @if($item->nama)
                                    {{ $item->nama }}
                                @else
                                    <span class="italic text-slate-400 font-medium">— Belum diisi —</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email</div>
                            <div class="text-slate-600 font-medium text-sm">{{ $item->email }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">No HP</div>
                            <div class="text-slate-500 font-medium text-sm">{{ $item->no_hp ?? '-' }}</div>
                        </div>

                        <div class="mb-4">
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Hak Akses / Peran</div>
                            <div class="flex flex-col items-start gap-1">
                                @if($item->role === 'supervisor')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 shadow-sm">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                        Supervisor
                                    </span>
                                @elseif($item->role === 'dosen')
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 shadow-sm">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                        Dosen
                                    </span>
                                    @if($item->is_first_login)
                                        <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 mt-0.5">
                                            ⏳ Profil Belum Dilengkapi
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-100 bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-700 shadow-sm">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>
                                        Admin
                                    </span>
                                    
                                    @if($item->laboratoriums && $item->laboratoriums->count() > 0)
                                        <span class="text-[11px] font-medium text-slate-500 mt-0.5">
                                            Tugas: <strong class="text-slate-700 font-semibold">{{ $item->laboratoriums->pluck('no_lab')->implode(', ') }}</strong>
                                        </span>
                                    @else
                                        <span class="text-[11px] font-medium text-red-500 italic mt-0.5">
                                            Belum ada lab
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Button Edit --}}
                            <a href="{{ route('users.edit', $item->no_induk) }}" class="flex-1 inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white py-2 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20" title="Edit User">
                                Edit
                            </a>
                            
                            {{-- Button Hapus --}}
                            <form id="delete-form-sm-{{ $item->no_induk }}" action="{{ route('users.destroy', $item->no_induk) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal('delete-form-sm-{{ $item->no_induk }}')" class="w-full inline-flex items-center justify-center rounded-lg border border-red-200 bg-white py-2 text-sm font-bold text-red-600 shadow-sm transition hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20" title="Hapus User">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <svg class="h-8 w-8 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-sm font-bold text-slate-900">Belum Ada Data User</p>
                        <p class="text-xs text-slate-500">Silakan tambahkan data pengguna baru sistem terlebih dahulu atau sesuaikan kata kunci dan filter pencarian Anda.</p>
                    </div>
                @endforelse
            </div>

            @if($users->hasPages())
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex items-center justify-between">
                    <div class="hidden sm:block">
                        <p class="text-sm text-slate-600">
                            Menampilkan <span class="font-bold text-slate-900">{{ $users->firstItem() }}</span> sampai <span class="font-bold text-slate-900">{{ $users->lastItem() }}</span> dari <span class="font-bold text-slate-900">{{ $users->total() }}</span> user
                        </p>
                    </div>
                    
                    <div>
                        <nav class="inline-flex -space-x-px rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden" aria-label="Pagination">
                            @if ($users->onFirstPage())
                                <span class="inline-flex items-center px-3 py-2 text-slate-300 bg-slate-50/50 cursor-not-allowed">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                                </span>
                            @else
                                <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="inline-flex items-center px-3 py-2 text-slate-500 hover:bg-slate-50 transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                                </a>
                            @endif

                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                @if ($page == $users->currentPage())
                                    <span class="inline-flex items-center bg-blue-950 px-4 py-2 text-sm font-black text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $users->appends(request()->query())->url($page) }}" class="inline-flex items-center border-l border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($users->hasMorePages())
                                <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="inline-flex items-center border-l border-slate-200 px-3 py-2 text-slate-500 hover:bg-slate-50 transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                </a>
                            @else
                                <span class="inline-flex items-center border-l border-slate-200 px-3 py-2 text-slate-300 bg-slate-50/50 cursor-not-allowed">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
        <div id="deleteModalContent" class="w-full max-w-sm scale-95 rounded-3xl bg-white p-6 shadow-2xl transition-transform duration-300">
            <div class="flex flex-col items-center gap-4 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50 border-[6px] border-red-50 text-red-500 mb-2">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Hapus User Ini?</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">Data pengguna yang sudah dihapus tidak dapat dikembalikan lagi. Pastikan keputusan Anda sudah benar.</p>
                </div>
                <div class="mt-4 flex w-full gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                        Batal
                    </button>
                    <button type="button" id="confirmDeleteBtn" class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropdownToggleBtn = document.getElementById('dropdownToggleBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownChevron = document.getElementById('dropdownChevron');
        const dropdownSelectedLabel = document.getElementById('dropdownSelectedLabel');
        const hiddenRoleInput = document.getElementById('hiddenRoleInput');
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        dropdownToggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            dropdownChevron.classList.toggle('rotate-180');
        });

        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                const selectedValue = this.getAttribute('data-value');
                const selectedText = this.innerText.trim();

                dropdownSelectedLabel.innerText = selectedText;
                hiddenRoleInput.value = selectedValue;

                dropdownMenu.classList.add('hidden');
                dropdownChevron.classList.remove('rotate-180');
                
                this.closest('form').submit();
            });
        });

        document.addEventListener('click', function() {
            dropdownMenu.classList.add('hidden');
            dropdownChevron.classList.remove('rotate-180');
        });

        let currentFormIdToSubmit = null;
        const deleteModal = document.getElementById('deleteModal');
        const deleteModalContent = document.getElementById('deleteModalContent');

        function openDeleteModal(formId) {
            currentFormIdToSubmit = formId;
            deleteModal.classList.remove('opacity-0', 'pointer-events-none');
            deleteModalContent.classList.remove('scale-95');
        }

        function closeDeleteModal() {
            currentFormIdToSubmit = null;
            deleteModal.classList.add('opacity-0', 'pointer-events-none');
            deleteModalContent.classList.add('scale-95');
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentFormIdToSubmit) {
                document.getElementById(currentFormIdToSubmit).submit();
            }
        });
    </script>
    @endsection