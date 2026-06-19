<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Dosen - Sistem Instalasi Lab</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            padding: 32px 16px;
        }
        .wrapper {
            max-width: 560px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%);
            padding: 36px 40px;
            text-align: center;
        }
        .header-logo-wrapper {
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
        .header-logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-width: 72px;
            max-height: 72px;
            display: block;
            margin: 0 auto;
        }
        .header-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .header p {
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            margin-top: 6px;
        }
        .body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 15px;
            color: #475569;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .credential-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            margin: 24px 0;
        }
        .credential-box h2 {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 16px;
        }
        .credential-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 14px;
        }
        .credential-row:last-child { margin-bottom: 0; }
        .credential-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            width: 110px;
            flex-shrink: 0;
            padding-top: 2px;
        }
        .credential-value {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            word-break: break-all;
        }
        .password-highlight {
            font-size: 18px;
            font-weight: 800;
            color: #1d4ed8;
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 2px;
            background: #eff6ff;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1.5px solid #bfdbfe;
        }
        .warning-box {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
            display: flex;
            gap: 12px;
        }
        .warning-box .icon { font-size: 18px; flex-shrink: 0; }
        .warning-box p {
            font-size: 13px;
            color: #92400e;
            line-height: 1.5;
        }
        .warning-box strong { font-weight: 700; }
        .steps {
            margin: 20px 0;
        }
        .steps h3 {
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 12px;
        }
        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 10px;
        }
        .step-number {
            width: 24px;
            height: 24px;
            background: #1d4ed8;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .step-text {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            padding-top: 3px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 28px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #1e3a5f 0%, #1d4ed8 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .footer {
            border-top: 1px solid #f1f5f9;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .footer a { color: #1d4ed8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <div class="header-logo-wrapper">
                    <img src="{{ $message->embed(public_path('images/image.png')) }}" alt="Logo ICT">
                </div>
                <h1>Akun Dosen Anda Siap!</h1>
                <p>Sistem Request Instalasi Software</p>
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">
                    Halo,<br><br>
                    Supervisor telah membuat akun untuk Anda di <strong>Sistem Manajemen Instalasi Laboratorium</strong>.
                    Berikut adalah kredensial login sementara Anda:
                </p>

                <!-- Credential Box -->
                <div class="credential-box">
                    <h2>🔐 Kredensial Login</h2>
                    <div class="credential-row">
                        <span class="credential-label">Email</span>
                        <span class="credential-value">{{ $recipientEmail }}</span>
                    </div>
                    <div class="credential-row">
                        <span class="credential-label">Password</span>
                        <span class="password-highlight">{{ $temporaryPassword }}</span>
                    </div>
                </div>

                <!-- Warning -->
                <div class="warning-box">
                    <span class="icon">⚠️</span>
                    <p>
                        <strong>Penting:</strong> Ini adalah password sementara. Setelah login, Anda <strong>wajib</strong> melengkapi profil dan membuat password baru sebelum bisa menggunakan sistem.
                    </p>
                </div>

                <!-- Steps -->
                <div class="steps">
                    <h3>Langkah selanjutnya:</h3>
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-text">Klik tombol di bawah untuk membuka halaman login</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-text">Login menggunakan email dan password sementara di atas</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-text">Isi NIP, No. HP, dan buat password baru Anda</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-text">Mulai gunakan sistem untuk pengajuan instalasi software</div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="btn-wrapper">
                    <a href="{{ $loginUrl }}" class="btn">Masuk ke Sistem &rarr;</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>
                    Email ini dikirim otomatis oleh Sistem Request Instalasi Software.<br>
                    Jika Anda tidak merasa mendaftar, abaikan email ini.<br>
                    &copy; {{ date('Y') }} Sistem Request Instalasi Software
                </p>
            </div>
        </div>
    </div>
</body>
</html>
