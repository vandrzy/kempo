@extends('tamplate')
@section('title', 'Buku')
@section('content')
    <h1>Tambah Data Buku</h1>
    <hr>
    <form action="{{ url('buku') }}" method="post" enctype="multipart/form-data">
        <div class="d-flex justify-content-between">
            <div class="p-1">
                <a href="{{ url('buku') }}" class="btn btn-dark">Kembali</a>
            </div>
            <div class="p-1">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
        <br>
        @csrf
        @include('admin.buku.form')
    </form>
@endsection
