@extends('tamplate')
@section('title', 'List Buku')
@section('content')
<div class="container my-4">
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
<br><br>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    @foreach ($buku as $a)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ $a->cover }}" class="card-img-top" alt="Gambar Produk">
                <div class="card-body">
                <h6 class="card-title">{{ $a->judul }}</h6>
                <p class="card-text fw-bold text-danger">Penulis : {{ $a->penulis->nama }}</p>
                </div>
                <div class="card-footer bg-white border-top-0">
                <a href="{{ url('detail/'.$a->id) }}" class="btn btn-success w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Card Barang -->


    <!-- Duplikat card sebanyak yang dibutuhkan -->

  </div>
  <br>
    <div class="d-flex justify-content-center">
        {{ $buku->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
@endsection
