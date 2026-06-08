<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\InstalasiController;

// 1. Route Publik & Autentikasi (Bisa diakses tanpa login)
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 2. Route Proteksi (Semua yang ada di dalam grup ini WAJIB login terlebih dahulu)
Route::middleware(['auth'])->group(function () {
    
    // Halaman Dashboard Utama (Satu untuk semua role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route CRUD Master Data (Resource)
    Route::resource('users', UserController::class);
    Route::resource('labs', LaboratoriumController::class);
    Route::resource('softwares', SoftwareController::class);
    Route::resource('instalasi', InstalasiController::class); // Tracker Lisensi yang baru dibuat

    // Fitur Transaksi Pengajuan (Sisi Dosen)
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // Fitur Pengelolaan & Approval (Sisi Supervisor - Sekarang Sudah Aman di dalam Auth)
    Route::get('/supervisor/pengajuan', [PengajuanController::class, 'indexSupervisor'])->name('supervisor.pengajuan.index');
    Route::patch('/supervisor/pengajuan/{id}/setujui', [PengajuanController::class, 'setujui'])->name('supervisor.pengajuan.setujui');
    Route::patch('/supervisor/pengajuan/{id}/tolak', [PengajuanController::class, 'tolak'])->name('supervisor.pengajuan.tolak');

    Route::get('/admin/tugas', [PengajuanController::class, 'indexAdmin'])->name('admin.tugas.index');

    
});