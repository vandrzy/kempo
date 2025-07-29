@extends('tamplate')
@section('title', 'Edit Pertandingan')
@section('content')
    <h1>Edit Data Pertandingan</h1>
    <hr>
    <form action="{{ url('pertandingan/'.$pertandingan->id) }}" method="post">
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('pertandingan') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
        @csrf
        @method('PUT')
        @include('admin.pertandingan.form')
    </form>
@endsection
