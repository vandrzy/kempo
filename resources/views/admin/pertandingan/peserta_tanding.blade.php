<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bagan Pertandingan & Pasangan Lawan Randori - {{ $pertandingan->pertandingan->nama ?? 'Randori' }}</title>
    <style>
        @page {
            margin: 1cm 0.8cm 1cm 0.8cm;
            size: A4 landscape;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #111827;
            line-height: 1.3;
        }

        .header-title {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .header-title h2 {
            margin: 0;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #111827;
        }

        .header-title h3 {
            margin: 3px 0 0 0;
            font-size: 11px;
            font-weight: bold;
            color: #dc2626;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .info-table td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .info-label {
            width: 14%;
            font-weight: bold;
            color: #374151;
        }

        .info-colon {
            width: 2%;
        }

        .info-val {
            width: 34%;
        }

        /* Table Bagan Duel */
        table.kempo-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9.5px;
        }

        table.kempo-table th, 
        table.kempo-table td {
            border: 1px solid #4b5563;
            padding: 5px 6px;
            vertical-align: middle;
        }

        table.kempo-table thead th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9px;
        }

        .row-merah {
            background-color: #fff1f2;
        }

        .row-putih {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .badge-babak {
            display: inline-block;
            padding: 2px 5px;
            background-color: #e5e7eb;
            color: #1f2937;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .badge-final {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-semifinal {
            background-color: #f3e8ff;
            color: #6b21a8;
        }

        .badge-menang {
            display: inline-block;
            padding: 1px 4px;
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            margin-left: 3px;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .sign-line {
            display: inline-block;
            width: 70%;
            border-bottom: 1px solid #111827;
            margin-top: 45px;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen -->
    <div class="header-title">
        <h2>PERSAUDARAAN SHORINJI KEMPO INDONESIA (PERKEMI)</h2>
        <h3>BAGAN PERTANDINGAN &amp; PASANGAN LAWAN RANDORI</h3>
    </div>

    <!-- Informasi Kelas Pertandingan -->
    <table class="info-table">
        <tr>
            <td class="info-label">Kegiatan</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">{{ $pertandingan->kegiatan->nama_kegiatan ?? ($pertandingan->kegiatan->nama ?? '-') }}</td>

            <td class="info-label">Kategori</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">Randori (Bagan Gugur)</td>
        </tr>
        <tr>
            <td class="info-label">Nomor / Kelas</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">{{ $pertandingan->pertandingan->nama ?? '-' }} (Kelas: {{ $pertandingan->pertandingan->kelas ?? '-' }})</td>

            <td class="info-label">Jenis Kelamin</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $pertandingan->pertandingan->jenis_kelamin ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Jumlah Partai</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">{{ $peserta_lawan->count() }} Partai Duel</td>

            <td class="info-label">Tanggal Cetak</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ date('d-m-Y') }} WIB</td>
        </tr>
    </table>

    <!-- Tabel Bagan Pertandingan & Pasangan Lawan Randori -->
    <table class="kempo-table">
        <thead>
            <tr>
                <th rowspan="1" style="width: 13%;">Babak &amp; Partai</th>
                <th rowspan="1" style="width: 11%;">Sudut</th>
                <th rowspan="1" style="width: 18%;">Kontingen</th>
                <th rowspan="1" style="width: 25%;">Nama Atlet</th>
                <th rowspan="1" style="width: 8%;">Skor</th>
                <th rowspan="1" style="width: 15%;">Status &amp; Pemenang</th>
            </tr>

        </thead>
        <tbody>
            @php
                $totalBabak = $totalBabakKategori ?? 1;
            @endphp
            @forelse ($peserta_lawan as $a)
                @php
                    $isReady = $a->pertandingan_peserta_kiri_id && $a->pertandingan_peserta_kanan_id;
                    $isBye = ($a->pertandingan_peserta_kanan_id === null && $a->keterangan_menang === 'BYE');

                    $namaBabak = 'Babak ' . $a->babak;
                    $badgeClass = 'badge-babak';
                    if ($a->babak == $totalBabak) {
                        $namaBabak = 'Final';
                        $badgeClass .= ' badge-final';
                    } elseif ($totalBabak >= 2 && $a->babak == $totalBabak - 1) {
                        $namaBabak = 'Semifinal';
                        $badgeClass .= ' badge-semifinal';
                    } elseif ($totalBabak >= 3 && $a->babak == $totalBabak - 2) {
                        $namaBabak = 'Perempatfinal';
                    }

                    $kiriMenang = ($a->pemenang_id && $a->pemenang_id == $a->pertandingan_peserta_kiri_id);
                    $kananMenang = ($a->pemenang_id && $a->pemenang_id == $a->pertandingan_peserta_kanan_id);

                    $atletMerah = optional(optional($a->PertandinganPesertaPertama)->Peserta);
                    $atletPutih = optional(optional($a->PertandinganPesertaKedua)->Peserta);
                @endphp

                <!-- Baris Sudut Merah (AKA) -->
                <tr class="row-merah">
                    <!-- Babak & Partai (Rowspan 2) -->
                    <td rowspan="2" class="text-center font-bold">
                        <span class="{{ $badgeClass }}">{{ $namaBabak }}</span><br>
                        <strong>Partai #{{ $a->nomor_partai }}</strong>
                    </td>

                    <!-- Sudut Merah -->
                    <td class="font-bold text-center" style="color: #dc2626;">
                        Merah
                    </td>

                    <!-- Kontingen Merah -->
                    <td class="font-bold">
                        {{ $atletMerah->kabupaten->name ?? ($atletMerah->kontigen ?? ($a->pertandingan_peserta_kiri_id ? 'Tanpa Kontingen' : 'Belum Ada')) }}
                    </td>

                    <!-- Nama Atlet Merah -->
                    <td>
                        @if($a->PertandinganPesertaPertama)
                            <strong style="{{ $kiriMenang ? 'color: #15803d;' : '' }}">{{ $atletMerah->nama ?? 'Atlet Merah' }}</strong>
                            @if($kiriMenang)
                                <span class="badge-menang">MENANG</span>
                            @endif
                        @else
                            <span style="color: #6b7280; font-style: italic;">{{ $a->getWaitingPlaceholderText('kiri') }}</span>
                        @endif
                    </td>


                    <!-- Skor Merah -->
                    <td class="text-center font-bold" style="font-size: 12px; color: #dc2626;">
                        {{ $a->skor_kiri !== null ? $a->skor_kiri : '-' }}
                    </td>

                    <!-- Status & Pemenang (Rowspan 2) -->
                    <td rowspan="2" class="text-center">
                        @if($isBye)
                            <span style="color: #15803d; font-weight: bold;">Lolos (BYE)</span>
                        @elseif($a->pemenang_id)
                            <strong style="color: #15803d;">{{ $kiriMenang ? 'Merah Menang' : 'Putih Menang' }}</strong><br>
                            <span style="font-size: 8.5px; color: #4b5563;">{{ $a->keterangan_menang ?? 'POIN' }} ({{ $a->skor_kiri }} - {{ $a->skor_kanan }})</span>
                        @elseif($isReady)
                            <span style="color: #b45309; font-weight: bold;">Siap Bertanding</span>
                        @else
                            <span style="color: #6b7280; font-style: italic;">Menunggu Lawan</span>
                        @endif
                    </td>
                </tr>

                <!-- Baris Sudut Putih (SHIRO) -->
                <tr class="row-putih">
                    <!-- Sudut Putih -->
                    <td class="font-bold text-center" style="color: #2563eb;">
                        Putih
                    </td>

                    <!-- Kontingen Putih -->
                    <td class="font-bold">
                        {{ $atletPutih->kabupaten->name ?? ($atletPutih->kontigen ?? ($a->pertandingan_peserta_kanan_id ? 'Tanpa Kontingen' : 'Belum Ada')) }}
                    </td>

                    <!-- Nama Atlet Putih -->
                    <td>
                        @if($a->PertandinganPesertaKedua)
                            <strong style="{{ $kananMenang ? 'color: #15803d;' : '' }}">{{ $atletPutih->nama ?? 'Atlet Putih' }}</strong>
                            @if($kananMenang)
                                <span class="badge-menang">MENANG</span>
                            @endif
                        @elseif($isBye)
                            <span style="color: #6b7280; font-style: italic;">Belum Ada Lawan (BYE)</span>
                        @else
                            <span style="color: #6b7280; font-style: italic;">{{ $a->getWaitingPlaceholderText('kanan') }}</span>
                        @endif
                    </td>


                    <!-- Skor Putih -->
                    <td class="text-center font-bold" style="font-size: 12px; color: #2563eb;">
                        {{ $a->skor_kanan !== null ? $a->skor_kanan : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #6b7280;">
                        Belum ada data bagan undian pertandingan untuk kelas ini. Silakan lakukan undi bagan terlebih dahulu.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Pengesahan -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                Mengetahui,<br>
                <strong>Koordinator Pertandingan</strong>
                <br><br>
                <div class="sign-line"></div>
                <br>
                ( ____________________________________ )
            </td>
            <td style="width: 50%;">
                <br>
                <strong>Technical Delegate</strong>
                <br><br>
                <div class="sign-line"></div>
                <br>
                ( ____________________________________ )
            </td>
        </tr>
    </table>

</body>
</html>
