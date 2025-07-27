@extends('tamplate')
@section('title', 'Users')
@section('content')
    <h1>Tambah Data Users</h1>
    <hr>
    <form action="{{ url('users') }}" method="post">
        <div class="d-flex justify-content-between">
            <div class="p-1">
                <a href="{{ url('users') }}" class="btn btn-dark">Kembali</a>
            </div>
            <div class="p-1">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
        <br>
        @csrf
        @include('admin.users.form')
    </form>
@endsection
