@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
    @php
        use App\Models\Pertandingan;
    @endphp
    <h1>Data Pertandingan</h1>
    <hr>
    <div class="flex justify-between items-center p-4">
        <div>
            <a href="{{ url('pertandingan/create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Data</a>
        </div>
        <div>
            <form action="" method="get">
                <input type="text" name="cari"
                    class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500"
                    placeholder="Cari data...">
                <button type="submit"
                    class="px-4 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    Cari
                </button>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 border-b text-gray-600" rowspan="2">No</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600" rowspan="2">Nama Pertandingan</th>
                    {{-- <th class="text-left px-6 py-3 border-b text-gray-600" rowspan="2">Jenis Kelamin</th> --}}
                    <th class="text-center px-6 py-3 border-b text-gray-600" colspan="3">Medali</th>
                    <th class="text-center px-6 py-3 border-b text-gray-600" rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th class="text-center px-6 py-3 border-b text-gray-600">Emas</th>
                    <th class="text-center px-6 py-3 border-b text-gray-600">Perak</th>
                    <th class="text-center px-6 py-3 border-b text-gray-600">Perunggu</th>
                </tr>
            </thead>
            <tbody>
            @php
                $total_gender_group = 0;
                $kategori_id = 0;
                $jenis_kelamin = "-";
            @endphp
            @foreach ($pertandingan as $a)

                @if ($kategori_id != $a->kategori_id)
                    @php
                        $kategori_id = $a->kategori_id;
                    @endphp
                    <tr>
                        <th class="px-6 py-4 border-b" style="text-align: left;" colspan="7">{{ $a->kategori->nama }}</th>
                    </tr>
                    @if ($a->jenis_kelamin != $jenis_kelamin)
                        @php
                            $jenis_kelamin = $a->jenis_kelamin ?? 'Mix';
                        @endphp
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left;" colspan="2">{{ $jenis_kelamin }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_emas }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perak }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perunggu }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: left;"></th>
                        </tr>
                    @endif
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-b">{{ $a->nama }}</td>
                        {{-- <td class="px-6 py-4 border-b">{{ $a->jenis_kelamin ?? 'Mix' }}</td> --}}
                        <td class="text-center px-6 py-4 border-b">{{ $a->emas }}</td>
                        <td class="text-center px-6 py-4 border-b">{{ $a->perak }}</td>
                        <td class="text-center px-6 py-4 border-b">{{ $a->perunggu }}</td>

                        <td class="px-6 py-4 border-b">
                            <a href="{{ url('pertandingan/'.$a->id) }}" class="text-green-500 hover:underline">Nomor Tanding</a><br>
                            <a href="{{ url('pertandingan/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a>
                            <form action="{{ url('pertandingan/'.$a->id) }}" method="post" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @else
                    @if ($a->jenis_kelamin != $jenis_kelamin)
                        @php
                            $jenis_kelamin = $a->jenis_kelamin ?? 'Mix';
                            // dd($a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_emas );
                        @endphp
                        <tr>
                            <th class="px-6 py-4 border-b" style="text-align: left;" colspan="2">{{ $jenis_kelamin }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_emas ?? '0' }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perak ?? '0' }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: center;">{{ $a->totalEmasPerJenisKelamin($kategori_id, $jenis_kelamin)->total_perunggu ?? '0' }}</th>
                            <th class="px-6 py-4 border-b" style="text-align: left;"></th>
                        </tr>
                    @endif
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-b">{{ $a->nama }}</td>
                        {{-- <td class="px-6 py-4 border-b">{{ $a->jenis_kelamin ?? 'Mix' }}</td> --}}
                        <td class="text-center px-6 py-4 border-b">{{ $a->emas }}</td>
                        <td class="text-center px-6 py-4 border-b">{{ $a->perak }}</td>
                        <td class="text-center px-6 py-4 border-b">{{ $a->perunggu }}</td>

                        <td class="px-6 py-4 border-b">
                            <a href="{{ url('pertandingan/'.$a->id) }}" class="text-green-500 hover:underline">Nomor Tanding</a><br>
                            <a href="{{ url('pertandingan/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a><br>
                            <form action="{{ url('pertandingan/'.$a->id) }}" method="post" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
            <!-- Tambah data lainnya -->
            </tbody>
        </table>
    </div><br>
    <div class="d-flex justify-content-center">
        {{ $pertandingan->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
@endsection
