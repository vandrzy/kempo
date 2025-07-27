@extends('tamplate')
@section('title', 'Users')
@section('content')
    <h1>Edit Data Users</h1>
    <hr>
    <form action="{{ url('users/'.$user->id) }}" method="post">
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
        @method('PUT')
        @include('admin.users.form')
    </form>
@endsection
