@extends('tamplate')
@section('title', 'Edit Penulis')
@section('content')
    <h1>Edit Data Buku</h1>
    <hr>
    <form action="{{ url('penulis/'.$penulis->id) }}" method="post">
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('penulis') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
        @csrf
        @method('PUT')
        @include('admin.penulis.form')
    </form>
@endsection
