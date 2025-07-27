@extends('tamplate')
@section('title', 'Edit Kategori')
@section('content')
    <h1>Edit Data Kategori</h1>
    <hr>
    <form action="{{ url('kategori/'.$kategori->id) }}" method="post">
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('kategori') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
        @csrf
        @method('PUT')
        @include('admin.kategori.form')
    </form>
@endsection
