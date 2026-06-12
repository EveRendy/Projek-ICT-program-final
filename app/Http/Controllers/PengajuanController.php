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
    // 1. MENU DOSEN: Menampilkan Riwayat Pengajuan (Tabel Horizontal Dosen)
    // =========================================================================
    public function riwayatPengajuan()
    {
        // 1. Ambil data user dan role yang sedang login
        $user = Auth::user();
        $role = $user->role ?? 'user';

        $pengajuans = Pengajuan::with(['laboratorium', 'software'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // 2. Tambahkan 'role' ke dalam compact agar dibaca oleh Blade
        return view('pengajuan.index', compact('pengajuans', 'role'));
    }

    // =========================================================================
    // 2. MENU DOSEN: Menampilkan List Status Approval SPV (Mockup Vertikal)
    // =========================================================================
    public function indexSupervisor()
    {
        $query = Pengajuan::with(['dosen', 'laboratorium.admin', 'software']);

        $summaryTotals = (clone $query)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status_persetujuan = 'pending' THEN 1 ELSE 0 END) as menunggu,
            SUM(CASE WHEN status_persetujuan = 'disetujui' AND status_progress = 'progress' THEN 1 ELSE 0 END) as progress,
            SUM(CASE WHEN status_persetujuan = 'disetujui' AND status_progress = 'terinstal' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status_persetujuan = 'disetujui' AND status_progress = 'gagal_terinstal' THEN 1 ELSE 0 END) as terkendala
        ")->first();

        $summary = [
            'total'      => $summaryTotals->total ?? 0,
            'menunggu'   => $summaryTotals->menunggu ?? 0,
            'progress'   => $summaryTotals->progress ?? 0,
            'selesai'    => $summaryTotals->selesai ?? 0,
            'terkendala' => $summaryTotals->terkendala ?? 0,
        ];

        $tugas = $query->where('status_persetujuan', 'pending')->latest()->get();
        
        return view('supervisor.index', compact('tugas', 'summary'));
    }

    // =========================================================================
    // 3. MENU UPDATE PENGERJAAN: Menampilkan Tugas Aktif
    // =========================================================================
    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        $labId = $request->get('lab_id');
        $laboratoriums = Laboratorium::all();

        // Base query awal: ambil semua yang sudah disetujui supervisor
        $query = Pengajuan::where('status_persetujuan', 'disetujui')
            ->with(['dosen', 'laboratorium', 'software']);

        // JIKA BUKAN SUPERVISOR (Artinya dia Admin/Teknisi biasa)
        if ($role !== 'supervisor') {
            // Batasi hanya tugas milik dia sendiri DAN yang statusnya masih 'progress'
            $query->where('tugaskan_admin', $user->id)
                  ->where('status_progress', 'progress');
        }
        // JIKA SUPERVISOR: Query di atas dilewati, sehingga supervisor bisa melihat SEMUA status data.

        if ($labId) {
            $query->where('laboratorium_id', $labId);
        }

        $tugas = $query->latest()->get();

        // Kalkulasi otomatis menyesuaikan data yang ditarik oleh query di atas
        $summary = [
            'total'      => $tugas->count(),
            'terkendala' => $tugas->where('status_progress', 'gagal_terinstal')->count(),
            'progress'   => $tugas->where('status_progress', 'progress')->count(),
            'selesai'    => $tugas->where('status_progress', 'terinstal')->count(),
        ];

        return view('admin.tugas', compact('tugas', 'summary', 'role', 'laboratoriums'));
    }

    // =========================================================================
    // NEW METHOD: Menampilkan Riwayat Tugas yang Selesai / Gagal Terinstal
    // =========================================================================
    public function indexPenyelesaian(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        $labId = $request->get('lab_id');
        $laboratoriums = Laboratorium::all();

        $query = Pengajuan::where('status_persetujuan', 'disetujui')
            ->with(['dosen', 'laboratorium', 'software']);

        if ($role !== 'supervisor') {
            // Jika Admin biasa: Hanya melihat data miliknya yang sudah selesai/terkendala
            $query->where('tugaskan_admin', $user->id)
                  ->whereIn('status_progress', ['terinstal', 'gagal_terinstal']);
        } else {
            // Jika Supervisor: Melihat data riwayat penyelesaian dari SELURUH admin/teknisi
            $query->whereIn('status_progress', ['terinstal', 'gagal_terinstal']);
        }

        if ($labId) {
            $query->where('laboratorium_id', $labId);
        }

        $tugas = $query->latest()->get();

        $summary = [
            'total'      => $tugas->count(),
            'terkendala' => $tugas->where('status_progress', 'gagal_terinstal')->count(),
            'progress'   => $tugas->where('status_progress', 'progress')->count(),
            'selesai'    => $tugas->where('status_progress', 'terinstal')->count(),
        ];

        return view('admin.penyelesaian', compact('tugas', 'summary', 'role', 'laboratoriums'));
    } // <-- SUDAH DIPERBAIKI DISINI

    // =========================================================================
    // 4. Form Buat Pengajuan Baru (Dosen)
    // =========================================================================
    public function statusPengajuanDosen()
    {
        $pengajuans = Pengajuan::with(['laboratorium', 'software'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Diarahkan ke file khusus status pengajuan yang baru dibuat
        return view('pengajuan.status', compact('pengajuans'));
    }

    // =========================================================================
    // 3. MENU DOSEN: Menampilkan Detail Status & Progress Pengajuan (Tombol Lihat)
    // =========================================================================
    public function detailPengajuan($id)
    {
        $pengajuan = Pengajuan::with(['laboratorium', 'software'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('pengajuan.status_detail', compact('pengajuan'));
    }

    // =========================================================================
    // 4. MENU DOSEN: Menampilkan Form Buat Pengajuan Baru Langsung
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
            'software_id'     => 'required_without:software_lain|nullable|exists:software,id',
            'versi_requested' => 'nullable|string',
            'software_lain'   => 'required_without:software_id|nullable|string|max:255',
            'versi_lain'      => 'nullable|string|max:255',
        ]);

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
            'status_persetujuan' => 'pending', 
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan instalasi berhasil dikirim!');
    }

    // =========================================================================
    // 6. MENU SUPERVISOR: Menampilkan Pengajuan yang Masih PENDING untuk Disetujui
    // =========================================================================
    public function indexSupervisor()
    {
        $query = Pengajuan::with(['dosen', 'laboratorium.admin', 'software']);

        $summaryTotals = (clone $query)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status_persetujuan = 'pending' THEN 1 ELSE 0 END) as menunggu,
            SUM(CASE WHEN status_persetujuan = 'disetujui' AND status_progress = 'progress' THEN 1 ELSE 0 END) as progress,
            SUM(CASE WHEN status_persetujuan = 'disetujui' AND status_progress = 'terinstal' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status_persetujuan = 'disetujui' AND status_progress = 'gagal_terinstal' THEN 1 ELSE 0 END) as terkendala
        ")->first();

        $summary = [
            'total'      => $summaryTotals->total ?? 0,
            'menunggu'   => $summaryTotals->menunggu ?? 0,
            'progress'   => $summaryTotals->progress ?? 0,
            'selesai'    => $summaryTotals->selesai ?? 0,
            'terkendala' => $summaryTotals->terkendala ?? 0,
        ];

        $tugas = $query->where('status_persetujuan', 'pending')->latest()->get();
        
        return view('supervisor.index', compact('tugas', 'summary'));
    }

    // =========================================================================
    // 8. PROSES APPROVAL: Aksi Setuju oleh Supervisor -> Teruskan ke Admin/Teknisi
    // =========================================================================
    public function setujui($id)
    {
        $pengajuan = Pengajuan::with('laboratorium')->findOrFail($id);
        $lab = $pengajuan->laboratorium;

        if (!$lab || !$lab->user_id) {
            return back()->with('error', 'Gagal menyetujui! Laboratorium belum memiliki Admin penanggung jawab.');
        }

        $pengajuan->update([
            'status_persetujuan' => 'disetujui',
            'tugaskan_admin'     => $lab->user_id, 
            'tgl_penugasan'      => now()->toDateString(),
            'status_progress'    => 'progress', 
        ]);

        return back()->with('success', 'Pengajuan berhasil disetujui!');
    }

    // =========================================================================
    // 9. PROSES REJECT: Aksi Tolak oleh Supervisor
    // =========================================================================
    public function tolak(Request $request, $id)
    {
        $request->validate(['catatan_spv' => 'required|string|max:500']);
        
        $pengajuan = Pengajuan::findOrFail($id);
        
        $pengajuan->update([
            'status_persetujuan' => 'ditolak',
            'catatan_spv'        => $request->catatan_spv,
            'status_progress'    => null, 
        ]);

        return back()->with('success', 'Pengajuan telah ditolak.');
    }

    // =========================================================================
    // 10. PROSES UPDATE PROGRESS: Aksi Update Status Instalasi oleh Admin/Teknisi
    // =========================================================================
    public function updateProgressTugas(Request $request, $id)
    {
        $request->validate([
            'status_progress' => 'required|in:progress,terinstal,gagal_terinstal',
            'dokumentasi'     => 'required|url|max:255',
            'catatan_admin'   => 'nullable|string|max:500', 
        ]);

        $id_pengajuan = is_object($id) ? $id->id : $id;
        $pengajuan = Pengajuan::findOrFail($id_pengajuan);

        $pengajuan->update([
            'status_progress' => $request->status_progress,
            'dokumentasi'     => $request->dokumentasi, 
            'catatan_admin'   => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status pengerjaan berhasil diperbarui!');
    }

    // =========================================================================
    // 11. LICENSE TRACKER / DASHBOARD UTAMA (Hanya Untuk Supervisor & Admin)
    // =========================================================================
    public function licenseTracker()
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        if ($role !== 'supervisor' && $role !== 'admin') {
            return redirect()->route('riwayat.index');
        }

        $query = Pengajuan::with(['software', 'laboratorium', 'dosen']);

        $summaryTotals = (clone $query)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status_persetujuan = 'pending' THEN 1 ELSE 0 END) as menunggu,
            SUM(CASE WHEN status_progress = 'progress' THEN 1 ELSE 0 END) as progress,
            SUM(CASE WHEN status_progress = 'terinstal' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status_progress = 'gagal_terinstal' THEN 1 ELSE 0 END) as terkendala
        ")->first();

        $summary = [
            'total'      => $summaryTotals->total ?? 0,
            'menunggu'   => $summaryTotals->menunggu ?? 0,
            'progress'   => $summaryTotals->progress ?? 0,
            'selesai'    => $summaryTotals->selesai ?? 0,
            'terkendala' => $summaryTotals->terkendala ?? 0,
        ];

        $pengajuans = $query->latest()->paginate(10);
        
        return view('riwayat.index', compact('pengajuans', 'role')); 
    }
}