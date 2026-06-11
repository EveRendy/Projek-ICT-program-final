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
        
        // 1. Tarik semua data mentah pengajuan sesuai filter role untuk keperluan ringkasan (Summary)
        $allDataQuery = Pengajuan::query();
        if ($role !== 'supervisor' && $role !== 'admin') {
            $allDataQuery->where('user_id', $user->id);
        }
        $allData = $allDataQuery->get(); // Mengambil data mentah dalam bentuk Laravel Collection

        // 2. Hitung statistik langsung dari Collection (Sangat aman dari bug syntax database)
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
        // PERBAIKAN UTAMA: Menghapus relasi 'user' yang memicu error
        $tableQuery = Pengajuan::with(['laboratorium', 'software', 'dosen']);
        
        if ($role !== 'supervisor' && $role !== 'admin') {
            $tableQuery->where('user_id', $user->id);
        }
        
        $pengajuans = $tableQuery->latest()->paginate(10);

        // 4. Kirim data ke view
        return view('riwayat.index', compact('pengajuans', 'summary', 'role'));
    }
}