<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Request Instalasi Software</title>
    
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
            <img
                src="{{ asset('images/instalimages.jpg') }}"
                alt="Lab Komputer"
                class="w-full h-full object-cover opacity-30">
        </div>
    </div>

    {{-- Right Side --}}
    <div class="flex items-center justify-center p-6 lg:p-12 bg-slate-50">
        <div class="w-full max-w-md">
            {{-- Mobile Brand --}}
            <div class="lg:hidden flex flex-col items-center mb-8">
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
            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Selamat Datang</h1>
                <p class="text-slate-500">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if($errors->has('loginError'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $errors->first('loginError') }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input
                            type="email"
                            name="email"
                            class="w-full h-14 pl-12 pr-4 rounded-2xl border border-slate-200 bg-white text-slate-800 text-base placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition hover:border-slate-300"
                            value="{{ old('email') }}"
                            placeholder="name@example.com"
                            required>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Kata Sandi
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
                            placeholder="••••••••"
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

                <button
                    type="submit"
                    class="w-full h-14 rounded-2xl bg-slate-900 text-white text-lg font-semibold hover:bg-slate-800 transition shadow-lg shadow-slate-900/20 active:scale-[0.98]">
                    Masuk
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
