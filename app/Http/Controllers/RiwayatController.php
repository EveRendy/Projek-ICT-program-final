<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Laboratorium; // Ditambahkan untuk dropdown filter lab
use App\Models\Software;     // Ditambahkan untuk dropdown filter software
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request) // Menambahkan parameter Request untuk menangkap input filter
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

        // Ambil data untuk opsi dropdown di View
        $list_laboratorium = Laboratorium::all();
        $list_software = Software::all();

        // 3. Eager loading relasi resmi dari Model Pengajuan dengan menerapkan Filter & Search
        $query = Pengajuan::with(['laboratorium', 'software', 'dosen', 'admin']);

        // Logika Fitur Pencarian (Mata Kuliah, Nama Pemohon/Dosen, atau Nama Software Lain)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('mata_kuliah', 'like', '%' . $search . '%')
                  ->orWhere('software_lain', 'like', '%' . $search . '%')
                  ->orWhereHas('dosen', function($userQuery) use ($search) {
                      $userQuery->where('nama', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('software', function($softQuery) use ($search) {
                      $softQuery->where('nama_software', 'like', '%' . $search . '%');
                  });
            });
        }

        // Logika Fitur Filter Laboratorium
        if ($request->filled('laboratorium')) {
            $query->where('laboratorium_id', $request->laboratorium);
        }

        // Logika Fitur Filter Software
        if ($request->filled('software')) {
            $query->where('software_id', $request->software);
        }

        // Eksekusi query dengan pagination dan mempertahankan query string di URL pagination
        $pengajuans = $query->latest()->paginate(10)->withQueryString();

        // 4. Kirim data ke view (Menambahkan list_laboratorium dan list_software)
        return view('riwayat.index', compact('pengajuans', 'summary', 'role', 'canPrint', 'list_laboratorium', 'list_software'));
    }
}