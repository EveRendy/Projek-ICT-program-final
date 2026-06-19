<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Laboratorium;
use App\Models\Software;
use App\Models\Instalasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    // =========================================================================
    // 1. MENU DOSEN: Menampilkan Riwayat Pengajuan (Tabel Horizontal Dosen)
    // =========================================================================
    public function riwayatPengajuan()
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';

        // 'laboratorium' dihapus dari with() karena sekarang menggunakan format array lab_ids
        $pengajuans = Pengajuan::with(['software'])
            ->where('user_id', $user->no_induk)
            ->latest()
            ->get();

        return view('pengajuan.index', compact('pengajuans', 'role'));
    }

    // =========================================================================
    // 2. MENU DOSEN / SUPERVISOR: Menampilkan List Status Approval SPV (Mockup Vertikal)
    // =========================================================================
    public function indexSupervisor()
    {
        // 'laboratorium.admin' dihapus dari with()
        $query = Pengajuan::with(['dosen', 'software']);

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

        // Pengajuan yang sudah disetujui tapi belum ada admin yang ditugaskan
        $tugasTanpaAdmin = Pengajuan::with(['dosen', 'software'])
            ->where('status_persetujuan', 'disetujui')
            ->whereNull('tugaskan_admin')
            ->latest()
            ->get();

        // Pengajuan yang fotonya dikirim admin dan menunggu verifikasi supervisor
        $tugasVerifikasiFoto = Pengajuan::with(['dosen', 'software'])
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->get();

        // Daftar laboratorium untuk modal edit
        $list_laboratorium = Laboratorium::orderBy('no_lab')->get();

        return view('supervisor.index', compact('tugas', 'tugasTanpaAdmin', 'summary', 'tugasVerifikasiFoto', 'list_laboratorium'));
    }

    // =========================================================================
    // 3. MENU UPDATE PENGERJAAN: Menampilkan Tugas Aktif
    // =========================================================================
    public function indexAdmin(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        $labNo = $request->get('lab'); // Menggunakan parameter 'lab' sesuai view
        $laboratoriums = Laboratorium::all();

        $query = Pengajuan::where('status_persetujuan', 'disetujui')
            ->with(['dosen', 'software']);

        if ($role !== 'supervisor') {
            $query->where('tugaskan_admin', $user->no_induk)
                  ->where('status_progress', 'progress');
        } else {
            // Supervisor bisa lihat semua yang masih aktif dikerjakan
            $query->where('status_progress', 'progress');
        }

        if ($labNo) {
            // Cari lab_id berdasarkan no_lab yang dipilih
            $laboratorium = Laboratorium::where('no_lab', $labNo)->first();
            if ($laboratorium) {
                // Karena lab_ids sekarang JSON array, kita gunakan whereJsonContains
                $query->whereJsonContains('lab_ids', (string) $laboratorium->id);
            }
        }

        $tugas = $query->latest()->get();

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
            ->with(['dosen', 'software']);

        // Mengizinkan status 'progress' agar tugas yang baru disetujui muncul di halaman penyelesaian
        if ($role !== 'supervisor') {
            $query->where('tugaskan_admin', $user->no_induk)
                  ->whereIn('status_progress', ['progress', 'terinstal', 'gagal_terinstal']);
        } else {
            $query->whereIn('status_progress', ['progress', 'terinstal', 'gagal_terinstal']);
        }

        if ($labId) {
            $query->whereJsonContains('lab_ids', (string) $labId);
        }

        $tugas = $query->latest()->get();

        $summary = [
            'total'      => $tugas->count(),
            'terkendala' => $tugas->where('status_progress', 'gagal_terinstal')->count(),
            'progress'   => $tugas->where('status_progress', 'progress')->count(),
            'selesai'    => $tugas->where('status_progress', 'terinstal')->count(),
        ];

        return view('admin.penyelesaian', compact('tugas', 'summary', 'role', 'laboratoriums'));
    }

    // =========================================================================
    // 4. Form Buat Pengajuan Baru (Dosen)
    // =========================================================================
    public function statusPengajuanDosen()
    {
        $pengajuans = Pengajuan::with(['software'])
            ->where('user_id', Auth::user()->no_induk)
            ->latest()
            ->get();

        return view('pengajuan.status', compact('pengajuans'));
    }

    // =========================================================================
    // 3. MENU DOSEN: Menampilkan Detail Status & Progress Pengajuan (Tombol Lihat)
    // =========================================================================
    public function detailPengajuan($id)
    {
        $pengajuan = Pengajuan::with(['software'])
            ->where('user_id', Auth::user()->no_induk)
            ->findOrFail($id);

        // --- MENGAMBIL DATA LABORATORIUM BERDASARKAN ARRAY LAB_IDS ---
        $laboratoriums = Laboratorium::whereIn('id', $pengajuan->lab_ids ?? [])->get();

        return view('pengajuan.status_detail', compact('pengajuan', 'laboratoriums'));
    }

    // =========================================================================
    // 4. MENU DOSEN: Menampilkan Form Buat Pengajuan Baru Langsung
    // =========================================================================
    public function create()
    {
        $laboratoriums = Laboratorium::orderBy('no_lab')->get();
        $softwares = Software::all();
        
        return view('pengajuan.create', compact('laboratoriums', 'softwares'));
    }

    // =========================================================================
    // 5. Memproses Penyimpanan Data Pengajuan (Dosen)
    // =========================================================================
    public function store(Request $request)
    {
        // --- DIUBAH: Validasi 'exists' dihapus pada software_id agar input "lainnya" tidak memunculkan error ---
        $request->validate([
            'mata_kuliah'     => 'required|string|max:255',
            'kelompok_matkul' => 'required|string|max:50',
            'laboratorium_id' => 'required|exists:laboratoriums,id',
            'software_id'     => 'required|string',
            'versi_requested' => 'nullable|string',
            'software_lain'   => 'required_if:software_id,lainnya|nullable|string|max:255',
            'versi_lain'      => 'nullable|string|max:255',
        ]);

        // Cek apakah software dengan versi yang sama sudah terinstal di lab yang dipilih
        if ($request->software_id !== 'lainnya') {
            $software = Software::find($request->software_id);
            $lab = Laboratorium::find($request->laboratorium_id);

            if ($software && $lab) {
                $existingInstalasi = Instalasi::where('id_software', $software->id_software)
                    ->where('no_lab', $lab->no_lab)
                    ->where('versi_terinstall', $request->versi_requested)
                    ->first();

                if ($existingInstalasi) {
                    return back()->withInput()->with('error', 'Software ' . $software->nama_software . ' versi ' . $request->versi_requested . ' sudah terinstal di ' . $lab->nama_lab . '. Silakan ajukan versi yang berbeda atau lab yang berbeda.');
                }
            }
        }

        $lab = Laboratorium::find($request->laboratorium_id);
        $finalLevel = $lab->level ?? 'Low';

        // --- DIUBAH: Cek Kompatibilitas hanya jika yang dipilih adalah software master (bukan 'lainnya') ---
        if ($request->software_id !== 'lainnya') {
            $software = Software::find($request->software_id);

            if ($software && $lab) {
                $swLevel = (int) $software->level_requirement; 
                
                $levelMap = ['Low' => 1, 'Medium' => 2, 'High' => 3];
                $labInt = $levelMap[$lab->level] ?? 1;
                
                if ($labInt === 1 && $swLevel >= 3) {
                    return back()->withInput()->with('error', 'Gagal mengirim pengajuan! Spesifikasi ' . $lab->no_lab . ' (Level ' . $lab->level . ') tidak mendukung software ini.');
                }
            }
        }

        $roleUser = Auth::user()->role;
        $statusOtomatis = ($roleUser === 'supervisor') ? 'disetujui' : 'pending';
        $statusProgress = ($roleUser === 'supervisor') ? 'progress' : null;
        $tugaskanAdmin  = null;
        $tglPenugasan   = null;
        
        if ($roleUser === 'supervisor' && $lab) {
            $tugaskanAdmin = $lab->user_id;
            $tglPenugasan  = now()->toDateString();
        }

        // Tentukan versi final yang tersimpan:
        // - Jika software master dan versi dipilih dari daftar → pakai versi_requested
        // - Jika software master tapi versi manual (lainnya) → pakai versi_lain
        // - Jika software lainnya → pakai versi_lain
        $versiFinal = $request->versi_requested;
        if ($request->versi_requested === 'lainnya' || $request->software_id === 'lainnya') {
            $versiFinal = $request->versi_lain;
        }

        Pengajuan::create([
            'tgl_pengajuan'      => now()->toDateString(),
            'mata_kuliah'        => $request->mata_kuliah,
            'kelompok_matkul'    => $request->kelompok_matkul,
            'user_id'            => Auth::user()->no_induk,
            'lab_ids'            => [$request->laboratorium_id],
            'level_akses'        => $finalLevel,
            'software_id'        => ($request->software_id === 'lainnya') ? null : $request->software_id,
            'versi_requested'    => $versiFinal,
            'software_lain'      => $request->software_lain,
            // versi_lain disimpan hanya jika benar-benar custom (bukan dari daftar)
            'versi_lain'         => ($request->versi_requested === 'lainnya' || $request->software_id === 'lainnya')
                                    ? $request->versi_lain
                                    : null,
            'status_persetujuan' => $statusOtomatis,
            'tugaskan_admin'     => $tugaskanAdmin,
            'tgl_penugasan'      => $tglPenugasan,
            'status_progress'    => $statusProgress,
        ]);

        $pesanSukses = ($roleUser === 'supervisor') 
            ? 'Pengajuan instalasi otomatis disetujui dan diteruskan ke Admin!' 
            : 'Pengajuan instalasi berhasil dikirim!';

        if ($roleUser === 'supervisor') {
            return redirect()->route('admin.tugas.index')->with('success', $pesanSukses);
        }

        return redirect()->route('pengajuan.status')->with('success', $pesanSukses);
    }

    // =========================================================================
    // 7b. EDIT PENGAJUAN OLEH SUPERVISOR (Sebelum Disetujui — Koreksi Typo)
    // =========================================================================
    public function editSebelumSetujui(Request $request, $id)
    {
        // Hanya supervisor yang boleh
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses ditolak.');
        }

        $pengajuan = Pengajuan::findOrFail($id);

        // Hanya boleh edit jika masih pending
        if ($pengajuan->status_persetujuan !== 'pending') {
            return back()->with('error', 'Pengajuan tidak dapat diedit karena sudah diproses.');
        }

        $request->validate([
            'mata_kuliah'      => 'required|string|max:255',
            'kelompok_matkul'  => 'required|string|max:50',
            'lab_id'           => 'required|exists:laboratoriums,id',
            'software_lain'    => 'nullable|string|max:255',
            'versi_requested'  => 'nullable|string|max:100',
            'versi_lain'       => 'nullable|string|max:100',
        ]);

        // Update field yang diizinkan untuk diedit supervisor
        $updateData = [
            'mata_kuliah'     => $request->mata_kuliah,
            'kelompok_matkul' => $request->kelompok_matkul,
            'lab_ids'         => [$request->lab_id],
        ];

        // Jika ini adalah pengajuan "software lainnya" (software_id null), izinkan edit nama & versi
        if (is_null($pengajuan->software_id)) {
            $updateData['software_lain']   = $request->software_lain ?? $pengajuan->software_lain;
            $updateData['versi_lain']      = $request->versi_lain ?? $pengajuan->versi_lain;
            $updateData['versi_requested'] = $request->versi_lain ?? $pengajuan->versi_lain;
        } else {
            // Software Master
            if ($request->has('versi_requested')) {
                if ($request->versi_requested === 'lainnya') {
                    $updateData['versi_requested'] = $request->versi_lain;
                    $updateData['versi_lain']      = $request->versi_lain;
                } else {
                    $updateData['versi_requested'] = $request->versi_requested;
                    $updateData['versi_lain']      = null;
                }
            }
        }

        $pengajuan->update($updateData);

        return back()->with('success', 'Data pengajuan berhasil dikoreksi oleh supervisor.');
    }

    // =========================================================================
    // 8. PROSES APPROVAL: Aksi Setuju oleh Supervisor -> Teruskan ke Admin/Teknisi
    // =========================================================================
    public function setujui(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Debugging: Cek kondisi software lainnya
        \Log::info('Processing approval for pengajuan ID: ' . $id);
        \Log::info('Software ID: ' . ($pengajuan->software_id ?? 'null'));
        \Log::info('Software Lain: ' . ($pengajuan->software_lain ?? 'null'));
        
        // --- MODIFIKASI: Otomatis buat software master jika software "Lainnya" (software_id == null) ---
        $softwareBaru = null;
        if (is_null($pengajuan->software_id) && !empty($pengajuan->software_lain)) {
            try {
                // Generate ID software otomatis dengan format SOFT001, SOFT002, dll (capital semua)
                $lastSoftware = Software::orderBy('id', 'desc')->first();
                $lastIdNum = $lastSoftware ? intval(substr($lastSoftware->id_software ?? 'SOFT000', -3)) : 0;
                $newIdSoftware = 'SOFT' . str_pad($lastIdNum + 1, 3, '0', STR_PAD_LEFT);

                \Log::info('Creating new software with ID: ' . $newIdSoftware);

                $versiBaru = $pengajuan->versi_lain ?? $pengajuan->versi_requested ?? 'Default';

                // Buat data baru di tabel Master Software secara otomatis
                $softwareBaru = Software::create([
                    'id_software'   => $newIdSoftware,
                    'nama_software' => $pengajuan->software_lain,
                    'keterangan'   => 1, // Default level 1 (Low Spec)
                    'versi'         => [$versiBaru],
                ]);

                // Kaitkan ID software baru ke pengajuan, dan hapus catatan 'lainnya'
                $pengajuan->software_id     = $softwareBaru->id;
                $pengajuan->software_lain   = null;
                $pengajuan->versi_requested = $versiBaru;
                $pengajuan->versi_lain      = null;

                \Log::info('Software baru berhasil dibuat: ' . $softwareBaru->nama_software . ' (ID: ' . $softwareBaru->id_software . ')');
            } catch (\Exception $e) {
                \Log::error('Gagal membuat software baru: ' . $e->getMessage());
                return back()->with('error', 'Gagal menyetujui! Error: ' . $e->getMessage());
            }

        } elseif (!is_null($pengajuan->software_id) && !empty($pengajuan->versi_lain)) {
            // --- KASUS BARU: Software master dipilih, tapi versinya manual (versi_lain) ---
            // Tambahkan versi baru ke array versi software master
            try {
                $software = Software::find($pengajuan->software_id);
                if ($software) {
                    $versiSekarang = $software->versi ?? [];
                    $versiBaru     = $pengajuan->versi_lain;

                    // Hanya tambahkan jika versi belum ada di daftar
                    if (!in_array($versiBaru, $versiSekarang)) {
                        $versiSekarang[] = $versiBaru;
                        $software->versi = $versiSekarang;
                        $software->save();

                        \Log::info("Versi baru '{$versiBaru}' ditambahkan ke software '{$software->nama_software}'");
                    }

                    // Pindahkan versi_lain ke versi_requested dan bersihkan versi_lain
                    $pengajuan->versi_requested = $versiBaru;
                    $pengajuan->versi_lain      = null;
                }
            } catch (\Exception $e) {
                \Log::error('Gagal menambahkan versi baru ke software master: ' . $e->getMessage());
                return back()->with('error', 'Gagal menyetujui! Error: ' . $e->getMessage());
            }
        } else {
            \Log::info('Tidak ada aksi software: software_id=' . ($pengajuan->software_id ?? 'null') . ', versi_lain=' . ($pengajuan->versi_lain ?? 'null'));
        }

        // Mengambil lab pertama dari pilihan untuk menentukan siapa Admin penanggung jawabnya
        $firstLabId = $pengajuan->lab_ids[0] ?? null;
        $lab = Laboratorium::find($firstLabId);

        if (!$lab) {
            return back()->with('error', 'Gagal menyetujui! Laboratorium tidak ditemukan.');
        }

        // Perbaikan: Tetap lanjutkan approval meskipun lab belum memiliki admin
        // Supervisor bisa menugaskan admin secara manual nanti
        $tugaskanAdmin = null;
        $tglPenugasan = null;

        if ($lab->user_id) {
            $tugaskanAdmin = $lab->user_id;
            $tglPenugasan = now()->toDateString();
        }

        // --- DIUBAH: Pastikan perubahan software_id juga ikut di-save jika ada ---
        $pengajuan->update([
            'status_persetujuan' => 'disetujui',
            'tugaskan_admin'     => $tugaskanAdmin, // Bisa null jika lab belum punya admin
            'tgl_penugasan'      => $tglPenugasan,  // Bisa null jika lab belum punya admin
            'status_progress'    => $tugaskanAdmin ? 'progress' : null, // Progress hanya jika sudah ada admin
            'software_id'        => $pengajuan->software_id,
            'software_lain'      => $pengajuan->software_lain,
            'versi_requested'    => $pengajuan->versi_requested,
            'versi_lain'         => $pengajuan->versi_lain,
        ]);

        // Pesan sukses yang berbeda jika software baru ditambahkan
        if ($softwareBaru) {
            return back()->with('success', 'Pengajuan berhasil disetujui! Software "' . $softwareBaru->nama_software . '" telah ditambahkan ke master software.');
        }

        return back()->with('success', 'Pengajuan berhasil disetujui!');
    }

    // =========================================================================
    // 9. UPDATE PENUGASAN ADMIN: Supervisor menugaskan admin secara manual setelah approval
    // =========================================================================
    public function updateAdminAssignment(Request $request, $id)
    {
        $request->validate([
            'admin_id' => 'required|string|exists:users,no_induk',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Pastikan pengajuan sudah disetujui tapi belum ada admin yang ditugaskan
        if ($pengajuan->status_persetujuan !== 'disetujui') {
            return back()->with('error', 'Pengajuan belum disetujui. Setujui pengajuan terlebih dahulu.');
        }

        if ($pengajuan->tugaskan_admin) {
            return back()->with('error', 'Pengajuan sudah memiliki admin yang ditugaskan.');
        }

        // Update pengajuan dengan admin yang ditugaskan
        $pengajuan->update([
            'tugaskan_admin'  => $request->admin_id,
            'tgl_penugasan'   => now()->toDateString(),
            'status_progress' => 'progress',
        ]);

        return back()->with('success', 'Admin berhasil ditugaskan ke pengajuan ini!');
    }

    // =========================================================================
    // 10. PROSES REJECT: Aksi Tolak oleh Supervisor
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
            'dokumentasi'     => 'nullable|url|max:255',
            'catatan_admin'   => 'nullable|string|max:500', 
        ]);

        $id_pengajuan = is_object($id) ? $id->id : $id;
        $pengajuan = Pengajuan::findOrFail($id_pengajuan);

        // Jika status diubah menjadi 'terinstal', otomatis buat record instalasi
        if ($request->status_progress == 'terinstal' && $pengajuan->software_id && !empty($pengajuan->lab_ids)) {
            // Cek apakah instalasi sudah ada untuk software dan lab yang bersangkutan
            $existingInstalasi = \App\Models\Instalasi::where('id_software', $pengajuan->software->id_software)
                ->whereIn('no_lab', $pengajuan->lab_ids ?? [])
                ->first();

            // Jika belum ada instalasi, buat record baru di tabel instalasi
            if (!$existingInstalasi) {
                foreach ($pengajuan->lab_ids as $labId) {
                    $lab = Laboratorium::find($labId);
                    if ($lab) {
                        \App\Models\Instalasi::create([
                            'id_software'      => $pengajuan->software->id_software,
                            'versi_terinstall' => $pengajuan->versi_requested ?? 'Default',
                            'no_lab'           => $lab->no_lab,
                            'status_lisensi'   => 'license_active',
                            'diinstal_oleh'   => $pengajuan->tugaskan_admin,
                        ]);
                    }
                }
            }
        }

        $pengajuan->update([
            'status_progress' => $request->status_progress,
            'dokumentasi'     => $request->dokumentasi ?? $pengajuan->dokumentasi, 
            'catatan_admin'   => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status pengerjaan berhasil diperbarui!');
    }

    // =========================================================================
    // 11. UPLOAD FOTO BUKTI: Admin upload foto hasil instalasi untuk dikirim ke supervisor
    // =========================================================================
    public function uploadFotoBukti(Request $request, $id)
    {
        $request->validate([
            'foto_bukti'    => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'catatan_admin' => 'nullable|string|max:500',
            'dokumentasi'   => 'nullable|url|max:255',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Hapus semua foto lama milik pengajuan ini untuk mencegah penumpukan file
        $this->hapusFotoLama($pengajuan->id);

        // --- KOMPRESI FOTO MENGGUNAKAN PHP GD ---
        $file        = $request->file('foto_bukti');
        $originalExt = strtolower($file->getClientOriginalExtension());
        $fileName    = 'bukti_' . $pengajuan->id . '_' . time() . '.jpg';
        $savePath    = storage_path('app/public/foto_bukti/' . $fileName);

        // Pastikan direktori ada
        if (!file_exists(storage_path('app/public/foto_bukti'))) {
            mkdir(storage_path('app/public/foto_bukti'), 0755, true);
        }

        // Buat resource GD sesuai tipe file asli
        if ($originalExt === 'png') {
            $source = imagecreatefrompng($file->getRealPath());
        } elseif (in_array($originalExt, ['jpeg', 'jpg'])) {
            $source = imagecreatefromjpeg($file->getRealPath());
        } else {
            // Fallback: coba baca via imagecreatefromstring
            $source = imagecreatefromstring(file_get_contents($file->getRealPath()));
        }

        if (!$source) {
            return back()->with('error', 'Gagal memproses gambar. Pastikan file yang diupload adalah gambar valid.');
        }

        // --- PARAMETER KOMPRESI ---
        // Max 800px lebar — cukup untuk bukti foto, jauh lebih ringan dari 1200px
        // Ukuran file turun ~55% dibanding 1200px@75%
        $maxW = 800;

        $origW = imagesx($source);
        $origH = imagesy($source);

        if ($origW > $maxW) {
            $newW = $maxW;
            $newH = (int) round($origH * ($maxW / $origW));
        } else {
            // Gambar sudah kecil, tidak perlu diperbesar
            $newW = $origW;
            $newH = $origH;
        }

        // Buat kanvas baru
        $canvas = imagecreatetruecolor($newW, $newH);

        // Background putih — penting untuk PNG transparan agar tidak jadi hitam
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        // Salin dan resize dengan resampling berkualitas tinggi
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Simpan JPEG kualitas 60% — optimal untuk foto bukti
        // (60% sudah cukup tajam untuk dilihat, tapi ukurannya ~40-50% lebih kecil dari 75%)
        imagejpeg($canvas, $savePath, 60);

        imagedestroy($source);
        imagedestroy($canvas);

        // Verifikasi file berhasil disimpan
        if (!file_exists($savePath)) {
            return back()->with('error', 'Gagal menyimpan foto. Silakan coba lagi.');
        }

        $dbPath = 'foto_bukti/' . $fileName;

        // Update pengajuan
        $pengajuan->update([
            'foto_bukti'             => $dbPath,
            'status_verifikasi'      => 'menunggu',
            'catatan_penolakan_foto' => null,
            'catatan_admin'          => $request->catatan_admin ?? $pengajuan->catatan_admin,
            'dokumentasi'            => $request->dokumentasi ?? $pengajuan->dokumentasi,
        ]);

        return back()->with('success', 'Foto bukti instalasi berhasil dikirim! Menunggu verifikasi supervisor.');
    }

    /**
     * Hapus semua file foto lama milik pengajuan tertentu dari storage.
     * Mencegah penumpukan file orphan saat admin upload ulang berkali-kali.
     */
    private function hapusFotoLama(int $pengajuanId): void
    {
        $dir = storage_path('app/public/foto_bukti');
        if (!is_dir($dir)) return;

        // Pola nama file: bukti_{id}_*.jpg
        $pattern = $dir . '/bukti_' . $pengajuanId . '_*.jpg';
        $files   = glob($pattern);

        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    // =========================================================================
    // 12. VERIFIKASI FOTO: Supervisor menyetujui foto bukti instalasi
    // =========================================================================
    public function approveFotoBukti(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // Update status pengajuan
        $pengajuan->update([
            'status_verifikasi' => 'disetujui',
            'status_progress'   => 'terinstal',
        ]);

        // Cek apakah instalasi sudah ada untuk software dan lab yang bersangkutan
        $existingInstalasi = \App\Models\Instalasi::where('id_software', $pengajuan->software->id_software)
            ->whereIn('no_lab', $pengajuan->lab_ids ?? [])
            ->first();

        // Jika belum ada instalasi, buat record baru di tabel instalasi
        if (!$existingInstalasi && $pengajuan->software_id && !empty($pengajuan->lab_ids)) {
            foreach ($pengajuan->lab_ids as $labId) {
                $lab = Laboratorium::find($labId);
                if ($lab) {
                    \App\Models\Instalasi::create([
                        'id_software'      => $pengajuan->software->id_software,
                        'versi_terinstall' => $pengajuan->versi_requested ?? 'Default',
                        'no_lab'           => $lab->no_lab,
                        'status_lisensi'   => 'license_active', // Default active untuk software yang baru terinstal
                        'diinstal_oleh'   => $pengajuan->tugaskan_admin, // Catat admin yang menginstal
                    ]);
                }
            }
        }

        return back()->with('success', 'Foto bukti diverifikasi. Instalasi dinyatakan selesai dan tercatat di License Tracker!');
    }

    // =========================================================================
    // 13. TOLAK FOTO: Supervisor menolak foto bukti dan mengirim catatan ke admin
    // =========================================================================
    public function tolakFotoBukti(Request $request, $id)
    {
        $request->validate([
            'catatan_penolakan_foto' => 'required|string|max:500',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        $pengajuan->update([
            'status_verifikasi'      => 'ditolak',
            'catatan_penolakan_foto' => $request->catatan_penolakan_foto,
            // Status progress tetap 'progress' agar admin bisa upload ulang
        ]);

        return back()->with('success', 'Foto bukti ditolak. Admin akan diminta upload ulang.');
    }

    // =========================================================================
    // 14. BULK ASSIGN ADMIN: Supervisor menugaskan admin ke semua pengajuan tertahan
    // =========================================================================
    public function bulkAssignAdmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|string|exists:users,no_induk',
        ]);

        $pengajuans = Pengajuan::where('status_persetujuan', 'disetujui')
            ->whereNull('tugaskan_admin')
            ->get();

        $updatedCount = 0;
        foreach ($pengajuans as $pengajuan) {
            $pengajuan->update([
                'tugaskan_admin' => $request->admin_id,
                'tgl_penugasan' => now()->toDateString(),
                'status_progress' => 'progress',
            ]);
            $updatedCount++;
        }

        return back()->with('success', $updatedCount . ' pengajuan berhasil ditugaskan ke admin ' . $request->admin_id . '!');
    }

    // =========================================================================
    // 16. CEK INSTALASI YANG SUDAH ADA (API)
    // =========================================================================
    public function cekInstalasi(Request $request)
    {
        $labId = $request->lab_id;
        $softwareId = $request->software_id;
        $versi = $request->versi;

        if (!$labId || !$softwareId || !$versi || $softwareId === 'lainnya') {
            return response()->json(['sudah_ada' => false]);
        }

        $software = Software::find($softwareId);
        $lab = Laboratorium::find($labId);

        if (!$software || !$lab) {
            return response()->json(['sudah_ada' => false]);
        }

        $existingInstalasi = Instalasi::where('id_software', $software->id_software)
            ->where('no_lab', $lab->no_lab)
            ->where('versi_terinstall', $versi)
            ->first();

        if ($existingInstalasi) {
            return response()->json([
                'sudah_ada' => true,
                'pesan' => $software->nama_software . ' versi ' . $versi . ' sudah terinstal di ' . $lab->nama_lab . '. Silakan ajukan versi yang berbeda atau lab yang berbeda.'
            ]);
        }

        return response()->json(['sudah_ada' => false]);
    }

    // =========================================================================
    // 17. LICENSE TRACKER / DASHBOARD UTAMA (Hanya Untuk Supervisor & Admin)
    // =========================================================================
    public function licenseTracker()
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';
        
        if ($role !== 'supervisor' && $role !== 'admin') {
            return redirect()->route('riwayat.index');
        }

        $query = Pengajuan::with(['software', 'dosen']);

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