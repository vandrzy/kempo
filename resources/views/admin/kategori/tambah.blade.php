@extends('tamplate')
@section('title', 'Kategori')
@section('content')
    <h1>Tambah Data Kategori</h1>
    <hr>
    <form action="{{ url('kategori') }}" method="post">
        <div class="d-flex justify-content-between">
            <div class="p-1">
                <a href="{{ url('kategori') }}" class="btn btn-dark">Kembali</a>
            </div>
            <div class="p-1">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
        <br>
        @csrf
        @include('admin.kategori.form')
    </form>
@endsection
