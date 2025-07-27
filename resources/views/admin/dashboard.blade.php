@extends('tamplate')
@section('content')
      <h2>Dashboard</h2>
      <p>Selamat Datang {{ Auth::user()->name }} di Aplikasi Manajemen Data Buku</p>
@endsection
