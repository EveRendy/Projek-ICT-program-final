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
        $pengajuans = $user->pengajuans()->with(['laboratorium', 'software'])->latest()->get();
        
        return view('pengajuan.index', compact('pengajuans'));
    }

    // =========================================================================
    // 2. MENU SUPERVISOR: Menampilkan Pengajuan PENDING untuk Approval
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
    // 3. MENU UPDATE PENGERJAAN: Digunakan oleh Supervisor & Admin/Teknisi
    // =========================================================================
    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        // Mengambil filter lab_id dari request dropdown
        $labId = $request->get('lab_id');
        $laboratoriums = Laboratorium::all();

        // Base Query tugas yang sudah di-approve
        $query = Pengajuan::where('status_persetujuan', 'disetujui')
            ->with(['dosen', 'laboratorium', 'software']);

        // Jika bukan supervisor, batasi hanya tugas milik admin yang sedang login
        if ($role !== 'supervisor') {
            $query->where('tugaskan_admin', $user->id);
        }

        // Terapkan filter laboratorium jika dipilih
        if ($labId) {
            $query->where('laboratorium_id', $labId);
        }

        $tugas = $query->latest()->get();

        // Kalkulasi Card Statistik berdasarkan data yang terfilter
        $summary = [
            'total'      => $tugas->count(),
            'terkendala' => $tugas->where('status_progress', 'gagal_terinstal')->count(),
            'progress'   => $tugas->where('status_progress', 'progress')->count(),
            'selesai'    => $tugas->where('status_progress', 'terinstal')->count(),
        ];

        // PERBAIKAN DI SINI: Ubah 'admin.penyelesaian' menjadi 'admin.tugas'
        return view('admin.tugas', compact('tugas', 'summary', 'role', 'laboratoriums'));
    }

    // =========================================================================
    // 4. Form Buat Pengajuan Baru (Dosen)
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
    // 6. Proses Menyetujui Pengajuan (Aksi Supervisor)
    // =========================================================================
    public function setujui(Pengajuan $pengajuan)
    {
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
    // 7. Proses Menolak Pengajuan (Aksi Supervisor)
    // =========================================================================
    public function tolak(Request $request, Pengajuan $pengajuan)
    {
        $request->validate(['catatan_spv' => 'required|string|max:500']);
        
        $pengajuan->update([
            'status_persetujuan' => 'ditolak',
            'catatan_spv'        => $request->catatan_spv,
            'status_progress'    => null, 
        ]);

        return back()->with('success', 'Pengajuan telah ditolak.');
    }

    // =========================================================================
    // 8. Proses Update Progress Pengerjaan (Aksi Admin)
    // =========================================================================
    public function updateProgressTugas(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'status_progress' => 'required|in:progress,terinstal,gagal_terinstal',
            'dokumentasi'     => 'required|url|max:255',
            'catatan_admin'   => 'nullable|string|max:500', 
        ]);

        $pengajuan->update([
            'status_progress' => $request->status_progress,
            'dokumentasi'     => $request->dokumentasi, 
            'catatan_admin'   => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status pengerjaan berhasil diperbarui!');
    }

    // =========================================================================
    // 9. License Tracker Page
    // =========================================================================
    public function licenseTracker()
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        $query = Pengajuan::with(['software', 'laboratorium', 'dosen']);

        if ($role !== 'supervisor' && $role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $pengajuans = $query->latest()->paginate(10);
        
        return view('riwayat.index', compact('pengajuans', 'role')); 
    }
}