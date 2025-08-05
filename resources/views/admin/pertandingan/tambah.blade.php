@extends('tamplate')
@section('title', 'Tambah Pertandingan')
@section('content')
    <h1>Tambah Data Pertandingan </h1><br>
    <hr>
    <form action="{{ url('pertandingan') }}" method="post">
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('pertandingan') }}?kegiatan_id={{ Request('kegiatan_id') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
        @csrf
        @include('admin.pertandingan.form')
    </form>
@endsection
