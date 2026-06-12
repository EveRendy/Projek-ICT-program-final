<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role ?? 'user'); 
        
        // Cek apakah user adalah supervisor atau admin untuk hak akses cetak laporan
        $canPrint = in_array($role, ['supervisor', 'admin']);

        // 1. Tarik SEMUA data mentah pengajuan tanpa filter agar semua role bisa melihatnya
        $allData = Pengajuan::all(); 

        // 2. Hitung statistik dari seluruh data
        $summary = [
            'total' => $allData->count(),
            
            'menunggu' => $allData->filter(function($item) {
                $statusPersetujuan = strtolower($item->status_persetujuan ?? 'pending');
                return $statusPersetujuan === 'pending';
            })->count(),
            
            'progress' => $allData->filter(function($item) {
                $statusProgress = strtolower($item->status_progress ?? '');
                return $statusProgress === 'progress';
            })->count(),
            
            'selesai' => $allData->filter(function($item) {
                $statusProgress = strtolower($item->status_progress ?? '');
                $statusPersetujuan = strtolower($item->status_persetujuan ?? '');
                return $statusProgress === 'terinstal' || ($statusPersetujuan === 'disetujui' && empty($statusProgress));
            })->count(),
            
            'gagal' => $allData->filter(function($item) {
                $statusProgress = strtolower($item->status_progress ?? '');
                $statusPersetujuan = strtolower($item->status_persetujuan ?? '');
                return $statusProgress === 'gagal_terinstal' || $statusPersetujuan === 'ditolak';
            })->count(),
        ];

        // 3. Ambil data dengan Eager Loading relasi yang VALID untuk tabel (Paginasi 10 baris)
        // Tanpa filter where(), agar dosen tetap bisa melihat riwayat pengajuan dosen lain
        $pengajuans = Pengajuan::with(['laboratorium', 'software', 'dosen'])
            ->latest()
            ->paginate(10);

        // 4. Kirim data ke view, termasuk variabel $canPrint
        return view('riwayat.index', compact('pengajuans', 'summary', 'role', 'canPrint'));
    }
}