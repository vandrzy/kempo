@extends('tamplate')
@section('title', 'Input Teknik | Pendaftaran Kempo')
@section('content')
<style>
    /* Fix untuk Select2 single yang tertimpa Tailwind */


.select2-container .select2-selection--single {
    height: 42px !important;
    display: flex !important;
    align-items: center !important;
    padding-left: 10px !important;
    border: 1px solid #d1d5db !important; /* border-gray-300 */

}

/* Biar teks tidak keluar lagi */
.select2-selection__rendered {
    line-height: 42px !important;
    margin-top: 0 !important;
}

/* Biar arrow icon sejajar */
.select2-selection__arrow {
    height: 42px !important;
    top: 0 !important;
    right: 10px !important;
}
</style>
<div class="max-w-6xl mx-auto my-6 px-4">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        @isset($_GET['pertandingan_id'])
                            <a href="{{ url('/teknik/'.$back) }}" style="background-color: rgb(53, 53, 53);" class="text-white px-4 py-2 rounded">Kembali</a>
                        @endisset
                        <h4 class="dark:text-white"> Kegiatan {{ $kegiatan->nama }} </h4>
                        @if(Auth::user())
                            <button style="background-color: rgb(53, 53, 53);" class="text-white px-4 py-2 rounded" id="copy_link">Salin Link Input Teknik</button>
                        @endif
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    @if (!isset($_GET['pertandingan_id']))
                        <form action="" method="get" id="cari_peserta_proses">
                            <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">
                            <div class="p-3">
                                <div class="mb-4">
                                    <label for="">Kabupaten <span style="color: red;">*</span></label>
                                    <select name="kabupaten_id" id="kabupaten_id" class="w-full border rounded px-3 py-2">
                                        <option selected disabled>Pilih Kabupaten</option>
                                        @foreach($kabupatens as $a)
                                            <option value="{{ $a->id }}" {{ old('kabupaten_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="">Kategori <span style="color: red;">*</span></label>
                                    <select name="kategori_id" id="kategori_id" class="w-full border rounded px-3 py-2">
                                        <option selected disabled>Pilih Kategori</option>
                                        @foreach($kategoris as $a)

                                            <option value="{{ $a[0]->kategori->id }}" {{ old('kategori_id') == $a[0]->kategori->id ? 'selected' : '' }}>{{ $a[0]->kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="">Pertandingan <span style="color: red;">*</span></label>
                                    <select name="pertandingan_id" id="pertandingan_id" class="w-full border rounded px-3 py-2">
                                        <option selected disabled>Pilih Pertandingan</option>
                                    </select>
                                </div>

                                {{-- <div class="mb-4">
                                    <label for="">Nama Peserta <span style="color: red;">*</span></label>
                                    <select name="peserta_id" id="peserta_id" class="select2 appearance-none ...">
                                        <option selected disabled>Input Peserta</option>
                                    </select>
                                </div> --}}
                                <div class="mb-4">
                                    <button type="submit"  style="border: solid 2px green; color: green; font-size: 22px;"   class="edit-tanding-form inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Input Teknik</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    @if (isset($_GET['pertandingan_id']))
                    <form action="{{ url('teknik/0') }}" method="post" id="form-peserta" enctype="multipart/form-data">
                        @csrf
                        <div class="max-w-md mx-auto bg-white shadow-md p-5 border border-gray-200 p-2">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Keterangan</h2>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-sm">
                            <li>Jika ada simbol "<span style="color: red;">*</span><span style="color: red;">*</span>" Harus diisi garis strip atau tanda hubung.</li>
                        </ul>
                        </div>

                        <div class="p-3 overflow-x-auto">
                            <div class="flex flex-wrap mt-4 -mx-3">
                                <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-12/12 lg:flex-none">
                                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                        <h1 class="m-4">Tahapan Teknik</h1>
                                        <h5 class="ml-4">Kabupaten : {{ $kabupaten->name }}</h5>

                                        <div class="kategori_pertandingan_show">
                                            <div class="flex-auto px-0 pt-0 pb-2">
                                                    <div class="p-3 overflow-x-auto">
                                                        <div class="flex flex-wrap mt-4 -mx-3">
                                                            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-5/12 lg:flex-none">
                                                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                                    <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">
                                                                    <div class="mb-4 p-3">
                                                                        <table class=" bg-white border rounded-lg overflow-hidden" style="width: 100%;">
                                                                            <thead class="bg-gray-100 border-b">
                                                                                <tr>
                                                                                    <th class="px-3 py-2 border-r" style="width: 20%;">No Urut</th>
                                                                                    <th class="px-3 py-2" style="text-align: left;">Nama Peserta</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($pesertas as $peserta)
                                                                                    @php
                                                                                        $kategoriList = $peserta->PertandinganPeserta->pluck('list_kategori.kategori')->unique()->values();
                                                                                        $pertandingan = $peserta->PertandinganPeserta->pluck('list_kategori.pertandingan')->unique()->values();
                                                                                    @endphp
                                                                                    @php
                                                                                        $kategori_id = 0;
                                                                                        $urutan = 0;

                                                                                    @endphp
                                                                                    @foreach ($peserta->PertandinganPeserta as $item)
                                                                                        <tr>
                                                                                            <td style="text-align: center" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                                                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80">
                                                                                                    {{ $item->urutan }}
                                                                                                </p>
                                                                                            </td>
                                                                                            <td style="text-align: left" class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                                                                <p class="mb-0 text-3xl font-semibold leading-tight dark:text-white dark:opacity-80"> {{ $peserta->nama }}</p>
                                                                                            </td>
                                                                                        </tr>
                                                                                        @php
                                                                                            $urutan = $urutan + 1;
                                                                                        @endphp
                                                                                    @endforeach
                                                                                @endforeach

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
                                                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                                    <div class="mb-4 p-3">
                                                                        @foreach ($pesertas as $peserta)
                                                                            @php
                                                                                $kategoriList = $peserta->PertandinganPeserta->pluck('list_kategori.kategori')->unique()->values();
                                                                                $pertandingan = $peserta->PertandinganPeserta->pluck('list_kategori.pertandingan')->unique()->values();
                                                                            @endphp
                                                                            @php
                                                                                $kategori_id = 0;
                                                                                $urutan = 0;
                                                                                $peserta_list = $loop->iteration;

                                                                            @endphp
                                                                            @foreach ($peserta->PertandinganPeserta as $item)
                                                                                {{-- @dd($item); --}}
                                                                                <div class="mt-4 ml-4">

                                                                                    <input type="hidden" name="pertandingan_peserta_id[]" value="{{ $item->id }}">
                                                                                    <input type="hidden" name="komposisi_id[]" value="{{ @$item->UrutanKomposisiPeserta->id ?? "" }}">
                                                                                    @if($peserta_list == 1)
                                                                                        @php
                                                                                            if($item->list_kategori->kategori->id != $kategori_id) {
                                                                                                echo "<h3>".$item->list_kategori->kategori->nama."</h5>";
                                                                                                $kategori_id = $item->list_kategori->kategori->id;
                                                                                            }
                                                                                        @endphp
                                                                                        <h4>{{ $item->list_kategori->pertandingan->nama }}</h3>
                                                                                        {{-- <h5>{{ $peserta->nama }} dari {{ $peserta->kabupaten->name }}</h5> --}}
                                                                                        <div class="flex flex-wrap mt-4 -mx-3" style="display: none;">
                                                                                            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-4/12 lg:flex-none">
                                                                                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                                                                    <div class="mb-4">
                                                                                                        <label for="">Nama <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                                                                                        <input type="text" hidden name="nama_tanding[{{ $urutan }}]" value="{{ @$item->UrutanKomposisiPeserta->nama ?? '' }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                                                                                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                                                                    <div class="mb-4">
                                                                                                        <label for="">Tingkat <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                                                                                        <input type="text" hidden name="tingkat_tanding[{{ $urutan }}]" value="{{ @$item->UrutanKomposisiPeserta->tingkat ?? '' }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Tingkat" id="">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                                                                                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                                                                    <div class="mb-4">
                                                                                                        <label for="">No. Tanding <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                                                                                        <input type="text" hidden readonly value="{{ $item->urutan }}"  class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full"  value="${arr[1]}" placeholder="No Tanding" id="">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="mb-4 urutan_{{$urutan}}">
                                                                                            <label for="">Urutan komposisi yang dimainkan : <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                                                                            <div class="urutan_list" data-id="{{ $urutan }}">
                                                                                                @if (@$item->UrutanKomposisiPeserta->urutan_komposisi)
                                                                                                    @php
                                                                                                        $kelas = explode(",",$item->UrutanKomposisiPeserta->urutan_komposisi);
                                                                                                        $value_kelas = [];
                                                                                                        for($i = 0; $i < 6; $i++) {
                                                                                                            # code...
                                                                                                            if(isset($kelas[$i])) {
                                                                                                                $value_kelas[] = $kelas[$i];
                                                                                                            } else {
                                                                                                                $value_kelas[] = "";
                                                                                                            }
                                                                                                        }
                                                                                                    @endphp
                                                                                                    @foreach ($value_kelas as $a)
                                                                                                        <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}" value="{{ $a }}" id="urutan_tanding_{{ $urutan }} urutan_tanding" class="urutan_tandings dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">
                                                                                                    @endforeach
                                                                                                    {{-- @foreach ($kelas as $a)
                                                                                                        @if($a != "")
                                                                                                        @endif
                                                                                                    @endforeach --}}
                                                                                                @else
                                                                                                    @for($i = 0; $i < 6; $i++)
                                                                                                        <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}" value="" id="urutan_tanding_{{ $urutan }} urutan_tanding" class="urutan_tandings dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">
                                                                                                    @endfor
                                                                                                @endif

                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                @php
                                                                                    $urutan = $urutan + 1;
                                                                                @endphp
                                                                            @endforeach
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4" style="text-align: center;">
                                <button type="submit"  style="border: solid 2px green; color: green; font-size: 22px;"   class="inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Simpan</button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>
<script>
    @section('script')
    $('#cari_peserta_proses').on('submit', function (e) {
        e.preventDefault(); // CEGAH REFRESH!


        let kabupaten_id = $('#kabupaten_id').val();
        let kategori_id = $('#kategori_id').val();
        let pertandingan_id = $('#pertandingan_id').val();
        // let peserta_id = $('#peserta_id').val();


        if (kabupaten_id === null) {
            e.preventDefault(); // stop submit form
            Swal.fire("Warning", "Kabupaten masih kosong!", "error");
            return;
        }

        if (kategori_id === null) {
            e.preventDefault(); // stop submit form
            Swal.fire("Warning", "Kategori masih kosong!", "error");
            return;
        }

        if (pertandingan_id === null) {
            e.preventDefault(); // stop submit form
            Swal.fire("Warning", "Pertandingan masih kosong!", "error");
            return;
        }

        // if (peserta_id === null) {
        //     e.preventDefault(); // stop submit form
        //     Swal.fire("Warning", "Peserta masih kosong!", "error");
        //     return;
        // }

        e.target.submit();
    });

                    $(`#peserta_id  `).select2({
                        theme: 'mytheme', // atau 'bootstrap5' jika pakai tema lain
                        allowClear: true,
                        width: '100%',
                        minimumResultsForSearch: 0 /// biar full lebar // Biar serasi dengan Bootstrap form-control
                    });

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

        function cari_peserta(kabupaten_id, kegiatan_id, kategori_id, pertandingan_id, e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('teknik/cari_peserta/lagi') }}",
                    type: "POST",
                    data: {
                        kabupaten_id: kabupaten_id,
                        kegiatan_id: kegiatan_id,
                        kategori_id: kategori_id,
                        pertandingan_id: pertandingan_id,

                    },
                    success: function(response){
                        console.log(response);
                        console.log(response.peserta.length);
                        $('#peserta_id').html('');
                        let data = "";

                        if(response.peserta.length > 0) {
                            data += `<option disabled selected>Cari Peserta</option>`;
                            $.each(response.peserta, function(index, value) {
                                data += `<option value="${value.id}">${value.nama}</option>`;
                            });
                        } else {
                            if(response.message == "Isi semua form dulu") {
                                data += `<option disabled selected>Lengkapi Form Dulu!</option>`;
                            } else {
                                data += `<option disabled selected>Peserta Tidak Tersedia!</option>`;
                            }
                        }



                        $('#peserta_id').html(data);
                        // Swal.fire('Berhasil', response.message, 'success');
                    },
                    error: function(xhr){
                        console.log(xhr.responseText);
                        Swal.fire('Error', 'Terjadi kesalahan!', 'error');
                    }
                });
        }

        // $('#kabupaten_id').on('change', function (e) {
        //     cari_peserta($("#kabupaten_id").val(), $("#kegiatan_id").val(), $("#kategori_id").val(),$("#pertandingan_id").val(), e);

        // });

        $('#kategori_id').on('change', function (e) {
            console.log($("#kegiatan_id").val());
            kategori_id($("#kegiatan_id").val(),$(this).val());
            // cari_peserta($("#kabupaten_id").val(), $("#kegiatan_id").val(), $("#kategori_id").val(),$("#pertandingan_id").val(), e);
        });

        // $('#pertandingan_id').on('change', function (e) {
        //     cari_peserta($("#kabupaten_id").val(), $("#kegiatan_id").val(), $("#kategori_id").val(),$(this).val(), e);

        // });

        $(document).on('submit', '#form-peserta', function(e) {
            e.preventDefault(); // cegah submit langsung

            Swal.fire({
                title: 'Yakin ingin Simpan?',
                text: "Pastikan data sudah benar sebelum melanjutkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a', // hijau
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // lanjut submit form
                    e.target.submit();
                }
            });
        });
        $(document).on("input", ".urutan_tanding", function () {
            var group = $(this).closest(".urutan_list");
            var ids = $(this).attr("ids");

            // ambil semua input dalam grup
            var inputs = group.find(".urutan_tanding");

            // filter input yang kosong
            var kosong = inputs.filter(function() {
                return $(this).val().trim() === "";
            });

            // kalau tidak ada input kosong sama sekali → tambahkan satu baru
            if (kosong.length === 0) {
                var newInput = $(`
                    <input type="text"
                        name="urutan_tanding[${ids}][]"
                        ids="${ids}"
                        value=""
                        class="urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full"
                        placeholder="Urutan komposisi yang dimainkan">
                `);
                group.append(newInput);
            }
            // kalau lebih dari satu kosong → sisakan hanya satu
            else if (kosong.length > 1) {
                kosong.slice(1).remove();
            }
        });

        $(document).on("blur", ".urutan_tanding", function () {
            var group = $(this).closest(".urutan_list");
            var inputs = group.find(".urutan_tanding");

            // pastikan minimal 1 input tetap ada (jangan semua hilang)
            if (inputs.length === 0) {
                group.append(`<input type="text" name="urutan_tanding[${$(this).attr('ids')}][]" class="urutan_tanding dynamic-textarea ...">`);
            }
        });


    @endsection
</script>
@endsection



