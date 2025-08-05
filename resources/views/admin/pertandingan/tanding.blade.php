@extends('tamplate')
@section('title', 'Pertandingan')
@section('content')
    <h1>Kegiatan </h1>
    <h1>{{ $pertandingan->kegiatan->nama }} / {{ $pertandingan->pertandingan->nama }} / Tanding</h1>

    <hr>
    <div class="flex justify-between items-center px-4 py-3">
        <!-- Kiri: Tombol Kembali -->
        <a href="{{ url('kegiatan/'.$pertandingan->kegiatan->id) }}" class="bg-black text-white px-4 py-2 rounded">
            Kembali
        </a>

        <!-- Kanan: Form Hapus dan Tambah Penantang -->
        <div class="flex items-center gap-3">
            <!-- Form Hapus -->

            <form action="{{ url('pertandingans/tanding-delete/') }}" method="post" class="delete-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="list_kategori_id" value="{{ $pertandingan->id }}">

                {{-- <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                <input type="hidden" name="pertandingan_id" value="{{ $pertandingan->id }}"> --}}
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Hapus semua penantang</button>
            </form>

            <!-- Form Tambah Penantang -->
            <form action="{{ url('pertandingans/tanding/'.$pertandingan->id) }}" method="post" class="tanding-form">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Penantang</button>
            </form>
        </div>
    </div>


    <div class="overflow-x-auto">
        <h1>Peserta tersedia : {{ $peserta_ready }}</h1>
        {{-- <table class="min-w-full bg-white border border-gray-200">
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
                    @if (isset($a->PertandinganPesertaPertama))
                        <td class="px-6 py-4 border-b border-l border-r">{{ $a->PertandinganPesertaPertama->urutan }}</td>
                        <td class="px-6 py-4 border-b border-l border-r">{{ $a->PertandinganPesertaPertama->Peserta->nama }}</td>
                    @else
                        <td class="px-6 py-4 border-b text-center border-l border-r" colspan="2">WO</td>
                    @endif

                    @if (isset($a->PertandinganPesertaKedua))
                        <td class="px-6 py-4 border-b border-l border-r">{{ $a->PertandinganPesertaKedua->urutan }}</td>
                        <td class="px-6 py-4 border-b border-l border-r">{{ $a->PertandinganPesertaKedua->Peserta->nama }}</td>
                    @else
                        <td class="px-6 py-4 border-b text-center border-l border-r" colspan="2">WO</td>
                    @endif

                    <td class="px-6 py-4 border-b border-l border-r">

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
        </table> --}}
        {{-- <a href="{{ url('pertandingan/'.$a->id) }}" class="text-green-500 hover:underline">Nomor Tanding</a><br>
        <a href="{{ url('pertandingan/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a><br> --}}
    <div class="flex gap-8 p-6 overflow-x-auto bg-white">
        {{-- <!-- Round 1 -->
        <div class="flex flex-col justify-between gap-6">
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">KABOLA</div>
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">PANTAR TIMUR</div>
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">PANTAR TENGAH</div>
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">LEMBUR</div>
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">MATARU</div>
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">ATU</div>
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">PUREMAN</div>
            <div class="bg-gray-100 p-2 rounded shadow w-40 text-center">TELUK MUTIARA</div>
        </div> --}}

        <div class="flex flex-col justify-between gap-6">
            @foreach ($peserta_lawan as $a)
                <div class="bg-red-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaPertama->urutan }} | {{ $a->PertandinganPesertaPertama->Peserta->nama }}</div>
                <div class="bg-blue-100 p-2 rounded shadow w-40 text-center">{{ $a->PertandinganPesertaKedua->urutan ?? '0' }} | {{ $a->PertandinganPesertaKedua->Peserta->nama ?? 'WO' }}</div>
                <hr>
            @endforeach

        </div>

        {{-- <!-- Round 2 -->
        <div class="flex flex-col justify-around gap-12">
            <div class="bg-blue-100 p-2 rounded shadow w-40 text-center">Pemenang I</div>
            <div class="bg-blue-100 p-2 rounded shadow w-40 text-center">Pemenang II</div>
            <div class="bg-blue-100 p-2 rounded shadow w-40 text-center">Pemenang III</div>
            <div class="bg-blue-100 p-2 rounded shadow w-40 text-center">Pemenang IV</div>
        </div>

        <!-- Round 3 -->
        <div class="flex flex-col justify-evenly gap-20">
            <div class="bg-green-100 p-2 rounded shadow w-40 text-center">Pemenang X</div>
            <div class="bg-green-100 p-2 rounded shadow w-40 text-center">Pemenang XI</div>
        </div>

        <!-- Final -->
        <div class="flex flex-col justify-center">
            <div class="bg-yellow-200 p-2 rounded shadow w-40 text-center">Final</div>
        </div> --}}
    </div>





    </div><br>
    <div class="d-flex justify-content-center">
        {{ $peserta_lawan->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
@endsection
