@extends('tamplate')
@section('title', 'Edit Kategori')
@section('content')
    <h1>Edit Data Kategori</h1>
    <hr>
    <form action="{{ url('kategori/'.$kategori->id) }}" method="post">
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
        @method('PUT')
        @include('admin.kategori.form')
    </form>
@endsection
