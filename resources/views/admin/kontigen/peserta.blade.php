@extends('tamplate')
@section('title', 'Data Kontigen '.$kabupaten->name )
@section('content')
<style>
    .check-hidden:not(:checked) {
    display: none;
    }

    .big-checkbox {
    width: 100px;
    height: 30px;
    accent-color: green;
    }

    .big-checkbox:disabled {
        accent-color: green; /* tetap hijau meski disabled */
    }
}
</style>
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="dark:text-white">Data Kontigen {{ $kabupaten->name }}</h4>
                </div>
                <a href="{{ url('kontingen') }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Kembali</a>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                <thead class="align-bottom">
                    <tr>
                        <th rowspan="2" class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-4xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">No</th>
                        {{-- <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Kegiatan</th>
                        <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Pertandingan</th> --}}
                        <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama</th>
                        <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">NIK</th>
                        <th rowspan="2" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Usia (Tahun)</th>
                        <th colspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Jenis Kelamin</th>
                        <th colspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Tingkat</th>
                        <th colspan="2" style="text-align: center" class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status</th>
                        <th rowspan="2" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                    </tr>
                    <tr>
                        <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">L</th>
                        <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">P</th>
                        <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">K</th>
                        <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">D</th>
                        <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">P</th>
                        <th style="text-align: center" class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-3xl border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">O</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peserta as $a)
                        <tr>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <h6 class="mb-0 text-sm leading-normal pl-4 dark:text-white">{{ $loop->iteration }}</h6>
                            </td>

                            {{-- <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->KegiatanPertandinganPeserta->list_kategori->kegiatan->nama ?? "" }}</p>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <ul>
                                    @isset($a->PertandinganPeserta)
                                        @foreach ($a->PertandinganPeserta as $b)
                                            <li><p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $b->list_kategori->pertandingan->nama ?? "" }}</p></li>
                                        @endforeach
                                    @endisset
                                </ul>
                            </td> --}}
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->nama }}</p>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ $a->nik }}</p>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">{{ (Date('Y')) - (Date('Y', strtotime($a->tanggal_lahir))) }} Tahun </p>
                            </td>
                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "L")
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </p>
                            </td>
                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "P")
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </p>
                            </td>
                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                                    @if(strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "K")
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </p>
                            </td>
                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                                    @if(strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "D")
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </p>
                            </td>
                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                                    @if(strtoupper(substr($a->status, 0, 1)) == "P")
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </p>
                            </td>
                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                                    @if(strtoupper(substr($a->status, 0, 1)) == "O")
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </p>
                            </td>
                            <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <div class="flex justify-center items-center space-x-4">
                                        <a href="{{ url('peserta/'.$a->id) }}?kontigen={{ $kabupaten->id }}" style="border: solid 2px green; color: green;"  class="inline-block px-5 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Detail</a>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $peserta->withQueryString()->links('vendor.pagination.tailwind') }}
                </div>
            </div>
            </div>
        </div>
        </div>
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
                        data += `<option value="">Cari Tanding</option>`;
                        $.each(response.data, function(index, value) {
                            data += `<option value="${value.pertandingan.id}">${value.pertandingan.nama}</option>`;
                        });
                    } else {
                        data += `<option value="">Tanding Tidak Tersedia!</option>`;
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
