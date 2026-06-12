<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\InstalasiController;
use App\Http\Controllers\RiwayatController; 
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
    Route::get('/pengajuan', [PengajuanController::class, 'create'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // -------------------------------------------------------------------------
    // MENU DOSEN: 2. STATUS PENGAJUAN (Pelacakan Progres - SUDAH DIPERBAIKI)
    // -------------------------------------------------------------------------
    Route::get('/status-pengajuan', [PengajuanController::class, 'statusPengajuanDosen'])->name('pengajuan.status');
    Route::get('/status-pengajuan/{id}', [PengajuanController::class, 'detailPengajuan'])->name('pengajuan.showStatus');

    // -------------------------------------------------------------------------
    // MENU UNTUK SEMUA ROLE: RIWAYAT PENGAJUAN & PENGERJAAN GLOBAL
    // -------------------------------------------------------------------------
    // PERBAIKAN: Diarahkan ke RiwayatController::class agar menggunakan logic baru kita
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // -------------------------------------------------------------------------
    // MENU: SUPERVISOR (Persetujuan Awal: Terima / Tolak)
    // -------------------------------------------------------------------------
    Route::get('/supervisor/pengajuan', [PengajuanController::class, 'indexSupervisor'])->name('supervisor.pengajuan.index');

    // Tombol Aksi di halaman Supervisor
    Route::post('/pengajuan/{id}/setujui', [PengajuanController::class, 'setujui'])->name('pengajuan.approve');
    Route::post('/pengajuan/{id}/tolak', [PengajuanController::class, 'tolak'])->name('pengajuan.reject');
    
    // Alias backup rute lama agar tidak ada error di view lama
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
    Route::get('/admin/penyelesaian', [PengajuanController::class, 'indexPenyelesaian'])->name('admin.penyelesaian.index');
    Route::put('/update-pengerjaan/{id}/selesai', [PengajuanController::class, 'updateProgressTugas'])->name('admin.updateProgressTugas');

    // -------------------------------------------------------------------------
    // MENU: ALIAS RIWAYAT GLOBAL (Diarahkan ke Controller & View yang sama)
    // -------------------------------------------------------------------------
    // PERBAIKAN: Semua rute riwayat lama Admin/SPV ditembakkan ke RiwayatController::class 
    // supaya tombol menu mana pun yang diklik di sidebar, hasilnya tetap sama dan sinkron.
    Route::get('/riwayat-global-view', [RiwayatController::class, 'index'])->name('riwayat.global');
    Route::get('/admin/riwayat-global', [RiwayatController::class, 'index'])->name('admin.riwayat.global');

    // -------------------------------------------------------------------------
    // MENU: CETAK LAPORAN
    // -------------------------------------------------------------------------
    Route::get('/cetak-laporan-lab/{no_lab}', [CetakController::class, 'cetakLaporanLab'])->name('cetak.laporan.lab');
});