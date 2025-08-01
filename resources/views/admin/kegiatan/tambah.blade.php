@extends('tamplate')
@section('title', 'Kegiatan')
@section('content')
    <h1>Tambah Data Kegiatan</h1>
    <hr>
    <form action="{{ url('kegiatan') }}" method="post">
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('kegiatan') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
        @csrf
        @include('admin.kegiatan.form')
    </form>
@endsection
