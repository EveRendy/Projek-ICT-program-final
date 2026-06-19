<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil — Sistem Instalasi Lab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:       #1d4ed8;
            --primary-dark:  #1e3a5f;
            --primary-light: #eff6ff;
            --surface:       #ffffff;
            --border:        #e2e8f0;
            --text-primary:  #0f172a;
            --text-secondary:#475569;
            --text-muted:    #94a3b8;
            --danger:        #ef4444;
            --danger-light:  #fef2f2;
            --success:       #10b981;
            --success-light: #f0fdf4;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #1d4ed8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* Decorative background blobs */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 400px; height: 400px;
            background: #60a5fa;
            top: -100px; right: -100px;
        }
        body::after {
            width: 350px; height: 350px;
            background: #818cf8;
            bottom: -80px; left: -80px;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
        }

        /* Brand header */
        .brand {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-logo-wrapper {
            width: 96px;
            height: 96px;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 8px;
            box-sizing: border-box;
        }
        .brand-logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-width: 72px;
            max-height: 72px;
            display: block;
            margin: 0 auto;
        }
        .brand-icon {
            width: 56px; height: 56px;
            background: rgba(255,255,255,0.12);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            backdrop-filter: blur(8px);
        }
        .brand-icon svg { color: white; }
        .brand h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }
        .brand p {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            margin-top: 4px;
        }

        /* Card */
        .card {
            background: var(--surface);
            border-radius: 24px;
            padding: 36px 40px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.05);
        }

        .card-header { margin-bottom: 28px; }
        .card-header .badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.8px;
            padding: 4px 10px; border-radius: 20px;
            margin-bottom: 12px;
        }
        .card-header h2 {
            font-size: 22px; font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }
        .card-header p {
            color: var(--text-secondary);
            font-size: 13.5px; line-height: 1.6;
            margin-top: 6px;
        }

        /* Alert info */
        .alert {
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px; line-height: 1.5;
            margin-bottom: 24px;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .alert-info  { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
        .alert-error { background: var(--danger-light); border: 1px solid #fecaca; color: #b91c1c; }
        .alert-success { background: var(--success-light); border: 1px solid #a7f3d0; color: #065f46; }
        .alert .icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }

        /* Errors list */
        .errors-list {
            background: var(--danger-light);
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }
        .errors-list p { font-size: 13px; font-weight: 700; color: #b91c1c; margin-bottom: 6px; }
        .errors-list ul { list-style: disc; padding-left: 18px; }
        .errors-list li { font-size: 12.5px; color: #b91c1c; margin-bottom: 2px; }

        /* Section divider */
        .section-label {
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.8px;
            color: var(--text-muted);
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            margin: 24px 0 16px;
        }
        .section-label:first-of-type { margin-top: 0; }

        /* Form */
        form { display: flex; flex-direction: column; gap: 16px; }
        
        .field { display: flex; flex-direction: column; gap: 6px; }
        label {
            font-size: 13px; font-weight: 600;
            color: var(--text-primary);
        }
        label .req { color: var(--danger); }
        
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); pointer-events: none;
            display: flex; align-items: center;
        }
        input {
            width: 100%;
            padding: 11px 14px 11px 38px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px; font-family: inherit;
            color: var(--text-primary);
            background: #f8fafc;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(29,78,216,0.1);
        }
        input.is-error { border-color: var(--danger); background: var(--danger-light); }
        
        .hint { font-size: 11.5px; color: var(--text-muted); line-height: 1.5; }
        .field-error { font-size: 12px; color: var(--danger); font-weight: 500; }

        /* Password strength */
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
            margin-top: 6px;
        }
        .strength-fill {
            height: 100%; border-radius: 4px;
            transition: width 0.3s, background 0.3s;
        }
        .strength-text { font-size: 11.5px; font-weight: 600; margin-top: 4px; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14.5px; font-weight: 700; font-family: inherit;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 14px rgba(29,78,216,0.3);
            margin-top: 8px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,78,216,0.38); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Step indicator */
        .steps-row {
            display: flex; align-items: center; gap: 0;
            margin-bottom: 28px;
        }
        .step-item {
            flex: 1; display: flex; flex-direction: column; align-items: center;
            position: relative;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 14px; left: 50%;
            width: 100%; height: 2px;
            background: var(--border);
        }
        .step-item.done:not(:last-child)::after { background: var(--primary); }
        .step-dot {
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: var(--text-muted);
            position: relative; z-index: 1;
        }
        .step-item.active .step-dot {
            background: var(--primary); border-color: var(--primary);
            color: white; box-shadow: 0 0 0 4px rgba(29,78,216,0.15);
        }
        .step-item.done .step-dot {
            background: var(--primary); border-color: var(--primary); color: white;
        }
        .step-label {
            font-size: 10px; font-weight: 600; color: var(--text-muted);
            margin-top: 6px; text-align: center;
        }
        .step-item.active .step-label { color: var(--primary); }
    </style>
</head>
<body>
    <div class="container">
        <!-- Brand -->
        <div class="brand">
            <div class="brand-logo-wrapper">
                <img
                    src="{{ asset('images/image.png') }}"
                    alt="Logo ICT">
            </div>
            <h1>Sistem Request Instalasi Software</h1>
            <p>Manajemen Instalasi Software Laboratorium</p>
        </div>

        <!-- Card -->
        <div class="card">
            <!-- Step Indicator -->
            <div class="steps-row">
                <div class="step-item done">
                    <div class="step-dot">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="step-label">Login</span>
                </div>
                <div class="step-item active">
                    <div class="step-dot">2</div>
                    <span class="step-label">Profil</span>
                </div>
                <div class="step-item">
                    <div class="step-dot">3</div>
                    <span class="step-label">Siap</span>
                </div>
            </div>

            <!-- Header -->
            <div class="card-header">
                <div class="badge">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Langkah 2 dari 3
                </div>
                <h2>Lengkapi Profil Anda</h2>
                <p>Masukkan NIP, nomor HP, dan buat password baru Anda. Data ini diperlukan untuk menggunakan sistem.</p>
            </div>

            {{-- Flash messages --}}
            @if (session('info'))
                <div class="alert alert-info">
                    <span class="icon">ℹ️</span>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="errors-list">
                    <p>Mohon perbaiki kesalahan berikut:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="completeProfileForm" action="{{ route('dosen.complete-profile.save') }}" method="POST" novalidate>
                @csrf

                <!-- Identitas -->
                <div class="section-label">Identitas Diri</div>

                {{-- Email (readonly, info saja) --}}
                <div class="field">
                    <label>Email Terdaftar</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,12 2,6"/></svg>
                        </span>
                        <input type="email" value="{{ $user->email }}" readonly style="background:#f1f5f9; color:#64748b; cursor:not-allowed;">
                    </div>
                </div>

                {{-- NIP --}}
                <div class="field">
                    <label for="no_induk">NIP / No. Induk Pegawai <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </span>
                        <input type="text" id="no_induk" name="no_induk"
                            value="{{ old('no_induk') }}"
                            placeholder="Contoh: 1234567890"
                            maxlength="10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="{{ $errors->has('no_induk') ? 'is-error' : '' }}"
                            required>
                    </div>
                    @error('no_induk')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                    <span class="hint">Hanya 10 digit angka.</span>
                </div>

                {{-- Nama Lengkap --}}
                <div class="field">
                    <label for="nama">Nama Lengkap <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input type="text" id="nama" name="nama"
                            value="{{ old('nama') }}"
                            placeholder="Contoh: Dr. Budi Santoso, M.Kom"
                            maxlength="100"
                            oninput="this.value = this.value.replace(/[^a-zA-Z\s.,]/g, '').replace(/\b\w/g, c => c.toUpperCase())"
                            class="{{ $errors->has('nama') ? 'is-error' : '' }}"
                            required>
                    </div>
                    @error('nama')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- No HP --}}
                <div class="field">
                    <label for="no_hp">Nomor HP <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.61 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6.29 6.29l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <input type="text" id="no_hp" name="no_hp"
                            value="{{ old('no_hp') }}"
                            placeholder="Contoh: 081234567890"
                            maxlength="15"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 15)"
                            class="{{ $errors->has('no_hp') ? 'is-error' : '' }}"
                            required>
                    </div>
                    @error('no_hp')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                    <span class="hint">10–15 digit angka saja.</span>
                </div>

                <!-- Keamanan -->
                <div class="section-label">Buat Password Baru</div>

                {{-- Password Baru --}}
                <div class="field">
                    <label for="new_password">Password Baru <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input type="password" id="new_password" name="new_password"
                            placeholder="Minimal 6 karakter"
                            minlength="6"
                            class="{{ $errors->has('new_password') ? 'is-error' : '' }}"
                            oninput="checkStrength(this.value); checkPasswordsMatch()"
                            required>
                    </div>
                    @error('new_password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill" style="width:0%; background:#e2e8f0;"></div></div>
                    <span class="strength-text" id="strengthText" style="color: var(--text-muted);">Belum diisi</span>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="field">
                    <label for="new_password_confirmation">Konfirmasi Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            placeholder="Ulangi password baru"
                            minlength="6"
                            oninput="checkPasswordsMatch()"
                            required>
                    </div>
                    <span class="field-error" id="matchError" style="display:none;">Password tidak cocok.</span>
                    <span style="font-size:12px; font-weight:600; color: var(--success); display:none;" id="matchOk">✓ Password cocok</span>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan & Masuk ke Dashboard
                </button>
            </form>
        </div>
    </div>

    <script>
        function checkStrength(val) {
            const fill = document.getElementById('strengthFill');
            const text = document.getElementById('strengthText');
            if (!val) {
                fill.style.width = '0%'; fill.style.background = '#e2e8f0';
                text.style.color = 'var(--text-muted)'; text.textContent = 'Belum diisi'; return;
            }
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^a-zA-Z0-9]/.test(val)) score++;

            const levels = [
                { pct: '20%', color: '#ef4444', label: 'Sangat Lemah', textColor: '#ef4444' },
                { pct: '40%', color: '#f97316', label: 'Lemah', textColor: '#f97316' },
                { pct: '60%', color: '#eab308', label: 'Sedang', textColor: '#b45309' },
                { pct: '80%', color: '#22c55e', label: 'Kuat', textColor: '#15803d' },
                { pct: '100%', color: '#10b981', label: 'Sangat Kuat', textColor: '#065f46' },
            ];
            const l = levels[Math.max(0, score - 1)];
            fill.style.width = l.pct; fill.style.background = l.color;
            text.style.color = l.textColor; text.textContent = l.label;
        }

        function checkPasswordsMatch() {
            const p1 = document.getElementById('new_password').value;
            const p2 = document.getElementById('new_password_confirmation').value;
            const err = document.getElementById('matchError');
            const ok = document.getElementById('matchOk');
            const btn = document.getElementById('submitBtn');

            if (!p2) { err.style.display = 'none'; ok.style.display = 'none'; btn.disabled = true; return; }

            const form = document.getElementById('completeProfileForm');
            const allFilled = form.checkValidity();

            if (p1 === p2) {
                err.style.display = 'none';
                ok.style.display = 'block';
                btn.disabled = !allFilled;
            } else {
                err.style.display = 'block';
                ok.style.display = 'none';
                btn.disabled = true;
            }
        }

        // Enable submit when all fields filled
        document.getElementById('completeProfileForm').addEventListener('input', function() {
            checkPasswordsMatch();
        });
    </script>
</body>
</html>
