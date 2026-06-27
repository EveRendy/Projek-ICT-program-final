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
            Buat akun pengguna baru. Untuk role <strong>Dosen</strong>, cukup masukkan email — sistem akan mengirimkan password sementara secara otomatis.
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

        <form id="user_form" action="{{ route('users.store') }}" method="POST" class="flex flex-col gap-5">
            @csrf

            {{-- ================================================================
                 ROLE SELECTOR (selalu tampil di atas)
            ================================================================= --}}
            <div>
                <label class="block text-sm font-bold text-slate-700">Peran Pengguna <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <button type="button" id="role_toggle" class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition cursor-pointer text-left">
                        <span id="role_label">-- Pilih Peran --</span>
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <input type="hidden" name="role" id="role_select" value="{{ old('role') }}" required>
                    
                    <div id="role_menu" class="hidden absolute z-50 mt-1.5 w-full rounded-2xl border border-slate-200 bg-white p-1.5 shadow-lg space-y-0.5 max-h-60 overflow-y-auto">
                        <button type="button" data-value="dosen" class="role-option w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">Dosen</button>
                        <button type="button" data-value="admin" class="role-option w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">Admin</button>
                    </div>
                </div>
            </div>

            {{-- ================================================================
                 PANEL DOSEN: Hanya email + info box
            ================================================================= --}}
            <div id="panel_dosen" class="hidden flex-col gap-5">
                {{-- Info box dosen --}}
                <div class="flex gap-3 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="h-5 w-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-blue-900">Email Dosen</p>
                        <p class="mt-1 text-xs leading-relaxed text-blue-700">
                            Cukup masukkan email dosen (Gmail, Outlook, dll). Sistem akan <strong>otomatis mengirimkan password sementara</strong> ke email tersebut. 
                            Dosen wajib melengkapi profil (NIP, No HP, dan password baru) saat pertama kali login.
                        </p>
                    </div>
                </div>

                {{-- Email dosen --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700">Email Dosen <span class="text-red-500">*</span></label>
                    <div class="relative mt-2">
                        <input type="email" id="email_input_dosen" name="email"
                            value="{{ old('email') }}"
                            placeholder="Contoh: budi.santoso@gmail.com"
                            maxlength="100"
                            class="w-full rounded-xl border @error('email') border-red-500 @else border-slate-200 @enderror bg-slate-50 px-3 py-2.5 pr-10 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        <div id="dosen_email_icon" class="absolute right-3 top-1/2 -translate-y-1/2 items-center hidden">
                            <svg id="dosen_email_check" class="h-5 w-5 text-green-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <svg id="dosen_email_cross" class="h-5 w-5 text-red-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                    </div>
                    <p id="dosen_email_warn" class="mt-1.5 text-xs font-semibold text-red-500 hidden">Format email tidak valid.</p>
                    @error('email')
                        <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-400">Gunakan email asli dosen. Password sementara akan dikirim ke email ini.</p>
                </div>
            </div>

            {{-- ================================================================
                 PANEL ADMIN: Form lengkap
            ================================================================= --}}
            <div id="panel_admin" class="hidden flex-col gap-5">
                {{-- NIM/NIP --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700">NIM/NIP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_induk" id="no_induk_input" value="{{ old('no_induk') }}" maxlength="20"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                        placeholder="Contoh: ADM001"
                        disabled
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    <p class="mt-1 text-xs text-slate-400">Maks. 20 karakter, hanya huruf dan angka.</p>
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama_input" value="{{ old('nama') }}" maxlength="100"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '').replace(/\b\w/g, c => c.toUpperCase())"
                        placeholder="Contoh: Budi Santoso"
                        disabled
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    <p class="mt-1 text-xs text-slate-400">Maks. 100 karakter, hanya huruf.</p>
                </div>

                {{-- Email admin (harus @lab.com) --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700">Email <span class="text-red-500">*</span></label>
                    <div class="relative mt-2">
                        <input type="email" id="email_input_admin" name="email"
                            value="{{ old('email') }}"
                            placeholder="Contoh: admin.lab@lab.com"
                            maxlength="100"
                            disabled
                            class="w-full rounded-xl border @error('email') border-red-500 @else border-slate-200 @enderror bg-slate-50 px-3 py-2.5 pr-10 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        <div id="admin_email_icon" class="absolute right-3 top-1/2 flex -translate-y-1/2 items-center hidden">
                            <svg id="admin_email_check" class="h-5 w-5 text-green-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <svg id="admin_email_cross" class="h-5 w-5 text-red-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                    </div>
                    <p id="admin_email_warn" class="mt-1.5 text-xs font-semibold text-red-500 hidden">Email harus menggunakan domain @lab.com</p>
                    @error('email')
                        <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password_input" minlength="6" maxlength="100"
                        placeholder="Masukkan password (min. 6 karakter)"
                        disabled
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    <p class="mt-1 text-xs text-slate-400">Min. 6, maks. 100 karakter.</p>
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700">No HP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" id="no_hp_input" value="{{ old('no_hp') }}" maxlength="15" minlength="10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 15)"
                        placeholder="Contoh: 081234567890"
                        disabled
                        class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    <p class="mt-1 text-xs text-slate-400">10–15 digit angka saja.</p>
                </div>

                {{-- Lab assignment --}}
                <div id="lab_assignment_container" class="hidden rounded-2xl border border-blue-100 bg-blue-50/50 p-4 transition-all">
                    <label class="block text-sm font-extrabold text-blue-900">Tugaskan di Laboratorium (Khusus Admin)</label>
                    <div class="relative mt-2">
                        <button type="button" id="lab_toggle" class="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition cursor-pointer text-left">
                            <span id="lab_label">-- Pilih Ruang Lab Tanggung Jawab --</span>
                            <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <input type="hidden" name="laboratorium_id" id="lab_select" value="{{ old('laboratorium_id') }}">
                        
                        <div id="lab_menu" class="hidden absolute z-50 mt-1.5 w-full rounded-2xl border border-slate-200 bg-white p-1.5 shadow-lg space-y-0.5 max-h-60 overflow-y-auto">
                            <button type="button" data-value="" class="lab-option w-full rounded-xl px-3 py-2 text-left text-sm text-slate-400 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">-- Pilih Ruang Lab Tanggung Jawab --</button>
                            @foreach($laboratoriums as $lab)
                                @php
                                    $labText = $lab->no_lab . ' (Level ' . $lab->level . ')' . ($lab->admin ? ' - Saat ini dijaga: '.$lab->admin->nama : ' - (Belum ada admin)');
                                @endphp
                                <button type="button" data-value="{{ $lab->id }}" data-text="{{ $lab->no_lab }} (Level {{ $lab->level }})" class="lab-option w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">
                                    {{ $labText }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <p class="mt-2 text-xs font-medium text-slate-500 leading-relaxed">
                        * Catatan: Memilih lab yang sudah ada penjaganya akan otomatis menggantikan posisi admin lama di lab tersebut.
                    </p>
                </div>
            </div>

            <hr class="my-2 border-slate-100">

            <div class="flex flex-col gap-3">
                <p id="validation_warning" class="text-sm font-semibold text-red-500 hidden">
                    * Tombol belum bisa ditekan. Pastikan semua kolom bertanda bintang sudah diisi dengan benar.
                </p>
                <div class="flex items-center gap-3">
                    <button type="submit" id="submit_btn" disabled
                        class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 opacity-50 cursor-not-allowed">
                        Simpan User
                    </button>
                    <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-500 transition hover:bg-slate-50">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </section>
</div>

<script>
    // ================================================================
    // STATE
    // ================================================================
    let currentRole = '{{ old("role") }}' || null;
    const emailRegexDosen = /^[^\s@]+@[^\s@]+\.[^\s@]+$/i;    // Email umum (bebas domain)
    const emailRegexAdmin = /^[^\s@]+@lab\.com$/i;             // Harus @lab.com

    // ================================================================
    // UTILITY: Custom dropdown
    // ================================================================
    function initCustomDropdown(toggleId, menuId, inputId, labelId, optionClass, callback) {
        const toggle = document.getElementById(toggleId);
        const menu   = document.getElementById(menuId);
        const input  = document.getElementById(inputId);
        const label  = document.getElementById(labelId);
        if (!toggle || !menu) return;
        const arrow = toggle.querySelector('svg');

        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = menu.classList.contains('hidden');
            closeAllDropdowns();
            if (isHidden) {
                menu.classList.remove('hidden');
                if (arrow) arrow.classList.add('rotate-180');
            }
        });

        document.querySelectorAll('.' + optionClass).forEach(function(option) {
            option.addEventListener('click', function() {
                const val  = option.getAttribute('data-value');
                const text = option.getAttribute('data-text') || option.innerText.trim();
                if (input) input.value = val;
                if (label) label.innerText = text;
                menu.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-180');
                if (callback) callback(val);
                checkFormValidity();
            });
        });

        // Restore label on page load (old value)
        if (input && input.value) {
            const target = Array.from(document.querySelectorAll('.' + optionClass))
                .find(o => o.getAttribute('data-value') === input.value);
            if (target && label) label.innerText = target.getAttribute('data-text') || target.innerText.trim();
        }
    }

    function closeAllDropdowns() {
        ['role_menu', 'lab_menu'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        });
        ['role_toggle', 'lab_toggle'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { const sv = el.querySelector('svg'); if(sv) sv.classList.remove('rotate-180'); }
        });
    }

    document.addEventListener('click', closeAllDropdowns);

    // ================================================================
    // ROLE SWITCH LOGIC
    // ================================================================
    function switchRole(role) {
        currentRole = role;
        const panelDosen = document.getElementById('panel_dosen');
        const panelAdmin = document.getElementById('panel_admin');

        // Field-field di masing-masing panel
        const adminFields = ['no_induk_input', 'nama_input', 'email_input_admin', 'password_input', 'no_hp_input'];
        const dosenFields = ['email_input_dosen'];

        if (role === 'dosen') {
            // Tampilkan panel dosen
            panelDosen.classList.remove('hidden');
            panelDosen.classList.add('flex');
            panelAdmin.classList.remove('flex');
            panelAdmin.classList.add('hidden');

            // PENTING: Disable semua input admin agar tidak ikut tersubmit (walau disembunyikan)
            adminFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.setAttribute('disabled', 'disabled'); el.value = ''; }
            });

            // Aktifkan input dosen
            dosenFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.removeAttribute('disabled');
            });

        } else if (role === 'admin') {
            // Tampilkan panel admin
            panelAdmin.classList.remove('hidden');
            panelAdmin.classList.add('flex');
            panelDosen.classList.remove('flex');
            panelDosen.classList.add('hidden');

            // PENTING: Disable input dosen agar tidak ikut tersubmit
            dosenFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.setAttribute('disabled', 'disabled'); el.value = ''; }
            });

            // Aktifkan semua input admin
            adminFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.removeAttribute('disabled');
            });

            // Tampilkan lab container karena role = admin
            document.getElementById('lab_assignment_container').classList.remove('hidden');
        }
        checkFormValidity();
    }

    function clearAdminFields() {
        const ids = ['no_induk_input', 'nama_input', 'email_input_admin', 'password_input', 'no_hp_input'];
        ids.forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    }


    // ================================================================
    // EMAIL VALIDATION
    // ================================================================
    function validateDosenEmail() {
        const input = document.getElementById('email_input_dosen');
        const iconWrap = document.getElementById('dosen_email_icon');
        const check = document.getElementById('dosen_email_check');
        const cross = document.getElementById('dosen_email_cross');
        const warn  = document.getElementById('dosen_email_warn');
        const val   = input ? input.value.trim() : '';

        if (!val) {
            iconWrap.classList.add('hidden'); warn.classList.add('hidden');
            input.classList.remove('border-red-500', 'border-green-500');
            return false;
        }
        iconWrap.classList.remove('hidden');
        const valid = emailRegexDosen.test(val);
        check.classList.toggle('hidden', !valid);
        cross.classList.toggle('hidden', valid);
        warn.classList.toggle('hidden', valid);
        input.classList.toggle('border-green-500', valid);
        input.classList.toggle('border-red-500', !valid);
        return valid;
    }

    function validateAdminEmail() {
        const input = document.getElementById('email_input_admin');
        const iconWrap = document.getElementById('admin_email_icon');
        const check = document.getElementById('admin_email_check');
        const cross = document.getElementById('admin_email_cross');
        const warn  = document.getElementById('admin_email_warn');
        const val   = input ? input.value.trim() : '';

        if (!val) {
            iconWrap.classList.add('hidden'); warn.classList.add('hidden');
            input.classList.remove('border-red-500', 'border-green-500');
            return false;
        }
        iconWrap.classList.remove('hidden');
        const valid = emailRegexAdmin.test(val);
        check.classList.toggle('hidden', !valid);
        cross.classList.toggle('hidden', valid);
        warn.classList.toggle('hidden', valid);
        input.classList.toggle('border-green-500', valid);
        input.classList.toggle('border-red-500', !valid);
        return valid;
    }

    // ================================================================
    // FORM VALIDITY CHECK
    // ================================================================
    function checkFormValidity() {
        const submitBtn = document.getElementById('submit_btn');
        const warning   = document.getElementById('validation_warning');

        let valid = false;

        if (currentRole === 'dosen') {
            const email = document.getElementById('email_input_dosen').value.trim();
            valid = emailRegexDosen.test(email);
        } else if (currentRole === 'admin') {
            const noInduk  = document.getElementById('no_induk_input').value.trim();
            const nama     = document.getElementById('nama_input').value.trim();
            const email    = document.getElementById('email_input_admin').value.trim();
            const password = document.getElementById('password_input').value.trim();
            const noHp     = document.getElementById('no_hp_input').value.trim();
            valid = noInduk.length > 0 && nama.length > 0
                && emailRegexAdmin.test(email)
                && password.length >= 6
                && noHp.length >= 10;
        }

        submitBtn.disabled = !valid;
        submitBtn.classList.toggle('opacity-50', !valid);
        submitBtn.classList.toggle('cursor-not-allowed', !valid);
        warning.classList.toggle('hidden', valid || !currentRole);
    }

    // ================================================================
    // INIT
    // ================================================================
    document.addEventListener('DOMContentLoaded', function() {
        initCustomDropdown('role_toggle', 'role_menu', 'role_select', 'role_label', 'role-option', switchRole);
        initCustomDropdown('lab_toggle',  'lab_menu',  'lab_select',  'lab_label',  'lab-option');

        // Email listeners
        document.getElementById('email_input_dosen')?.addEventListener('input', function() {
            validateDosenEmail(); checkFormValidity();
        });
        document.getElementById('email_input_admin')?.addEventListener('input', function() {
            validateAdminEmail(); checkFormValidity();
        });

        // Admin field listeners
        ['no_induk_input','nama_input','password_input','no_hp_input'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', checkFormValidity);
        });

        // Restore on old() value (after validation error)
        const oldRole = document.getElementById('role_select').value;
        if (oldRole) switchRole(oldRole);
    });
</script>
@endsection