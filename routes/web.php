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
    // MENU DOSEN: 1. PENGAJUAN (Langsung Menampilkan Formulir Input)
    // -------------------------------------------------------------------------
    // Diubah ke 'create' agar ketika dosen klik menu Pengajuan, langsung muncul Form Isian
    Route::get('/pengajuan', [PengajuanController::class, 'create'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // -------------------------------------------------------------------------
    // MENU DOSEN: 2. STATUS PENGAJUAN (Pelacakan Approval SPV - Tampilan List)
    // -------------------------------------------------------------------------
    Route::get('/status-pengajuan', [PengajuanController::class, 'statusPengajuan'])->name('pengajuan.status');
    Route::get('/status-pengajuan/{id}', [PengajuanController::class, 'detailPengajuan'])->name('pengajuan.showStatus');

    // -------------------------------------------------------------------------
    // MENU DOSEN: 3. RIWAYAT PENGAJUAN (Menampilkan Tabel Data Horizontal Dosen)
    // -------------------------------------------------------------------------
    Route::get('/riwayat', [PengajuanController::class, 'riwayatPengajuan'])->name('riwayat.index');

    // -------------------------------------------------------------------------
    // MENU: SUPERVISOR (Persetujuan Awal: Terima / Tolak)
    // -------------------------------------------------------------------------
    Route::get('/supervisor/pengajuan', [PengajuanController::class, 'indexSupervisor'])->name('supervisor.pengajuan.index');

    // Tombol Aksi di halaman Supervisor
    Route::patch('/pengajuan/{pengajuan}/setujui', [PengajuanController::class, 'setujui'])->name('supervisor.pengajuan.setujui');
    Route::patch('/pengajuan/{pengajuan}/tolak', [PengajuanController::class, 'tolak'])->name('supervisor.pengajuan.tolak');

    // -------------------------------------------------------------------------
    // MENU: ADMIN / TEKNISI (Update Status Jalannya Instalasi)
    // -------------------------------------------------------------------------
    Route::get('/admin/instalasi', [PengajuanController::class, 'indexAdmin'])->name('admin.instalasi.index');
    Route::put('/admin/instalasi/{id}/update', [PengajuanController::class, 'updateProgressTugas'])->name('admin.instalasi.update');

    // [ALIAS SECURITY] Pengaman rute lama agar link di view Admin tidak error
    Route::get('/update-pengerjaan', [PengajuanController::class, 'indexAdmin'])->name('pengerjaan.index');
    Route::get('/admin/tugas', [PengajuanController::class, 'indexAdmin'])->name('admin.tugas.index');
    
    // BERHASIL DIPERBAIKI: Mengarah ke 'indexPenyelesaian' agar membuka file penyelesaian.blade.php
    Route::get('/admin/penyelesaian', [PengajuanController::class, 'indexPenyelesaian'])->name('admin.penyelesaian.index');
    
    Route::put('/update-pengerjaan/{id}/selesai', [PengajuanController::class, 'updateProgressTugas'])->name('admin.updateProgressTugas');

    // -------------------------------------------------------------------------
    // MENU: LICENSE TRACKER / RIWAYAT GLOBAL (Khusus Supervisor & Admin)
    // -------------------------------------------------------------------------
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

 

// Arahkan ke CetakController dan panggil method/fungsi 'cetakLaporanLab'
    Route::get('/laporan/lab/{no_lab}', [CetakController::class, 'cetakLaporanLab'])->name('laporan.lab');
    // URL dipisahkan ke '/admin/riwayat-global' agar tidak menimpa rute '/riwayat' milik Dosen
    Route::get('/admin/riwayat-global', [PengajuanController::class, 'licenseTracker'])->name('admin.riwayat.global');
});