@extends('tamplate')
@section('title', 'Edit Penulis')
@section('content')
    <h1>Edit Data Buku</h1>
    <hr>
    <form action="{{ url('penulis/'.$penulis->id) }}" method="post">
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
        @method('PUT')
        @include('admin.penulis.form')
    </form>
@endsection
