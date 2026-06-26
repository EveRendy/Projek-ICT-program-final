<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Request Instalasi Software</title>

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
                <div class="w-16 h-16 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                    </svg>
                </div>
            </div>

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Lupa Password?</h1>
                <p class="text-slate-500 text-sm leading-relaxed">Tidak perlu khawatir. Masukkan alamat email yang terdaftar dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
            </div>

            {{-- Success Message --}}
            @if(session('status'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if($errors->has('email'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
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
                            class="w-full h-14 pl-12 pr-4 rounded-2xl border border-slate-200 bg-white text-slate-800 text-base placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition hover:border-slate-300"
                            value="{{ old('email') }}"
                            placeholder="name@example.com"
                            autofocus
                            required>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full h-14 rounded-2xl bg-slate-900 text-white text-lg font-semibold hover:bg-slate-800 transition shadow-lg shadow-slate-900/20 active:scale-[0.98]">
                    Kirim Tautan Reset
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-900 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke halaman Login
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
