<?php

namespace App\Http\Controllers;

use App\Models\Instalasi;
use App\Models\Software;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstalasiController extends Controller
{
    public function index()
    {
        // Mengambil data instalasi beserta relasinya
        $instalasis = Instalasi::with(['software', 'laboratorium', 'teknisi'])->latest()->get();
        return view('instalasi.index', compact('instalasis'));
    }

    public function create()
    {
        // Mengirim master data untuk dropdown pilihan di form
        $softwares = Software::all();
        $laboratoriums = Laboratorium::all();
        return view('instalasi.create', compact('softwares', 'laboratoriums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_software'    => 'required|exists:softwares,id_software',
            'no_lab'         => 'required|exists:laboratoriums,no_lab',
            'status_lisensi' => 'required|in:free_license,license_active,license_expired',
            'tgl_aktif'      => 'nullable|date',
            'tgl_expired'    => 'nullable|date|after_or_equal:tgl_aktif',
        ]);

        Instalasi::create([
            'id_software'    => $request->id_software,
            'no_lab'         => $request->no_lab,
            'status_lisensi' => $request->status_lisensi,
            'tgl_aktif'      => $request->tgl_aktif,
            'tgl_expired'    => $request->tgl_expired,
            // Otomatis mengambil no_induk milik teknisi yang sedang login
            'diinstal_oleh'  => Auth::user()->no_induk, 
        ]);

        return redirect()->route('instalasi.index')->with('success', 'Data instalasi dan lisensi berhasil dicatat!');
    }

    // Fungsi edit, update, dan destroy bisa ditambahkan secara identik dengan CRUD sebelumnya
}