@extends('tamplate')
@section('title', 'Detail Buku')
@section('content')
<div class="max-w-6xl mx-auto my-6 px-4">
    <h1 class="text-3xl font-bold mb-2">Detail Buku</h1>
    <hr class="border-t border-gray-300 mb-4">

    <div class="flex justify-between mb-4">
        <div>
            <a href="{{ url('/') }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">Kembali</a>
        </div>
        <div></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <img src="{{ @$buku ? url($buku->cover) : '' }}" alt="Cover Buku"
                class="w-3/4 border border-black rounded shadow" id="preview">
        </div>

        <div>
            <h2 class="text-2xl font-semibold mb-2">{{ $buku->judul }}</h2>
            <h4 class="text-gray-600 mb-1">Penulis : {{ $buku->penulis->nama }}</h4>
            <h4 class="text-gray-600 mb-1">Publisher : {{ $buku->publisher }}</h4>
            <h4 class="text-gray-600 mb-1">Rilis Tahun : {{ $buku->tahun }}</h4>

            <h5 class="text-gray-600 mb-2">
                @foreach ($buku->KategoriBuku as $a)
                    {{ $a->kategori->nama }},
                @endforeach
            </h5>

            <hr class="border-t border-gray-200 my-2">

            <p class="text-gray-700">{{ $buku->deskripsi }}</p>
        </div>
    </div>
</div>

@endsection
