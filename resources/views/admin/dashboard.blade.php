@extends('tamplate')
@section('title', 'Dashboard')
@section('content')
    <h2 class="text-2xl font-bold mb-2">Dashboard</h2>
    <p class="text-gray-700">Selamat Datang <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span> di Aplikasi Pendaftaran Kempo</p>
@endsection
