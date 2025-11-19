{{-- resources/views/pdf/table.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>{{ $title ?? 'Laporan' }}</title>

  <style>
    /* DOMPDF: gunakan font yang mendukung UTF-8 seperti DejaVu Sans */
    @font-face {
      font-family: "DejaVuSans";
      src: url("file:///usr/share/fonts/truetype/dejavu/DejaVuSans.ttf");
    }

    /* Page setup */
    @page {
        margin-top: 120px; /* ruang untuk header */
        margin-bottom: 50px;
    }

    header {
        position: fixed;
        top: -80px; /* sesuaikan agar pas */
        left: 0;
        right: 0;
        height: 50px;
        padding-bottom: 10px;
        border-bottom: 1px solid #000;
    }

    body {
      font-family: DejaVuSans, sans-serif;
      font-size: 12px;
      color: #222;
      margin: 0;
      padding: 0;
    }


    footer {
      position: fixed;
      bottom: -20px;
      left: 0;
      right: 0;
      height: 40px;
      border-top: 1px solid #ccc;
      text-align: right;
      font-size: 11px;
      padding: 8px 0;
    }

    .container {
      margin: 10px 0 60px; /* beri ruang untuk header & footer */
      padding: 0 2px;
    }

    h1 {
      font-size: 16px;
      margin: 0 0 8px;
    }

    .meta {
      font-size: 11px;
      color: #555;
      margin-bottom: 12px;
    }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: left;
            padding: 6px;
        }

        td {
            padding: 6px;
        }

        /* garis header tabel lebih tebal */
        th {
            border-bottom: 2px solid #000;
        }

    .small {
      font-size: 11px;
      color: #666;
    }

    /* Jika tabel panjang: izinkan pemecahan halaman pada baris */
    tr { page-break-inside: avoid; }
  </style>
</head>
<body>
  <header>
    <div style="display:flex; justify-content:space-between; align-items:center;">
      <div>
        <strong>{{ $company ?? 'Pendaftaran Kempo' }}</strong><br>
        {{-- <span class="small">{{ $company_address ?? 'Alamat perusahaan' }}</span> --}}
      </div>
      <div style="text-align:right;">
        <strong>{{ $title ?? 'Laporan' }}</strong><br>
        <span class="small">Tanggal: {{ $date ?? date('Y-m-d') }}</span>
      </div>
    </div>
  </header>

  {{-- <footer>
    <div style="padding-right:10px;">
      Halaman {{ '{PAGE_NUM}' }} / {{ '{PAGE_COUNT}' }}
    </div>
  </footer> --}}

  <div class="container">
    <h1>{{ $title ?? 'Laporan' }} Kegiatan {{ $kegiatan->nama }}</h1>
    <div class="meta">
        @if($_GET['print'] == "peserta")
            {{ $subtitle ?? 'Peserta dari '.$kabupaten->name }}

        @endif

        @if($_GET['print'] == "official")
            {{ $subtitle ?? 'Official dari '.$kabupaten->name }}

        @endif
    </div>

    {{-- <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
        <thead class="align-bottom">
            <tr>
                <th rowspan="2" class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-4xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama</th>
                <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">NIK</th>
                <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Usia (Tahun)</th>
                <th colspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Jenis Kelamin</th>
                <th colspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tingkat</th>
                <th colspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
            </tr>
            <tr>
                <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">L</th>
                <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">P</th>
                <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">K</th>
                <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">D</th>
                <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">P</th>
                <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">O</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peserta as $a)
                <tr>
                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $loop->iteration }}</p>

                    </td>
                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->nama }}</p>
                    </td>
                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->nik }}</p>
                    </td>
                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ (Date('Y')) - (Date('Y', strtotime($a->tanggal_lahir))) }} Tahun </p>
                    </td>
                    <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                            @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "L")
                                <span style="font-family: DejaVu Sans; font-size: 24px; color: green;">&#10003;</span>
                            @endif
                        </p>
                    </td>
                    <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                            @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "P")
                                <span style="font-family: DejaVu Sans; font-size: 24px; color: green;">&#10003;</span>
                            @endif
                        </p>
                    </td>
                    <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                            @if(strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "K")
                                <span style="font-family: DejaVu Sans; font-size: 24px; color: green;">&#10003;</span>
                            @endif
                        </p>
                    </td>
                    <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                            @if(strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "D")
                                <span style="font-family: DejaVu Sans; font-size: 24px; color: green;">&#10003;</span>
                            @endif
                        </p>
                    </td>
                    <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                            @if(strtoupper(substr($a->status, 0, 1)) == "P")
                                <span style="font-family: DejaVu Sans; font-size: 24px; color: green;">&#10003;</span>
                            @endif
                        </p>
                    </td>
                    <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                        <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                            @if(strtoupper(substr($a->status, 0, 1)) == "O")
                                <span style="font-family: DejaVu Sans; font-size: 24px; color: green;">&#10003;</span>
                            @endif
                        </p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="text-align: center;">No</th>
                <th rowspan="2" style="text-align: center;">Nama</th>
                <th rowspan="2" style="text-align: center;">NIK</th>
                <th rowspan="2" style="text-align: center;">Usia <br>Tahun</th>
                <th colspan="2" style="text-align: center;">Jenis Kelamin</th>
                <th colspan="2" style="text-align: center;">Tingkat</th>
                <th colspan="2" style="text-align: center;">Status</th>
            </tr>
            <tr>
                <th style="text-align: center;">L</th>
                <th style="text-align: center;">P</th>
                <th style="text-align: center;">K</th>
                <th style="text-align: center;">D</th>
                <th style="text-align: center;">P</th>
                <th style="text-align: center;">O</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($peserta as $a)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $a->nama }}</td>
                <td>{{ $a->nik }}</td>
                <td style="text-align: center;">{{ (Date('Y')) - (Date('Y', strtotime($a->tanggal_lahir))) }} Tahun </td>
                <td style="text-align: center;">
                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "L")
                        <span style="font-family: DejaVu Sans; font-size: 24px; color: black;">&#10003;</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "P")
                        <span style="font-family: DejaVu Sans; font-size: 24px; color: black;">&#10003;</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if(strtoupper(substr($a->tingkatan_sabuk ?? $a->tingkat, 0, 1)) == "K")
                        <span style="font-family: DejaVu Sans; font-size: 24px; color: black;">&#10003;</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if(strtoupper(substr($a->tingkatan_sabuk ?? $a->tingkat, 0, 1)) == "D")
                        <span style="font-family: DejaVu Sans; font-size: 24px; color: black;">&#10003;</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if(strtoupper(substr($a->status, 0, 1)) == "P")
                        <span style="font-family: DejaVu Sans; font-size: 24px; color: black;">&#10003;</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if(strtoupper(substr($a->status, 0, 1)) == "O")
                        <span style="font-family: DejaVu Sans; font-size: 21px; color: black;">&#10003;</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
  </div>
</body>
</html>


