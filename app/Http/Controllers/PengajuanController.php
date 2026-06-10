<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Laboratorium;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    // =========================================================================
    // 1. MENU DOSEN: Menampilkan Riwayat Pengajuan Milik Dosen Sendiri
    // =========================================================================
    public function indexPengajuan()
    {
        $user = Auth::user();
        
        // Khusus Dosen/User Biasa: Hanya menampilkan riwayat pengajuan miliknya sendiri
        $pengajuans = $user->pengajuans()->with(['laboratorium', 'software'])->latest()->get();
        return view('pengajuan.index', compact('pengajuans'));
    }

    // =========================================================================
    // 2. MENU SUPERVISOR: Menampilkan Pengajuan yang Masih PENDING untuk Disetujui
    // =========================================================================
    public function indexSupervisor()
    {
        $query = Pengajuan::with(['dosen', 'laboratorium.admin', 'software']);

        // Summary khusus untuk dashboard pemantauan Supervisor
        $summary = [
            'total'      => (clone $query)->count(),
            'menunggu'   => (clone $query)->where('status_persetujuan', 'pending')->count(),
            'progress'   => (clone $query)->where('status_persetujuan', 'disetujui')->where('status_progress', 'progress')->count(),
            'selesai'    => (clone $query)->where('status_persetujuan', 'disetujui')->where('status_progress', 'terinstal')->count(),
            'terkendala' => (clone $query)->where('status_persetujuan', 'disetujui')->where('status_progress', 'gagal_terinstal')->count(),
        ];

        // KUNCI UTAMA: Supervisor di halaman ini fokus memproses data yang status_persetujuan-nya 'pending'
        $tugas = $query->where('status_persetujuan', 'pending')->latest()->get();
        
        return view('supervisor.index', compact('tugas', 'summary'));
    }

