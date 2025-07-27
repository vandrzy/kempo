@extends('tamplate')
@section('title', 'Kategori')
@section('content')
    <h1>Data Kategori</h1>
    <hr>
    <div class="d-flex justify-content-between">
        <div class="p-1">
            <a href="{{ url('kategori/create') }}" class="btn btn-primary">Tambah Data</a><br><br>
        </div>
        <div class="p-1">
            <form action="" method="get">
                <input type="text" name="cari" id="">
                <button type="submit" >Cari</button>

            </form>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategori as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $a->nama }}</td>
                    <td>
                        <a href="{{ url('kategori/'.$a->id.'/edit') }}" class="btn btn-success">Edit</a>
                        <form action="{{ url('kategori/'.$a->id) }}" method="post"  class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $kategori->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection
