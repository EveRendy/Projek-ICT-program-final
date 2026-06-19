<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Menampilkan Halaman Form Login
    public function showLogin()
    {
        // Jika sudah login, langsung lempar ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    // 2. Memproses Data Form Login
    public function login(Request $request)
    {
        // Validasi input dari user
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Proses pengecekan ke database (Email & Password cocok atau tidak)
        if (Auth::attempt($credentials)) {
            // Jika cocok, buat ulang session untuk keamanan
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Cek apakah dosen ini login pertama kali dan perlu lengkapi profil
            if ($user->role === 'dosen' && $user->is_first_login) {
                return redirect()->route('dosen.complete-profile')
                    ->with('info', 'Selamat datang! Silakan lengkapi profil Anda untuk melanjutkan.');
            }

            // Alihkan ke halaman dashboard
            return redirect()->intended('dashboard');
        }

        // Jika salah, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'loginError' => 'Email atau password yang kamu masukkan salah!',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Hancurkan session agar tidak bisa diakses kembali
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // 4. Update Password User
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.'
        ]);

        $user = Auth::user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->with('password_error', 'Password lama tidak sesuai!');
        }

        $user->update([
            'password' => \Hash::make($request->new_password)
        ]);

        return back()->with('password_success', 'Password berhasil diubah!');
    }

    // 5. Tampilkan Halaman Lengkapi Profil (untuk Dosen first login)
    public function showCompleteProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya dosen dengan is_first_login yang boleh akses halaman ini
        if (!$user || $user->role !== 'dosen' || !$user->is_first_login) {
            return redirect()->route('dashboard');
        }

        return view('dosen.complete_profile', compact('user'));
    }

    // 6. Simpan Data Profil Lengkap Dosen
    public function saveCompleteProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Double check: hanya dosen first login yang bisa submit
        if (!$user || $user->role !== 'dosen' || !$user->is_first_login) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'no_induk' => [
                'required',
                'string',
                'max:20',
                'regex:/^[a-zA-Z0-9]+$/',
                \Illuminate\Validation\Rule::unique('users', 'no_induk')->ignore($user->no_induk, 'no_induk'),
            ],
            'nama'         => 'required|string|max:100',
            'no_hp'        => 'required|digits_between:10,15',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'no_induk.required'      => 'NIP/No. Induk wajib diisi.',
            'no_induk.unique'        => 'NIP/No. Induk ini sudah digunakan.',
            'no_induk.regex'         => 'NIP/No. Induk hanya boleh huruf dan angka.',
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'no_hp.required'         => 'No. HP wajib diisi.',
            'no_hp.digits_between'   => 'No. HP harus antara 10-15 digit angka.',
            'new_password.required'  => 'Password baru wajib diisi.',
            'new_password.min'       => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Update data user: ganti no_induk, nama, no_hp, password, dan set is_first_login = false
        $user->update([
            'no_induk'       => strtoupper($request->no_induk),
            'nama'           => $request->nama,
            'no_hp'          => $request->no_hp,
            'password'       => Hash::make($request->new_password),
            'is_first_login' => false,
        ]);

        // Re-login agar session sinkron dengan no_induk baru
        Auth::login($user->fresh());

        return redirect()->route('dashboard')
            ->with('success', 'Profil berhasil dilengkapi! Selamat datang di sistem.');
    }
}