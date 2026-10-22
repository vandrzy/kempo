<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Hasil Randori - {{ $listKategori->pertandingan->nama ?? 'Pertandingan' }}</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #111;
            line-height: 1.3;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .header-title {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .header-title h2 {
            margin: 0;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .header-title h3 {
            margin: 2px 0 0 0;
            font-size: 11px;
            font-weight: normal;
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
            width: 15%;
            font-weight: bold;
        }

        .info-colon {
            width: 2%;
        }

        .info-val {
            width: 33%;
        }

        /* Main Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #333;
            padding: 5px 4px;
            vertical-align: middle;
        }

        table.data-table th {
            background-color: #f2f2f2;
            font-size: 9px;
            text-align: center;
            font-weight: bold;
        }

        .sudut-merah {
            background-color: #ffe6e6;
            color: #990000;
        }

        .sudut-putih {
            background-color: #f5f5f5;
            color: #111111;
        }

        /* Juara Section */
        .juara-box {
            border: 1px solid #444;
            padding: 8px 12px;
            margin-bottom: 12px;
            background-color: #fafafa;
        }

        .juara-box h4 {
            margin: 0 0 6px 0;
            font-size: 11px;
            text-transform: uppercase;
        }

        .juara-table {
            width: 100%;
            border-collapse: collapse;
        }

        .juara-table td {
            padding: 2px 4px;
            font-size: 10px;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .sign-line {
            display: inline-block;
            width: 75%;
            border-bottom: 1px solid #222;
            margin-top: 35px;
        }
    </style>
</head>
<body>

    <div class="header-title">
        <h2 class="uppercase">PERSAUDARAAN BELADIRI SHORINJI KEMPO (PERKEMI)</h2>
        <h3>LEMBAR HASIL PERTANDINGAN RANDORI (PERKELAHIAN BEBAS)</h3>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Kegiatan</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">{{ $listKategori->kegiatan->nama ?? '-' }}</td>

            <td class="info-label">Tatami</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">{{ $scoresheet['tatami'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Kategori</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $listKategori->kategori->nama ?? '-' }}</td>

            <td class="info-label">Tanggal</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $scoresheet['tanggal'] ?? date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Nomor / Kelas</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold">{{ $listKategori->pertandingan->nama ?? '-' }} ({{ $listKategori->pertandingan->kelas ?? '-' }})</td>

            <td class="info-label">Jenis Kelamin</td>
            <td class="info-colon">:</td>
            <td class="info-val">{{ $listKategori->pertandingan->jenis_kelamin ?? 'Laki-Laki / Perempuan' }}</td>
        </tr>
        @if(!empty($filteredKabupaten))
        <tr>
            <td class="info-label">Kontingen Khusus</td>
            <td class="info-colon">:</td>
            <td class="info-val font-bold" colspan="4" style="color: #1e40af;">{{ $filteredKabupaten->name }}</td>
        </tr>
        @endif
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">Partai</th>
                <th style="width: 80px;">Babak</th>
                <th style="width: 25%;" class="sudut-merah">Sudut Merah (Aka)</th>
                <th style="width: 45px;">Skor Merah</th>
                <th style="width: 25%;" class="sudut-putih">Sudut Putih (Shiro)</th>
                <th style="width: 45px;">Skor Putih</th>
                <th style="width: 120px;">Pemenang Partai</th>
                <th>Cara / Catatan Kemenangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $matches = $scoresheet['partai'] ?? [];
            @endphp

            @if(!empty($matches))
                @foreach($matches as $row)
                    <tr>
                        <td class="text-center font-bold">{{ $row['nomor'] ?? $loop->iteration }}</td>
                        <td class="text-center">{{ $row['babak'] ?? 'Penyisihan' }}</td>
                        <td class="sudut-merah">
                            <strong>{{ $row['merah_kontingen'] ?? '-' }}</strong><br>
                            <span>{{ $row['merah_nama'] ?? '-' }}</span>
                        </td>
                        <td class="text-center font-bold" style="font-size: 12px;">{{ $row['merah_skor'] ?? '-' }}</td>
                        <td class="sudut-putih">
                            <strong>{{ $row['putih_kontingen'] ?? '-' }}</strong><br>
                            <span>{{ $row['putih_nama'] ?? '-' }}</span>
                        </td>
                        <td class="text-center font-bold" style="font-size: 12px;">{{ $row['putih_skor'] ?? '-' }}</td>
                        <td class="text-center font-bold" style="color: #0b6623;">
                            {{ $row['pemenang'] ?? '-' }}
                        </td>
                        <td class="text-center">
                            {{ $row['cara_menang'] ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            @else
                {{-- Fallback: baris kosong siap isi jika belum disimpan melalui website --}}
                @for($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="text-center font-bold">{{ $i }}</td>
                        <td class="text-center">{{ $i == 5 ? 'Final' : ($i >= 3 ? 'Semifinal' : 'Penyisihan') }}</td>
                        <td class="sudut-merah"></td>
                        <td class="text-center"></td>
                        <td class="sudut-putih"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>

    <div class="juara-box">
        <h4>Hasil Akhir Peraih Medali (Randori)</h4>
        <table class="juara-table">
            <tr>
                <td style="width: 20%;" class="font-bold">Medali Emas (Juara I)</td>
                <td style="width: 2%;">:</td>
                <td style="width: 28%;">
                    <strong>{{ $juara['emas']['kabupaten_nama'] ?? '-' }}</strong>
                    @if(!empty($juara['emas']['peserta_nama'])) ({{ $juara['emas']['peserta_nama'] }}) @endif
                </td>

                <td style="width: 20%;" class="font-bold">Medali Perak (Juara II)</td>
                <td style="width: 2%;">:</td>
                <td style="width: 28%;">
                    <strong>{{ $juara['perak']['kabupaten_nama'] ?? '-' }}</strong>
                    @if(!empty($juara['perak']['peserta_nama'])) ({{ $juara['perak']['peserta_nama'] }}) @endif
                </td>
            </tr>
            <tr>
                <td class="font-bold">Medali Perunggu (Juara III)</td>
                <td>:</td>
                <td colspan="4">
                    <strong>{{ $juara['perunggu']['kabupaten_nama'] ?? '-' }}</strong>
                    @if(!empty($juara['perunggu']['peserta_nama'])) ({{ $juara['perunggu']['peserta_nama'] }}) @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 33%;">
                Wasit Utama<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_1'] ?? '( ............................................... )' }}</span>
            </td>
            <td style="width: 33%;">
                Juri Sudut (Fukushin)<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_2'] ?? '( ............................................... )' }}</span>
            </td>
            <td style="width: 33%;">
                Ketua Pertandingan / Pengawas Tatami<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_kepala'] ?? '( ............................................... )' }}</span>
            </td>
        </tr>
    </table>

</body>
</html>
