<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Instalasi Software Komputer - {{ $no_lab }}</title>
    <style>
        /* Pengaturan Halaman */
        @page {
            margin: 1.5cm 2cm 2cm 2cm;
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #2d3748;
        }

        /* Desain Kop Surat / Header */
        .header-container {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .header-container td {
            vertical-align: middle;
        }
        .institution-title {
            font-size: 16px;
            font-weight: bold;
            color: #1a365d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 500;
            color: #4a5568;
            margin-top: 3px;
        }
        .doc-subtitle {
            font-size: 10px;
            color: #718096;
        }
        
        /* Garis Pembatas Kop Ganda */
        .line-thick {
            border-top: 2px solid #1a365d;
            margin-top: 10px;
            margin-bottom: 1px;
        }
        .line-thin {
            border-top: 1px solid #1a365d;
            margin-bottom: 20px;
        }

        /* Informasi Metadata */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
        }
        .meta-table td {
            padding: 8px 12px;
            vertical-align: middle;
        }
        .meta-label {
            width: 15%;
            font-weight: bold;
            color: #4a5568;
        }
        .meta-value {
            color: #1a202c;
        }

        /* Desain Tabel Utama (Laporan) */
        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #ffffff;
        }
        .table-laporan th {
            background-color: #1a365d;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            border: 1px solid #1a365d;
            padding: 8px 10px;
        }
        .table-laporan td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            color: #2d3748;
            vertical-align: middle;
        }
        /* Zebra Striping (Baris Selang-Seling) */
        .table-laporan tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        /* Utilitas Teks */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Footer Halaman */
        .footer {
            position: fixed;
            bottom: -0.5cm;
            left: 0;
            right: 0;
            font-size: 9px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <table class="header-container">
        <tr>
            <td>
                <div class="institution-title">Laboratorium ICT Terpadu</div>
                <div class="doc-title">Laporan Riwayat Instalasi Software Komputer</div>
                <div class="doc-subtitle">Sistem Request Instalasi Software</div>
            </td>
        </tr>
    </table>
    
    <div class="line-thick"></div>
    <div class="line-thin"></div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Laboratorium</td>
            <td style="width: 1%;">:</td>
            <td class="meta-value font-bold">{{ $no_lab }}</td>
            <td class="text-right meta-value" style="font-style: italic; color: #718096;">
                Tanggal Cetak: {{ \Carbon\Carbon::parse($tanggal_cetak)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <table class="table-laporan">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 45%;">Nama Software</th>
                <th style="width: 25%;" class="text-center">Versi Terinstal</th>
                <th style="width: 25%;" class="text-center">Tanggal Aktif / Instalasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftar_instalasi as $index => $instalasi)
            <tr>
                <td class="text-center" style="color: #718096;">{{ $index + 1 }}</td>
                <td class="font-bold" style="color: #1a202c;">
                    {{ $instalasi->software->nama_software ?? '-' }}
                </td>
                <td class="text-center">{{ $instalasi->versi_terinstall ?? '-' }}</td>
                <td class="text-center">
                    {{ $instalasi->tgl_aktif ? \Carbon\Carbon::parse($instalasi->tgl_aktif)->format('d-m-Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 20px; color: #a0aec0; font-style: italic;">
                    Belum ada data software yang terinstal di laboratorium ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer table-laporan">
        <table style="width: 100%;">
            <tr>
                <td>Dokumen ini sah dan dicetak secara otomatis melalui Sistem Manajemen Lab.</td>
                <td class="text-right">Halaman 1 dari 1</td>
            </tr>
        </table>
    </div>

</body>
</html>