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
                    <span class="text-slate-950">User Manager</span>
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
            <p class="mt-2 text-2xl font-black text-slate-950">{{ $users->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-blue-500">Supervisor</p>
            <p class="mt-2 text-2xl font-black text-slate-950">{{ $users->where('role', 'supervisor')->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-600">Admin</p>
            <p class="mt-2 text-2xl font-black text-slate-950">{{ $users->where('role', 'admin')->count() }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-emerald-500">Dosen</p>
            <p class="mt-2 text-2xl font-black text-slate-950">{{ $users->where('role', 'dosen')->count() }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-[0.12em] text-slate-400 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">No Induk</th>
                        <th scope="col" class="px-6 py-4">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-4">Email</th>
                        <th scope="col" class="px-6 py-4">No HP</th>
                        <th scope="col" class="px-6 py-4">Hak Akses / Role</th>
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
                                {{ $item->nama }}
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
                                    <a href="{{ route('users.edit', $item->id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-blue-600 focus:outline-none" title="Edit User">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    
                                    {{-- Form Hapus dengan ID Unik --}}
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('users.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        {{-- Tombol Type Button yang memanggil Modal JS --}}
                                        <button type="button" onclick="openDeleteModal('delete-form-{{ $item->id }}')" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-600 shadow-sm transition hover:bg-red-100 focus:outline-none" title="Hapus User">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a1 1 0 001 1h4a1 1 0 001-1V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v1M10 11v6"></path></svg>
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
                                    <p class="text-sm font-bold text-slate-900">Belum Anda Data User</p>
                                    <p class="text-xs text-slate-500">Silakan tambahkan data pengguna baru sistem terlebih dahulu.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
    let currentFormIdToSubmit = null;
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalContent = document.getElementById('deleteModalContent');

    function openDeleteModal(formId) {
        currentFormIdToSubmit = formId;
        // Munculkan background overlay
        deleteModal.classList.remove('opacity-0', 'pointer-events-none');
        // Efek zoom in pop up
        deleteModalContent.classList.remove('scale-95');
    }

    function closeDeleteModal() {
        currentFormIdToSubmit = null;
        // Sembunyikan background overlay
        deleteModal.classList.add('opacity-0', 'pointer-events-none');
        // Efek zoom out pop up
        deleteModalContent.classList.add('scale-95');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (currentFormIdToSubmit) {
            document.getElementById(currentFormIdToSubmit).submit();
        }
    });
</script>
@endsection