@extends('tamplate')
@section('title', 'Detail Buku')
@section('content')
<div class="container my-4">
    <h1>Detail Buku</h1>
    <hr>
    <div class="d-flex justify-content-between">
        <div class="p-1">
            <a href="{{ url('/') }}" class="btn btn-dark">Kembali</a>
        </div>
        <div class="p-1">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <img src="{{ @$buku ? url($buku->cover) : '' }}" style="width: 75%; border: solid black 1px;" id="preview" alt=""><br>
        </div>
        <div class="col-md-6 col-sm-12">
            <h2>{{ $buku->judul }}</h2>
            <h4 style="color: gray;">Penulis : {{ $buku->penulis->nama }}</h4>
            <h4 style="color: gray;">Publisher : {{ $buku->publisher }}</h4>

            <h4 style="color: gray;">Rilis Tahun : {{ $buku->tahun }}</h4>

            <h5 style="color: gray;">
                @foreach ($buku->KategoriBuku as $a)
                    {{ $a->kategori->nama }},
                @endforeach
            </h5>
            <hr>
            <p>{{ $buku->deskripsi }}</p>

        </div>
    </div>
</div>
@endsection
