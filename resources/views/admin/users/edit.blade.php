@extends('tamplate')
@section('title', 'Users')
@section('content')
    <h1>Edit Data Users</h1>
    <hr>
    <form action="{{ url('users/'.$user->id) }}" method="post">
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('users') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
        <br>
        @csrf
        @method('PUT')
        @include('admin.users.form')
    </form>
@endsection
