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
        $role = $user->role ?? 'user';
        
        // 1. Siapkan kerangka query dasar (Eager loading relasi agar tidak lambat/N+1 issue)
        $query = Pengajuan::with(['laboratorium', 'software', 'dosen', 'user']);

        // 2. Terapkan filter berdasarkan Role User
        if ($role !== 'supervisor' && $role !== 'admin') {
            // KONTROL: Jika nanti muncul error 'Unknown column user_id', 
            // silakan ganti 'user_id' di bawah ini menjadi 'dosen_id' (sesuai isi DB Anda)
            $kolomDosen = 'user_id'; 
            
            // Filter: Dosen hanya melihat data pengajuannya sendiri
            $query->where($kolomDosen, $user->id);
        }

        // 3. Hitung ringkasan data ($summary) sesuai filter role menggunakan clone query
        $summary = [
            'total'      => (clone $query)->count(),
            
            'menunggu'   => (clone $query)->where(function($q) {
                                $q->whereIn('status_progress', ['menunggu', 'pending'])
                                  ->orWhereNull('status_progress');
                            })->count(),
                            
            'progress'   => (clone $query)->whereIn('status_progress', ['progress', 'on progress'])->count(),
            
            'selesai'    => (clone $query)->whereIn('status_progress', ['terinstal', 'installed', 'selesai'])->count(),
            
            'terkendala' => (clone $query)->whereIn('status_progress', ['terkendala', 'gagal_terinstal', 'gagal'])->count(),
        ];

        // 4. Ambil data aktual untuk ditampilkan di tabel dengan sistem Pagination (10 baris per halaman)
        // Di blade digunakan variabel $pengajuans, maka kita beri nama variabelnya $pengajuans
        $pengajuans = $query->latest()->paginate(10);

        // 5. Kirim data ke view
        return view('riwayat.index', compact('pengajuans', 'summary', 'role'));
    }
}