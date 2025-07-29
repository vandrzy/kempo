@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
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
                    <th class="text-left px-6 py-3 border-b text-gray-600">No</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nama Pertandingan</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Jenis Kelamin</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @php
                $kategori_id = 0;
            @endphp
            @foreach ($pertandingan as $a)

                @if ($kategori_id != $a->kategori_id)
                    @php
                        $kategori_id = $a->kategori_id;
                    @endphp
                    <tr>
                        <th class="px-6 py-4 border-b" style="text-align: left;" colspan="4">{{ $a->kategori->nama }}</th>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-b">{{ $a->nama }}</td>
                        <td class="px-6 py-4 border-b">{{ $a->jenis_kelamin ?? 'Mix' }}</td>

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
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-b">{{ $a->nama }}</td>
                        <td class="px-6 py-4 border-b">{{ $a->jenis_kelamin ?? 'Mix' }}</td>

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
