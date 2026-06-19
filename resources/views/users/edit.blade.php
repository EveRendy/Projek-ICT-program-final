@extends('layouts.app')

@section('content')
<div class="mx-auto flex w-full max-w-3xl flex-col gap-6 px-4 py-6 sm:px-6 lg:px-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-2xl font-black tracking-tight text-slate-950 mb-6">Edit Data User</h2>

        {{-- PERBAIKAN DI SINI: Mengubah $user->id menjadi $user->no_induk --}}
        <form action="{{ route('users.update', $user->no_induk) }}" method="POST" class="flex flex-col gap-5">
            @csrf
            @method('PUT') 
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">No Induk (NIM / NIDN)</label>
                <input type="text" name="no_induk"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                    value="{{ $user->no_induk }}" required maxlength="20"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')">
                <p class="mt-1 text-xs text-slate-400">Maks. 20 karakter, hanya huruf dan angka.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                    value="{{ $user->nama }}" required maxlength="100"
                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '').replace(/\b\w/g, c => c.toUpperCase())">
                <p class="mt-1 text-xs text-slate-400">Maks. 100 karakter, hanya huruf.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" name="email"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                    value="{{ $user->email }}" required maxlength="100"
                    placeholder="contoh@lab.com">
                <p class="mt-1 text-xs text-slate-400">Maks. 100 karakter. Harus domain @lab.com.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak diganti)</span></label>
                <input type="password" name="password" minlength="6" maxlength="100"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                    placeholder="Min. 6 karakter">
                <p class="mt-1 text-xs text-slate-400">Min. 6, maks. 100 karakter.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">No HP</label>
                <input type="text" name="no_hp"
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition"
                    value="{{ $user->no_hp }}" required maxlength="15" minlength="10"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 15)"
                    placeholder="Contoh: 081234567890">
                <p class="mt-1 text-xs text-slate-400">10–15 digit angka saja.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Peran Akses</label>
                <x-custom-select
                    id="role_select"
                    name="role"
                    label="Pilih Peran"
                    :selected="$user->role"
                    :options="[
                        ['value' => 'dosen',      'label' => 'Dosen'],
                        ['value' => 'admin',      'label' => 'Admin'],
                        ['value' => 'supervisor', 'label' => 'Supervisor'],
                    ]" />
            </div>

            <div id="lab_assignment_container" class="{{ $user->role == 'admin' ? 'block' : 'hidden' }}">
                <label class="block text-sm font-semibold text-blue-700 mb-1">Tugaskan di Laboratorium (Khusus Admin)</label>
                <x-custom-select
                    name="laboratorium_id"
                    label="-- Pilih Ruang Lab Tanggung Jawab --"
                    :selected="$currentLabId ?? ''"
                    :options="array_merge(
                        [['value' => '', 'label' => '-- Pilih Ruang Lab Tanggung Jawab --']],
                        $laboratoriums->map(fn($lab) => [
                            'value' => $lab->id,
                            'label' => $lab->no_lab . ' (Level ' . $lab->level . ')' . ($lab->user_id && $lab->user_id != $user->no_induk ? ' — Sudah ada admin lain' : ''),
                        ])->toArray()
                    )" />
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
        // Baca dari hidden input yang dikelola x-custom-select
        const roleInput    = document.getElementById('val_role_select') ?? document.querySelector('[name="role"]');
        const labContainer = document.getElementById('lab_assignment_container');
        if (!roleInput || !labContainer) return;

        if (roleInput.value === 'admin') {
            labContainer.classList.remove('hidden');
            labContainer.classList.add('block');
        } else {
            labContainer.classList.remove('block');
            labContainer.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleLabDropdown();
        // Pantau perubahan hidden input role dari custom select
        const roleInput = document.querySelector('[name="role"]');
        if (roleInput) {
            const observer = new MutationObserver(toggleLabDropdown);
            observer.observe(roleInput, { attributes: true, attributeFilter: ['value'] });
            roleInput.addEventListener('change', toggleLabDropdown);
        }
        // Override pickCustomSelect agar trigger toggleLabDropdown setelah pilih
        const origPick = window.pickCustomSelect;
        window.pickCustomSelect = function(uid, value, label, autosubmit) {
            origPick(uid, value, label, autosubmit);
            toggleLabDropdown();
        };
    });
</script>
@endsection