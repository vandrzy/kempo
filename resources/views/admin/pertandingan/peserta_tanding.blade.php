<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Nomor Tanding</title>
<style>
.table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  font-size: 12px;
  margin-bottom: 1rem;
}

.table thead {
  display: table-header-group; /* penting: header tabel muncul di setiap halaman */
}

.table tfoot {
  display: table-footer-group;
}

.table th,
.table td {
  padding: 8px 10px;
  border: 1px solid #444;
  text-align: left;
  vertical-align: middle;
}

.table thead th {
  background: #f3f4f6;
  font-weight: bold;
}

.table tbody tr:nth-child(even) td {
  background: #fbfbfb;
}

.table tr {
  page-break-inside: avoid; /* jangan pecah row */
}
</style>

</head>
<body>
    <h4 class="dark:text-white">{{ $pertandingan->kegiatan->nama }} / {{ $pertandingan->pertandingan->nama }} / Tanding</h4>
    <table class="table">
        <thead>
            <tr>
                <th rowspan="2" style="text-align: center">No</th>
                <th rowspan="2" style="text-align: center">Nama</th>
                <th colspan="2" style="text-align: center">Tingkat</th>
                <th rowspan="2" style="text-align: center">Kelas</th>
                <th rowspan="2" style="text-align: center">Berat Badan</th>
            </tr>
            <tr>
                <th style="text-align: center">KYU</th>
                <th style="text-align: center">DAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peserta_lawan as $a)
                <tr>
                    <td>{{ $a->PertandinganPesertaPertama->urutan }}</td>
                    <td style="text-align: center">{{ $a->PertandinganPesertaPertama->Peserta->nama }}</td>
                    <td style="text-align: center">{{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "V" : ''}}</td>
                    <td style="text-align: center">{{ strtoupper(substr($a->PertandinganPesertaPertama->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? 'V' : ''}}</td>
                    <td style="text-align: center">{{ $a->PertandinganPesertaPertama->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas }}</td>
                    <td style="text-align: center">{{ $a->PertandinganPesertaPertama->Peserta->berat_badan }} Kg</td>
                </tr>
                <tr>
                    <td>{{ @$a->PertandinganPesertaKedua->urutan ?? 0 }}</td>
                    <td style="text-align: center">{{ @$a->PertandinganPesertaKedua->Peserta->nama ?? "WO" }}</td>
                    <td style="text-align: center">{{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "K" ? "V" : ''}}</td>
                    <td style="text-align: center">{{ strtoupper(substr(@$a->PertandinganPesertaKedua->Peserta->tingkatan_sabuk , 0, 1)) == "D" ? 'V' : ''}}</td>
                    <td style="text-align: center">{{ @$a->PertandinganPesertaKedua->Peserta->PertandinganPesertaUtama->list_kategori->pertandingan->kelas ?? ""}}</td>
                    <td style="text-align: center">{{ @$a->PertandinganPesertaKedua->Peserta->berat_badan ?? ""}} Kg</td>
                </tr>
                {{-- <div style="background-color: rgb(237, 162, 162); color: black;" class="bg-red-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaPertama->urutan }} | {{ $a->PertandinganPesertaPertama->Peserta->nama }}</div>
                <div style="background-color: rgb(116, 202, 234); color: black;" class="bg-blue-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaKedua->urutan ?? '0' }} | {{ $a->PertandinganPesertaKedua->Peserta->nama ?? 'WO' }}</div> --}}

            @endforeach
        </tbody>
    </table>
    {{-- <div class="page_pertama">
        <h4>Peserta tersedia : {{ $peserta_ready }}</h4>
        <div class="peserta-wrapper">
            @foreach ($peserta_lawan as $a)
                <div class="peserta merah">
                    {{ $a->PertandinganPesertaPertama->urutan }}
                    | {{ $a->PertandinganPesertaPertama->Peserta->nama }}
                </div>

                <div class="peserta biru">
                    {{ $a->PertandinganPesertaKedua->urutan ?? '0' }}
                    | {{ $a->PertandinganPesertaKedua->Peserta->nama ?? 'WO' }}
                </div>

                <hr>
            @endforeach
        </div>

        <div class="pagination">
            {{ $peserta_lawan->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
    </div> --}}

      <!-- Pindah halaman -->
  {{-- <div class="page-break"></div> --}}
  {{-- <div class="container">
    @foreach ($peserta_lawan as $a)
      <div class="pair">
        <div class="card">
          <div class="title">{{ $pertandingan->kegiatan->nama }}</div>
          <div class="number">{{ $a->PertandinganPesertaPertama->urutan }}</div>
          <div class="category">Kategori:<br>{{ $pertandingan->pertandingan->nama }}</div>
        </div>

        <div class="card">
          <div class="title">{{ $pertandingan->kegiatan->nama }}</div>
          <div class="number">{{ $a->PertandinganPesertaKedua->urutan ?? '0' }}</div>
          <div class="category">Kategori:<br>{{ $pertandingan->pertandingan->nama }}</div>
        </div>
      </div>
    @endforeach
  </div> --}}
</body>
</html>
