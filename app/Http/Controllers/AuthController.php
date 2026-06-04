<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}