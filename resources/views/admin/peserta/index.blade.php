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
                <select name="kegiatan_id" class="pilihan" id="kegiatan_id">
                    @foreach ($kegiatan as $a)
                        <option value="{{ $a->id }}" {{ Request('kegiatan_id') == $a->id ? 'selected' : (@$kegiatan_get->id == $a->id ? 'selected' : '') }} >{{ $a->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-3/12">
                <select name="kategori_id"  class="mx-2 pilihan"   id="kategori_id">
                    @if(isset($_GET['kategori_id']) && $_GET['kategori_id'] != "")
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $a)
                            <option value="{{ $a->kategori->id }}" {{ Request('kategori_id') == $a->kategori->id ? 'selected' : '' }} >{{ $a->kategori->nama }}</option>
                        @endforeach
                    @else
                        <option value="">Pilih Kegiatan Dulu</option>
                    @endif
                </select>
            </div>
            <div class="w-full md:w-3/12">
                <select name="pertandingan_id"  class="mx-2 pilihan"  id="pertandingan_id">
                    @if(isset($_GET['pertandingan_id']) && $_GET['pertandingan_id'] != "")
                        <option value="">Pilih Pertandingan</option>
                        @foreach ($pertandingan as $a)
                            <option value="{{ $a->pertandingan->id }}" {{ Request('pertandingan_id') == $a->pertandingan->id ? 'selected' : '' }}>{{ $a->pertandingan->nama }}</option>
                        @endforeach
                    @else
                        <option value="">Pilih Kategori Dulu</option>
                    @endif

                </select>
            </div>
            <div class="w-full md:w-3/12">
                <input type="text" name="nama" value="{{ Request('nama') }}" placeholder="Cari Nama Peserta" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="">
            </div>


        </div>
        <button type="submit" name="submit" value="export" class="bg-green-500 text-white px-6 py-2 rounded">Export</button>
        {{-- <a href="{{ url('pesertas/export') }}" target="_blank" class="bg-green-500 text-white px-6 mx-2 py-3 rounded">Export</a> --}}
        <button type="submit" name="submit" value="cari" class="bg-blue-500 text-white px-6 py-2 rounded">Cari</button>
    </form>
    <br>
   <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 border-b text-gray-600">No</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Nama</th>
                    <th class="text-left px-6 py-3 border-b text-gray-600">Kegiatan</th>
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
                        <td class="px-6 py-4 border-b">{{ $a->KegiatanPertandinganPeserta->list_kategori->kegiatan->nama ?? "" }}</td>
                        <td class="px-6 py-4 border-b">
                            <ul>
                                {{-- PertandinganPesertaUtama --}}
                                {{-- <li>{{ $a->PertandinganPesertaUtama->Pertandingan->nama }}</li> --}}

                                @isset($a->PertandinganPeserta)
                                    @foreach ($a->PertandinganPeserta as $b)
                                        <li>{{ $b->list_kategori->pertandingan->nama ?? "" }}</li>
                                    @endforeach
                                @endisset
                            </ul>

                        </td>
                        <td class="px-6 py-4 border-b">
                            <a href="{{ url('peserta/'.$a->id) }}" class="text-green-500 hover:underline">Detail</a>
                            {{-- <a href="{{ url('peserta/'.$a->id.'/edit') }}" class="text-blue-500 hover:underline">Edit</a> --}}
                            <form action="{{ url('peserta/'.$a->id) }}" method="post" class="text-red-500 hover:underline delete-form">
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
        {{ $peserta->withQueryString()->links('vendor.pagination.tailwind') }}
    </div>
@endsection
<script>
    @section('script')

        function kegiatan_id(id) {
            $('#kategori_id').attr('status', 'true');
            $('#kategori_id').html('');

            $('#pertandingan_id').attr('status', 'false');
            $('#pertandingan_id').html('<option value="">Cari Kategori Dulu</option>');
            $.ajax({
                url: `{{url("pertandingans")}}/${id}?type=kategori_id`, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    $('#kategori_id').html('');

                    let data = "";
                    // console.log(response);
                    // alert(response.data.length);
                    if(response.data.length > 0) {
                        data += `<option value="">Cari Kategori</option>`;
                        $.each(response.data, function(index, value) {
                            data += `<option value="${value.kategori.id}">${value.kategori.nama}</option>`;
                        });
                    } else {
                        data += `<option value="">Kategori Tidak Tersedia!</option>`;
                    }
                    $('#kategori_id').html(data);

                },
                error: function(xhr, status, error) {
                    console.error('Gagal:', error);
                    alert('Gagal mengambil data!');
                }
            });
        }

        function kategori_id(kegiatan_id,id) {
            $('#pertandingan_id').attr('status', 'true');
            $('#pertandingan_id').html('');
            $.ajax({
                url: `{{url("pertandingans")}}/${id}?type=pertandingan_id&kegiatan_id=${kegiatan_id}`, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    $('#pertandingan_id').html('');
                    let data = "";

                    if(response.data.length > 0) {
                        data += `<option value="">Cari Pertandingan</option>`;
                        $.each(response.data, function(index, value) {
                            data += `<option value="${value.pertandingan.id}">${value.pertandingan.nama}</option>`;
                        });
                    } else {
                        data += `<option value="">Pertandingan Tidak Tersedia!</option>`;
                    }



                    $('#pertandingan_id').html(data);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal:', error);
                    alert('Gagal mengambil data!');
                }
            });
        }

        @if (Request('kategori_id') == "")
            kegiatan_id($('#kegiatan_id').val());
        @endif

        @if (Request('pertandingan_id') == "")
            kategori_id($("#kegiatan_id").val(),$("#kategori_id").val());
        @endif

        $('#kegiatan_id').on('change', function (e) {
            kegiatan_id($(this).val());
        });


        $('#kategori_id').on('change', function (e) {
            kategori_id($("#kegiatan_id").val(),$(this).val());
        });
    @endsection
</script>
