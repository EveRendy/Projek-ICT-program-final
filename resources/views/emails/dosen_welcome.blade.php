<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun Dosen</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f1f5f9; margin: 0; padding: 40px 20px; color: #334155;">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);">
        <!-- Header Section -->
        <tr>
            <td style="background-color: #1e293b; padding: 40px 30px; text-align: center; border-bottom: 4px solid #3b82f6;">
                <img src="{{ asset('images/image.png') }}" alt="Logo ICT" style="height: 70px; margin-bottom: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);">
                <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 900; letter-spacing: 0.5px;">Selamat Datang!</h1>
                <p style="color: #94a3b8; margin: 8px 0 0 0; font-size: 14px; font-weight: 500;">Sistem Manajemen Laboratorium ICT</p>
            </td>
        </tr>

        <!-- Body Section -->
        <tr>
            <td style="padding: 40px 40px;">
                <h2 style="color: #0f172a; font-size: 20px; font-weight: 800; margin-top: 0; margin-bottom: 15px;">Halo, Dosen Universitas Bandar Lampung</h2>
                
                <p style="font-size: 15px; line-height: 1.7; color: #475569; margin-bottom: 25px;">Akun Anda untuk <strong style="color: #0f172a;">Sistem Instalasi Software Laboratorium ICT</strong> telah berhasil dibuat. Anda sekarang dapat masuk dan mulai mengajukan instalasi software untuk kebutuhan perkuliahan Anda.</p>
                
                <!-- Info Card -->
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 30px 0; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);">
                    <h3 style="color: #1e293b; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 15px 0; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">Detail Login Anda</h3>
                    
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size: 14px;">
                        <tr>
                            <td style="padding: 8px 0; color: #64748b; width: 130px; font-weight: 500;">Email Akun</td>
                            <td style="padding: 8px 0; color: #3b82f6; font-weight: 700; text-decoration: none;">
                                {{ $recipientEmail }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #64748b; font-weight: 500;">Password Sementara</td>
                            <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">
                                <span style="background-color: #e2e8f0; color: #0f172a; padding: 4px 8px; border-radius: 6px; font-size: 16px; font-family: monospace; letter-spacing: 1px;">{{ $temporaryPassword }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: center; margin: 35px 0;">
                    <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 14px 28px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);">Masuk ke Dashboard</a>
                </div>

                <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px 20px; border-radius: 6px; margin-bottom: 30px;">
                    <p style="margin: 0; color: #b45309; font-size: 14px; line-height: 1.6;">
                        <strong style="display: block; margin-bottom: 6px; color: #92400e; font-size: 15px;">⚠️ Penting!</strong>
                        Saat pertama kali Anda masuk, Anda akan langsung diminta untuk <strong>mengubah password sementara Anda</strong> dan melengkapi profil (Nama, No HP) demi keamanan akun Anda.
                    </p>
                </div>

                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding-top: 10px;">
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 0;">Terima kasih atas kerja samanya.</p>
                            <p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 5px;">Salam hangat,<br>Tim Laboratorium ICT</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Footer Section -->
        <tr>
            <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px; text-align: center;">
                <p style="color: #94a3b8; font-size: 12px; margin: 0; line-height: 1.5;">
                    Email ini dikirim secara otomatis oleh <strong>Sistem Manajemen Laboratorium ICT</strong>.<br>
                    Mohon untuk tidak membalas ke alamat email ini.
                </p>
            </td>
        </tr>
    </table>

</body>
</html>
