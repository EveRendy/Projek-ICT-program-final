<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

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
        
        // 4. Selesai: Menghitung pengajuan yang status progress-nya sudah 'selesai' 
        // (yang nanti di-update oleh Admin melalui menu Update Pengerjaan)
        $selesai = Pengajuan::where('status_progress', 'selesai')->count();
        
        // 5. Hari Ini: Menggunakan kolom 'tgl_pengajuan' sesuai format format data 
        // yang Anda set di method store() yaitu now()->toDateString()
        $pengajuanHariIni = Pengajuan::where('tgl_pengajuan', now()->toDateString())->count();

        // Mengirimkan semua data penghitungan ke view dashboard
        return view('dashboard', compact(
            'totalPengajuan',
            'menungguInstalasi',
            'sedangDiinstal',
            'selesai',
            'pengajuanHariIni'
        ));
    }
}