@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-2xl font-black tracking-tight text-slate-950 mb-6">Edit Data User</h2>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="flex flex-col gap-5">
            @csrf
            @method('PUT') 
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">No Induk (NIM / NIDN)</label>
                <input type="text" name="no_induk" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition" value="{{ $user->no_induk }}" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition" value="{{ $user->nama }}" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition" value="{{ $user->email }}" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak diganti)</span></label>
                <input type="password" name="password" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">No HP</label>
                <input type="text" name="no_hp" class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition" value="{{ $user->no_hp }}">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Role Akses</label>
                <select name="role" id="role_select" class="block w-full appearance-none rounded-xl border border-slate-300 bg-white px-4 py-2.5 pr-10 text-sm text-slate-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20fill=%22none%22%20viewBox=%220%200%2024%2024%22%20stroke-width=%222%22%20stroke=%22%2364748b%22%3E%3Cpath%20stroke-linecap=%22round%22%20stroke-linejoin=%22round%22%20d=%22M19.5%208.25l-7.5%207.5-7.5-7.5%22/%3E%3C/svg%3E')] bg-[position:right_1rem_center] bg-no-repeat bg-[length:1em_1em]" onchange="toggleLabDropdown()" required>
                    <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                </select>
            </div>

            <div id="lab_assignment_container" class="{{ $user->role == 'admin' ? 'block' : 'hidden' }}">
                <label class="block text-sm font-semibold text-blue-700 mb-1">Tugaskan di Laboratorium (Khusus Admin)</label>
                <select name="laboratorium_id" class="block w-full appearance-none rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 pr-10 text-sm text-blue-900 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20fill=%22none%22%20viewBox=%220%200%2024%2024%22%20stroke-width=%222%22%20stroke=%22%231e40af%22%3E%3Cpath%20stroke-linecap=%22round%22%20stroke-linejoin=%22round%22%20d=%22M19.5%208.25l-7.5%207.5-7.5-7.5%22/%3E%3C/svg%3E')] bg-[position:right_1rem_center] bg-no-repeat bg-[length:1em_1em]">
                    <option value="">-- Pilih Ruang Lab Tanggung Jawab --</option>
                    @foreach($laboratoriums as $lab)
                        <option value="{{ $lab->id }}" {{ (isset($currentLabId) && $currentLabId == $lab->id) ? 'selected' : '' }}>
                            {{ $lab->no_lab }} (Level {{ $lab->level }})
                            @if($lab->user_id && $lab->user_id != $user->id)
                                - (Akan merebut tugas dari: {{ $lab->admin->name ?? 'Admin Lain' }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-2 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    Simpan Perubahan
                </button>
                <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleLabDropdown() {
        const roleSelect = document.getElementById('role_select');
        const labContainer = document.getElementById('lab_assignment_container');

        if (roleSelect.value === 'admin') {
            labContainer.classList.remove('hidden');
            labContainer.classList.add('block');
        } else {
            labContainer.classList.remove('block');
            labContainer.classList.add('hidden');
        }
    }
    
    // Panggil saat load pertama kali untuk memastikan hidden/show nya sesuai dengan role awal
    document.addEventListener('DOMContentLoaded', toggleLabDropdown);
</script>
@endsection