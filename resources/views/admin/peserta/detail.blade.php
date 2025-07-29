@extends('tamplate')
@section('title', 'Detail Peserta')
@section('content')
    <h1 class="text-3xl font-bold mb-2">Detail Peserta</h1>
    <hr class="border-t border-gray-300 mb-4">

    <div class="flex justify-between mb-4">
        <div>
            <a href="{{ url('/peserta') }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">Kembali</a>
        </div>
        <div></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <img src="{{ @$peserta ? url($peserta->foto) : '' }}" alt="Foto Peserta"
                class="w-3/4 border border-black rounded shadow" id="preview">
        </div>

        <div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <tbody>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Nama</th>
                            <td class="px-6 py-4 border-b">{{ $peserta->nama }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Jenis Kelamin</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Golongan Darah</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->golongan_darah }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">NIK</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->nik }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Tingkat</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->tingkat }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Status</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->status }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Tempat, Tanggal Lahir</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->tempat }}, {{ $peserta->tanggal_lahir }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Alamat Rumah</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->alamat }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left">Telepon</td>
                            <td class="px-6 py-4 border-b">{{ $peserta->telepon }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left" colspan="2">Keterangan Khusus Peserta Prestasi Terbaik</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 border-b" colspan="2">{{ $peserta->keterangan }}</td>
                        </tr>
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left" colspan="2">Nomor dan Kelas Pertandingan yang akan diikuti</td>
                        </tr>
                        @foreach ($peserta->PertandinganPeserta as $a)
                            <tr>
                                <td class="px-6 py-4 border-b">{{ $a->id }}-{{ $a->peserta_id }}-{{ $a->pertandingan_id }}</td>
                                <td class="px-6 py-4 border-b">{{ $a->Pertandingan->nama }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
