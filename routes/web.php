<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\RiwayatController; 
use App\Http\Controllers\CetakController;
use App\Http\Controllers\HardwareController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\ForgotPasswordController;


// =========================================================================
// 1. Route Publik & Autentikasi
// =========================================================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update.action');
});


// =========================================================================
// 2. Route Proteksi (Wajib Login)
// =========================================================================
// =========================================================================
// Route khusus: Dosen yang baru pertama kali login (hanya butuh auth, bukan check.first.login)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/dosen/complete-profile', [AuthController::class, 'showCompleteProfile'])->name('dosen.complete-profile');
    Route::post('/dosen/complete-profile', [AuthController::class, 'saveCompleteProfile'])->name('dosen.complete-profile.save');
});

// =========================================================================
// Route Proteksi (Wajib Login + Profil Harus Sudah Dilengkapi)
// =========================================================================
Route::middleware(['auth', 'check.first.login'])->group(function () {
    
    // Halaman Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route Update Password User
    Route::put('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');
    
    // Route CRUD Master Data (Resource)
    Route::resource('users', UserController::class);
    Route::patch('/users/{no_induk}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');

    // Route Hardware
    Route::resource('hardware', HardwareController::class)->only(['index', 'store', 'update', 'destroy']);
    

    
    // Route kustom untuk update status laboratorium oleh Supervisor sebelum resource labs
    Route::patch('/labs/{id}/update-status', [LaboratoriumController::class, 'updateStatus'])->name('labs.updateStatus');
    Route::patch('/labs/{id}/toggle-active', [LaboratoriumController::class, 'toggleActive'])->name('labs.toggleActive');
    Route::resource('labs', LaboratoriumController::class);
    
    Route::resource('softwares', SoftwareController::class);
    
    // Route Pelacak Lisensi
    Route::get('/license', [LicenseController::class, 'index'])->name('license.index');
    Route::get('/license/{labId}', [LicenseController::class, 'showLab'])->name('license.show-lab');
    Route::get('/license/{labId}/software/{softwareId}', [LicenseController::class, 'showSoftware'])->name('license.show-software');
    Route::post('/license/store', [LicenseController::class, 'store'])->name('license.store');
    Route::put('/license/{id}/update', [LicenseController::class, 'update'])->name('license.update');
    Route::delete('/license/{id}/destroy', [LicenseController::class, 'destroy'])->name('license.destroy');
    Route::delete('/license/{labId}/software/{softwareId}/destroy', [LicenseController::class, 'destroySoftware'])->name('license.destroy-software');

    // -------------------------------------------------------------------------
    // MENU DOSEN: 1. PENGAJUAN (Langsung Menampilkan Formulir Input)
    // -------------------------------------------------------------------------
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // -------------------------------------------------------------------------
    // MENU DOSEN: 2. STATUS PENGAJUAN (Pelacakan Progres - SUDAH DIPERBAIKI)
    // -------------------------------------------------------------------------
    Route::get('/status-pengajuan', [PengajuanController::class, 'statusPengajuanDosen'])->name('pengajuan.status');
    Route::get('/status-pengajuan/{id}', [PengajuanController::class, 'detailPengajuan'])->name('pengajuan.showStatus');
    Route::get('/cek-instalasi', [PengajuanController::class, 'cekInstalasi'])->name('pengajuan.cek.instalasi');

    // -------------------------------------------------------------------------
    // MENU UNTUK SEMUA ROLE: RIWAYAT PENGAJUAN & PENGERJAAN GLOBAL
    // -------------------------------------------------------------------------
    // PERBAIKAN: Tanda komentar (//) sudah dihapus agar route ini aktif kembali
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    // -------------------------------------------------------------------------
    // MENU: SUPERVISOR (Persetujuan Awal: Terima / Tolak)
    // -------------------------------------------------------------------------
    Route::get('/supervisor/pengajuan', [PengajuanController::class, 'indexSupervisor'])->name('supervisor.pengajuan.index');

    // Tombol Aksi di halaman Supervisor
    Route::post('/pengajuan/{id}/setujui', [PengajuanController::class, 'setujui'])->name('pengajuan.approve');
    Route::post('/pengajuan/{id}/tolak', [PengajuanController::class, 'tolak'])->name('pengajuan.reject');

    // Penugasan admin secara manual oleh supervisor setelah approval
    Route::post('/pengajuan/{id}/assign-admin', [PengajuanController::class, 'updateAdminAssignment'])->name('pengajuan.assign.admin');

    // Bulk assignment admin ke semua pengajuan tertahan
    Route::post('/supervisor/bulk-assign-admin', [PengajuanController::class, 'bulkAssignAdmin'])->name('supervisor.bulk.assign.admin');

    // Alias backup rute lama agar tidak ada error di view lama
    Route::patch('/pengajuan/{pengajuan}/setujui', [PengajuanController::class, 'setujui'])->name('supervisor.pengajuan.setujui');
    Route::patch('/pengajuan/{pengajuan}/tolak', [PengajuanController::class, 'tolak'])->name('supervisor.pengajuan.tolak');

    // Edit pengajuan oleh supervisor sebelum disetujui (untuk koreksi typo dosen)
    Route::patch('/supervisor/pengajuan/{id}/edit', [PengajuanController::class, 'editSebelumSetujui'])->name('supervisor.pengajuan.edit');

    // -------------------------------------------------------------------------
    // MENU: ADMIN / TEKNISI (Update Status Jalannya Instalasi)
    // -------------------------------------------------------------------------
    Route::get('/admin/instalasi', [PengajuanController::class, 'indexAdmin'])->name('admin.instalasi.index');
    Route::put('/admin/instalasi/{id}/update', [PengajuanController::class, 'updateProgressTugas'])->name('admin.instalasi.update');

    // Upload foto bukti instalasi oleh admin
    Route::post('/admin/instalasi/{id}/foto-bukti', [PengajuanController::class, 'uploadFotoBukti'])->name('admin.foto.upload');

    // Verifikasi foto bukti oleh supervisor
    Route::patch('/supervisor/foto/{id}/approve', [PengajuanController::class, 'approveFotoBukti'])->name('supervisor.foto.approve');
    Route::patch('/supervisor/foto/{id}/tolak', [PengajuanController::class, 'tolakFotoBukti'])->name('supervisor.foto.tolak');

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
    Route::get('/preview-laporan-lab/{no_lab}', [CetakController::class, 'previewLaporanLab'])->name('preview.laporan.lab');
});