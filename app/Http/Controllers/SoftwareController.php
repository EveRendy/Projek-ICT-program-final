<?php

namespace App\Http\Controllers;

use App\Models\Software;
use App\Models\Laboratorium; // Tambahan Model Laboratorium
use App\Models\Instalasi;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    // 1. DAFTAR SOFTWARE (Read) dengan Search, Filter, dan Paginasi
    public function index(Request $request)
    {
        // Ambil data laboratorium untuk dropdown filter
        $laboratoriums = Laboratorium::all();

        $query = Software::query();

        // Logika Pencarian berdasarkan Nama atau ID Software
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_software', 'like', "%{$search}%")
                  ->orWhere('id_software', 'like', "%{$search}%");
            });
        }

        // Logika Filter Kategori (Keterangan: 1, 2, 3)
        if ($request->filled('kategori')) {
            $query->where('keterangan', $request->kategori);
        }

        // Logika Filter Laboratorium
        // CATATAN: Buka comment di bawah ini JIKA tabel software memiliki kolom 'laboratorium_id'
        // atau gunakan whereHas() jika menggunakan relasi many-to-many.
        /*
        if ($request->filled('laboratorium')) {
            $query->where('laboratorium_id', $request->laboratorium);
        }
        */

        // Paginasi 10 data per halaman & withQueryString agar filter tidak hilang saat ganti halaman
        $softwares = $query->latest()->paginate(10)->withQueryString();
        $listLab = Instalasi::select('no_lab')
                        ->distinct()
                        ->whereNotNull('no_lab')
                        ->orderBy('no_lab', 'asc')
                        ->pluck('no_lab'); // Mengubah hasil menjadi array/koleksi sederhana

    // 3. Kirim variabel $listLab ke dalam view bersama data software Anda
    return view('softwares.index', compact('softwares', 'listLab'));

        return view('softwares.index', compact('softwares', 'laboratoriums'));
    }

    // 2. FORM TAMBAH (Create)
    public function create()
    {
        return view('softwares.create');
    }

    // 3. PROSES SIMPAN (Create - Proses)
    public function store(Request $request)
    {
        $request->validate([
            'id_software' => 'required|string|unique:software,id_software|max:50',
            'nama_software' => 'required|string|max:255',
            'versi_raw' => 'required|string', // Menerima teks input yang dipisah koma
            'keterangan' => 'required|integer|in:1,2,3',
        ]);

        // Memecah teks koma menjadi array, lalu membersihkan spasi yang tidak rapi
        $versiArray = array_map('trim', explode(',', $request->versi_raw));

        Software::create([
            'id_software' => strtoupper($request->id_software),
            'nama_software' => $request->nama_software,
            'versi' => $versiArray, // Otomatis dicast jadi JSON oleh Model
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('softwares.index')->with('success', 'Software berhasil ditambahkan!');
    }

    public function show(Software $software)
    {
        //
    }

    // 4. FORM EDIT (Update)
    public function edit($id)
    {
        $software = Software::findOrFail($id);
        
        // Mengubah kembali data array JSON menjadi teks string dipisah koma untuk form input
        $versiRaw = implode(', ', $software->versi);

        return view('softwares.edit', compact('software', 'versiRaw'));
    }

    // 5. PROSES UPDATE (Update - Proses)
    public function update(Request $request, $id)
    {
        $software = Software::findOrFail($id);

        $request->validate([
            'id_software' => 'required|string|max:50|unique:software,id_software,' . $software->id,
            'nama_software' => 'required|string|max:255',
            'versi_raw' => 'required|string',
            'keterangan' => 'required|integer|in:1,2,3',
        ]);

        $versiArray = array_map('trim', explode(',', $request->versi_raw));

        $software->update([
            'id_software' => strtoupper($request->id_software),
            'nama_software' => $request->nama_software,
            'versi' => $versiArray,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('softwares.index')->with('success', 'Data software berhasil diperbarui!');
    }

    // 6. PROSES HAPUS (Delete)
    public function destroy($id)
    {
        $software = Software::findOrFail($id);
        $software->delete();

        return redirect()->route('softwares.index')->with('success', 'Software berhasil dihapus!');
    }
}