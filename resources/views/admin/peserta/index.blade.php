@extends('tamplate')
@section('title', 'Peserta')
@section('content')
    <h1>Data Peserta</h1>
    <hr>
    <br>
 {{-- <a href="{{ url('buku/create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Data</a><br><br> --}}
    <form action="" method="get">
        <div class="flex flex-wrap">
            <div class="w-full md:w-3/12">
                <input type="text" name="nama" value="{{ Request('nama') }}" placeholder="Cari Nama Peserta" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="">
            </div>
            <div class="w-full md:w-3/12">
                <select name="kategori_id"  class="mx-2 pilihan"   id="kategori_id">
                    <option value="">Cari Kategori Pertandingan</option>
                    @foreach ($kategori as $a)
                        <option value="{{ $a->id }}" {{ Request('kategori_id') == $a->id ? 'selected' : '' }} >{{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-3/12">
                <select name="pertandingan_id"  class="mx-2 pilihan"  id="pertandingan_id">
                    <option value="">Cari Pertandingan</option>
                    @if(isset($_GET['pertandingan_id']))
                        @foreach ($pertandingan as $a)
                            <option value="{{ $a->id }}" {{ Request('pertandingan_id') == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
                        @endforeach
                    @endif

                </select>
            </div>

            <div class="w-full md:w-3/12">
                <button type="submit" class="bg-blue-500 text-white px-6 mx-2 py-2 rounded">Cari</button>
            </div>
        </div>
    </form>
    <br>
   <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 border-b text-gray-600">No</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nama</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Pertandingan</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @if(count($peserta) <= 0)
                <tr>
                    <td class="px-6 py-4 border-b" colspan="4">Data tidak Tersedia!</td>
                </tr>
            @else
                @foreach ($peserta as $a)
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-b">{{ $a->nama }}</td>
                        <td class="px-6 py-4 border-b">
                            <ul>
                                @foreach ($a->PertandinganPeserta as $b)
                                    <li>{{ $b->Pertandingan->nama }}</li>
                                @endforeach
                            </ul>

                        </td>

                        <td class="px-6 py-4 border-b">
                            <a href="{{ url('peserta/'.$a->id) }}" class="text-green-500 hover:underline">Detail</a>
                            {{-- <a href="{{ url('peserta/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a> --}}
                            <form action="{{ url('peserta/'.$a->id) }}" method="post" class="text-red-500 hover:underline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
            <!-- Tambah data lainnya -->
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $peserta->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection
<script>
    @section('script')

        $('#kategori_id').on('change', function (e) {

            $('#pertandingan_id').attr('status', 'true');
            $('#pertandingan_id').html('');
            $.ajax({
                url: `{{url("pertandingans")}}/${$(this).val()}`, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    $('#pertandingan_id').html('');

                    let data = "";

                    data += `<option value="">Cari Pertandingan</option>`;
                    $.each(response.data, function(index, value) {
                        data += `<option value="${value.id}">${value.nama}</option>`;
                    });

                    $('#pertandingan_id').html(data);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal:', error);
                    alert('Gagal mengambil data!');
                }
            });
        });
    @endsection
</script>
