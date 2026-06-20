<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - Request Instalasi Software</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50">

<div class="min-h-screen lg:grid lg:grid-cols-2">
    {{-- Left Side --}}
    <div class="hidden lg:flex flex-col relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
        </div>
        
        <div class="relative z-10 flex flex-col h-full p-12">
            <div class="flex items-center justify-center gap-4 mb-12 w-full">
                <div class="w-24 h-24 bg-white rounded-2xl shadow-lg flex items-center justify-center p-2 box-border">
                    <img
                        src="{{ asset('images/image.png') }}"
                        alt="Logo ICT"
                        class="w-full h-full max-w-[72px] max-h-[72px] object-contain block mx-auto">
                </div>
                <div>
                    <h5 class="text-sm font-medium text-slate-300">Laboratorium</h5>
                    <h3 class="text-2xl font-bold text-white">ICT TERPADU</h3>
                </div>
            </div>
            
            <div class="flex-1 flex flex-col justify-center">
                <h1 class="text-5xl font-bold text-white mb-6 leading-tight">
                    Lengkapi Profil<br>
                    Akun Anda
                </h1>
                <p class="text-lg text-slate-300 max-w-md">
                    Demi keamanan dan kelancaran sistem, kami mewajibkan Anda untuk melengkapi profil dan mengubah password sementara Anda sebelum dapat menggunakan sistem.
                </p>
                
                <div class="mt-8 flex gap-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-500/20 text-blue-400 font-bold border border-blue-500/30">1</div>
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-500 text-white font-bold shadow-lg shadow-blue-500/30 ring-4 ring-blue-500/20">2</div>
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-slate-800 text-slate-500 font-bold border border-slate-700">3</div>
                </div>
            </div>
            
            <div class="mt-auto">
                <p class="text-sm text-slate-400">
                    © 2024 Universitas Budi Luhur. All rights reserved.
                </p>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 w-full h-1/2">
            <img
                src="{{ asset('images/instalimages.jpg') }}"
                alt="Lab Komputer"
                class="w-full h-full object-cover opacity-30">
        </div>
    </div>

    {{-- Right Side --}}
    <div class="flex items-center justify-center p-6 lg:p-12 overflow-y-auto">
        <div class="w-full max-w-md my-auto">
            {{-- Mobile Brand --}}
            <div class="lg:hidden flex flex-col items-center mb-8 mt-8">
                <div class="w-24 h-24 bg-white rounded-2xl shadow-lg flex items-center justify-center p-2 box-border mb-4">
                    <img
                        src="{{ asset('images/image.png') }}"
                        alt="Logo ICT"
                        class="w-full h-full max-w-[72px] max-h-[72px] object-contain block mx-auto">
                </div>
                <div class="text-center">
                    <h5 class="text-sm font-medium text-slate-600">Laboratorium</h5>
                    <h3 class="text-2xl font-bold text-slate-800">ICT TERPADU</h3>
                    <p class="text-xs text-slate-500">Universitas Budi Luhur</p>
                </div>
            </div>
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Lengkapi Data Diri</h1>
                <p class="text-slate-500">Silakan isi formulir di bawah ini dengan lengkap.</p>
            </div>

            @if (session('info'))
                <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <ul class="list-disc pl-4 font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="completeProfileForm" action="{{ route('dosen.complete-profile.save') }}" method="POST" class="space-y-5" novalidate>
                @csrf

                <div class="flex items-center mb-2 mt-4">
                    <div class="h-px bg-slate-200 flex-1"></div>
                    <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Identitas Anda</span>
                    <div class="h-px bg-slate-200 flex-1"></div>
                </div>

                {{-- Email (Readonly) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Terdaftar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="email" value="{{ $user->email }}" readonly class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm font-medium cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    {{-- NIP --}}
                    <div>
                        <label for="no_induk" class="block text-sm font-semibold text-slate-700 mb-2">NIP <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <input type="text" id="no_induk" name="no_induk" value="{{ old('no_induk') }}" class="w-full h-12 pl-12 pr-4 rounded-xl border {{ $errors->has('no_induk') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-white hover:border-slate-300' }} text-slate-800 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Contoh: 1234567890" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="w-full h-12 pl-12 pr-4 rounded-xl border {{ $errors->has('nama') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-white hover:border-slate-300' }} text-slate-800 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Contoh: Dr. Budi Santoso, M.Kom" maxlength="100" required>
                        </div>
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-2">Nomor HP <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" class="w-full h-12 pl-12 pr-4 rounded-xl border {{ $errors->has('no_hp') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-white hover:border-slate-300' }} text-slate-800 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Contoh: 081234567890" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 15)" required>
                        </div>
                    </div>
                </div>

                <div class="flex items-center mb-2 mt-6">
                    <div class="h-px bg-slate-200 flex-1"></div>
                    <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Keamanan</span>
                    <div class="h-px bg-slate-200 flex-1"></div>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    {{-- Password Baru --}}
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-slate-700 mb-2">Password Baru <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" id="new_password" name="new_password" class="w-full h-12 pl-12 pr-4 rounded-xl border {{ $errors->has('new_password') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-white hover:border-slate-300' }} text-slate-800 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Minimal 6 karakter" minlength="6" oninput="checkPasswordsMatch()" required>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 bg-white hover:border-slate-300 text-slate-800 text-sm placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="Ulangi password baru" minlength="6" oninput="checkPasswordsMatch()" required>
                        </div>
                        <p id="matchError" class="mt-2 text-xs font-semibold text-rose-500 hidden">Password tidak cocok.</p>
                        <p id="matchOk" class="mt-2 text-xs font-semibold text-emerald-500 hidden">✓ Password cocok.</p>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" id="submitBtn" disabled class="w-full h-14 rounded-2xl bg-slate-900 text-white text-base font-semibold hover:bg-slate-800 transition shadow-lg shadow-slate-900/20 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2">
                        Simpan dan Lanjutkan
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function checkPasswordsMatch() {
        const p1 = document.getElementById('new_password').value;
        const p2 = document.getElementById('new_password_confirmation').value;
        const err = document.getElementById('matchError');
        const ok = document.getElementById('matchOk');
        const btn = document.getElementById('submitBtn');

        if (!p2) { 
            err.classList.add('hidden'); 
            ok.classList.add('hidden'); 
            btn.disabled = true; 
            return; 
        }

        const form = document.getElementById('completeProfileForm');
        const allFilled = form.checkValidity();

        if (p1 === p2) {
            err.classList.add('hidden');
            ok.classList.remove('hidden');
            btn.disabled = !allFilled;
        } else {
            err.classList.remove('hidden');
            ok.classList.add('hidden');
            btn.disabled = true;
        }
    }

    document.getElementById('completeProfileForm').addEventListener('input', checkPasswordsMatch);
</script>

</body>
</html>
