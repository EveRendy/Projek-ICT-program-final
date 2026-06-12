<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\InstalasiController;
use App\Http\Controllers\RiwayatController; // <-- INI YANG BARU 
use App\Http\Controllers\CetakController;

// =========================================================================
// 1. Route Publik & Autentikasi
// =========================================================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =========================================================================
// 2. Route Proteksi (Wajib Login)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    
    // Halaman Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route CRUD Master Data (Resource)
    Route::resource('users', UserController::class);
    Route::resource('labs', LaboratoriumController::class);
    Route::resource('softwares', SoftwareController::class);
    Route::resource('instalasi', InstalasiController::class); 

    // -------------------------------------------------------------------------
    // MENU: PENGAJUAN (Khusus Dosen)
    // -------------------------------------------------------------------------
    Route::get('/pengajuan', [PengajuanController::class, 'indexPengajuan'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');


    // -------------------------------------------------------------------------
    // MENU: SUPERVISOR (Persetujuan Awal: Terima / Tolak)
    // -------------------------------------------------------------------------
    Route::get('/supervisor/pengajuan', [PengajuanController::class, 'indexSupervisor'])->name('supervisor.pengajuan.index');

    // Tombol Aksi di halaman Supervisor
    Route::patch('/pengajuan/{pengajuan}/setujui', [PengajuanController::class, 'setujui'])->name('supervisor.pengajuan.setujui');
    Route::patch('/pengajuan/{pengajuan}/tolak', [PengajuanController::class, 'tolak'])->name('supervisor.pengajuan.tolak');


    // -------------------------------------------------------------------------
    // MENU: ADMIN / TEKNISI (Hanya Update Status Instalasi jika Sudah Disetujui)
    // -------------------------------------------------------------------------
    // Halaman utama Admin untuk mengelola instalasi yang ditugaskan kepadanya (Progress)
    Route::get('/admin/instalasi', [PengajuanController::class, 'indexAdmin'])->name('admin.instalasi.index');
    
    // Fitur simpan data progress instalasi dari Admin
    Route::put('/admin/instalasi/{id}/update', [PengajuanController::class, 'updateProgressTugas'])->name('admin.instalasi.update');

    // [ALIAS SECURITY] Pengaman rute lama agar link lama tidak error 500
    Route::get('/update-pengerjaan', [PengajuanController::class, 'indexAdmin'])->name('pengerjaan.index');
    Route::get('/admin/tugas', [PengajuanController::class, 'indexAdmin'])->name('admin.tugas.index');
    
    // BERHASIL DIPERBAIKI: Mengarah ke 'indexPenyelesaian' agar membuka file penyelesaian.blade.php
    Route::get('/admin/penyelesaian', [PengajuanController::class, 'indexPenyelesaian'])->name('admin.penyelesaian.index');
    
    Route::put('/update-pengerjaan/{id}/selesai', [PengajuanController::class, 'updateProgressTugas'])->name('admin.updateProgressTugas');


    // -------------------------------------------------------------------------
    // MENU: RIWAYAT / LICENSE TRACKER
    // -------------------------------------------------------------------------
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

 

// Arahkan ke CetakController dan panggil method/fungsi 'cetakLaporanLab'
    Route::get('/laporan/lab/{no_lab}', [CetakController::class, 'cetakLaporanLab'])->name('laporan.lab');
});