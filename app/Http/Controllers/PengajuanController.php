<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Laboratorium;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    // 1. Menampilkan Riwayat Pengajuan Dosen
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Menggunakan relasi balik yang baru saja kita buat
        $pengajuans = Auth::user()->pengajuans()->with(['laboratorium', 'software'])->latest()->get();
        return view('pengajuan.index', compact('pengajuans'));
    }

    // 2. Menampilkan Form Buat Pengajuan Baru
    public function create()
    {
        $laboratoriums = Laboratorium::all();
        $softwares = Software::all();
        return view('pengajuan.create', compact('laboratoriums', 'softwares'));
    }

    // 3. Memproses Penyimpanan Data Pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah'     => 'required|string|max:255',
            'kelompok_matkul' => 'required|string|max:50',
            'laboratorium_id' => 'required|exists:laboratoriums,id',
            // Software & versi master boleh kosong jika dosen mengisi software/versi lain
            'software_id'     => 'nullable|exists:software,id',
            'versi_requested' => 'nullable|string',
            'software_lain'   => 'nullable|string|max:255',
            'versi_lain'      => 'nullable|string|max:255',
        ]);

        // Cek validasi alternatif: Dosen wajib isi software master ATAU software lain
        if (!$request->software_id && !$request->software_lain) {
            return back()->withErrors(['software_error' => 'Kamu harus memilih software yang terdaftar atau mengisi nama software lain!'])->withInput();
        }

        // Simpan ke database pengajuan
        Pengajuan::create([
            'tgl_pengajuan'     => now()->toDateString(),
            'mata_kuliah'       => $request->mata_kuliah,
            'kelompok_matkul'   => $request->kelompok_matkul,
            'user_id'           => Auth::id(), // ID Dosen yang sedang login
            'laboratorium_id'   => $request->laboratorium_id,
            'software_id'       => $request->software_id,
            'versi_requested'   => $request->versi_requested,
            'software_lain'     => $request->software_lain,
            'versi_lain'        => $request->versi_lain,
            'status_persetujuan'=> 'pending',
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan instalasi berhasil dikirim!');
    }

    // 4. Menampilkan Semua Pengajuan (Khusus Supervisor)
    public function indexSupervisor()
    {
        // Mengambil semua data pengajuan dari seluruh dosen beserta relasinya
        $pengajuans = Pengajuan::with(['dosen', 'laboratorium.admin', 'software'])->latest()->get();
        return view('supervisor.index', compact('pengajuans'));
    }

    // 5. Proses Menyetujui Pengajuan (Aturan Bisnis 1: Otomatis Tunjuk Admin Lab)
    public function setujui($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Ambil data laboratorium tujuan untuk mencari tahu siapa adminnya
        $lab = $pengajuan->laboratorium;

        if (!$lab->user_id) {
            return back()->with('error', 'Gagal menyetujui! Laboratorium ' . $lab->no_lab . ' belum memiliki Admin penanggung jawab. Silakan atur di menu Manajemen Lab.');
        }

        // Update data pengajuan
        $pengajuan->update([
            'status_persetujuan' => 'disetujui',
            'tugaskan_admin'     => $lab->user_id, // Aturan 1: Otomatis menugaskan admin lab terkait
            'tgl_penugasan'      => now()->toDateString(),
            'status_progress'    => 'progress', // Aturan 2: Status instalasi otomatis jadi 'progress'
        ]);

        return back()->with('success', 'Pengajuan berhasil disetujui! Tugas otomatis diteruskan ke ' . $lab->admin->name);
    }

    // 6. Proses Menolak Pengajuan
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_spv' => 'required|string|max:500',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        
        $pengajuan->update([
            'status_persetujuan' => 'ditolak',
            'catatan_spv'        => $request->catatan_spv,
            'status_progress'    => null, // Menjamin status instalasi tetap kosong karena ditolak
        ]);

        return back()->with('success', 'Pengajuan telah ditolak dengan alasan tertentu.');
    }

    // 7. Menampilkan Daftar Tugas Instalasi Khusus Admin yang Sedang Login
    public function indexAdmin()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Mengambil data pengajuan yang ditugaskan ke ID Admin ini dan statusnya sudah disetujui oleh SPV
        $tugas = Pengajuan::where('tugaskan_admin', $user->id)
            ->where('status_persetujuan', 'disetujui')
            ->with(['dosen', 'laboratorium', 'software'])
            ->latest()
            ->get();

        return view('admin.penyelesaian', compact('tugas'));
    }

    // Fungsi untuk memperbarui progress tugas oleh Admin (SUDAH DIPERBAIKI)
    public function updateProgressTugas(Request $request, $id)
    {
        // Validasi input dari admin disesuaikan dengan Enum Database & Form Blade Anda
        $request->validate([
            'status_progress' => 'required|in:progress,terinstal,gagal_terinstal', // DIPERBAIKI DISINI
            'dokumentasi'     => 'required|url|max:255', // Harus berupa link drive yang valid
            'catatan_admin'   => 'nullable|string|max:500', 
        ], [
            'status_progress.in' => 'Status yang dipilih tidak valid.',
            'dokumentasi.url'    => 'Dokumentasi harus berupa tautan/URL yang valid (contoh: https://...).',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // KEAMANAN: Pastikan yang mengubah adalah benar-benar admin yang ditugaskan
        if ($pengajuan->tugaskan_admin !== Auth::id()) {
            abort(403, 'Akses ditolak! Anda tidak ditugaskan untuk pengajuan ini.');
        }

        // Update data pengajuan berdasarkan input form
        $pengajuan->update([
            'status_progress' => $request->status_progress,
            'dokumentasi'     => $request->dokumentasi, 
            'catatan_admin'   => $request->catatan_admin,
        ]);

        return back()->with('success', 'Progress tugas berhasil diperbarui!');
    }
}