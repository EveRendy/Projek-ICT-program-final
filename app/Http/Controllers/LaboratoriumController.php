<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    //tampilkan data
    public function index()
    {
        $labs = Laboratorium::all();
        return view('labs.index', compact('labs'));
    }

    //fungsi tambah
    public function create()
    {
        return view('labs.create');
    }

    //simpan data
    public function store(Request $request)
    {
        $request->validate([
            'no_lab' => 'required|string|unique:laboratoriums,no_lab|max:50',
            'level' => 'required|integer|in:1,2,3', // Mengunci pilihan level 1, 2, atau 3
            'jumlah_pc' => 'required|integer|min:1',
        ]);

        Laboratorium::create([
            'no_lab' => strtoupper($request->no_lab), // Otomatis mengubah input jadi huruf kapital (ex: lab01 -> LAB01)
            'level' => $request->level,
            'jumlah_pc' => $request->jumlah_pc,
        ]);

        return redirect()->route('labs.index')->with('success', 'Laboratorium berhasil ditambahkan!');
    }

    public function show(Laboratorium $laboratorium)
    {
        //
    }

    //fungsi edit
    public function edit($id)
    {
        // Mengambil data lab berdasarkan ID
        $lab = Laboratorium::findOrFail($id);
        return view('labs.edit', compact('lab'));
    }

    //proses edit
    public function update(Request $request, $id)
    {
        $lab = Laboratorium::findOrFail($id);

        $request->validate([
            'no_lab' => 'required|string|max:50|unique:laboratoriums,no_lab,' . $lab->id,
            'level' => 'required|integer|in:1,2,3',
            'jumlah_pc' => 'required|integer|min:1',
        ]);

        $lab->update([
            'no_lab' => strtoupper($request->no_lab),
            'level' => $request->level,
            'jumlah_pc' => $request->jumlah_pc,
        ]);

        return redirect()->route('labs.index')->with('success', 'Data laboratorium berhasil diperbarui!');
    }

    //fungsi hapus
    public function destroy($id)
    {
        $lab = Laboratorium::findOrFail($id);
        $lab->delete();

        return redirect()->route('labs.index')->with('success', 'Laboratorium berhasil dihapus!');
    }
}