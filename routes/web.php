<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\PengajuanController;


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Dashboard (Pastikan middleware 'auth' aktif kembali)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');


// Masukkan ke dalam grup middleware auth agar aman
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route CRUD User otomatis
    Route::resource('users', UserController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    
    // Tambahkan route resource laboratorium di bawah ini
    Route::resource('labs', LaboratoriumController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('labs', LaboratoriumController::class);
    
    // Tambahkan route resource software di bawah ini
    Route::resource('softwares', SoftwareController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('labs', LaboratoriumController::class);
    Route::resource('softwares', SoftwareController::class);

    // Route untuk fitur pengajuan dosen
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
});

// Route Khusus Pengelolaan Supervisor
Route::get('/supervisor/pengajuan', [PengajuanController::class, 'indexSupervisor'])->name('supervisor.pengajuan.index');
Route::patch('/supervisor/pengajuan/{id}/setujui', [PengajuanController::class, 'setujui'])->name('supervisor.pengajuan.setujui');
Route::patch('/supervisor/pengajuan/{id}/tolak', [PengajuanController::class, 'tolak'])->name('supervisor.pengajuan.tolak');