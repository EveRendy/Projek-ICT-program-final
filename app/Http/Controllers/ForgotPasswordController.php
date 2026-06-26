<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password.
     */
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    /**
     * Kirim email link reset password.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Pastikan email terdaftar
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
        }

        // Kirim link reset (akan menggunakan settingan mail di .env dan tabel password_reset_tokens)
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => 'Kami telah mengirimkan tautan atur ulang kata sandi ke email Anda!'])
                    : back()->withErrors(['email' => 'Gagal mengirim email reset password. Coba lagi nanti.']);
    }

    /**
     * Tampilkan form reset password dengan token.
     */
    public function showResetForm($token)
    {
        return view('reset-password', ['token' => $token]);
    }

    /**
     * Proses reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', 'Kata sandi Anda telah berhasil diatur ulang. Silakan login dengan kata sandi baru.')
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
