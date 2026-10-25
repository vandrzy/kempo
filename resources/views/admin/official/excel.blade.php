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
<h1>RANGKUMAN IDENTITAS OFFICIAL</h1>
<h2>Kegiatan : {{ $kegiatan->nama ?? 'Semua Kegiatan' }}</h2>
<table class="basic-table">
    <thead>
        <tr>
            <th rowspan="2" style="text-align: center">No</th>
            <th rowspan="2" style="text-align: center">Kontingen</th>
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
            @php
                $sabuk = $a->tingkatan_sabuk ?? $a->tingkat ?? '';
                $jk = strtoupper(substr($a->jenis_kelamin ?? '', 0, 1));
                $tingkatK = strtoupper(substr($sabuk, 0, 1)) === 'K';
                $tingkatD = strtoupper(substr($sabuk, 0, 1)) === 'D';
                $statusP = strtoupper(substr($a->status ?? '', 0, 1)) === 'P';
                $statusO = strtoupper(substr($a->status ?? 'O', 0, 1)) === 'O';
                $usia = !empty($a->tanggal_lahir) ? ((int)date('Y') - (int)date('Y', strtotime($a->tanggal_lahir))) : '-';
            @endphp
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $a->kabupaten->name ?? '-' }}</td>
                <td>{{ $a->nama }}</td>
                <td style="mso-number-format:'\@';">'{{ $a->nik }}</td>
                <td style="text-align: center;">{{ $usia !== '-' ? $usia . ' Tahun' : '-' }}</td>
                <td style="text-align: center">{{ $jk === 'L' ? 'V' : '' }}</td>
                <td style="text-align: center">{{ $jk === 'P' ? 'V' : '' }}</td>
                <td style="text-align: center">{{ $tingkatK ? 'V' : '' }}</td>
                <td style="text-align: center">{{ $tingkatD ? 'V' : '' }}</td>
                <td style="text-align: center">{{ $statusP ? 'V' : '' }}</td>
                <td style="text-align: center">{{ $statusO ? 'V' : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
