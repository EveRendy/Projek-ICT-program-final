@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-2xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <nav class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="transition hover:text-blue-700">Dashboard</a>
            <span>/</span>
            <a href="{{ route('users.index') }}" class="transition hover:text-blue-700">Kelola User</a>
            <span>/</span>
            <span class="text-slate-950">Tambah User</span>
        </nav>
        <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Tambah User Baru</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Buat akun pengguna baru dan tentukan hak akses laboratorium untuk peran Dosen, Admin, atau Supervisor.
        </p>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                <p class="mb-1 font-bold">Terjadi kesalahan pengisian data:</p>
                <ul class="list-inside list-disc font-medium text-red-600/90 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="flex flex-col gap-5">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700">No Induk</label>
                <input type="text" name="no_induk" value="{{ old('no_induk') }}" required
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Password</label>
                <input type="password" name="password" required
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Role Pengguna</label>
                <select name="role" id="role_select" onchange="toggleLabDropdown()" required
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 transition cursor-pointer">
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                </select>
            </div>

            <div id="lab_assignment_container" class="hidden rounded-2xl border border-blue-100 bg-blue-50/50 p-4 transition-all">
                <label class="block text-sm font-extrabold text-blue-900">Tugaskan di Laboratorium (Khusus Admin)</label>
                <select name="laboratorium_id"
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 transition cursor-pointer">
                    <option value="">-- Pilih Ruang Lab Tanggung Jawab --</option>
                    @foreach($laboratoriums as $lab)
                        <option value="{{ $lab->id }}" {{ old('laboratorium_id') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->no_lab }} (Level {{ $lab->level }}) 
                            {{ $lab->admin ? ' - Saat ini dijaga: '.$lab->admin->nama : ' - (Belum ada admin)' }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-2 text-xs font-medium text-slate-500 leading-relaxed">
                    * Catatan: Memilih lab yang sudah ada penjaganya akan otomatis menggantikan posisi admin lama di lab tersebut.
                </p>
            </div>

            <hr class="my-2 border-slate-100">

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    Simpan User
                </button>
                <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </section>
</div>

<script>
    function toggleLabDropdown() {
        const roleSelect = document.getElementById('role_select');
        const labContainer = document.getElementById('lab_assignment_container');

        if (roleSelect && roleSelect.value === 'admin') {
            labContainer.classList.remove('hidden');
        } else {
            labContainer.classList.add('hidden');
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        toggleLabDropdown();
    });
</script>
@endsection