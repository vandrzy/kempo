<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Laporan Hasil Pertandingan - {{ $kegiatan_aktif->nama ?? 'Shorinji Kempo' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.35;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Document Header */
        .doc-header {
            text-align: center;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 10px 0;
            margin-bottom: 14px;
        }

        .doc-header h1 {
            font-size: 13pt;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .doc-header h2 {
            font-size: 11pt;
            font-weight: 700;
            margin: 4px 0 0 0;
            color: #1e3a8a;
            text-transform: uppercase;
        }

        .doc-header .timestamp {
            font-size: 7.5pt;
            color: #64748b;
            margin-top: 5px;
            font-style: italic;
        }

        /* Bab Title */
        .bab-title {
            background-color: #0f172a;
            color: #ffffff;
            padding: 6px 10px;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 15px;
            margin-bottom: 4px;
            border-radius: 2px;
        }

        .bab-subtitle {
            font-size: 8pt;
            color: #475569;
            margin-bottom: 10px;
            font-style: italic;
        }

        /* Page Breaks */
        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* Main Tables */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.5pt;
        }

        table.report-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            text-align: center;
        }

        table.report-table td {
            border: 1px solid #cbd5e1;
            padding: 4.5px 6px;
            vertical-align: middle;
        }

        table.report-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Per-Class Box for Bab II & III */
        .kelas-box {
            page-break-inside: avoid;
            margin-bottom: 10px;
            border: 1px solid #94a3b8;
            border-radius: 3px;
            overflow: hidden;
        }

        .kelas-header {
            background-color: #e2e8f0;
            color: #0f172a;
            padding: 5px 8px;
            font-size: 8.5pt;
            font-weight: bold;
            border-bottom: 1px solid #94a3b8;
        }

        .kelas-header .kelas-jenis {
            float: right;
            font-size: 7.5pt;
            padding: 1px 6px;
            border-radius: 2px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .jenis-embu {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .jenis-randori {
            background-color: #fee2e2;
            color: #991b1b;
        }

        table.kelas-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        table.kelas-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            font-size: 7.5pt;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding: 4px 6px;
        }

        table.kelas-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 4px 6px;
            vertical-align: middle;
        }

        table.kelas-table tr:last-child td {
            border-bottom: none;
        }

        /* Medal Badges */
        .medali-emas {
            color: #92400e;
            font-weight: bold;
        }

        .medali-perak {
            color: #334155;
            font-weight: bold;
        }

        .medali-perunggu {
            color: #c2410c;
            font-weight: bold;
        }

        /* Pengesahan Block */
        .pengesahan-container {
            page-break-inside: avoid;
            margin-top: 30px;
            width: 100%;
        }

        .pengesahan-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .pengesahan-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- ============================================================== -->
    <!-- DOKUMEN HEADER                                                 -->
    <!-- ============================================================== -->
    <div class="doc-header">
        <h1>Buku Laporan Hasil Pertandingan Kejuaraan Shorinji Kempo</h1>
        <h2>{{ $kegiatan_aktif->nama ?? 'Kejuaraan Shorinji Kempo' }}</h2>
        <div class="timestamp">
            Dicetak dari Sistem Informasi Pertandingan (SIAP-PERKEMI) pada {{ $timestamp_cetak ?? date('d-m-Y H:i') }}
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- BAB I: KLASEMEN PEROLEHAN MEDALI (JUARA UMUM)                   -->
    <!-- ============================================================== -->
    <div class="bab-title">
        BAB I: KLASEMEN PEROLEHAN MEDALI (JUARA UMUM)
    </div>
    <div class="bab-subtitle">
        Rekapitulasi resmi perolehan medali per kontingen (diurutkan berdasarkan Emas &gt; Perak &gt; Perunggu &gt; Total Medali).
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 45px;">RANK</th>
                <th style="text-align: left;">NAMA KONTINGEN</th>
                <th style="width: 70px;">EMAS</th>
                <th style="width: 70px;">PERAK</th>
                <th style="width: 70px;">PERUNGGU</th>
                <th style="width: 80px;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($klasemen as $index => $row)
                <tr>
                    <td class="text-center font-bold" style="{{ $index < 3 ? 'background-color: #fef3c7;' : '' }}">
                        {{ $index + 1 }}
                    </td>
                    <td class="text-left font-bold" style="color: #0f172a;">
                        {{ $row['nama'] }}
                    </td>
                    <td class="text-center font-bold" style="color: #92400e;">
                        {{ $row['emas'] }}
                    </td>
                    <td class="text-center font-bold" style="color: #334155;">
                        {{ $row['perak'] }}
                    </td>
                    <td class="text-center font-bold" style="color: #c2410c;">
                        {{ $row['perunggu'] }}
                    </td>
                    <td class="text-center font-bold" style="background-color: #eff6ff; color: #1e40af;">
                        {{ $row['total'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 12px; color: #64748b;">
                        Belum ada data perolehan medali kontingen.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($klasemen) > 0)
        <tfoot>
            <tr style="background-color: #e2e8f0; font-weight: bold; border-top: 2px solid #64748b;">
                <td colspan="2" class="text-center" style="padding: 6px;">TOTAL KESELURUHAN MEDALI</td>
                <td class="text-center" style="color: #92400e; padding: 6px;">{{ $total_medali_emas }}</td>
                <td class="text-center" style="color: #334155; padding: 6px;">{{ $total_medali_perak }}</td>
                <td class="text-center" style="color: #c2410c; padding: 6px;">{{ $total_medali_perunggu }}</td>
                <td class="text-center" style="background-color: #dbeafe; color: #1e3a8a; padding: 6px;">{{ $total_seluruh_medali ?? ($total_medali_emas + $total_medali_perak + $total_medali_perunggu) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- ============================================================== -->
    <!-- BAB II: DAFTAR PERAIH MEDALI KATEGORI EMBU                     -->
    <!-- ============================================================== -->
    <div class="page-break"></div>

    <div class="bab-title">
        BAB II: DAFTAR PERAIH MEDALI KATEGORI EMBU
    </div>
    <div class="bab-subtitle">
        Daftar peraih Medali Emas, Perak, dan Perunggu khusus nomor Kerapian Teknik (Embu) &bull; Total: {{ count($daftar_juara_embu) }} Nomor Pertandingan
    </div>

    @forelse($daftar_juara_embu as $index => $item)
        <div class="kelas-box">
            <div class="kelas-header">
                <span class="kelas-jenis jenis-embu">Embu</span>
                <span>{{ $index + 1 }}. {{ strtoupper($item['pertandingan_nama']) }}</span>
                <span style="font-weight: normal; color: #475569; margin-left: 6px;">
                    ({{ $item['kategori_nama'] }} &bull; Kelas: {{ $item['kelas'] }})
                </span>
            </div>
            <table class="kelas-table">
                <thead>
                    <tr>
                        <th style="width: 130px; text-align: left;">MEDALI</th>
                        <th style="text-align: left;">NAMA ATLET</th>
                        <th style="width: 180px; text-align: left;">KONTINGEN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="medali-emas">EMAS (JUARA 1)</td>
                        <td class="font-bold">{{ $item['emas']['peserta'] ?: '-' }}</td>
                        <td>{{ $item['emas']['kabupaten'] ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="medali-perak">PERAK (JUARA 2)</td>
                        <td class="font-bold">{{ $item['perak']['peserta'] ?: '-' }}</td>
                        <td>{{ $item['perak']['kabupaten'] ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="medali-perunggu">PERUNGGU (JUARA 3)</td>
                        <td class="font-bold">{{ $item['perunggu']['peserta'] ?: '-' }}</td>
                        <td>{{ $item['perunggu']['kabupaten'] ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @empty
        <div style="padding: 15px; text-align: center; color: #64748b; border: 1px dashed #cbd5e1; border-radius: 4px;">
            Belum ada data nomor pertandingan Kategori Embu pada kegiatan ini.
        </div>
    @endforelse

    <!-- ============================================================== -->
    <!-- BAB III: DAFTAR PERAIH MEDALI KATEGORI RANDORI                 -->
    <!-- ============================================================== -->
    <div class="page-break"></div>

    <div class="bab-title">
        BAB III: DAFTAR PERAIH MEDALI KATEGORI RANDORI
    </div>
    <div class="bab-subtitle">
        Daftar peraih Medali Emas, Perak, dan Perunggu khusus nomor Pertarungan Bebas (Randori) &bull; Total: {{ count($daftar_juara_randori) }} Nomor Pertandingan
    </div>

    @forelse($daftar_juara_randori as $index => $item)
        <div class="kelas-box">
            <div class="kelas-header">
                <span class="kelas-jenis jenis-randori">Randori</span>
                <span>{{ $index + 1 }}. {{ strtoupper($item['pertandingan_nama']) }}</span>
                <span style="font-weight: normal; color: #475569; margin-left: 6px;">
                    ({{ $item['kategori_nama'] }} &bull; Kelas: {{ $item['kelas'] }})
                </span>
            </div>
            <table class="kelas-table">
                <thead>
                    <tr>
                        <th style="width: 130px; text-align: left;">MEDALI</th>
                        <th style="text-align: left;">NAMA ATLET</th>
                        <th style="width: 180px; text-align: left;">KONTINGEN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="medali-emas">EMAS (JUARA 1)</td>
                        <td class="font-bold">{{ $item['emas']['peserta'] ?: '-' }}</td>
                        <td>{{ $item['emas']['kabupaten'] ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="medali-perak">PERAK (JUARA 2)</td>
                        <td class="font-bold">{{ $item['perak']['peserta'] ?: '-' }}</td>
                        <td>{{ $item['perak']['kabupaten'] ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="medali-perunggu">PERUNGGU (JUARA 3)</td>
                        <td class="font-bold">{{ $item['perunggu']['peserta'] ?: '-' }}</td>
                        <td>{{ $item['perunggu']['kabupaten'] ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @empty
        <div style="padding: 15px; text-align: center; color: #64748b; border: 1px dashed #cbd5e1; border-radius: 4px;">
            Belum ada data nomor pertandingan Kategori Randori pada kegiatan ini.
        </div>
    @endforelse

    <!-- ============================================================== -->
    <!-- BAGIAN FOOTER / PENGESAHAN                                      -->
    <!-- ============================================================== -->
    <div class="pengesahan-container">
        <table class="pengesahan-table">
            <tr>
                <td style="width: 55%; border: none;"></td>
                <td style="width: 45%; text-align: center; border: none; vertical-align: top;">
                    <div style="font-size: 8.5pt; color: #334155; margin-bottom: 8px; line-height: 1.4;">
                        Ditetapkan di: <strong>{{ $kota_penetapan ?? 'Samarinda' }}</strong><br>
                        Pada tanggal: <strong>{{ $tanggal_penetapan }}</strong>
                    </div>
                    <div style="font-size: 8.5pt; color: #475569;">Mengetahui,</div>
                    <div style="font-size: 9pt; font-weight: bold; color: #0f172a; margin-top: 2px;">Ketua Panitia Pelaksana</div>
                    <div style="height: 65px;"></div>
                    <div style="font-size: 9pt; font-weight: bold; color: #0f172a;">
                        ( ........................................................... )
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Script Page Number untuk DomPDF -->
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
                $font = $fontMetrics->get_font('Helvetica', 'normal');
                $size = 8;
                $systemText = "SIAP-PERKEMI • Buku Laporan Hasil Pertandingan";
                $pageText = "Halaman " . $pageNumber . " dari " . $pageCount;
                $canvas->text(34, 805, $systemText, $font, $size, array(0.4, 0.4, 0.4));
                $canvas->text(500, 805, $pageText, $font, $size, array(0.4, 0.4, 0.4));
            });
        }
    </script>

</body>
</html>
