@extends('tamplate')
@section('title', 'Buku')
@section('content')
    <h1>Data Buku</h1>
    <hr>
    <br>
 <a href="{{ url('buku/create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Data</a><br><br>
    <form action="" method="get">
        <div class="flex flex-wrap">
            <div class="w-full md:w-3/12">
                <input type="text" name="judul" value="{{ Request('judul') }}" placeholder="Cari Judul Buku" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="">
            </div>
            <div class="w-full md:w-3/12">
                <select name="penulis"  class="mx-2 pilihan"  id="">
                    <option value="">Cari Nama Penulis Buku</option>
                    @foreach ($penulis as $a)
                        <option value="{{ $a->id }}" {{ Request('penulis') == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-3/12">
                <select name="kategori"  class="mx-2 pilihan"   id="">
                    <option value="">Cari Kategori Buku</option>
                    @foreach ($kategori as $a)
                        <option value="{{ $a->id }}" {{ Request('kategori') == $a->id ? 'selected' : '' }} >{{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-3/12">
                <button type="submit" class="bg-blue-500 text-white px-6 mx-2 py-2 rounded">Cari</button>
            </div>
        </div>
    </form>
    <br>
   <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 border-b text-gray-600">No</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Judul</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Penulis</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Kategori</th>

                    <th class="text-left px-6 py-3 border-b text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($buku as $a)
                <tr>
                    <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 border-b">{{ $a->judul }}</td>
                    <td class="px-6 py-4 border-b">{{ $a->penulis->nama }}</td>
                    <td class="px-6 py-4 border-b">
                        @foreach ($a->KategoriBuku as $b)
                            {{ $b->kategori->nama }},
                        @endforeach
                    </td>
                    <td class="px-6 py-4 border-b">
                        <a href="{{ url('buku/'.$a->id) }}" class="text-green-500 hover:underline">Detail</a>
                        <a href="{{ url('buku/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a>
                        <form action="{{ url('buku/'.$a->id) }}" method="post" class="text-red-500 hover:underline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <!-- Tambah data lainnya -->
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $buku->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection
