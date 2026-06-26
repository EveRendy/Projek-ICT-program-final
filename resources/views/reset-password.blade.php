<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Sandi - Request Instalasi Software</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white">

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
                    <img src="{{ asset('images/image.png') }}" alt="Logo ICT" class="w-full h-full max-w-[72px] max-h-[72px] object-contain block mx-auto">
                </div>
                <div>
                    <h5 class="text-sm font-medium text-slate-300">Laboratorium</h5>
                    <h3 class="text-2xl font-bold text-white">ICT TERPADU</h3>
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-center">
                <h1 class="text-5xl font-bold text-white mb-6 leading-tight">
                    Sistem Request<br>
                    Instalasi Software
                </h1>
                <p class="text-lg text-slate-300 max-w-md">
                    Kelola permintaan instalasi software di laboratorium dengan mudah dan terstruktur.
                </p>
            </div>

            <div class="mt-auto">
                <p class="text-sm text-slate-400">
                    © 2026 Universitas Budi Luhur. All rights reserved.
                </p>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-1/2">
            <img src="{{ asset('images/instalimages.jpg') }}" alt="Lab Komputer" class="w-full h-full object-cover opacity-30">
        </div>
    </div>

    {{-- Right Side --}}
    <div class="flex items-center justify-center p-6 lg:p-12 bg-slate-50">
        <div class="w-full max-w-md">
            {{-- Mobile Brand --}}
            <div class="lg:hidden flex flex-col items-center mb-8">
                <div class="w-24 h-24 bg-white rounded-2xl shadow-lg flex items-center justify-center p-2 box-border mb-4">
                    <img src="{{ asset('images/image.png') }}" alt="Logo ICT" class="w-full h-full max-w-[72px] max-h-[72px] object-contain block mx-auto">
                </div>
                <div class="text-center">
                    <h5 class="text-sm font-medium text-slate-600">Laboratorium</h5>
                    <h3 class="text-2xl font-bold text-slate-800">ICT TERPADU</h3>
                    <p class="text-xs text-slate-500">Universitas Budi Luhur</p>
                </div>
            </div>

            {{-- Icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"></path>
                    </svg>
                </div>
            </div>

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Buat Sandi Baru</h1>
                <p class="text-slate-500 text-sm leading-relaxed">Kata sandi baru harus berbeda dari kata sandi yang pernah digunakan sebelumnya. Minimal 6 karakter.</p>
            </div>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-sm">
                    <div class="flex items-center gap-3 text-rose-700 font-semibold">
                        <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Terjadi kesalahan:</span>
                    </div>
                    <ul class="mt-2 list-disc text-rose-600 pl-12 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update.action') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email (readonly) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input
                            type="email"
                            name="email"
                            class="w-full h-14 pl-12 pr-4 rounded-2xl border border-slate-200 bg-slate-100 text-slate-600 text-base outline-none cursor-not-allowed"
                            value="{{ request()->email ?? old('email') }}"
                            readonly
                            required>
                    </div>
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            class="w-full h-14 pl-12 pr-14 rounded-2xl border border-slate-200 bg-white text-slate-800 text-base placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition hover:border-slate-300"
                            placeholder="Minimal 6 karakter"
                            autofocus
                            required>
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Konfirmasi Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full h-14 pl-12 pr-4 rounded-2xl border border-slate-200 bg-white text-slate-800 text-base placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition hover:border-slate-300"
                            placeholder="Ulangi kata sandi baru"
                            required>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full h-14 rounded-2xl bg-slate-900 text-white text-lg font-semibold hover:bg-slate-800 transition shadow-lg shadow-slate-900/20 active:scale-[0.98]">
                    Simpan Kata Sandi Baru
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908l3.59 3.59"></path></svg>`;
        } else {
            this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
        }
    });
</script>

</body>
</html>
