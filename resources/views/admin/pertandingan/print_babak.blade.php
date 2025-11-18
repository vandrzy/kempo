<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Penilaian Wasit Shorinji Kempo</title>
    <style>
        /* Setup Halaman A4 */
        @page {
            margin: 1cm;
            size: A4 portrait;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
        }

        /* Utilitas Umum */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .w-full { width: 100%; }
        .mb-10 { margin-bottom: 10px; }
        .mt-5 { margin-top: 5px; }

        /* Struktur Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td, th {
            padding: 4px;
            vertical-align: middle;
        }

        /* Tabel dengan Border (Main Form) */
        .table-bordered td,
        .table-bordered th {
            border: 1px solid #000;
        }

        /* Header Layout */
        .header-table td {
            border: none;
            vertical-align: top;
        }

        .box-no-urut {
            width: 100px;
            height: 60px;
            border: 2px solid #000;
            position: relative;
        }
        .box-no-urut span {
            position: absolute;
            top: 2px;
            left: 2px;
            font-size: 10px;
        }

        .header-title {
            padding-left: 20px;
        }
        .header-title h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header-info {
            margin-top: 5px;
        }

        /* Metadata Row (Hari, Tanggal, dll) */
        .meta-table td {
            border: none;
            padding: 5px 0;
        }

        /* Styling Khusus Kolom */
        .col-no { width: 30px; text-align: center; }
        .col-desc { width: auto; }
        .col-score-ref { width: 60px; text-align: center; font-size: 10px; }
        .col-sub-idx { width: 30px; text-align: center; }
        .col-input { width: 80px; }
        .col-std { width: 40px; text-align: center; }

        /* Teks Vertikal (HACK untuk Dompdf) */
        .vertical-text-wrapper {
            height: 150px; /* Tinggi area vertikal */
            width: 20px;
            position: relative;
            overflow: hidden;
            display: block;
            margin: 0 auto;
        }

        .vertical-text {
            transform: rotate(-90deg);
            transform-origin: center center;
            white-space: nowrap;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px; /* Harus lebar agar teks tidak wrap saat diputar */
            margin-left: -100px; /* Setengah width */
            text-align: center;
            font-weight: bold;
        }

        /* Footer Section */
        .footer-section {
            margin-top: 20px;
        }
        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            width: 80%;
        }

        .signature-table td {
            border: none;
            vertical-align: top;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 30px;
            width: 200px;
        }

        /* Helper untuk layout form scoring */
        .bg-gray { background-color: #f0f0f0; } /* Opsional jika ingin shading */
        .small-note { font-size: 9px; font-style: italic; }

        .page-break {
            page-break-after: always;
        }

    </style>
</head>
<body>

    <div class="kolom_1">
        <table class="header-table">
            <tr>
                <td style="width: 110px;">
                    <center>
                        <span style="">No. Urut</span>
                    </center>

                    <div class="box-no-urut">
                        <div style="transform: translateY(-25px)">
                            <center>
                                <h1 style="font-size: 40px;">{{ $kolom1->undian + 1 }}</h1>
                            </center>
                        </div>
                    </div>
                </td>
                <td class="header-title">
                    <h2>DAFTAR PENILAIAN WASIT</h2>
                    <h2>{{ $tanding->pertandingan->nama }}</h2>
                    <div class="header-info">
                        <strong>KONTINGEN : {{ $kabupaten_first->name ?? "Bye" }}</strong>
                    </div>
                    <div class="header-info" style="margin-top: 10px;">
                        BABAK : <u>1. Penyisihan / Pertama*</u> &nbsp;&nbsp;&nbsp; 2. Final / Kedua*
                    </div>
                </td>
            </tr>
        </table>

        <table class="meta-table" style="width: 90%;">
            <tr>
                <td width="10%">Hari :</td>
                <td width="13%">Tanggal :</td>
                <td width="10%">Pool : ..............</td>
                <td width="10%" class="text-right">Court : ..............</td>
            </tr>
        </table>

        @if ($pesertas->count() == 0)
            {{-- <h5 class="m-4" style="text-align: center">Peserta tidak tersedia!</h5> --}}
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="col-no" style="width: 15%;">No. Urut</th>
                        <th>Urutan Komposisi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="text-center">1</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">2</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">3</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">4</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">5</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">6</td><td>&nbsp;</td></tr>
                </tbody>
            </table>

        @else
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="col-no" style="width: 15%;">No. Urut</th>
                        <th>Urutan Komposisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesertas as $peserta)
                        @php
                            $kategoriList = $peserta->PertandinganPeserta->pluck('list_kategori.kategori')->unique()->values();
                            $pertandingan = $peserta->PertandinganPeserta->pluck('list_kategori.pertandingan')->unique()->values();
                        @endphp
                        @php
                            $kategori_id = 0;
                            $urutan = 0;
                            $peserta_list = $loop->iteration;

                        @endphp
                        @foreach ($peserta->PertandinganPeserta as $item)
                            @if($peserta_list == 1)
                                @php
                                    if($item->list_kategori->kategori->id != $kategori_id) {
                                        $kategori_id = $item->list_kategori->kategori->id;
                                    }
                                @endphp
                                @if (@$item->UrutanKomposisiPeserta->urutan_komposisi)
                                    @php
                                        $kelas = explode(",",$item->UrutanKomposisiPeserta->urutan_komposisi);
                                        $value_kelas = [];
                                        for($i = 0; $i < 6; $i++) {
                                            # code...
                                            if(isset($kelas[$i])) {
                                                $value_kelas[] = $kelas[$i];
                                            } else {
                                                $value_kelas[] = "";
                                            }
                                        }
                                    @endphp
                                    @foreach ($value_kelas as $index => $a)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $a }}</td>
                                        </tr>

                                        {{-- <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}"  id="urutan_tanding_{{ $urutan }} urutan_tanding" class="urutan_tandings dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id=""> --}}
                                    @endforeach
                                    {{-- @foreach ($kelas as $a)
                                        @if($a != "")
                                        @endif
                                    @endforeach --}}
                                @endif
                            @endif
                            @php
                                $urutan = $urutan + 1;
                            @endphp
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
        {{-- <table class="table-bordered">
            <thead>
                <tr>
                    <th class="col-no" style="width: 15%;">No. Urut</th>
                    <th>Urutan Komposisi</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="text-center">1</td><td>&nbsp;</td></tr>
                <tr><td class="text-center">2</td><td>&nbsp;</td></tr>
                <tr><td class="text-center">3</td><td>&nbsp;</td></tr>
                <tr><td class="text-center">4</td><td>&nbsp;</td></tr>
                <tr><td class="text-center">5</td><td>&nbsp;</td></tr>
                <tr><td class="text-center">6</td><td>&nbsp;</td></tr>
            </tbody>
        </table> --}}

        <div class="text-right small-note" style="margin-bottom: 2px; padding-right: 5px;">standard</div>

        <table class="table-bordered">
            <tr>
                <td rowspan="7" style="width: 100px; background-color: #fff;">
                    <div class="vertical-text-wrapper">
                        <div class="vertical-text">Penguasaan Teknik (60)</div>
                    </div>
                </td>
                <td rowspan="6" style="vertical-align: top; padding: 10px; width: 40%;">
                    GOHO : Serangan, bertahan, serangan balasan, lima unsur serangan dan lain-lain
                    <br><br><br>
                    JUHO : Shuha, nukiwaza, gyaku waza, nage waza, katame waza dan lain-lain
                </td>
                <td rowspan="6" class="text-center" style="width:10%;">
                    <span style="font-size: 12px;">60</span><br>
                    <span class="small-note">(Masing-2 10)</span>
                </td>
                <td class="text-center" style="width:10%;">1</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">5</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">6</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td colspan="1" class="no-border"></td>
                <td colspan="3" class="text-center">Sub Total-1</td>
                <td class="text-center">48</td>
            </tr>

            <tr>
                <td rowspan="6" style="width: 30px;">
                    <div class="vertical-text-wrapper" style="height: 100px;">
                        <div class="vertical-text">Ekspresi (40)</div>
                    </div>
                </td>
                <td>1. Rangkaian, Irama, Harmoni</td>
                <td></td>
                <td rowspan="4" class="text-center">
                    <span style="font-size: 12px;">40</span><br>
                    <span class="small-note">(Masing-2 10)</span>
                </td>

                <td></td> <td class="text-center">8</td>
            </tr>
            <tr>
                <td>2. Tai gamae, Kuda-kuda, Keindahan</td>
                <td></td>

                <td colspan="1"></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td>3. Energi, kiai, ketrampilan</td>
                <td></td>

                <td colspan="1"></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td>4. Nafas, pandangan mata, zanshin</td>
                <td></td>

                <td colspan="1"></td>
                <td class="text-center">8</td>
            </tr>

            <tr>
                <td colspan="1" rowspan="2" class="no-border"></td>
                <td colspan="2" class="text-center">Sub Total-2</td>
                <td></td>
                <td class="text-center">32</td>
            </tr>

            <tr>
                <td colspan="2" class="text-center text-bold">Total</td>
                <td></td>
                <td class="text-center text-bold">80</td>
            </tr>
            <tr>
                <td colspan="1" class="text-center text-bold">Note</td>
                <td colspan="5"></td>

            </tr>
        </table>

        <table class="signature-table" style="margin-top: 20px;">
            <tr>
                <td width="50%">
                    <strong>Catatan Wasit :</strong><br>
                    1. <span class="dotted-line"></span><br>
                    {{-- <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br> --}}
                    <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br>
                    2. <span class="dotted-line"></span><br>
                    {{-- <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br> --}}
                    <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br>
                    3. <span class="dotted-line"></span><br>
                    <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span>
                </td>
                <td width="50%" style="padding-left: 50px;">
                    Wasit Penilai : I, II, III, IV, V**
                    <br><br><br><br><br>
                    <div class="signature-line"></div>
                    <table style="width: 100%; margin-top: 3px;">
                        <tr>
                            <td width="30">Nama</td>
                            <td>: </td>
                        </tr>
                        <tr>
                            <td>Tingkat</td>
                            <td>: </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div style="margin-top: 20px; font-size: 10px;">
            * Coret yang tidak perlu/berlaku<br>
            ** Lingkari angka yang berlaku dan coret yang tidak perlu/berlaku
        </div>
    </div>

    <div class="page-break"></div>

    <div class="kolom_2">
        <table class="header-table">
            <tr>
                <td style="width: 110px;">
                    <center>
                        <span style="">No. Urut</span>
                    </center>

                    <div class="box-no-urut">
                        <div style="transform: translateY(-25px)">
                            <center>
                                <h1 style="font-size: 40px;">{{ $kolom2->undian + 1 }}</h1>
                            </center>
                        </div>
                    </div>
                </td>
                <td class="header-title">
                    <h2>DAFTAR PENILAIAN WASIT</h2>
                    <h2>{{ $tanding->pertandingan->nama }}</h2>
                    <div class="header-info">
                        <strong>KONTINGEN : {{ $kabupaten_first->name ?? "Bye" }}</strong>
                    </div>
                    <div class="header-info" style="margin-top: 10px;">
                        BABAK : 1. Penyisihan / Pertama* &nbsp;&nbsp;&nbsp; <u>2. Final / Kedua*</u>
                    </div>
                </td>
            </tr>
        </table>

        <table class="meta-table" style="width: 90%;">
            <tr>
                <td width="10%">Hari :</td>
                <td width="13%">Tanggal :</td>
                <td width="10%">Pool : ..............</td>
                <td width="10%" class="text-right">Court : ..............</td>
            </tr>
        </table>

        @if ($pesertas->count() == 0)
            {{-- <h5 class="m-4" style="text-align: center">Peserta tidak tersedia!</h5> --}}
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="col-no" style="width: 15%;">No. Urut</th>
                        <th>Urutan Komposisi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="text-center">1</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">2</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">3</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">4</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">5</td><td>&nbsp;</td></tr>
                    <tr><td class="text-center">6</td><td>&nbsp;</td></tr>
                </tbody>
            </table>

        @else
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="col-no" style="width: 15%;">No. Urut</th>
                        <th>Urutan Komposisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesertas as $peserta)
                        @php
                            $kategoriList = $peserta->PertandinganPeserta->pluck('list_kategori.kategori')->unique()->values();
                            $pertandingan = $peserta->PertandinganPeserta->pluck('list_kategori.pertandingan')->unique()->values();
                        @endphp
                        @php
                            $kategori_id = 0;
                            $urutan = 0;
                            $peserta_list = $loop->iteration;

                        @endphp
                        @foreach ($peserta->PertandinganPeserta as $item)
                            @if($peserta_list == 1)
                                @php
                                    if($item->list_kategori->kategori->id != $kategori_id) {
                                        $kategori_id = $item->list_kategori->kategori->id;
                                    }
                                @endphp
                                @if (@$item->UrutanKomposisiPeserta->urutan_komposisi)
                                    @php
                                        $kelas = explode(",",$item->UrutanKomposisiPeserta->urutan_komposisi);
                                        $value_kelas = [];
                                        for($i = 0; $i < 6; $i++) {
                                            # code...
                                            if(isset($kelas[$i])) {
                                                $value_kelas[] = $kelas[$i];
                                            } else {
                                                $value_kelas[] = "";
                                            }
                                        }
                                    @endphp
                                    @foreach ($value_kelas as $index => $a)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $a }}</td>
                                        </tr>

                                        {{-- <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}"  id="urutan_tanding_{{ $urutan }} urutan_tanding" class="urutan_tandings dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id=""> --}}
                                    @endforeach
                                    {{-- @foreach ($kelas as $a)
                                        @if($a != "")
                                        @endif
                                    @endforeach --}}
                                @endif
                            @endif
                            @php
                                $urutan = $urutan + 1;
                            @endphp
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="text-right small-note" style="margin-bottom: 2px; padding-right: 5px;">standard</div>

        <table class="table-bordered">
            <tr>
                <td rowspan="7" style="width: 100px; background-color: #fff;">
                    <div class="vertical-text-wrapper">
                        <div class="vertical-text">Penguasaan Teknik (60)</div>
                    </div>
                </td>
                <td rowspan="6" style="vertical-align: top; padding: 10px; width: 40%;">
                    GOHO : Serangan, bertahan, serangan balasan, lima unsur serangan dan lain-lain
                    <br><br><br>
                    JUHO : Shuha, nukiwaza, gyaku waza, nage waza, katame waza dan lain-lain
                </td>
                <td rowspan="6" class="text-center" style="width:10%;">
                    <span style="font-size: 12px;">60</span><br>
                    <span class="small-note">(Masing-2 10)</span>
                </td>
                <td class="text-center" style="width:10%;">1</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">5</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td class="text-center">6</td>
                <td></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td colspan="1" class="no-border"></td>
                <td colspan="3" class="text-center">Sub Total-1</td>
                <td class="text-center">48</td>
            </tr>

            <tr>
                <td rowspan="6" style="width: 30px;">
                    <div class="vertical-text-wrapper" style="height: 100px;">
                        <div class="vertical-text">Ekspresi (40)</div>
                    </div>
                </td>
                <td>1. Rangkaian, Irama, Harmoni</td>
                <td></td>
                <td rowspan="4" class="text-center">
                    <span style="font-size: 12px;">40</span><br>
                    <span class="small-note">(Masing-2 10)</span>
                </td>

                <td></td> <td class="text-center">8</td>
            </tr>
            <tr>
                <td>2. Tai gamae, Kuda-kuda, Keindahan</td>
                <td></td>

                <td colspan="1"></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td>3. Energi, kiai, ketrampilan</td>
                <td></td>

                <td colspan="1"></td>
                <td class="text-center">8</td>
            </tr>
            <tr>
                <td>4. Nafas, pandangan mata, zanshin</td>
                <td></td>

                <td colspan="1"></td>
                <td class="text-center">8</td>
            </tr>

            <tr>
                <td colspan="1" rowspan="2" class="no-border"></td>
                <td colspan="2" class="text-center">Sub Total-2</td>
                <td></td>
                <td class="text-center">32</td>
            </tr>

            <tr>
                <td colspan="2" class="text-center text-bold">Total</td>
                <td></td>
                <td class="text-center text-bold">80</td>
            </tr>
            <tr>
                <td colspan="1" class="text-center text-bold">Note</td>
                <td colspan="5"></td>

            </tr>
        </table>

        <table class="signature-table" style="margin-top: 20px;">
            <tr>
                <td width="50%">
                    <strong>Catatan Wasit :</strong><br>
                    1. <span class="dotted-line"></span><br>
                    {{-- <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br> --}}
                    <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br>
                    2. <span class="dotted-line"></span><br>
                    {{-- <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br> --}}
                    <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span><br>
                    3. <span class="dotted-line"></span><br>
                    <span class="dotted-line" style="width: 90%; margin-left: 15px; margin-top: 5px;"></span>
                </td>
                <td width="50%" style="padding-left: 50px;">
                    Wasit Penilai : I, II, III, IV, V**
                    <br><br><br><br><br>
                    <div class="signature-line"></div>
                    <table style="width: 100%; margin-top: 3px;">
                        <tr>
                            <td width="30">Nama</td>
                            <td>: </td>
                        </tr>
                        <tr>
                            <td>Tingkat</td>
                            <td>: </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div style="margin-top: 20px; font-size: 10px;">
            * Coret yang tidak perlu/berlaku<br>
            ** Lingkari angka yang berlaku dan coret yang tidak perlu/berlaku
        </div>
    </div>
</body>
</html>
