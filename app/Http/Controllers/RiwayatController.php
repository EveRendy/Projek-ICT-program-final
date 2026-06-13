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
        
        // Hak akses cetak laporan untuk supervisor atau admin
        $canPrint = in_array($role, ['supervisor', 'admin']);

        // 1. Ambil semua data mentah tanpa filter untuk perhitungan statistik keseluruhan
        $allData = Pengajuan::all(); 

        // 2. Hitung ringkasan statistik (Summary)
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

        // 3. Eager loading relasi resmi dari Model Pengajuan (Menghindari N+1 Query)
        $pengajuans = Pengajuan::with(['laboratorium', 'software', 'dosen', 'admin'])
            ->latest()
            ->paginate(10);

        // 4. Kirim data ke view
        return view('riwayat.index', compact('pengajuans', 'summary', 'role', 'canPrint'));
    }
}