<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Instalasi Software - {{ $no_lab }}</title>
    <style>
        /* Desain CSS khusus dompdf (Gunakan font standar dan Table Layout) */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .subtitle {
            font-size: 12px;
            margin: 5px 0 0 0;
            color: #555;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px 0;
        }
        /* Style untuk tabel data utama */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #f4f4f4;
            color: #000;
            border: 1px solid #999;
            padding: 8px;
            font-weight: bold;
            text-align: center;
        }
        .data-table td {
            border: 1px solid #999;
            padding: 7px;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 3px 6px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        /* Penanda halaman (Footer otomatis dari dompdf) */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: right;
            font-size: 10px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="footer">
        Laporan Antarmuka Lab - Halaman
    </div>

    <table class="header-table">
        <tr>
            <td>
                <div class="title">Laporan Inventarisasi Instalasi Software</div>
                <div class="subtitle">Sistem Informasi Manajemen Laboratorium Komputer</div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td width="18%"><strong>Nomor Laboratorium</strong></td>
            <td width="2%">:</td>
            <td width="30%">{{ $no_lab }}</td>
            <td width="20%"><strong>Tanggal Cetak</strong></td>
            <td width="2%">:</td>
            <td width="28%">{{ $tanggal_cetak }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Software</th>
                <th width="12%">Versi</th>
                <th width="15%">Status Lisensi</th>
                <th width="15%">Tgl Aktif</th>
                <th width="15%">Tgl Expired</th>
                <th width="13%">Diinstal Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftar_instalasi as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->software->nama_software ?? 'N/A' }}</td>
                <td class="text-center">
                    @if(is_array($item->software->versi) || is_object($item->software->versi))
                        @foreach($item->software->versi as $v)
                            <span style="background-color: #eee; padding: 2px 5px; margin: 2px; font-size: 10px; border-radius: 3px;">{{ $v }}</span>
                        @endforeach
                    @else
                        {{ $item->software->versi ?? '-' }}
                    @endif
                </td>
                
                <td class="text-center">
                    {{ $item->status_lisensi }}
                </td>
                <td class="text-center">
                    {{ $item->tgl_aktif ? date('d-m-Y', strtotime($item->tgl_aktif)) : '-' }}
                </td>
                <td class="text-center">
                    {{ $item->tgl_expired ? date('d-m-Y', strtotime($item->tgl_expired)) : 'Selamanya' }}
                </td>
                <td>{{ $item->diinstal_oleh }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>