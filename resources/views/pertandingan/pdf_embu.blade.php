<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Penilaian {{ (!empty($isRandori)) ? 'Randori' : 'Embu' }} - {{ $listKategori->pertandingan->nama ?? 'Pertandingan' }}</title>
    <style>
        @page {
            margin: 1.2cm 1cm 1.2cm 1cm;
            size: A4 portrait;
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
        .text-red { color: #dc2626; }

        .page-break {
            page-break-after: always;
            break-after: page;
        }

        .header-title {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .header-title h2 {
            margin: 0;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .header-title h3 {
            margin: 3px 0 0 0;
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
        }

        .info-table td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .info-label {
            width: 18%;
            font-weight: bold;
        }

        .info-colon {
            width: 2%;
        }

        .info-val {
            width: 30%;
        }

        /* Main Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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

        .col-score {
            width: 38px;
            text-align: center;
        }

        .col-total {
            width: 65px;
            text-align: center;
            font-weight: bold;
            background-color: #fcfcfc;
        }

        .col-rank {
            width: 80px;
            text-align: center;
            font-weight: bold;
        }

        /* Juara Section */
        .juara-box {
            border: 1px solid #444;
            padding: 8px 12px;
            margin-top: 15px;
            margin-bottom: 15px;
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
            padding: 3px 4px;
            font-size: 10px;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            padding: 0 5px;
        }

        .sign-line {
            display: inline-block;
            width: 85%;
            border-bottom: 1px solid #222;
            margin-top: 35px;
        }
    </style>
</head>
<body>

@php
    $dataBabak1 = $dataBabak1 ?? [];
    $dataBabak2 = $dataBabak2 ?? [];
    $dataNilaiAkhir = $dataNilaiAkhir ?? [];
@endphp

@if(!empty($isRandori))
    {{-- TEMPLATE 1 HALAMAN UNTUK RANDORI --}}
    <div class="header-title">
        <h2 class="uppercase">PERSAUDARAAN BELADIRI SHORINJI KEMPO (PERKEMI)</h2>
        <h3>LEMBAR PENILAIAN PERTANDINGAN RANDORI</h3>
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
            <td class="info-val">{{ $listKategori->pertandingan->jenis_kelamin ?? 'Campuran / Bebas' }}</td>
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
                <th rowspan="2" style="width: 25px;">No.</th>
                <th rowspan="2" style="width: 120px;">Kontingen</th>
                <th rowspan="2">Nama Kenshi / Peserta</th>
                <th colspan="5">Nilai Wasit / Juri</th>
                <th rowspan="2" class="col-score">Denda (Minus)</th>
                <th rowspan="2" class="col-total">Total Nilai</th>
                <th rowspan="2" class="col-rank">Peringkat</th>
            </tr>
            <tr>
                <th class="col-score">J-1</th>
                <th class="col-score">J-2</th>
                <th class="col-score">J-3</th>
                <th class="col-score">J-4</th>
                <th class="col-score">J-5</th>
            </tr>
        </thead>
        <tbody>
            @php
                $entries = $scoresheet['peserta'] ?? [];
            @endphp

            @if(!empty($entries))
                @foreach($entries as $index => $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-bold">{{ $row['kontingen'] ?? '-' }}</td>
                        <td>{{ $row['peserta'] ?? '-' }}</td>
                        <td class="text-center">{{ $row['juri_1'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_2'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_3'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_4'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_5'] ?? '' }}</td>
                        <td class="text-center text-red">{{ $row['hukuman'] ?? '0' }}</td>
                        <td class="col-total">{{ $row['total'] ?? '' }}</td>
                        <td class="col-rank">
                            @if(isset($row['peringkat']) && $row['peringkat'] !== '')
                                Juara {{ $row['peringkat'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                @php
                    $grouped = $peserta_list->groupBy(function($item) {
                        return $item->Peserta->kabupaten->name ?? 'Tanpa Kontingen';
                    });
                @endphp
                @forelse($grouped as $kabName => $items)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-bold">{{ $kabName }}</td>
                        <td>
                            @foreach($items as $it)
                                {{ $it->Peserta->nama ?? '-' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">0</td>
                        <td class="col-total"></td>
                        <td class="col-rank"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Belum ada data peserta untuk kategori ini.</td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>

    <div class="juara-box">
        <h4>Rekapitulasi Peraih Medali / Juara</h4>
        <table class="juara-table">
            <tr>
                <td style="width: 15%;" class="font-bold">Medali Emas (Juara I)</td>
                <td style="width: 2%;">:</td>
                <td style="width: 33%;">
                    <strong>{{ $juara['emas']['kabupaten_nama'] ?? '-' }}</strong>
                    @if(!empty($juara['emas']['peserta_nama'])) ({{ $juara['emas']['peserta_nama'] }}) @endif
                </td>

                <td style="width: 15%;" class="font-bold">Medali Perak (Juara II)</td>
                <td style="width: 2%;">:</td>
                <td style="width: 33%;">
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
            <td style="width: 18%;">
                Juri I<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_1'] ?? '( ......................... )' }}</span>
            </td>
            <td style="width: 18%;">
                Juri II<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_2'] ?? '( ......................... )' }}</span>
            </td>
            <td style="width: 18%;">
                Juri III<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_3'] ?? '( ......................... )' }}</span>
            </td>
            <td style="width: 18%;">
                Juri IV<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_4'] ?? '( ......................... )' }}</span>
            </td>
            <td style="width: 18%;">
                Juri V<br>
                <div class="sign-line"></div>
                <span>{{ $scoresheet['wasit_5'] ?? '( ......................... )' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="padding-top: 15px;">
                Mengetahui,<br>
                <strong>Wasit Kepala / Pengawas Pertandingan</strong>
                <br>
                <div class="sign-line" style="width: 200px;"></div>
                <br>
                <span>{{ $scoresheet['wasit_kepala'] ?? '( ............................................... )' }}</span>
            </td>
        </tr>
    </table>

@else
    {{-- TEMPLATE 3 HALAMAN UNTUK EMBU --}}

    {{-- ======================================================== --}}
    {{-- HALAMAN 1: SCORE SHEET BABAK 1                          --}}
    {{-- ======================================================== --}}
    <div class="page-break">
        <div class="header-title">
            <h2 class="uppercase">PERSAUDARAAN BELADIRI SHORINJI KEMPO (PERKEMI)</h2>
            <h3>LEMBAR PENILAIAN PERTANDINGAN KERAPIAN TEKNIK (EMBUSHO) - BABAK 1</h3>
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
                <td class="info-val">{{ $listKategori->pertandingan->jenis_kelamin ?? 'Campuran / Bebas' }}</td>
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
                    <th rowspan="2" style="width: 25px;">No.</th>
                    <th rowspan="2" style="width: 130px;">Kontingen</th>
                    <th rowspan="2">Nama Kenshi / Peserta</th>
                    <th colspan="5">Nilai Wasit / Juri</th>
                    <th rowspan="2" class="col-score">Denda (Minus)</th>
                    <th rowspan="2" class="col-total">Total Nilai Babak 1</th>
                </tr>
                <tr>
                    <th class="col-score">J-1</th>
                    <th class="col-score">J-2</th>
                    <th class="col-score">J-3</th>
                    <th class="col-score">J-4</th>
                    <th class="col-score">J-5</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataBabak1 as $index => $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-bold">{{ $row['kontingen'] ?? '-' }}</td>
                        <td>{{ $row['nama'] ?? '-' }}</td>
                        <td class="text-center">{{ $row['juri_1'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_2'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_3'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_4'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_5'] ?? '' }}</td>
                        <td class="text-center text-red">{{ $row['hukuman'] ?? '0' }}</td>
                        <td class="col-total">{{ $row['total'] ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada data peserta untuk Babak 1.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ======================================================== --}}
    {{-- HALAMAN 2: SCORE SHEET BABAK 2                          --}}
    {{-- ======================================================== --}}
    <div class="page-break">
        <div class="header-title">
            <h2 class="uppercase">PERSAUDARAAN BELADIRI SHORINJI KEMPO (PERKEMI)</h2>
            <h3>LEMBAR PENILAIAN PERTANDINGAN KERAPIAN TEKNIK (EMBUSHO) - BABAK 2</h3>
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
                <td class="info-val">{{ $listKategori->pertandingan->jenis_kelamin ?? 'Campuran / Bebas' }}</td>
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
                    <th rowspan="2" style="width: 25px;">No.</th>
                    <th rowspan="2" style="width: 130px;">Kontingen</th>
                    <th rowspan="2">Nama Kenshi / Peserta</th>
                    <th colspan="5">Nilai Wasit / Juri</th>
                    <th rowspan="2" class="col-score">Denda (Minus)</th>
                    <th rowspan="2" class="col-total">Total Nilai Babak 2</th>
                </tr>
                <tr>
                    <th class="col-score">J-1</th>
                    <th class="col-score">J-2</th>
                    <th class="col-score">J-3</th>
                    <th class="col-score">J-4</th>
                    <th class="col-score">J-5</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataBabak2 as $index => $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-bold">{{ $row['kontingen'] ?? '-' }}</td>
                        <td>{{ $row['nama'] ?? '-' }}</td>
                        <td class="text-center">{{ $row['juri_1'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_2'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_3'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_4'] ?? '' }}</td>
                        <td class="text-center">{{ $row['juri_5'] ?? '' }}</td>
                        <td class="text-center text-red">{{ $row['hukuman'] ?? '0' }}</td>
                        <td class="col-total">{{ $row['total'] ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Belum ada data peserta untuk Babak 2.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ======================================================== --}}
    {{-- HALAMAN 3: REKAPITULASI NILAI AKHIR, JUARA & TANDA TANGAN--}}
    {{-- ======================================================== --}}
    <div>
        <div class="header-title">
            <h2 class="uppercase">PERSAUDARAAN BELADIRI SHORINJI KEMPO (PERKEMI)</h2>
            <h3>LEMBAR PENILAIAN PERTANDINGAN KERAPIAN TEKNIK (EMBUSHO) - REKAPITULASI NILAI AKHIR</h3>
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
                <td class="info-val">{{ $listKategori->pertandingan->jenis_kelamin ?? 'Campuran / Bebas' }}</td>
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
                    <th style="width: 25px;">No.</th>
                    <th style="width: 140px;">Kontingen</th>
                    <th>Nama Atlet / Anggota</th>
                    <th style="width: 75px;" class="text-center">Nilai Babak 1</th>
                    <th style="width: 75px;" class="text-center">Nilai Babak 2</th>
                    <th style="width: 75px;" class="col-total">Nilai Akhir</th>
                    <th style="width: 85px;" class="col-rank">Juara</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataNilaiAkhir as $index => $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-bold">{{ $row['kontingen'] ?? '-' }}</td>
                        <td>{{ $row['nama'] ?? '-' }}</td>
                        <td class="text-center font-bold">{{ $row['nilai_babak_1'] ?? '-' }}</td>
                        <td class="text-center font-bold">{{ $row['nilai_babak_2'] ?? '-' }}</td>
                        <td class="col-total">{{ $row['nilai_akhir'] ?? '-' }}</td>
                        <td class="col-rank">
                            @if(!empty($row['juara']) && $row['juara'] !== '-')
                                <span style="{{ str_contains($row['juara'], 'Juara 1') ? 'color: #b45309; font-weight: bold;' : (str_contains($row['juara'], 'Juara 2') ? 'color: #4b5563; font-weight: bold;' : (str_contains($row['juara'], 'Juara 3') ? 'color: #78350f; font-weight: bold;' : '')) }}">
                                    {{ $row['juara'] }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada rekapitulasi nilai akhir untuk kategori ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Bagian Rekap Peraih Medali HANYA DI HALAMAN 3 --}}
        <div class="juara-box">
            <h4>Rekapitulasi Peraih Medali / Juara</h4>
            <table class="juara-table">
                <tr>
                    <td style="width: 15%;" class="font-bold">Medali Emas (Juara I)</td>
                    <td style="width: 2%;">:</td>
                    <td style="width: 33%;">
                        <strong>{{ $juara['emas']['kabupaten_nama'] ?? '-' }}</strong>
                        @if(!empty($juara['emas']['peserta_nama'])) ({{ $juara['emas']['peserta_nama'] }}) @endif
                    </td>

                    <td style="width: 15%;" class="font-bold">Medali Perak (Juara II)</td>
                    <td style="width: 2%;">:</td>
                    <td style="width: 33%;">
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

        {{-- Blok Tanda Tangan Wasit HANYA DI HALAMAN 3 --}}
        <table class="signature-table">
            <tr>
                <td style="width: 18%;">
                    Juri I<br>
                    <div class="sign-line"></div>
                    <span>{{ $scoresheet['wasit_1'] ?? '( ......................... )' }}</span>
                </td>
                <td style="width: 18%;">
                    Juri II<br>
                    <div class="sign-line"></div>
                    <span>{{ $scoresheet['wasit_2'] ?? '( ......................... )' }}</span>
                </td>
                <td style="width: 18%;">
                    Juri III<br>
                    <div class="sign-line"></div>
                    <span>{{ $scoresheet['wasit_3'] ?? '( ......................... )' }}</span>
                </td>
                <td style="width: 18%;">
                    Juri IV<br>
                    <div class="sign-line"></div>
                    <span>{{ $scoresheet['wasit_4'] ?? '( ......................... )' }}</span>
                </td>
                <td style="width: 18%;">
                    Juri V<br>
                    <div class="sign-line"></div>
                    <span>{{ $scoresheet['wasit_5'] ?? '( ......................... )' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="padding-top: 15px;">
                    Mengetahui,<br>
                    <strong>Wasit Kepala / Pengawas Pertandingan</strong>
                    <br>
                    <div class="sign-line" style="width: 200px;"></div>
                    <br>
                    <span>{{ $scoresheet['wasit_kepala'] ?? '( ............................................... )' }}</span>
                </td>
            </tr>
        </table>
    </div>
@endif

</body>
</html>
