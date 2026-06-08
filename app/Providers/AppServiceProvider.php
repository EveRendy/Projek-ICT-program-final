<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
// Memberikan akses 'Super Admin' kepada Supervisor
        Gate::before(function ($user, $ability) {
            if ($user->role === 'supervisor') {
                return true; // Langsung izinkan semua akses tanpa syarat
            }
            // Penting: Jangan return false di sini. Biarkan kosong (null) 
            // agar Laravel lanjut mengecek aturan Gate lainnya untuk Dosen/Admin.
        });

        // --------------------------------------------------------
        // Definisi Gate spesifik kamu sebelumnya tetap biarkan saja di sini
        // Contoh:
        Gate::define('is-dosen', function ($user) {
            return $user->role === 'dosen';
        });

        Gate::define('is-admin', function ($user) {
            return $user->role === 'admin';
        });
        // --------------------------------------------------------
    }
}
