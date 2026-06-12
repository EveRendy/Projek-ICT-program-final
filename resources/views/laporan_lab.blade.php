<!DOCTYPE html>
<html>
<head>
    <title>Laporan Instalasi Software Komputer</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Heading Styles */
        .title { 
            font-size: 18px; 
            font-weight: bold; 
            margin-bottom: 5px; 
            text-transform: uppercase;
        }
        .subtitle { 
            font-size: 13px; 
            margin-bottom: 15px; 
            color: #555;
        }
        .line-separator {
            border: 1.5px solid #000;
            margin-bottom: 25px;
        }

        /* Meta Info */
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        /* Main Table Styles */
        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-laporan th, .table-laporan td {
            border: 1px solid #bbb;
            padding: 10px 8px;
        }
        .table-laporan th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #222;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            font-style: italic;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="text-center title">LAPORAN INSTALASI SOFTWARE KOMPUTER</div>
    <div class="text-center subtitle">Universitas Computer Laboratory</div>
    <div class="line-separator"></div>

    <table class="meta-table">
        <tr>
            <td style="width: 15%; font-weight: bold;">Nomor Lab</td>
            <td style="width: 2%;">:</td>
            <td>{{ $no_lab }}</td>
            <td class="text-right" style="font-style: italic; color: #555;">
                Tanggal Cetak: {{ $tanggal_cetak }}
            </td>
        </tr>
    </table>

    <table class="table-laporan">
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">No</th>
                <th>Nama Software</th>
                <th style="width: 25%;">Versi</th>
                <th style="width: 30%;">Tanggal Instalasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftar_instalasi as $index => $instalasi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $instalasi->software->nama_software ?? '-' }}</td>
                
                <td>{{ $instalasi->versi_terinstall ?? '-' }}</td>
                
                <td>
                    {{ $instalasi->tgl_aktif ? \Carbon\Carbon::parse($instalasi->tgl_aktif)->format('d-m-Y') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right footer">
        Dicetak secara otomatis oleh Sistem Manajemen Lab.
    </div>

</body>
</html>