<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Nomor Tanding</title>
  <style>
    body { font-family: sans-serif; font-size: 12pt; }
    .container { width: 100%; }
    .pair { width: 100%; margin-bottom: 20px; }
    .card {
        display: inline-block;
        vertical-align: top;
        border: 3px solid black;
        border-radius: 12px;
        width: 280px;
        height: 380px;
        margin-right: 20px;
        text-align: center;
        padding: 20px;
        page-break-inside: avoid;
    }
    .title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
    .number { font-size: 80px; font-weight: bold; margin: 40px 0; }
    .category { font-size: 16px; }
  </style>
  <style>
  h4 {
    font-size: 14px;
    margin-bottom: 10px;
  }
  .peserta-wrapper {
    margin-top: 10px;
  }
  .peserta {
    width: 150px;
    padding: 6px;
    margin-bottom: 6px;
    text-align: center;
    border-radius: 6px;
    font-size: 12px;
  }
  .peserta.merah {
    background-color: rgb(237, 162, 162);
    color: black;
  }
  .peserta.biru {
    background-color: rgb(116, 202, 234);
    color: black;
  }
  hr {
    border: 0;
    border-top: 1px solid #ccc;
    margin: 8px 0;
  }
  .pagination {
    margin-top: 20px;
    text-align: center;
  }
      .page-break {
        page-break-after: always;
    }

</style>

</head>
<body>
    <div class="page_pertama">
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
    </div>

      <!-- Pindah halaman -->
  <div class="page-break"></div>
  <div class="container">
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
  </div>
</body>
</html>
