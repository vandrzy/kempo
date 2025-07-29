@extends('tamplate')
@section('title', 'Kategori')
@section('content')
    <h1>Data Kategori</h1>
    <hr>
    <div class="flex justify-between items-center p-4">
        <div>
            <a href="{{ url('kategori/create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Data</a>
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
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nama Kategori</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Usia</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Tahun</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(count($kategori) == 0)
                    <tr>
                        <td class="px-6 py-4 border-b" colspan="3">Data tidak tersedia!</td>
                    </tr>
                @else
                    @foreach ($kategori as $a)
                        <tr>
                            <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 border-b">{{ $a->nama }}</td>
                            <td class="px-6 py-4 border-b">{{ $a->usia_awal }} - {{ $a->usia_akhir }}</td>
                            <td class="px-6 py-4 border-b">{{ $a->tahun_awal }} - {{ $a->tahun_akhir }}</td>

                            <td class="px-6 py-4 border-b">
                                <a href="{{ url('kategori/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ url('kategori/'.$a->id) }}" method="post" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            <!-- Tambah data lainnya -->
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $kategori->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection
