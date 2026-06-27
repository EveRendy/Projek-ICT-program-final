<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\Hardware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoriumController extends Controller
{
    private function hitungLevel($cpuGen, $ramSize, $hasVga, $vgaSeries, $vgaVram): int
    {
        // 1. First check RAM: if less than 8GB → ALWAYS Level 1
        $ramNum = (int)filter_var($ramSize, FILTER_SANITIZE_NUMBER_INT);
        if ($ramNum < 8) {
            return 1;
        }

        // 2. Determine CPU level
        $cpuLevel = 1;

        // Parse Intel generation: "4th Gen", "1st Gen", etc.
        if (preg_match('/(\d+)(st|nd|rd|th) Gen/i', $cpuGen, $matches)) {
            $gen = (int)$matches[1];
            $cpuLevel = $gen <= 5 ? 1 : 2;
        } elseif (strpos($cpuGen, 'Ryzen') !== false) {
            // For AMD Ryzen: check series
            if (preg_match('/Ryzen (1000|2000) Series/i', $cpuGen)) {
                // Ryzen 1000 & 2000 Series → Level 1
                $cpuLevel = 1;
            } else {
                // Ryzen 3000+ → Level 2
                $cpuLevel = 2;
            }
        } else {
            // For other CPU types: default to level 1 (Core 2 Duo, Athlon, Phenom, FX, A-Series)
            $cpuLevel = 1;
        }

        // If no VGA → use CPU level (max 2)
        if (!$hasVga) {
            return min($cpuLevel, 2);
        }

        // 3. Determine VGA level based on VRAM
        $vgaLevel = 1;
        $useCpuOnly = false;
        
        if (!empty($vgaVram)) {
            $vram = (int)$vgaVram;
            if ($vram <= 2) {
                // VRAM ≤ 2GB: follow CPU level only
                $useCpuOnly = true;
            } elseif ($vram <= 4) {
                $vgaLevel = 2;
            } elseif ($vram >= 6) {
                $vgaLevel = 3;
            } else {
                // 5GB VRAM: default to level 2
                $vgaLevel = 2;
            }
        } else {
            // If no VRAM specified: default to level 2
            $vgaLevel = 2;
        }

        // 4. Final level
        if ($useCpuOnly) {
            $finalLevel = $cpuLevel;
        } else {
            $finalLevel = max($cpuLevel, $vgaLevel);
        }
        
        // 5. If CPU level is 1, max overall level is 2
        $maxLevel = $cpuLevel === 1 ? 2 : 3;
        return min($finalLevel, $maxLevel);
    }

    // 1. Tampilkan daftar laboratorium
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'supervisor') {
            $labs = Laboratorium::with('admin')->get();
        } else {
            $labs = Laboratorium::where('user_id', $user->no_induk)->get();
        }

        return view('labs.index', compact('labs'));
    }

    // 2. Tampilkan form tambah lab
    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Get hardware data from database with children (Generations for CPU, Series for VGA)
        $cpuBrands = Hardware::where('category', 'cpu')->where('type', 'brand')->with('children')->get();
        $vgaBrands = Hardware::where('category', 'vga')->where('type', 'brand')->with('children')->get();
        
        $hardware = [
            'cpu_brands' => $cpuBrands,
            'vga_brands' => $vgaBrands
        ];
        
        return view('labs.create', compact('hardware'));
    }

    // 3. Simpan lab baru
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'supervisor'])) {
            abort(403, 'Anda tidak memiliki akses untuk membuat data.');
        }

        $request->validate([
            'no_lab'    => 'required|string|unique:laboratoriums,no_lab|max:50|regex:/^\S+$/',
            'nama_lab'  => 'required|string|max:100',
            'jumlah_pc' => 'required|integer|min:1',
            'cpu_brand' => 'required|string',
            'cpu_gen' => 'required|string',
            'cpu_model' => ['required', 'string', new \App\Rules\ValidCpuModel($request->cpu_brand)],
            'cpu_suffix' => 'nullable|string',
            'ram_size' => 'required|string',
            'vga_brand' => 'nullable|string',
            'vga_series' => 'nullable|string',
            'vga_model' => 'nullable|string',
            'vga_suffix' => 'nullable|string',
            'vga_vram' => 'nullable|numeric',
        ]);

        $hasVga = (bool)$request->has_vga;
        
        $cpuModelFull = $request->cpu_model;
        if ($request->cpu_suffix && $request->cpu_suffix !== 'Polos') {
            $cpuModelFull .= ' ' . $request->cpu_suffix;
        }

        $specs = [
            'CPU ' . $request->cpu_brand,
            $request->cpu_gen,
            $cpuModelFull,
            $request->ram_size,
        ];

        if ($hasVga) {
            $vgaSeriesFull = $request->vga_model; // Use vga_model instead of vga_series as the main identifier
            if ($request->vga_suffix && $request->vga_suffix !== 'Polos') {
                $vgaSeriesFull .= ' ' . $request->vga_suffix;
            }
            if ($request->vga_vram) {
                $vgaSeriesFull .= ' ' . $request->vga_vram . 'GB';
            }
            
            $specs[] = 'VGA Tambahan';
            if ($request->vga_brand) $specs[] = $request->vga_brand;
            $specs[] = $vgaSeriesFull;
        }

        $statusBaru = (Auth::user()->role === 'supervisor') ? 'approved' : 'pending';

        $createData = [
            'no_lab'    => strtoupper(str_replace(' ', '', $request->no_lab)),
            'nama_lab'  => $request->nama_lab,
            'level'       => $this->hitungLevel(
                $request->cpu_gen,
                (int)filter_var($request->ram_size, FILTER_SANITIZE_NUMBER_INT),
                $hasVga,
                $request->vga_series,
                $request->vga_vram
            ),
            'jumlah_pc' => $request->jumlah_pc,
            'spesifikasi' => $specs,
            'status'      => $statusBaru,
        ];

        if (Auth::user()->role === 'supervisor') {
            if ($request->filled('user_id')) {
                $createData['user_id'] = $request->user_id;
            }
        } else {
            $createData['user_id'] = Auth::user()->no_induk;
        }

        Laboratorium::create($createData);

        return redirect()->route('labs.index')->with('success', 'Laboratorium berhasil ditambahkan!');
    }

    // 4. Detail lab
    public function show(Laboratorium $laboratorium)
    {
        //
    }

    // 5. Form edit lab
    public function edit($id)
    {
        $lab = Laboratorium::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'admin' && $lab->user_id !== $user->no_induk) {
            abort(403, 'Akses ditolak. Anda tidak diperbolehkan mengedit laboratorium milik admin lain.');
        }

        if (!in_array($user->role, ['admin', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }

        $cpuBrands = Hardware::where('category', 'cpu')->where('type', 'brand')->with('children')->get();
        $vgaBrands = Hardware::where('category', 'vga')->where('type', 'brand')->with('children')->get();
        
        $hardware = [
            'cpu_brands' => $cpuBrands,
            'vga_brands' => $vgaBrands
        ];
        
        $selectedSpecs = is_array($lab->spesifikasi) ? $lab->spesifikasi : [];

        return view('labs.edit', compact('lab', 'hardware', 'selectedSpecs'));
    }

    // 6. Update lab
    public function update(Request $request, $id)
    {
        $lab = Laboratorium::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'admin' && $lab->user_id !== $user->no_induk) {
            abort(403, 'Akses ditolak. Anda tidak diperbolehkan mengubah laboratorium milik admin lain.');
        }

        if (!in_array($user->role, ['admin', 'supervisor'])) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'no_lab'    => 'required|string|max:50|unique:laboratoriums,no_lab,' . $lab->id,
            'nama_lab'  => 'required|string|max:100',
            'jumlah_pc' => 'required|integer|min:1',
            'cpu_brand' => 'required|string',
            'cpu_gen' => 'required|string',
            'cpu_model' => ['required', 'string', new \App\Rules\ValidCpuModel($request->cpu_brand)],
            'cpu_suffix' => 'nullable|string',
            'ram_size' => 'required|string',
            'vga_brand' => 'nullable|string',
            'vga_series' => 'nullable|string',
            'vga_model' => 'nullable|string',
            'vga_suffix' => 'nullable|string',
            'vga_vram' => 'nullable|numeric',
        ]);

        $hasVga = (bool)$request->has_vga;

        $cpuModelFull = $request->cpu_model;
        if ($request->cpu_suffix && $request->cpu_suffix !== 'Polos') {
            $cpuModelFull .= ' ' . $request->cpu_suffix;
        }

        $specs = [
            'CPU ' . $request->cpu_brand,
            $request->cpu_gen,
            $cpuModelFull,
            $request->ram_size,
        ];

        if ($hasVga) {
            $vgaSeriesFull = $request->vga_model; // Use vga_model instead of vga_series as the main identifier
            if ($request->vga_suffix && $request->vga_suffix !== 'Polos') {
                $vgaSeriesFull .= ' ' . $request->vga_suffix;
            }
            if ($request->vga_vram) {
                $vgaSeriesFull .= ' ' . $request->vga_vram . 'GB';
            }
            
            $specs[] = 'VGA Tambahan';
            if ($request->vga_brand) $specs[] = $request->vga_brand;
            $specs[] = $vgaSeriesFull;
        }

        $statusBaru = $lab->status;
        if ($user->role === 'admin') {
            $statusBaru = 'pending';
        } else if ($user->role === 'supervisor') {
            $statusBaru = 'approved';
        }

        $updateData = [
            'no_lab'    => strtoupper(str_replace(' ', '', $request->no_lab)),
            'nama_lab'  => $request->nama_lab,
            'level'       => $this->hitungLevel(
                $request->cpu_gen,
                (int)filter_var($request->ram_size, FILTER_SANITIZE_NUMBER_INT),
                $hasVga,
                $request->vga_series,
                $request->vga_vram
            ),
            'jumlah_pc' => $request->jumlah_pc,
            'spesifikasi' => $specs,
            'status'      => $statusBaru,
        ];

        if (Auth::user()->role === 'supervisor' && $request->filled('user_id')) {
            $updateData['user_id'] = $request->user_id;
        }

        $lab->update($updateData);

        return redirect()->route('labs.index')->with('success', 'Laboratorium berhasil diupdate!');
    }

    // 7. Hapus lab
    public function destroy($id)
    {
        $lab = Laboratorium::findOrFail($id);
        $lab->delete();
        return redirect()->route('labs.index')->with('success', 'Laboratorium berhasil dihapus!');
    }

    // 8. Update status (Supervisor)
    public function updateStatus($id, Request $request)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Anda tidak memiliki izin!');
        }

        $lab = Laboratorium::findOrFail($id);
        $lab->update(['status' => $request->status]);
        return redirect()->route('labs.index')->with('success', 'Status laboratorium berhasil diubah!');
    }

    // 9. Toggle aktif
    public function toggleActive($id)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Anda tidak memiliki izin!');
        }

        $lab = Laboratorium::findOrFail($id);
        $lab->update(['is_active' => !$lab->is_active]);
        return redirect()->route('labs.index')->with('success', 'Status aktif laboratorium berhasil diubah!');
    }
}
