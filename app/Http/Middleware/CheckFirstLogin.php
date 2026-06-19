<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstLogin
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memblokir dosen yang is_first_login = true
     * agar tidak bisa mengakses halaman lain sebelum melengkapi profil.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'dosen' && $user->is_first_login) {
            // Jika bukan sedang di halaman lengkapi profil itu sendiri, redirect
            if (!$request->routeIs('dosen.complete-profile') && !$request->routeIs('dosen.complete-profile.save')) {
                return redirect()->route('dosen.complete-profile')
                    ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu sebelum menggunakan sistem.');
            }
        }

        return $next($request);
    }
}
