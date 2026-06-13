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
        $totalPengajuan = Pengajuan::count();
        
        $menungguInstalasi = Pengajuan::where('status_persetujuan', 'pending')->count();
        
        $sedangDiinstal = Pengajuan::where('status_progress', 'progress')->count();
        
        $selesai = Pengajuan::where('status_progress', 'terinstal')->count();

        $pengajuanHariIni = Pengajuan::where('tgl_pengajuan', now()->toDateString())->count();

        $pengajuanDisetujui = Pengajuan::where('status_persetujuan', 'disetujui')->count();
        $pengajuanDitolak = Pengajuan::where('status_persetujuan', 'ditolak')->count();
        $pengajuanDitolakDetail = Pengajuan::where('status_persetujuan', 'ditolak')
            ->with(['dosen', 'laboratorium', 'software'])
            ->latest()
            ->get();
        $totalSoftware = Software::count();
        $totalLaboratorium = Laboratorium::count();
        $totalUser = User::count();
        $totalInstalasi = Instalasi::count();
        $aktivitasTerbaru = Pengajuan::with(['dosen', 'laboratorium', 'software'])
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
        $tugasTerbaru = Pengajuan::where('tugaskan_admin', $user->id)
            ->with(['dosen', 'laboratorium', 'software'])
            ->latest()
            ->take(5)
            ->get();

        $dosenTotalPengajuan = (clone $pengajuanDosen)->count();
        $dosenPengajuanDisetujui = (clone $pengajuanDosen)->where('status_persetujuan', 'disetujui')->count();
        $dosenPengajuanPending = (clone $pengajuanDosen)->where('status_persetujuan', 'pending')->count();
        $dosenPengajuanDitolak = (clone $pengajuanDosen)->where('status_persetujuan', 'ditolak')->count();
        $pengajuanTerbaruDosen = Pengajuan::where('user_id', $user->id)
            ->with(['laboratorium', 'software'])
            ->latest()
            ->take(5)
            ->get();

        // Mengirimkan semua data ke view dashboard
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
