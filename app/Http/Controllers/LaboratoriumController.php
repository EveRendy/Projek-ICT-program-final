<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoriumController extends Controller
{
    /**
     * Hitung level spesifikasi lab di server-side.
     * Logika ini IDENTIK dengan fungsi hitungLevelOtomatis() di JavaScript
     * sehingga tidak bisa di-bypass dari client.
     *
     * Scoring matrix:
     *   totalScore = scoreCpuTier + scoreGen + scoreRam + scoreVga
     *   0–3  → Level 1 (Rendah)
     *   4–8  → Level 2 (Menengah)
     *   9+   → Level 3 (Tinggi)
     */
    private function hitungLevel(Request $request): int
    {
        $hardware = config('lab_hardware');
        $brand    = $request->input('cpu_brand');
        $cpuTier  = $request->input('cpu_tier');
        $cpuDetail = $request->input('cpu_detail');
        $ramSize  = $request->input('ram_size');

        // Ambil skor tiap komponen dari config (default 0 jika tidak ditemukan)
        $scoreCpuTier = 0;
        if ($brand === 'intel') {
            $scoreCpuTier = $hardware['intel_tiers'][$cpuTier] ?? 0;
        } elseif ($brand === 'amd') {
            $scoreCpuTier = $hardware['amd_tiers'][$cpuTier] ?? 0;
        }

        $scoreGen = 0;
        if ($brand === 'intel') {
            $scoreGen = $hardware['intel_generations'][$cpuDetail] ?? 0;
        } elseif ($brand === 'amd') {
            $scoreGen = $hardware['amd_series'][$cpuDetail] ?? 0;
        }

        $scoreRam = $hardware['ram_options'][$ramSize] ?? 0;

        // Skor VGA — hanya jika ada VGA tambahan dan model VGA dipilih
        $scoreVga = 0;
        $specsInput = $request->input('spesifikasi_hardware', []);
        if (in_array('VGA Tambahan', $specsInput)) {
            foreach ($specsInput as $item) {
                if (isset($hardware['vga_options'][$item])) {
                    $scoreVga = $hardware['vga_options'][$item];
                    break;
                }
            }
        }

        $totalScore = $scoreCpuTier + $scoreGen + $scoreRam + $scoreVga;

        $thresholds = $hardware['level_thresholds'];
        if ($totalScore <= $thresholds['level_1_max']) {
            return 1;
        } elseif ($totalScore <= $thresholds['level_2_max']) {
            return 2;
        } else {
            return 3;
        }
    }

    private function buildSpesifikasiFromRequest(Request $request): array
    {
        $hardware = config('lab_hardware');
        $brand = $request->input('cpu_brand');
        $cpuTier = $request->input('cpu_tier');
        $cpuDetail = $request->input('cpu_detail');
        $ramSize = $request->input('ram_size');

        $brandLabel = $brand === 'intel' ? 'CPU Intel' : ($brand === 'amd' ? 'CPU AMD' : null);

        $validTiers = array_merge(
            array_keys($hardware['intel_tiers']),
            array_keys($hardware['amd_tiers'])
        );

        $validCpuDetails = array_merge(
            array_keys($hardware['intel_generations']),
            array_keys($hardware['amd_series'])
        );

        $specs = array_values(array_filter([
            $brandLabel,
            in_array($cpuTier, $validTiers, true) ? $cpuTier : null,
            in_array($cpuDetail, $validCpuDetails, true) ? $cpuDetail : null,
            isset($hardware['ram_options'][$ramSize]) ? $ramSize : null,
        ]));

        if ($request->has('spesifikasi_hardware') && is_array($request->spesifikasi_hardware)) {
            foreach ($request->spesifikasi_hardware as $item) {
                if ($item === 'VGA Tambahan') {
                    $specs[] = 'VGA Tambahan';
                } elseif (isset($hardware['vga_options'][$item])) {
                    $specs[] = $item;
                }
            }
        }

        return $specs;
    }

    private function hardwareValidationRules(): array
    {
        return [
            'cpu_brand'  => 'required|in:intel,amd',
            'cpu_tier'   => 'required|string',
            'cpu_detail' => 'required|string',
            'ram_size'   => 'required|string',
            // 'level' tidak divalidasi dari input client — dihitung ulang server-side via hitungLevel()
        ];
    }

    // 1. TAMPILKAN LAB BERDASARKAN SIAPA YANG LOGIN
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'supervisor') {
            // PERBAIKAN: Ditambahkan with('admin') agar nama admin ikut ditarik dari database
            $labs = Laboratorium::with('admin')->get();
        } else {
            // DIUBAH: Menggunakan $user->no_induk menggantikan $user->id
            $labs = Laboratorium::where('user_id', $user->no_induk)->get();
        }

        return view('labs.index', compact('labs'));
    }

    // 2. TAMPILKAN FORM TAMBAH LAB (Create)
    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        return view('labs.create');
    }

    // 3. SIMPAN DATA LAB BARU
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Anda tidak memiliki akses untuk membuat data.');
        }

        if ($request->has('no_lab')) {
            $request->merge([
                'no_lab' => strtoupper($request->input('no_lab')),
            ]);
        }

        $request->validate(array_merge([
            'no_lab' => 'required|string|unique:laboratoriums,no_lab|max:50',
            'jumlah_pc' => 'required|integer|min:1',
        ], $this->hardwareValidationRules()));

        $cpuDetail = $request->input('cpu_detail');
        $cpuTier = $request->input('cpu_tier');
        $hardware = config('lab_hardware');
        $brand = $request->input('cpu_brand');

        $isValidTier = ($brand === 'intel' && isset($hardware['intel_tiers'][$cpuTier]))
            || ($brand === 'amd' && isset($hardware['amd_tiers'][$cpuTier]));

        $isValidCpu = ($brand === 'intel' && isset($hardware['intel_generations'][$cpuDetail]))
            || ($brand === 'amd' && isset($hardware['amd_series'][$cpuDetail]));

        if (!$isValidTier || !$isValidCpu || !isset($hardware['ram_options'][$request->ram_size])) {
            return back()->withInput()->withErrors(['cpu_tier' => 'Spesifikasi prosesor atau RAM tidak valid.']);
        }

        if (collect($request->spesifikasi_hardware ?? [])->contains('VGA Tambahan')) {
            $vgaSelected = collect($request->spesifikasi_hardware ?? [])
                ->first(fn ($item) => isset($hardware['vga_options'][$item]));
            if (!$vgaSelected) {
                return back()->withInput()->withErrors(['spesifikasi_hardware' => 'Pilih model VGA jika VGA tambahan dicentang.']);
            }
        }

        $roleUser = Auth::user()->role;
        $statusOtomatis = ($roleUser === 'supervisor') ? 'approved' : 'pending';
        
        $pesanSukses = ($roleUser === 'supervisor') 
            ? 'Laboratorium baru berhasil ditambahkan dan langsung otomatis Aktif!'
            : 'Laboratorium berhasil ditambahkan dan menunggu persetujuan supervisor!';

        Laboratorium::create([
            'user_id'     => Auth::user()->no_induk,
            'no_lab'      => strtoupper($request->no_lab),
            'level'       => $this->hitungLevel($request), // Kalkulasi server-side, tidak percaya input client
            'jumlah_pc'   => $request->jumlah_pc,
            'spesifikasi' => $this->buildSpesifikasiFromRequest($request),
            'status'      => $statusOtomatis,
        ]);

        return redirect()->route('labs.index')->with('success', $pesanSukses);
    }

    // 4. DETAIL LAB (Opsional)
    public function show(Laboratorium $laboratorium)
    {
        //
    }

    // 5. TAMPILKAN FORM EDIT LAB (Dengan Proteksi Kepemilikan)
    public function edit($id)
    {
        $lab = Laboratorium::findOrFail($id);
        $user = Auth::user();

        // DIUBAH: Menggunakan $user->no_induk untuk proteksi kepemilikan lab
        if ($user->role === 'admin' && $lab->user_id !== $user->no_induk) {
            abort(403, 'Akses ditolak. Anda tidak diperbolehkan mengedit laboratorium milik admin lain.');
        }

        if (!in_array($user->role, ['admin', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }

        return view('labs.edit', compact('lab'));
    }

    // 6. PROSES UPDATE DATA LAB (Dengan Proteksi Kepemilikan)
    public function update(Request $request, $id)
    {
        $lab = Laboratorium::findOrFail($id);
        $user = Auth::user();

        // DIUBAH: Menggunakan $user->no_induk untuk mengecek hak akses edit admin
        if ($user->role === 'admin' && $lab->user_id !== $user->no_induk) {
            abort(403, 'Akses ditolak. Anda tidak diperbolehkan mengubah laboratorium milik admin lain.');
        }

        if (!in_array($user->role, ['admin', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }

        if ($request->has('no_lab')) {
            $request->merge([
                'no_lab' => strtoupper($request->input('no_lab')),
            ]);
        }

        $request->validate(array_merge([
            'no_lab' => 'required|string|max:50|unique:laboratoriums,no_lab,' . $lab->id,
            'jumlah_pc' => 'required|integer|min:1',
            'user_id' => 'nullable|string|exists:users,no_induk',
        ], $this->hardwareValidationRules()));

        $cpuDetail = $request->input('cpu_detail');
        $cpuTier = $request->input('cpu_tier');
        $hardware = config('lab_hardware');
        $brand = $request->input('cpu_brand');

        $isValidTier = ($brand === 'intel' && isset($hardware['intel_tiers'][$cpuTier]))
            || ($brand === 'amd' && isset($hardware['amd_tiers'][$cpuTier]));

        $isValidCpu = ($brand === 'intel' && isset($hardware['intel_generations'][$cpuDetail]))
            || ($brand === 'amd' && isset($hardware['amd_series'][$cpuDetail]));

        if (!$isValidTier || !$isValidCpu || !isset($hardware['ram_options'][$request->ram_size])) {
            return back()->withInput()->withErrors(['cpu_tier' => 'Spesifikasi prosesor atau RAM tidak valid.']);
        }

        if (collect($request->spesifikasi_hardware ?? [])->contains('VGA Tambahan')) {
            $vgaSelected = collect($request->spesifikasi_hardware ?? [])
                ->first(fn ($item) => isset($hardware['vga_options'][$item]));
            if (!$vgaSelected) {
                return back()->withInput()->withErrors(['spesifikasi_hardware' => 'Pilih model VGA jika VGA tambahan dicentang.']);
            }
        }

        $statusBaru = ($user->role === 'supervisor') ? $lab->status : 'pending';
        $pesanSukses = ($user->role === 'supervisor')
            ? 'Data laboratorium berhasil diperbarui oleh Supervisor!'
            : 'Data laboratorium berhasil diperbarui dan status kembali menunggu persetujuan supervisor!';

        // Update data lab
        $updateData = [
            'no_lab'      => strtoupper($request->no_lab),
            'level'       => $this->hitungLevel($request), // Kalkulasi server-side
            'jumlah_pc'   => $request->jumlah_pc,
            'spesifikasi' => $this->buildSpesifikasiFromRequest($request),
            'status'      => $statusBaru,
        ];

        // Jika supervisor mengubah admin penanggung jawab
        $oldUserId = $lab->user_id;
        $newUserId = null;

        if ($user->role === 'supervisor' && $request->filled('user_id')) {
            $newUserId = $request->user_id;
            $updateData['user_id'] = $newUserId;
        }

        $lab->update($updateData);

        // Jika supervisor mengubah admin dan ada pengajuan yang sudah disetujui tapi belum punya admin
        if ($user->role === 'supervisor' && $newUserId && $newUserId !== $oldUserId) {
            // Cari semua pengajuan untuk lab ini yang sudah disetujui tapi belum punya admin
            $pengajuans = \App\Models\Pengajuan::where('status_persetujuan', 'disetujui')
                ->whereNull('tugaskan_admin')
                ->get()
                ->filter(function($pengajuan) use ($lab) {
                    $labIds = is_string($pengajuan->lab_ids) ? json_decode($pengajuan->lab_ids, true) : $pengajuan->lab_ids;
                    return in_array($lab->id, $labIds ?? []);
                });

            $updatedCount = 0;
            foreach ($pengajuans as $pengajuan) {
                $pengajuan->update([
                    'tugaskan_admin' => $newUserId,
                    'tgl_penugasan' => now()->toDateString(),
                    'status_progress' => 'progress',
                ]);
                $updatedCount++;
            }

            if ($updatedCount > 0) {
                $pesanSukses .= ' ' . $updatedCount . ' pengajuan yang sudah disetujui otomatis ditugaskan ke admin baru!';
            }
        }

        return redirect()->route('labs.index')->with('success', $pesanSukses);
    }

    // 7. HAPUS DATA LAB (Dengan Proteksi Kepemilikan)
    public function destroy($id)
    {
        $lab = Laboratorium::findOrFail($id);
        $user = Auth::user();

        // DIUBAH: Menggunakan $user->no_induk untuk proteksi sebelum menghapus data
        if ($user->role === 'admin' && $lab->user_id !== $user->no_induk) {
            abort(403, 'Akses ditolak. Anda tidak diperbolehkan menghapus laboratorium milik admin lain.');
        }

        // Jalankan aturan pembatasan hapus jika status approved bagi Admin
        if ($user->role === 'admin' && $lab->status === 'approved') {
            return redirect()->route('labs.index')->with('error', 'Gagal! Admin tidak diperbolehkan menghapus data laboratorium yang telah disetujui.');
        }

        $lab->delete();

        return redirect()->route('labs.index')->with('success', 'Laboratorium berhasil dihapus dari sistem!');
    }

    // 8. UPDATE STATUS LAB (Approve / Reject) - HANYA SUPERVISOR
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses ditolak. Hanya Supervisor yang dapat melakukan persetujuan.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $lab = Laboratorium::findOrFail($id);
        $lab->update([
            'status' => $request->status
        ]);

        $pesan = $request->status === 'approved' ? 'Laboratorium telah disetujui!' : 'Laboratorium telah ditolak!';

        return redirect()->route('labs.index')->with('success', $pesan);
    }
}