<?php

namespace App\Http\Controllers;

use App\Models\Hardware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HardwareController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses ditolak.');
        }

        // Get brands with their immediate children (Generations/Series)
        $cpuBrands = Hardware::where('category', 'cpu')->where('type', 'brand')->with('children')->get();
        $vgaBrands = Hardware::where('category', 'vga')->where('type', 'brand')->with('children')->get();

        return view('hardware.index', compact('cpuBrands', 'vgaBrands'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'category' => 'required|in:cpu,vga',
            'type' => 'required|in:brand,generation,series',
            'name' => 'required|string|max:100',
            'parent_id' => 'nullable|exists:hardware,id',
            'base_score' => 'nullable|integer|min:1|max:100',
        ]);

        Hardware::create([
            'category' => $request->category,
            'type' => $request->type,
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'base_score' => $request->base_score,
        ]);

        return redirect()->route('hardware.index')->with('success', 'Data hardware berhasil ditambahkan!');
    }

    public function update(Request $request, Hardware $hardware)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'base_score' => 'nullable|integer|min:1|max:100',
        ]);

        $hardware->update([
            'name' => $request->name,
            'base_score' => $request->base_score,
        ]);

        return redirect()->route('hardware.index')->with('success', 'Data hardware berhasil diupdate!');
    }

    public function destroy(Hardware $hardware)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses ditolak.');
        }

        $hardware->delete();

        return redirect()->route('hardware.index')->with('success', 'Data hardware beserta turunannya berhasil dihapus!');
    }

    public function autoGenerate(Request $request)
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'category' => 'required|in:cpu,vga',
            'brand_id' => 'required|exists:hardware,id',
            'base_score' => 'nullable|integer|min:1|max:100',
        ]);

        $brand = Hardware::findOrFail($request->brand_id);

        if ($request->category === 'cpu') {
            $request->validate([
                'cpu_gen' => 'required|string|max:100',
            ]);
            
            // Check if Generation exists
            $gen = Hardware::firstOrCreate([
                'parent_id' => $brand->id,
                'category' => 'cpu',
                'type' => 'generation',
                'name' => $request->cpu_gen
            ], [
                'base_score' => $request->base_score
            ]);
            
            // If it existed but we want to update the score
            if ($request->filled('base_score') && !$gen->wasRecentlyCreated) {
                $gen->update(['base_score' => $request->base_score]);
            }

            return redirect()->route('hardware.index')->with('success', "Generasi CPU {$request->cpu_gen} berhasil ditambahkan/diupdate!");
            
        } elseif ($request->category === 'vga') {
            $request->validate([
                'vga_series' => 'required|string|max:100',
            ]);
            
            $series = Hardware::firstOrCreate([
                'parent_id' => $brand->id,
                'category' => 'vga',
                'type' => 'series',
                'name' => $request->vga_series
            ], [
                'base_score' => $request->base_score
            ]);

            if ($request->filled('base_score') && !$series->wasRecentlyCreated) {
                $series->update(['base_score' => $request->base_score]);
            }

            return redirect()->route('hardware.index')->with('success', "Seri VGA {$request->vga_series} berhasil ditambahkan/diupdate!");
        }

        return redirect()->route('hardware.index')->with('error', 'Kategori tidak valid.');
    }
}
