@extends('tamplate')
@section('title', 'Kategori')
@section('content')
    <h1>Tambah Data Penulis</h1>
    <hr>
    <form action="{{ url('penulis') }}" method="post">
        <div class="d-flex justify-content-between">
            <div class="p-1">
                <a href="{{ url('penulis') }}" class="btn btn-dark">Kembali</a>
            </div>
            <div class="p-1">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
        <br>
        @csrf
        @include('admin.penulis.form')
    </form>
@endsection
