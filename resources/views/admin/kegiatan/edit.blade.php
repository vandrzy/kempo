@extends('tamplate')
@section('title', 'Edit Kegiatan')
@section('content')
    <h1>Edit Data Kegiatan</h1>
    <hr>
    <form action="{{ url('kegiatan/'.$kegiatan->id) }}" method="post">
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('kegiatan') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
        @csrf
        @method('PUT')
        @include('admin.kegiatan.form')
    </form>
@endsection
