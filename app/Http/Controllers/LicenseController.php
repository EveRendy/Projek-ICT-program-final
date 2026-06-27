<?php

namespace App\Http\Controllers;

use App\Models\LicenseTracking;
use App\Models\Laboratorium;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LicenseController extends Controller
{
    // Halaman utama: Pilih laboratorium
    public function index()
    {
        $user = Auth::user();
        
        // Jika user adalah admin dan hanya memiliki satu lab, redirect langsung ke lab tersebut
        if ($user->role !== 'supervisor') {
            $laboratoriums = Laboratorium::where('user_id', $user->no_induk)->with('admin')->get();
            if ($laboratoriums->count() === 1) {
                return redirect()->route('license.show-lab', $laboratoriums->first()->id);
            }
            return view('license.index', compact('laboratoriums'));
        }
        
        // Supervisor melihat semua lab
        $laboratoriums = Laboratorium::with('admin')->get();
        return view('license.index', compact('laboratoriums'));
    }

    // Setelah memilih laboratorium: Tampilkan daftar Software
    public function showLab(Request $request, $labId)
    {
        $laboratorium = Laboratorium::with('admin')->findOrFail($labId);
        $user = Auth::user();
        
        // Cek otorisasi
        if ($user->role !== 'supervisor' && $laboratorium->user_id !== $user->no_induk) {
            abort(403, 'Anda tidak memiliki akses ke laboratorium ini.');
        }
        
        // Cek apakah lab memiliki admin
        if (!$laboratorium->admin) {
            abort(403, 'Laboratorium ini belum memiliki admin yang ditugaskan.');
        }

        // Generate PC numbers sesuai jumlah PC di laboratorium
        $validPcNumbers = ['PC Dosen'];
        for ($i = 1; $i <= max(0, $laboratorium->jumlah_pc - 1); $i++) {
            $validPcNumbers[] = 'PC' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        // Hapus semua lisensi yang tidak sesuai dengan jumlah PC saat ini di lab
        LicenseTracking::where('laboratorium_id', $labId)
            ->whereNotIn('pc_number', $validPcNumbers)
            ->delete();

        // Daftar tipe lisensi yang tersedia
        $licenseTypes = [
            'paid' => 'Berbayar',
            'free' => 'Gratis'
        ];

        // Query software dengan filter - hanya software yang memiliki lisensi di lab ini
        $softwaresQuery = Software::whereHas('licenseTrackings', function($query) use ($labId) {
            $query->where('laboratorium_id', $labId);
        });

        if ($request->has('search') && $request->search) {
            $softwaresQuery->where('nama_software', 'like', '%' . $request->search . '%');
        }

        $softwares = $softwaresQuery->get();

        // Untuk setiap software, hitung jumlah lisensi per tipe di lab ini
        $softwareLicenseCounts = [];
        foreach ($softwares as $software) {
            $counts = LicenseTracking::where('laboratorium_id', $labId)
                ->where('software_id', $software->id)
                ->selectRaw('license_type, COUNT(*) as count')
                ->groupBy('license_type')
                ->pluck('count', 'license_type')
                ->toArray();
            
            $softwareLicenseCounts[$software->id] = [
                'paid' => $counts['paid'] ?? 0,
                'free' => ($counts['free'] ?? 0) + ($counts['opensource'] ?? 0)
            ];
        }

        // Filter software berdasarkan tipe lisensi (jika ada)
        if ($request->has('license_type') && $request->license_type) {
            $filteredSoftwareIds = [];
            foreach ($softwareLicenseCounts as $softwareId => $counts) {
                if (isset($counts[$request->license_type]) && $counts[$request->license_type] > 0) {
                    $filteredSoftwareIds[] = $softwareId;
                }
            }
            $softwares = $softwares->whereIn('id', $filteredSoftwareIds);
        }

        $allSoftware = \App\Models\Software::orderBy('nama_software')->get();

        return view('license.show-lab', compact('laboratorium', 'softwares', 'licenseTypes', 'softwareLicenseCounts', 'allSoftware'));
    }

    // Setelah memilih Software: Tampilkan daftar PC dan lisensi
    public function showSoftware($labId, $softwareId)
    {
        $laboratorium = Laboratorium::with('admin')->findOrFail($labId);
        $software = Software::findOrFail($softwareId);
        $user = Auth::user();
        
        if ($user->role !== 'supervisor' && $laboratorium->user_id !== $user->no_induk) {
            abort(403, 'Anda tidak memiliki akses ke laboratorium ini.');
        }
        
        // Cek apakah lab memiliki admin
        if (!$laboratorium->admin) {
            abort(403, 'Laboratorium ini belum memiliki admin yang ditugaskan.');
        }

        // Generate PC numbers sesuai jumlah PC di laboratorium
        $pcNumbers = ['PC Dosen'];
        for ($i = 1; $i <= max(0, $laboratorium->jumlah_pc - 1); $i++) {
            $pcNumbers[] = 'PC' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        // Hapus lisensi yang tidak sesuai dengan jumlah PC saat ini di lab
        LicenseTracking::where('laboratorium_id', $labId)
            ->where('software_id', $softwareId)
            ->whereNotIn('pc_number', $pcNumbers)
            ->delete();

        // Ambil semua lisensi untuk software ini di lab ini
        $licenses = LicenseTracking::where('laboratorium_id', $labId)
            ->where('software_id', $softwareId)
            ->with('software')
            ->get();

        // Kelompokkan lisensi per PC
        $licensesByPc = $licenses->keyBy('pc_number');

        return view('license.show-software', compact('laboratorium', 'software', 'pcNumbers', 'licensesByPc'));
    }

    public function store(Request $request)
    {
        $lab = Laboratorium::findOrFail($request->laboratorium_id);
        
        // Generate list PC yang valid
        $validPcNumbers = ['PC Dosen'];
        for ($i = 1; $i <= max(0, $lab->jumlah_pc - 1); $i++) {
            $validPcNumbers[] = 'PC' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        $validPcNumbers[] = 'all'; // untuk opsi semua PC

        $request->validate([
            'laboratorium_id' => 'required|exists:laboratoriums,id',
            'software_id' => 'required|exists:software,id',
            'pc_number' => [
                'required',
                'string',
                'in:' . implode(',', $validPcNumbers)
            ],
            'license_account' => 'nullable|string',
            'license_password' => 'nullable|string',
            'unique_code' => 'nullable|string',
            'license_type' => 'required|in:paid,free',
            'active_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:active_date',
        ]);

        $data = $request->only([
            'license_account',
            'license_password',
            'unique_code',
            'license_type',
            'active_date',
            'expiry_date'
        ]);

        // Jika lisensi gratis, kosongkan tanggal
        if ($request->license_type === 'free') {
            $data['active_date'] = null;
            $data['expiry_date'] = null;
        }

        if ($request->pc_number === 'all') {
            $pcList = ['PC Dosen'];
            for ($i = 1; $i <= max(0, $lab->jumlah_pc - 1); $i++) {
                $pcList[] = 'PC' . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
            
            foreach ($pcList as $pc) {
                LicenseTracking::updateOrCreate(
                    [
                        'laboratorium_id' => $request->laboratorium_id,
                        'software_id' => $request->software_id,
                        'pc_number' => $pc,
                    ],
                    $data
                );
            }
            return redirect()->back()->with('success', 'Lisensi berhasil ditambahkan ke semua PC!');
        }

        LicenseTracking::updateOrCreate(
            [
                'laboratorium_id' => $request->laboratorium_id,
                'software_id' => $request->software_id,
                'pc_number' => $request->pc_number,
            ],
            $data
        );

        return redirect()->back()->with('success', 'Lisensi berhasil ditambahkan!');
    }

    // Update lisensi
    public function update(Request $request, $id)
    {
        $license = LicenseTracking::findOrFail($id);

        $request->validate([
            'license_account' => 'nullable|string',
            'license_password' => 'nullable|string',
            'unique_code' => 'nullable|string',
            'license_type' => 'required|in:paid,free',
            'active_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:active_date',
        ]);

        $data = $request->all();

        // Jika lisensi gratis, kosongkan tanggal
        if ($request->license_type === 'free') {
            $data['active_date'] = null;
            $data['expiry_date'] = null;
        }

        $license->update($data);

        return redirect()->back()->with('success', 'Lisensi berhasil diperbarui!');
    }

    // Hapus lisensi
    public function destroy($id)
    {
        $license = LicenseTracking::findOrFail($id);
        $license->delete();

        return redirect()->back()->with('success', 'Lisensi berhasil dihapus!');
    }

    // Hapus semua lisensi untuk software tertentu di lab
    public function destroySoftware($labId, $softwareId)
    {
        $licenses = LicenseTracking::where('laboratorium_id', $labId)
            ->where('software_id', $softwareId)
            ->delete();

        return redirect()->back()->with('success', 'Semua lisensi untuk software ini berhasil dihapus!');
    }
}
