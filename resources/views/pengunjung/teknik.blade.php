@extends('tamplate')
@section('title', 'Input Teknik | Pendaftaran Kempo')
@section('content')
<div class="max-w-6xl mx-auto my-6 px-4">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="dark:text-white"> Kegiatan {{ $peserta->PertandinganPeserta->first()->list_kategori->kegiatan->nama }} | Input Teknik Peserta {{ $peserta->nama }}</h4>
                        @if(Auth::user())
                            <button style="background-color: rgb(53, 53, 53);" class="text-white px-4 py-2 rounded" id="copy_link">Salin Link Input Teknik</button>
                        @endif
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <form action="{{ url('teknik/'.$peserta_id) }}" method="post" id="form-peserta" enctype="multipart/form-data">
                        @csrf
                        <div class="max-w-md mx-auto bg-white shadow-md p-5 border border-gray-200 p-2">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Keterangan</h2>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-sm">
                            <li>Jika ada simbol "<span style="color: red;">*</span><span style="color: red;">*</span>" Harus diisi garis strip atau tanda hubung.</li>
                            {{-- <li>Jika ada simbol "<span style="color: red;">*</span>" Wajib diisi.</li> --}}
                        </ul>
                        </div>

                        <div class="p-3 overflow-x-auto">
                            <div class="flex flex-wrap mt-4 -mx-3">
                                <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-12/12 lg:flex-none">
                                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                        <h1 class="m-4">Tahapan Teknik</h1>
                                        <div class="kategori_pertandingan_show">
                                        @php
                                            $kategoriList = $peserta->PertandinganPeserta->pluck('list_kategori.kategori')->unique()->values();
                                            $pertandingan = $peserta->PertandinganPeserta->pluck('list_kategori.pertandingan')->unique()->values();
                                            // dd($kategoriList);
                                        @endphp
                                        {{-- {{ $peserta->PertandinganPeserta->list_kategori->kategori }} --}}
                                        @php
                                            $kategori_id = 0;
                                            $urutan = 0;
                                        @endphp
                                        @foreach ($peserta->PertandinganPeserta as $item)
                                            <div class="mt-4 ml-4">
                                                @php
                                                    if($item->list_kategori->kategori->id != $kategori_id) {
                                                        echo "<h5>".$item->list_kategori->kategori->nama."</h5>";
                                                        $kategori_id = $item->list_kategori->kategori->id;
                                                    }
                                                @endphp
                                                <input type="hidden" name="pertandingan_peserta_id[]" value="{{ $item->id }}">
                                                <input type="hidden" name="komposisi_id[]" value="{{ @$item->UrutanKomposisiPeserta->id ?? "" }}">
                                                <h3>{{ $item->list_kategori->pertandingan->nama }}</h3>
                                                <div class="flex flex-wrap mt-4 -mx-3">
                                                    <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-4/12 lg:flex-none">
                                                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                            <div class="mb-4">
                                                                <label for="">Nama <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                                                <input type="text" name="nama_tanding[{{ $urutan }}]" value="{{ @$item->UrutanKomposisiPeserta->nama ?? '' }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                                                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                            <div class="mb-4">
                                                                <label for="">Tingkat <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                                                <input type="text"  name="tingkat_tanding[{{ $urutan }}]" value="{{ @$item->UrutanKomposisiPeserta->tingkat ?? '' }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Tingkat" id="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                                                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                                            <div class="mb-4">
                                                                <label for="">No. Tanding <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                                                <input type="text" readonly value="{{ $item->urutan }}"  class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full"  value="${arr[1]}" placeholder="No Tanding" id="">
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
                                                            @endphp
                                                            @foreach ($kelas as $a)
                                                                @if($a != "")
                                                                    <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}" value="{{ $a }}" id="urutan_tanding_{{ $urutan }} urutan_tanding" class="urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">
                                                                @endif
                                                            @endforeach
                                                        @else
                                                                <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}" value="" id="urutan_tanding_{{ $urutan }} urutan_tanding" class="urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                $urutan = $urutan + 1;
                                            @endphp
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4" style="text-align: center;">
                                <button type="submit"  style="border: solid 2px green; color: green; font-size: 22px;"   class="inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<script>
    @section('script')

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



