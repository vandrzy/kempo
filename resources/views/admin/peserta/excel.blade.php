<style>
    .basic-table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }

    .basic-table th,
    .basic-table td {
        border: 1px solid #ccc;
        padding: 8px 12px;
        text-align: left;
    }

    .basic-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .basic-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .basic-table tr:hover {
        background-color: #e6f7ff;
    }
</style>
<h1>RANGKUMAN IDENTITAS ATLET DAN OFFICIAL</h1>
<h2>Kegiatan : {{ $kegiatan->nama }}</h2>
<table class="basic-table">
    <thead>
        <tr>
            <th rowspan="2" style="text-align: center">No</th>
            <th rowspan="2" style="text-align: center">Nama</th>
            <th rowspan="2" style="text-align: center">NIK</th>
            <th rowspan="2" style="text-align: center">Usia <br> (Thn)</th>
            <th colspan="2" style="text-align: center">Jenis Kelamin</th>
            <th colspan="2" style="text-align: center">Tingkat</th>
            <th colspan="2" style="text-align: center">Status</th>
        </tr>
        <tr>
            <th style="text-align: center">L</th>
            <th style="text-align: center">P</th>
            <th style="text-align: center">K</th>
            <th style="text-align: center">D</th>
            <th style="text-align: center">P</th>
            <th style="text-align: center">O</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($peserta as $a)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $a->nama }}</td>
                <td>"{{ $a->nik }}"</td>
                <td>{{ (Date('Y')) - (Date('Y', strtotime($a->tanggal_lahir))) }} Tahun</td>
                <td colspan="2" style="text-align: center">{{ strtoupper(substr($a->jenis_kelamin, 0, 1)) }} </td>
                <td colspan="2" style="text-align: center">{{ strtoupper(substr($a->tingkat, 0, 1)) }} </td>
                <td colspan="2" style="text-align: center">{{ strtoupper(substr($a->status, 0, 1)) }}  </td>
            </tr>
        @endforeach
    </tbody>
</table>
