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
        // 1. Get CPU Base Score
        $cpuScore = 0;
        $cpuNode = \App\Models\Hardware::where('category', 'cpu')
            ->where('type', 'generation')
            ->where('name', $cpuGen)
            ->first();
        if ($cpuNode && $cpuNode->base_score) {
            $cpuScore = $cpuNode->base_score;
        }

        // 2. Get VGA Base Score
        $vgaScore = 0;
        if ($hasVga && !empty($vgaSeries)) {
            $vgaNode = \App\Models\Hardware::where('category', 'vga')
                ->where('type', 'series')
                ->where('name', $vgaSeries)
                ->first();
            
            if ($vgaNode && $vgaNode->base_score) {
                $vgaScore = $vgaNode->base_score;
                
                // Add minor multiplier for VRAM (e.g., +1 point per GB)
                $vram = (int)$vgaVram;
                $vgaScore += ($vram * 1);
            }
        }

        // 3. Get RAM Score (e.g. 1 point per GB)
        $ramNum = (int)filter_var($ramSize, FILTER_SANITIZE_NUMBER_INT);
        $ramScore = $ramNum;

        // Total
        $totalScore = $cpuScore + $vgaScore + $ramScore;

        // Thresholds:
        // < 40 = Level 1
        // 40 - 80 = Level 2
        // > 80 = Level 3
        if ($totalScore < 40) {
            return 1;
        } elseif ($totalScore <= 80) {
            return 2;
        } else {
            return 3;
        }
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
            $vgaSeriesFull = $request->vga_series;
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
            $vgaSeriesFull = $request->vga_series;
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
