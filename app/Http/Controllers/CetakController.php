<?php

namespace App\Http\Controllers;

use App\Models\Instalasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    public function cetakLaporanLab($no_lab)
    {
        //Ambil data instalasi
        $dataInstalasi = Instalasi::with('software')
                            ->where('no_lab', $no_lab)
                            ->get();

        if ($dataInstalasi->isEmpty()) {
            return redirect()->back()->with('error', 'Data untuk ' . $no_lab . ' tidak ditemukan.');
        }

        $data = [
            'no_lab' => $no_lab,
            'tanggal_cetak' => date('d-m-Y H:i'),
            'daftar_instalasi' => $dataInstalasi
        ];

        $pdf = Pdf::loadView('laporan_lab', $data);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Software_' . $no_lab . '.pdf');
    }
}