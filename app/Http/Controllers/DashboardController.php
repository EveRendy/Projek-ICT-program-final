<?php

namespace App\Http\Controllers;

use App\Models\Instalasi;
use App\Models\Laboratorium;
use App\Models\Pengajuan;
use App\Models\Software;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Pengajuan: Menghitung seluruh baris data di tabel pengajuan
        $totalPengajuan = Pengajuan::count();   
        
        // 2. Menunggu Instalasi: Berdasarkan acuan method store() Anda, 
        // status awal saat dosen membuat pengajuan adalah 'pending'
        $menungguInstalasi = Pengajuan::where('status_persetujuan', 'pending')->count();
        
        // 3. Sedang Diinstal: Berdasarkan acuan method setujui() Anda, 
        // saat disetujui SPV, 'status_progress' otomatis di-update menjadi 'progress'
        $sedangDiinstal = Pengajuan::where('status_progress', 'progress')->count();
        
        // 4. Selesai: sesuai enum migration, status selesai instalasi adalah 'terinstal'
        $selesai = Pengajuan::where('status_progress', 'terinstal')->count();
        
        // 5. Hari Ini: Menggunakan kolom 'tgl_pengajuan' sesuai format format data 
        // yang Anda set di method store() yaitu now()->toDateString()
        $pengajuanHariIni = Pengajuan::where('tgl_pengajuan', now()->toDateString())->count();

        $pengajuanDisetujui = Pengajuan::where('status_persetujuan', 'disetujui')->count();
        $pengajuanDitolak = Pengajuan::where('status_persetujuan', 'ditolak')->count();
        
        // DIUBAH: Menghapus 'laboratorium'
        $pengajuanDitolakDetail = Pengajuan::where('status_persetujuan', 'ditolak')
            ->with(['dosen', 'software'])
            ->latest()
            ->get();
            
        $totalSoftware = Software::count();
        $totalLaboratorium = Laboratorium::count();
        $totalUser = User::count();
        $totalInstalasi = Instalasi::count();
        
        // DIUBAH: Menghapus 'laboratorium'
        $aktivitasTerbaru = Pengajuan::with(['dosen', 'software'])
            ->latest()
            ->take(5)
            ->get();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $tugasAdmin = Pengajuan::where('tugaskan_admin', $user->id);
        $pengajuanDosen = Pengajuan::where('user_id', $user->id);

        $adminTotalInstalasi = (clone $tugasAdmin)->where('status_persetujuan', 'disetujui')->count();
        $adminInstalasiSelesai = (clone $tugasAdmin)->where('status_progress', 'terinstal')->count();
        $adminInstalasiBerjalan = (clone $tugasAdmin)->where('status_progress', 'progress')->count();
        $adminInstalasiPending = (clone $tugasAdmin)
            ->where('status_persetujuan', 'disetujui')
            ->whereNull('status_progress')
            ->count();
            
        // DIUBAH: Menghapus 'laboratorium'
        $tugasTerbaru = Pengajuan::where('tugaskan_admin', $user->id)
            ->with(['dosen', 'software'])
            ->latest()
            ->take(5)
            ->get();

        $dosenTotalPengajuan = (clone $pengajuanDosen)->count();
        $dosenPengajuanDisetujui = (clone $pengajuanDosen)->where('status_persetujuan', 'disetujui')->count();
        $dosenPengajuanPending = (clone $pengajuanDosen)->where('status_persetujuan', 'pending')->count();
        $dosenPengajuanDitolak = (clone $pengajuanDosen)->where('status_persetujuan', 'ditolak')->count();
        
        // DIUBAH: Menghapus 'laboratorium'
        $pengajuanTerbaruDosen = Pengajuan::where('user_id', $user->id)
            ->with(['software'])
            ->latest()
            ->take(5)
            ->get();

        // Mengirimkan semua data penghitungan ke view dashboard
        return view('dashboard', compact(
            'totalPengajuan',
            'menungguInstalasi',
            'sedangDiinstal',
            'selesai',
            'pengajuanHariIni',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'pengajuanDitolakDetail',
            'totalSoftware',
            'totalLaboratorium',
            'totalUser',
            'totalInstalasi',
            'aktivitasTerbaru',
            'adminTotalInstalasi',
            'adminInstalasiSelesai',
            'adminInstalasiBerjalan',
            'adminInstalasiPending',
            'tugasTerbaru',
            'dosenTotalPengajuan',
            'dosenPengajuanDisetujui',
            'dosenPengajuanPending',
            'dosenPengajuanDitolak',
            'pengajuanTerbaruDosen'
        ));
    }
}