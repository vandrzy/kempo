@extends('tamplate')
@section('title', 'Kegiatan')
@section('content')
    <h1>Data Kegiatan</h1>
    <hr>
    <div class="flex justify-between items-center p-4">
        <div>
            <a href="{{ url('kegiatan/create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Data</a>
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
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nama Kegiatan</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(count($kegiatan) == 0)
                    <tr>
                        <td class="px-6 py-4 border-b" colspan="3">Data tidak tersedia!</td>
                    </tr>
                @else
                    @foreach ($kegiatan as $a)
                        <tr>
                            <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 border-b">{{ $a->nama }}</td>
                            <td class="px-6 py-4 border-b">
                                <a href="{{ url('pendaftaran/'.base64_encode($a->nama).'-'.base64_encode($a->id).'/daftar') }}" target="_blank" class="text-green-500 hover:underline">Link Pendaftaran</a> <br>
                                <a href="{{ url('kegiatan/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a> <br>
                                <form action="{{ url('kegiatan/'.$a->id) }}" method="post" class="delete-form">
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
        {{ $kegiatan->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
@endsection