// =========================================================================
    // 3. MENU UPDATE PENGERJAAN: Digunakan oleh Supervisor (Approve/Reject) 
    //    dan Admin/Teknisi (Update Progress Instalasi)
    // =========================================================================
    public function indexAdmin()
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';

        // --- ALUR LOGIKA JIKA YANG LOGIN ADALAH SUPERVISOR ---
        if ($role === 'supervisor') {
            // Ambil semua pengajuan yang status persetujuannya masih 'pending'
            $query = Pengajuan::where('status_persetujuan', 'pending')
                ->with(['dosen', 'laboratorium', 'software']);
            
            $tugas = $query->latest()->get();

            $summary = [
                'total'      => $tugas->count(),
                'menunggu'   => $tugas->count(),
                'progress'   => 0,
                'selesai'    => 0,
                'terkendala' => 0,
            ];

            return view('admin.penyelesaian', compact('tugas', 'summary', 'role'));
        }

        // --- ALUR LOGIKA JIKA YANG LOGIN ADALAH ADMIN / TEKNISI BIASA ---
        $query = Pengajuan::where('tugaskan_admin', $user->id)
            ->where('status_persetujuan', 'disetujui')
            ->with(['dosen', 'laboratorium', 'software']);

        $tugas = $query->latest()->get();

        $summary = [
            'total'      => $tugas->count(),
            'menunggu'   => $tugas->where(function ($item) {
                return is_null($item->status_progress) || $item->status_progress === 'menunggu';
            })->count(),
            'progress'   => $tugas->where('status_progress', 'progress')->count(),
            'selesai'    => $tugas->where('status_progress', 'terinstal')->count(),
            'terkendala' => $tugas->where('status_progress', 'gagal_terinstal')->count(),
        ];

        return view('admin.penyelesaian', compact('tugas', 'summary', 'role'));
    }

    // =========================================================================
    // 4. Menampilkan Form Buat Pengajuan Baru (Dosen)
    // =========================================================================
    public function create()
    {
        $laboratoriums = Laboratorium::all();
        $softwares = Software::all();
        return view('pengajuan.create', compact('laboratoriums', 'softwares'));
    }

    // =========================================================================
    // 5. Memproses Penyimpanan Data Pengajuan (Dosen)
    // =========================================================================
    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah'     => 'required|string|max:255',
            'kelompok_matkul' => 'required|string|max:50',
            'laboratorium_id' => 'required|exists:laboratoriums,id',
            'software_id'     => 'nullable|exists:software,id',
            'versi_requested' => 'nullable|string',
            'software_lain'   => 'nullable|string|max:255',
            'versi_lain'      => 'nullable|string|max:255',
        ]);

        if (!$request->software_id && !$request->software_lain) {
            return back()->withErrors(['software_error' => 'Kamu harus memilih software yang terdaftar atau mengisi nama software lain!'])->withInput();
        }

        Pengajuan::create([
            'tgl_pengajuan'      => now()->toDateString(),
            'mata_kuliah'        => $request->mata_kuliah,
            'kelompok_matkul'    => $request->kelompok_matkul,
            'user_id'            => Auth::id(), 
            'laboratorium_id'    => $request->laboratorium_id,
            'software_id'        => $request->software_id,
            'versi_requested'    => $request->versi_requested,
            'software_lain'      => $request->software_lain,
            'versi_lain'         => $request->versi_lain,
            'status_persetujuan' => 'pending', // Default masuk ke supervisor dulu
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan instalasi berhasil dikirim!');
    }

    // =========================================================================
    // 6. Proses Menyetujui Pengajuan (Aksi Supervisor) -> Teruskan ke Admin
    // =========================================================================
    public function setujui($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $lab = $pengajuan->laboratorium;

        if (!$lab->user_id) {
            return back()->with('error', 'Gagal menyetujui! Laboratorium ' . $lab->no_lab . ' belum memiliki Admin penanggung jawab.');
        }

        $pengajuan->update([
            'status_persetujuan' => 'disetujui',
            'tugaskan_admin'     => $lab->user_id, // Mengisi kolom admin otomatis
            'tgl_penugasan'      => now()->toDateString(),
            'status_progress'    => 'progress', // Langsung set status awal pengerjaan untuk Admin
        ]);

        return back()->with('success', 'Pengajuan berhasil disetujui! Tugas otomatis diteruskan ke Admin ' . $lab->admin->name);
    }

    // =========================================================================
    // 7. Proses Menolak Pengajuan (Aksi Supervisor)
    // =========================================================================
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_spv' => 'required|string|max:500',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        
        $pengajuan->update([
            'status_persetujuan' => 'ditolak',
            'catatan_spv'        => $request->catatan_spv,
            'status_progress'    => null, 
        ]);

        return back()->with('success', 'Pengajuan telah ditolak.');
    }

    // =========================================================================
    // 8. Proses Update Progress / Kelayakan Instalasi (Aksi Admin)
    // =========================================================================
    public function updateProgressTugas(Request $request, $id)
    {
        $request->validate([
            'status_progress' => 'required|in:progress,terinstal,gagal_terinstal',
            'dokumentasi'     => 'required|url|max:255',
            'catatan_admin'   => 'nullable|string|max:500', 
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        $pengajuan->update([
            'status_progress' => $request->status_progress,
            'dokumentasi'     => $request->dokumentasi, 
            'catatan_admin'   => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status instalasi berhasil diperbarui oleh Admin!');
    }

    // =========================================================================
    // 9. HALAMAN RIWAYAT / LICENSE TRACKER
    // =========================================================================
    public function licenseTracker()
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        $query = Pengajuan::with(['software', 'laboratorium', 'dosen']);

        if ($role !== 'supervisor' && $role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $summary = [
            'total'      => (clone $query)->count(),
            'menunggu'   => (clone $query)->where('status_persetujuan', 'pending')->count(),
            'progress'   => (clone $query)->where('status_progress', 'progress')->count(),
            'selesai'    => (clone $query)->where('status_progress', 'terinstal')->count(),
            'terkendala' => (clone $query)->where('status_progress', 'gagal_terinstal')->count(),
        ];

        $pengajuans = $query->latest()->paginate(10);

        return view('riwayat.index', compact('pengajuans', 'summary', 'role')); 
    }
}