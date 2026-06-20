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
        $list_laboratorium = Laboratorium::orderBy('no_lab')->get();
        $list_software = Software::all();

        // 3. Eager loading untuk semua relasi yang dibutuhkan
        $query = Pengajuan::with(['software', 'dosen', 'admin']);

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

        // Logika Fitur Filter Software — harus diterapkan SEBELUM eksekusi query
        if ($request->filled('software')) {
            $query->where('software_id', $request->software);
        }

        // Logika Fitur Filter Laboratorium
        // lab_ids menyimpan array of ID (integer), filter menggunakan ID laboratorium
        if ($request->filled('laboratorium') && $request->laboratorium !== '') {
            $labId = (string) $request->laboratorium;
            
            // Ambil semua data yang sudah difilter search & software, lalu filter lab di PHP
            $allFiltered = $query->latest()->get();
            $filtered = $allFiltered->filter(function($item) use ($labId) {
                $labIdsArray = $item->lab_ids; // sudah di-cast ke array oleh model
                if (!is_array($labIdsArray)) return false;
                
                foreach ($labIdsArray as $id) {
                    if ((string)$id === $labId) {
                        return true;
                    }
                }
                return false;
            });
            
            // Manual pagination
            $perPage = 10;
            $page = request()->get('page', 1);
            $offset = ($page - 1) * $perPage;
            $paginatedData = $filtered->slice($offset, $perPage)->values();
            
            $pengajuans = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedData,
                $filtered->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // Tanpa filter laboratorium: eksekusi langsung dengan pagination
            $pengajuans = $query->latest()->paginate(10)->withQueryString();
        }

        // 4. Kirim data ke view
        return view('riwayat.index', compact('pengajuans', 'summary', 'role', 'canPrint', 'list_laboratorium', 'list_software'));
    }
}