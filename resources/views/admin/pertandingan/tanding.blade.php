@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
    <h1>Data Pertandingan</h1>
    <hr>
    <form action="{{ url('pertandingans/tanding/'.$pertandingan->id) }}" method="post" class="tanding-form">
        @csrf
        <div class="flex justify-between items-center p-3">
            <div>
                <a href="{{ url('pertandingan') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Penantang</button>
            </div>
        </div>
    </form>
    <div class="overflow-x-auto">
        <h1>Peserta tersedia : {{ $peserta_ready }}</h1>
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-center px-6 py-3 border-b text-gray-600" colspan="2">Pemain Pertama</th>
                    <th class="text-center px-6 py-3 border-b text-gray-600" colspan="2">Pemain Kedua</th>
                    <th class="text-center px-6 py-3 border-b text-gray-600" rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nomor</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nama</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nomor</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nama</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($peserta_lawan as $a)
                <tr>
                    <td class="px-6 py-4 border-b">{{ $a->PertandinganPesertaPertama->id }}-{{ $a->PertandinganPesertaPertama->peserta_id }}-{{ $a->PertandinganPesertaPertama->pertandingan_id }}</td>
                    <td class="px-6 py-4 border-b">{{ $a->PertandinganPesertaPertama->Peserta->nama }}</td>
                    <td class="px-6 py-4 border-b">{{ $a->PertandinganPesertaKedua->id }}-{{ $a->PertandinganPesertaKedua->peserta_id }}-{{ $a->PertandinganPesertaKedua->pertandingan_id }}</td>
                    <td class="px-6 py-4 border-b">{{ $a->PertandinganPesertaKedua->Peserta->nama }}</td>

                    <td class="px-6 py-4 border-b">
                        {{-- <a href="{{ url('pertandingan/'.$a->id) }}" class="text-green-500 hover:underline">Nomor Tanding</a><br>
                        <a href="{{ url('pertandingan/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a><br> --}}
                        <form action="{{ url('pertandingans/tanding-delete/'.$a->id) }}" method="post" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            <!-- Tambah data lainnya -->
            </tbody>
        </table>
    </div><br>
    <div class="d-flex justify-content-center">
        {{ $peserta_lawan->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
@endsection
