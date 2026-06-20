<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Instalasi — Lab {{ $no_lab }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ====================================================
           BASE & SCREEN STYLES
           ==================================================== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ====================================================
           TOOLBAR (Only visible on screen)
           ==================================================== */
        .toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background-color: #0f172a;
            border-bottom: 1px solid #1e293b;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.3);
        }
        .toolbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .toolbar-brand img {
            height: 38px;
            border-radius: 8px;
        }
        .toolbar-brand-text h2 {
            font-size: 14px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.2px;
        }
        .toolbar-brand-text p {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 1px;
        }
        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-toolbar {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-back {
            background-color: #1e293b;
            color: #94a3b8;
            border: 1px solid #334155;
        }
        .btn-back:hover { background-color: #334155; color: #e2e8f0; }
        .btn-download {
            background-color: #1d4ed8;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(29,78,216,0.3);
        }
        .btn-download:hover { background-color: #1e40af; }
        .btn-print {
            background-color: #059669;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(5,150,105,0.3);
        }
        .btn-print:hover { background-color: #047857; }

        .toolbar-badge {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 8px;
        }

        /* ====================================================
           PAGE WRAPPER (Centers the A4 paper on screen)
           ==================================================== */
        .page-wrapper {
            padding: 96px 24px 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ====================================================
           THE A4 PAPER / DOCUMENT
           ==================================================== */
        .document {
            background: #ffffff;
            width: 794px; /* ~A4 width at 96dpi */
            min-height: 1123px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.06);
            border-radius: 4px;
            padding: 56px 64px;
            position: relative;
        }

        /* Document Header / Kop Surat */
        .doc-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
        }
        .doc-header-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .doc-header-divider {
            width: 2px;
            height: 60px;
            background: #e2e8f0;
            flex-shrink: 0;
        }
        .doc-header-text {}
        .doc-header-text .institution {
            font-size: 18px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.3px;
            text-transform: uppercase;
        }
        .doc-header-text .doc-title {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-top: 3px;
        }
        .doc-header-text .doc-subtitle {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Garis kop */
        .header-rule {
            margin-top: 0;
            margin-bottom: 0;
        }
        .header-rule-thick {
            border: none;
            border-top: 3px solid #1e293b;
            margin-bottom: 2px;
        }
        .header-rule-thin {
            border: none;
            border-top: 1px solid #1e293b;
            margin-bottom: 28px;
        }

        /* Meta info bar */
        .meta-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .meta-bar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            padding: 6px 12px;
        }
        .meta-tag-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-tag-value {
            font-size: 13px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.3px;
        }
        .meta-bar-right {
            text-align: right;
        }
        .meta-bar-right .date-label {
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-bar-right .date-value {
            font-size: 13px;
            font-weight: 700;
            color: #e2e8f0;
            margin-top: 3px;
        }

        /* Section title */
        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Data table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .data-table thead tr {
            background: #0f172a;
        }
        .data-table thead th {
            padding: 11px 14px;
            color: #e2e8f0;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-align: left;
        }
        .data-table thead th:first-child { border-radius: 8px 0 0 0; text-align: center; }
        .data-table thead th:last-child { border-radius: 0 8px 0 0; }
        .data-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .data-table tbody tr:hover {
            background-color: #f0f9ff;
        }
        .data-table tbody td {
            padding: 11px 14px;
            color: #334155;
            vertical-align: middle;
        }
        .data-table tbody td.no-col {
            text-align: center;
            color: #94a3b8;
            font-weight: 600;
            font-size: 11px;
        }
        .data-table tbody td.name-col {
            font-weight: 700;
            color: #0f172a;
        }
        .data-table tbody td.center-col {
            text-align: center;
        }
        .badge-version {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-date {
            display: inline-block;
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
        }
        .empty-row td {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-style: italic;
        }

        /* Summary footer */
        .summary-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 12px;
        }
        .summary-bar .total-label {
            font-weight: 600;
            color: #64748b;
        }
        .summary-bar .total-value {
            font-weight: 900;
            font-size: 16px;
            color: #0f172a;
        }

        /* Signature section */
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-title {
            font-size: 11px;
            color: #475569;
            font-weight: 500;
        }
        .signature-city-date {
            font-size: 11px;
            color: #475569;
            margin-bottom: 60px;
        }
        .signature-name {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            border-top: 1.5px solid #0f172a;
            padding-top: 8px;
        }
        .signature-role {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }

        /* Document Footer */
        .doc-footer {
            position: absolute;
            bottom: 32px;
            left: 64px;
            right: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .doc-footer p {
            font-size: 9px;
            color: #94a3b8;
        }

        /* ====================================================
           PRINT STYLES — only the document, no toolbar
           ==================================================== */
        @media print {
            @page {
                size: A4;
                margin: 1.5cm 2cm;
            }
            body {
                background: #ffffff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .toolbar { display: none !important; }
            .page-wrapper {
                padding: 0;
            }
            .document {
                width: 100%;
                min-height: auto;
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                margin: 0;
            }
            .data-table tbody tr:hover {
                background-color: inherit;
            }
        }
    </style>
</head>
<body>

    <!-- ==========================================
         TOOLBAR (Screen only)
         ========================================== -->
    <div class="toolbar">
        <div class="toolbar-brand">
            <img src="{{ asset('images/image.png') }}" alt="Logo ICT">
            <div class="toolbar-brand-text">
                <h2>Laboratorium ICT Terpadu</h2>
                <p>Pratinjau Laporan Instalasi Software</p>
            </div>
        </div>

        <div class="toolbar-actions">
            <span class="toolbar-badge">Lab: {{ $no_lab }}{{ $lab && $lab->nama_lab ? ' : ' . $lab->nama_lab : '' }}</span>

            <a href="{{ url()->previous() }}" class="btn-toolbar btn-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>

            <a href="{{ route('cetak.laporan.lab', $no_lab) }}" target="_blank" class="btn-toolbar btn-download">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh PDF
            </a>

            <button onclick="window.print()" class="btn-toolbar btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Sekarang
            </button>
        </div>
    </div>

    <!-- ==========================================
         DOCUMENT (A4 Paper)
         ========================================== -->
    <div class="page-wrapper">
        <div class="document">

            <!-- Kop Surat -->
            <div class="doc-header">
                <img class="doc-header-logo" src="{{ asset('images/image.png') }}" alt="Logo ICT">
                <div class="doc-header-divider"></div>
                <div class="doc-header-text">
                    <div class="institution">Laboratorium ICT Terpadu</div>
                    <div class="doc-title">Laporan Riwayat Instalasi Software Komputer</div>
                    <div class="doc-subtitle">Sistem Request Instalasi Software — Universitas Budi Luhur</div>
                </div>
            </div>
            <hr class="header-rule header-rule-thick">
            <hr class="header-rule header-rule-thin">

            <!-- Meta Bar -->
            <div class="meta-bar">
                <div class="meta-bar-left">
                    <div class="meta-tag">
                        <div>
                            <div class="meta-tag-label">Laboratorium</div>
                            <div class="meta-tag-value">{{ $no_lab }}{{ $lab && $lab->nama_lab ? ' : ' . $lab->nama_lab : '' }}</div>
                        </div>
                    </div>
                    @if($lab)
                    <div class="meta-tag">
                        <div>
                            <div class="meta-tag-label">Jumlah PC</div>
                            <div class="meta-tag-value">{{ $lab->jumlah_pc ?? '—' }} Unit</div>
                        </div>
                    </div>
                    <div class="meta-tag">
                        <div>
                            <div class="meta-tag-label">Level Lab</div>
                            <div class="meta-tag-value">Level {{ $lab->level ?? '—' }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="meta-bar-right">
                    <div class="date-label">Tanggal Cetak</div>
                    <div class="date-value">{{ $tanggal_cetak->translatedFormat('d F Y') }}</div>
                </div>
            </div>

            <!-- Table Title -->
            <div class="section-title">Daftar Software Terinstal</div>

            <!-- Data Table -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:45%;">Nama Software</th>
                        <th style="width:25%; text-align:center;">Versi Terinstal</th>
                        <th style="width:25%; text-align:center;">Tanggal Instalasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftar_instalasi as $index => $instalasi)
                    <tr>
                        <td class="no-col">{{ $index + 1 }}</td>
                        <td class="name-col">{{ $instalasi->software->nama_software ?? '—' }}</td>
                        <td class="center-col">
                            <span class="badge-version">{{ $instalasi->versi_terinstall ?? '—' }}</span>
                        </td>
                        <td class="center-col">
                            @if($instalasi->tgl_aktif)
                                <span class="badge-date">{{ \Carbon\Carbon::parse($instalasi->tgl_aktif)->translatedFormat('d M Y') }}</span>
                            @else
                                <span style="color:#94a3b8;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="4">Belum ada software yang terinstal di laboratorium ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Summary -->
            <div class="summary-bar">
                <span class="total-label">Total Software Terinstal</span>
                <span class="total-value">{{ $daftar_instalasi->count() }} Software</span>
            </div>

            <!-- Signature -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-title">Mengetahui,</div>
                    <div class="signature-city-date">Jakarta, {{ $tanggal_cetak->translatedFormat('d F Y') }}</div>
                    <div class="signature-name">Supervisor Lab</div>
                    <div class="signature-role">Laboratorium ICT Terpadu</div>
                </div>
            </div>

            <!-- Document Footer -->
            <div class="doc-footer">
                <p>Dokumen ini dicetak secara otomatis melalui Sistem Manajemen Laboratorium ICT.</p>
                <p>{{ $tanggal_cetak->format('d-m-Y H:i') }}</p>
            </div>

        </div>
    </div>

</body>
</html>
