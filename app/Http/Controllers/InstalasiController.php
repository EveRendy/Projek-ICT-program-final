<?php

namespace App\Http\Controllers;

use App\Models\Instalasi;
use App\Models\Software;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstalasiController extends Controller
{
    public function index(Request $request) 
    {
        // 1. Ambil data master untuk modal popup tambah data & dropdown filter
        $softwares = Software::all();
        $laboratoriums = Laboratorium::all();
        
        // 2. Gunakan query builder dengan Eager Loading relasi
        $query = Instalasi::with(['software', 'laboratorium', 'teknisi']);

        // 3. Fitur Filter: Berdasarkan Laboratorium
        if ($request->has('lab') && $request->lab != '') {
            $query->where('no_lab', $request->lab);
        }

        // Fitur Filter: Berdasarkan Software
        if ($request->has('software') && $request->software != '') {
            $query->where('id_software', $request->software);
        }

        // 4. Ambil data final dengan Pagination (diurutkan dari yang terbaru)
        // DISESUAIKAN: Mengganti ->get() menjadi ->paginate(10) agar kompatibel dengan Blade Pagination
        $instalasis = $query->latest()->paginate(10);
        
        // 5. Return ke view dengan menyertakan semua data yang dibutuhkan
        return view('instalasi.index', compact('instalasis', 'softwares', 'laboratoriums'));
    }

    public function create()
    {
        $softwares = Software::all();
        $laboratoriums = Laboratorium::all();
        return view('instalasi.create', compact('softwares', 'laboratoriums'));
    }

    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'id_software'      => 'required|exists:software,id_software', 
            'versi_terinstall' => 'required|string|max:50', 
            'no_lab'           => 'required|exists:laboratoriums,no_lab',
            'status_lisensi'   => 'required|in:free_license,license_active,license_expired',
            'tgl_aktif'        => 'nullable|date',
            'tgl_expired'      => 'nullable|date|after_or_equal:tgl_aktif',
        ], [
            'tgl_expired.after_or_equal' => 'Tanggal expired tidak boleh lebih awal dari tanggal aktif.',
        ]);

        // Menyimpan data instalasi baru ke database
        Instalasi::create([
            'id_software'      => $request->id_software,
            'versi_terinstall' => $request->versi_terinstall, 
            'no_lab'           => $request->no_lab,
            'status_lisensi'   => $request->status_lisensi,
            'tgl_aktif'        => $request->tgl_aktif,
            'tgl_expired'      => $request->tgl_expired,
            'diinstal_oleh'    => Auth::user()->no_induk, 
        ]);

        return redirect()->route('instalasi.index')->with('success', 'Data instalasi dan lisensi berhasil dicatat!');
    }

    public function edit(string $id)
    {
        $instalasi = Instalasi::findOrFail($id); 
        $softwares = Software::all();
        $laboratoriums = Laboratorium::all();
        
        return view('instalasi.edit', compact('instalasi', 'softwares', 'laboratoriums'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_software'      => 'required|exists:software,id_software', 
            'versi_terinstall' => 'required|string|max:50', 
            'no_lab'           => 'required|exists:laboratoriums,no_lab',
            'status_lisensi'   => 'required|in:free_license,license_active,license_expired',
            'tgl_aktif'        => 'nullable|date',
            'tgl_expired'      => 'nullable|date|after_or_equal:tgl_aktif',
        ], [
            'tgl_expired.after_or_equal' => 'Tanggal expired tidak boleh lebih awal dari tanggal aktif.',
        ]);

        $instalasi = Instalasi::findOrFail($id);

        $instalasi->update([
            'id_software'      => $request->id_software,
            'versi_terinstall' => $request->versi_terinstall, 
            'no_lab'           => $request->no_lab,
            'status_lisensi'   => $request->status_lisensi,
            'tgl_aktif'        => $request->tgl_aktif,
            'tgl_expired'      => $request->tgl_expired,
        ]);

        return redirect()->route('instalasi.index')->with('success', 'Data instalasi dan lisensi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $instalasi = Instalasi::findOrFail($id);
        $instalasi->delete();

        return redirect()->route('instalasi.index')->with('success', 'Data instalasi berhasil dihapus!');
    }
}