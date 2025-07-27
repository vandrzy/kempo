@extends('tamplate')
@section('title', 'List Buku')
@section('content')
<div class="max-w-7xl mx-auto my-4 px-4">
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
<br><br>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach ($buku as $a)
        <div class="flex">
            <div class="bg-white rounded-lg shadow-sm flex flex-col w-full">
                <img src="{{ $a->cover }}" alt="Gambar Produk" class="rounded-t-lg object-cover h-50 w-full">
                <div class="p-4 flex-grow">
                    <h6 class="text-lg font-semibold mb-2">{{ $a->judul }}</h6>
                    <p class="text-red-600 font-bold">Penulis: {{ $a->penulis->nama }}</p>
                </div>
                <div class="p-4 border-t border-gray-200 bg-white">
                    <a href="{{ url('detail/'.$a->id) }}" class="block text-center bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 transition duration-200">Lihat Detail</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

  <br>
    <div class="d-flex justify-content-center">
        {{ $buku->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
@endsection
