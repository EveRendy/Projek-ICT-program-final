<?php

namespace App\Http\Controllers;

use App\Models\Instalasi;
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
        $dataInstalasi = Instalasi::with('software')
                            ->where('no_lab', $no_lab)
                            ->get();

        $lab = Laboratorium::where('no_lab', $no_lab)->first();

        $data = [
            'no_lab'           => $no_lab,
            'lab'              => $lab,
            'tanggal_cetak'    => now(),
            'daftar_instalasi' => $dataInstalasi,
        ];

        return view('laporan_preview', $data);
    }

    /**
     * Download langsung sebagai PDF
     */
    public function cetakLaporanLab($no_lab)
    {
        $dataInstalasi = Instalasi::with('software')
                            ->where('no_lab', $no_lab)
                            ->get();

        if ($dataInstalasi->isEmpty()) {
            return redirect()->back()->with('error', 'Data untuk ' . $no_lab . ' tidak ditemukan.');
        }

        $lab = Laboratorium::where('no_lab', $no_lab)->first();

        $data = [
            'no_lab'           => $no_lab,
            'lab'              => $lab,
            'tanggal_cetak'    => date('d-m-Y H:i'),
            'daftar_instalasi' => $dataInstalasi,
        ];

        $pdf = Pdf::loadView('laporan_lab', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Software_' . $no_lab . '.pdf');
    }
}