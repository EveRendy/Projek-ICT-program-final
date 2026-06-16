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
            Buat akun pengguna baru dan tentukan hak akses laboratorium untuk peran Dosen atau Admin.
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
            
            <div>
                <label class="block text-sm font-bold text-slate-700">NIM/NIP <span class="text-red-500">*</span></label>
                <input type="text" name="no_induk" value="{{ old('no_induk') }}" required maxlength="20"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                    placeholder="Contoh: 2511501500"
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="255"
                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '').replace(/\b\w/g, c => c.toUpperCase())"
                    placeholder="Contoh: Budi Santoso"
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Email <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <input type="email" id="email_input" name="email" value="{{ old('email') }}" required maxlength="255"
                        placeholder="Contoh: budi.santoso@lab.com"
                        class="w-full rounded-xl border @error('email') border-red-500 @else border-slate-200 @enderror bg-slate-50 px-3 py-2.5 pr-10 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    
                    <div id="email_icon_container" class="absolute right-3 top-1/2 flex -translate-y-1/2 items-center hidden">
                        <svg id="email_check_icon" class="h-5 w-5 text-green-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg id="email_cross_icon" class="h-5 w-5 text-red-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <p id="email_js_warning" class="mt-1.5 text-xs font-semibold text-red-500 hidden">Email harus menggunakan domain @lab.com</p>
                
                @error('email')
                    <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="8" maxlength="255"
                    placeholder="Masukkan password yang kuat"
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">No HP <span class="text-red-500">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required maxlength="20"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    placeholder="Contoh: 081234567890"
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700">Role Pengguna <span class="text-red-500">*</span></label>
                <div class="relative mt-2">
                    <button type="button" id="role_toggle" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition cursor-pointer text-left">
                        <span id="role_label">-- Pilih Role --</span>
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <input type="hidden" name="role" id="role_select" value="{{ old('role') }}" required>
                    
                    <div id="role_menu" class="hidden absolute z-50 mt-1.5 w-full rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg space-y-0.5 max-h-60 overflow-y-auto">
                        <button type="button" data-value="dosen" class="role-option w-full rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">Dosen</button>
                        <button type="button" data-value="admin" class="role-option w-full rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">Admin</button>
                    </div>
                </div>
            </div>

            <div id="lab_assignment_container" class="hidden rounded-2xl border border-blue-100 bg-blue-50/50 p-4 transition-all">
                <label class="block text-sm font-extrabold text-blue-900">Tugaskan di Laboratorium (Khusus Admin)</label>
                <div class="relative mt-2">
                    <button type="button" id="lab_toggle" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition cursor-pointer text-left">
                        <span id="lab_label">-- Pilih Ruang Lab Tanggung Jawab --</span>
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <input type="hidden" name="laboratorium_id" id="lab_select" value="{{ old('laboratorium_id') }}">
                    
                    <div id="lab_menu" class="hidden absolute z-50 mt-1.5 w-full rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg space-y-0.5 max-h-60 overflow-y-auto">
                        <button type="button" data-value="" class="lab-option w-full rounded-lg px-3 py-2 text-left text-sm text-slate-400 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">-- Pilih Ruang Lab Tanggung Jawab --</button>
                        @foreach($laboratoriums as $lab)
                            @php
                                $labText = $lab->no_lab . ' (Level ' . $lab->level . ')' . ($lab->admin ? ' - Saat ini dijaga: '.$lab->admin->nama : ' - (Belum ada admin)');
                            @endphp
                            <button type="button" data-value="{{ $lab->id }}" data-text="{{ $lab->no_lab }} (Level {{ $lab->level }})" class="lab-option w-full rounded-lg px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 focus:bg-slate-50 outline-none transition font-medium">
                                {{ $labText }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <p class="mt-2 text-xs font-medium text-slate-500 leading-relaxed">
                    * Catatan: Memilih lab yang sudah ada penjaganya akan otomatis menggantikan posisi admin lama di lab tersebut.
                </p>
            </div>

            <hr class="my-2 border-slate-100">

            <div class="flex flex-col gap-3">
                <p id="validation_warning" class="text-sm font-semibold text-red-500 hidden">
                    * Tombol belum bisa ditekan. Pastikan semua kolom bertanda bintang sudah diisi dan format email valid (@lab.com).
                </p>
                <div class="flex items-center gap-3">
                    <button type="submit" id="submit_btn" disabled class="inline-flex items-center justify-center rounded-xl bg-blue-950 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30 opacity-50 cursor-not-allowed">
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
    function toggleLabDropdown() {
        const roleSelect = document.getElementById('role_select');
        const labContainer = document.getElementById('lab_assignment_container');

        if (roleSelect && roleSelect.value === 'admin') {
            labContainer.classList.remove('hidden');
        } else {
            labContainer.classList.add('hidden');
            const labSelect = document.getElementById('lab_select');
            const labLabel = document.getElementById('lab_label');
            if(labSelect) labSelect.value = '';
            if(labLabel) labLabel.innerText = '-- Pilih Ruang Lab Tanggung Jawab --';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('user_form');
        const submitBtn = document.getElementById('submit_btn');
        const validationWarning = document.getElementById('validation_warning');
        
        // Element Email Validation
        const emailInput = document.getElementById('email_input');
        const emailIconContainer = document.getElementById('email_icon_container');
        const emailCheckIcon = document.getElementById('email_check_icon');
        const emailCrossIcon = document.getElementById('email_cross_icon');
        const emailJsWarning = document.getElementById('email_js_warning');

        // Regex untuk mengecek validitas format email dan memastikan berakhiran @lab.com
        const emailRegex = /^[^\s@]+@lab\.com$/i;

        function initCustomDropdown(toggleId, menuId, inputId, labelId, optionClass, callback) {
            const toggle = document.getElementById(toggleId);
            const menu = document.getElementById(menuId);
            const input = document.getElementById(inputId);
            const label = document.getElementById(labelId);
            const arrow = toggle.querySelector('svg');

            if (!toggle || !menu) return;

            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = menu.classList.contains('hidden');
                document.getElementById('role_menu').classList.add('hidden');
                document.getElementById('lab_menu').classList.add('hidden');
                document.getElementById('role_toggle').querySelector('svg').classList.remove('rotate-180');
                document.getElementById('lab_toggle').querySelector('svg').classList.remove('rotate-180');

                if (isHidden) {
                    menu.classList.remove('hidden');
                    arrow.classList.add('rotate-180');
                }
            });

            document.querySelectorAll('.' + optionClass).forEach(function(option) {
                option.addEventListener('click', function() {
                    const val = option.getAttribute('data-value');
                    const text = option.getAttribute('data-text') || option.innerText;

                    input.value = val;
                    label.innerText = text;
                    menu.classList.add('hidden');
                    arrow.classList.remove('rotate-180');

                    if (callback) callback(val);
                    checkFormValidity();
                });
            });

            if (input.value) {
                const targetOption = Array.from(document.querySelectorAll('.' + optionClass))
                    .find(opt => opt.getAttribute('data-value') === input.value);
                if (targetOption) {
                    label.innerText = targetOption.getAttribute('data-text') || targetOption.innerText;
                }
            }
        }

        initCustomDropdown('role_toggle', 'role_menu', 'role_select', 'role_label', 'role-option', function(value) {
            toggleLabDropdown();
        });
        initCustomDropdown('lab_toggle', 'lab_menu', 'lab_select', 'lab_label', 'lab-option');

        document.addEventListener('click', function() {
            document.getElementById('role_menu').classList.add('hidden');
            document.getElementById('lab_menu').classList.add('hidden');
            document.getElementById('role_toggle').querySelector('svg').classList.remove('rotate-180');
            document.getElementById('lab_toggle').querySelector('svg').classList.remove('rotate-180');
        });

        // UPDATE: Logika Pengecekan Validasi Email Terpisah
        function validateEmailDomain() {
            const emailValue = emailInput.value.trim();
            let isEmailValid = false;

            if(emailValue === '') {
                emailIconContainer.classList.add('hidden');
                emailJsWarning.classList.add('hidden');
                emailInput.classList.remove('border-red-500', 'border-green-500');
            } else {
                emailIconContainer.classList.remove('hidden');
                
                if(emailRegex.test(emailValue)) {
                    // Jika benar
                    emailCheckIcon.classList.remove('hidden');
                    emailCrossIcon.classList.add('hidden');
                    emailJsWarning.classList.add('hidden');
                    emailInput.classList.remove('border-red-500');
                    emailInput.classList.add('border-green-500');
                    isEmailValid = true;
                } else {
                    // Jika salah format/domain
                    emailCheckIcon.classList.add('hidden');
                    emailCrossIcon.classList.remove('hidden');
                    emailJsWarning.classList.remove('hidden');
                    emailInput.classList.add('border-red-500');
                    emailInput.classList.remove('border-green-500');
                }
            }
            return isEmailValid;
        }

        // UPDATE: Logika Validasi Form Utama disesuaikan
        function checkFormValidity() {
            const isEmailDomainValid = validateEmailDomain();

            // Form hanya bisa disubmit jika check HTML5 native passed DAN domain JS email valid
            if (form.checkValidity() && (emailInput.value === '' || isEmailDomainValid)) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                validationWarning.classList.add('hidden');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                validationWarning.classList.remove('hidden');
            }
        }

        form.addEventListener('input', checkFormValidity);
        form.addEventListener('change', checkFormValidity);

        toggleLabDropdown();
        // Menjalankan validasi manual saat pertama kali load halaman
        checkFormValidity();
    });
</script>
@endsection