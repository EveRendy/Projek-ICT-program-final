<?php

namespace App\Http\Controllers;

use App\Models\Instalasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    public function cetakLaporanLab($no_lab)
    {
        // 1. Ambil data instalasi berdasarkan nomor lab, lengkap dengan relasi software-nya
        $dataInstalasi = Instalasi::with('software')
                            ->where('no_lab', $no_lab)
                            ->get();

        // 2. Jika data kosong, Anda bisa beri handle (opsional)
        if ($dataInstalasi->isEmpty()) {
            return redirect()->back()->with('error', 'Data untuk ' . $no_lab . ' tidak ditemukan.');
        }

        // 3. Siapkan data yang akan dilempar ke view Blade
        $data = [
            'no_lab' => $no_lab,
            'tanggal_cetak' => date('d-m-Y H:i'),
            'daftar_instalasi' => $dataInstalasi
        ];

        // 4. Load view 'laporan_lab' dan masukkan datanya
        $pdf = Pdf::loadView('laporan_lab', $data);

        // 5. Atur kertas menjadi A4 Potrait (opsional)
        $pdf->setPaper('a4', 'portrait');

        // 6. Stream ke browser
        return $pdf->stream('Laporan_Software_' . $no_lab . '.pdf');
    }
}