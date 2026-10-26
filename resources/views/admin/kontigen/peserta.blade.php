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
</style>
    <div class="w-full min-w-0 max-w-full">
        <div class="kempo-card">
            <!-- Header Kartu -->
            <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Data Kontingen {{ $kabupaten->name }}</h4>
                        <p class="text-xs text-neutral-400 m-0 mt-0.5">Kegiatan: <span class="font-semibold text-neutral-600">{{ $kegiatan->nama }}</span></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2 border-t border-[#ebebeb]/60">
                    <div>
                        <!-- Tombol Kembali (Button 2 Secondary) -->
                        <a href="{{ url('kontingen') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-neutral-700 bg-neutral-100 hover:bg-neutral-200 border border-neutral-200 shadow-2xs hover:-translate-y-px transition-all">
                            <i class="fas fa-arrow-left text-[10px]"></i>
                            <span>Kembali ke Kontingen</span>
                        </a>
                    </div>
                    <div>
                        <form action="{{ url('kontingen/'.$kabupaten->id) }}" method="get" class="m-0">
                            <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                            <select name="status" onchange="this.form.submit()"
                                class="px-3.5 py-2 text-xs rounded-xl bg-white border border-[#ebebeb] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs text-[#000000] font-medium"
                                id="kegiatan_id">
                                <option value="peserta" {{ Request('status') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                <option value="official" {{ Request('status') == 'official' ? 'selected' : '' }}>Official</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="kempo-table-wrapper kempo-scrollbar">
                <table class="kempo-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center w-14">No</th>
                            <th rowspan="2" class="text-left">Nama</th>
                            <th rowspan="2" class="text-left">NIK</th>
                            <th rowspan="2" class="text-center">Usia</th>
                            <th colspan="2" class="text-center border-b border-[#ebebeb]">Jenis Kelamin</th>
                            <th colspan="2" class="text-center border-b border-[#ebebeb]">Tingkat</th>
                            <th colspan="2" class="text-center border-b border-[#ebebeb]">Status</th>
                            <th rowspan="2" class="text-center w-28">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-center w-10">L</th>
                            <th class="text-center w-10">P</th>
                            <th class="text-center w-10">K</th>
                            <th class="text-center w-10">D</th>
                            <th class="text-center w-10">P</th>
                            <th class="text-center w-10">O</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peserta as $a)
                            <tr>
                                <td class="text-center tabular-nums text-xs font-semibold text-neutral-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-left font-semibold text-[#000000]">
                                    {{ $a->nama }}
                                </td>
                                <td class="text-left tabular-nums text-neutral-600 text-xs sm:text-sm">
                                    {{ $a->nik }}
                                </td>
                                <td class="text-center tabular-nums text-neutral-700 text-xs sm:text-sm">
                                    {{ (Date('Y')) - (Date('Y', strtotime($a->tanggal_lahir))) }} Thn
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "L")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold" title="Laki-laki">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->jenis_kelamin, 0, 1)) == "P")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold" title="Perempuan">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "K")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-bold" title="KYU">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->tingkatan_sabuk, 0, 1)) == "D")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 text-[10px] font-bold" title="DAN">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->status, 0, 1)) == "P")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold" title="Peserta">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(strtoupper(substr($a->status, 0, 1)) == "O")
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-bold" title="Official">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusType = request('status', 'peserta');
                                        $routePrefix = ($statusType == 'official') ? 'official' : 'peserta';
                                        $kegId = request('kegiatan_id', $kegiatan->id ?? '');
                                    @endphp
                                    <a href="{{ url($routePrefix . '/' . $a->id) }}?kontigen={{ $kabupaten->id }}&ehh={{ $kegId }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#3276e4] hover:bg-[#2862c2] shadow-2xs hover:-translate-y-px transition-all">
                                        <i class="fas fa-eye text-[10px]"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-8 text-neutral-400 text-sm">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    Tidak ada data peserta/official ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#ebebeb] bg-[#fdfdfd] flex justify-center">
                {{ $peserta->withQueryString()->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>



@endsection

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

