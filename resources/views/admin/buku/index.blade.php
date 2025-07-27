@extends('tamplate')
@section('title', 'Buku')
@section('content')
    <h1>Data Buku</h1>
    <hr>

    <a href="{{ url('buku/create') }}" class="btn btn-primary">Tambah Data</a><br><br>
    <form action="" method="get">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <input type="text" name="judul" value="{{ Request('judul') }}" placeholder="Cari Judul Buku" class="form-control" id="">
            </div>
            <div class="col-md-3 col-sm-12">
                <select name="penulis"  class="form-control pilihan"  id="">
                    <option value="">Cari Nama Penulis Buku</option>
                    @foreach ($penulis as $a)
                        <option value="{{ $a->id }}" {{ Request('penulis') == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-12">
                <select name="kategori"  class="form-control pilihan"   id="">
                    <option value="">Cari Kategori Buku</option>
                    @foreach ($kategori as $a)
                        <option value="{{ $a->id }}" {{ Request('kategori') == $a->id ? 'selected' : '' }} >{{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-sm-12">
                <button type="submit" class="btn btn-dark">Cari</button>
            </div>
        </div>
    </form>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($buku as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $a->judul }}</td>
                    <td>{{ $a->penulis->nama }}</td>
                    <td>
                        @foreach ($a->KategoriBuku as $b)
                            {{ $b->kategori->nama }},
                        @endforeach
                    </td>

                    <td>
                        <a href="{{ url('buku/'.$a->id.'/edit') }}" class="btn btn-success">Edit</a>
                        <form action="{{ url('buku/'.$a->id) }}" method="post" class="delete-form">
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
        {{ $buku->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection
