<?php

namespace App\Http\Controllers;

use App\Models\LicenseTracking;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    /**
     * Tampilkan halaman preview laporan di browser (dengan tombol Print & Download)
     */
    public function previewLaporanLab($no_lab)
    {
        $lab = Laboratorium::where('no_lab', $no_lab)->first();
        $dataInstalasi = [];
        
        if ($lab) {
            $dataInstalasi = LicenseTracking::with('software')
                                ->where('laboratorium_id', $lab->id)
                                ->get();
        }

        // Kelompokkan berdasarkan nama software dan versi
        $groupedData = $dataInstalasi->groupBy(function ($item) {
            return ($item->software->nama_software ?? 'unknown') . '|' . $item->versi_terinstall;
        })->map(function ($group) {
            // Ambil item pertama dari grup
            $firstItem = $group->first();
            return $firstItem;
        })->values();

        $data = [
            'no_lab'           => $no_lab,
            'lab'              => $lab,
            'tanggal_cetak'    => now(),
            'daftar_instalasi' => $groupedData,
        ];

        return view('laporan_preview', $data);
    }

    /**
     * Hasilkan file PDF untuk di-download
     */
    public function cetakLaporanLab($no_lab)
    {
        $lab = Laboratorium::where('no_lab', $no_lab)->first();
        
        if (!$lab) {
            return redirect()->back()->with('error', 'Laboratorium tidak ditemukan.');
        }

        $dataInstalasi = LicenseTracking::with('software')
                            ->where('laboratorium_id', $lab->id)
                            ->get();

        if ($dataInstalasi->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada riwayat instalasi atau lisensi untuk lab ini.');
        }

        // Kelompokkan berdasarkan nama software dan versi
        $groupedData = $dataInstalasi->groupBy(function ($item) {
            return ($item->software->nama_software ?? 'unknown') . '|' . $item->versi_terinstall;
        })->map(function ($group) {
            // Ambil item pertama dari grup
            $firstItem = $group->first();
            return $firstItem;
        })->values();

        $data = [
            'no_lab'           => $no_lab,
            'lab'              => $lab,
            'tanggal_cetak'    => now(),
            'daftar_instalasi' => $groupedData,
        ];

        $pdf = Pdf::loadView('laporan_lab', $data)
                  ->setPaper('a4', 'landscape'); 

        return $pdf->download('Laporan_Instalasi_Lab_' . $no_lab . '.pdf');
    }
}