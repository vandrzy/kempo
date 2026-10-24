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
    border: 1px solid #ebebeb !important;
    border-radius: 0.75rem !important;
}

.select2-selection__rendered {
    line-height: 42px !important;
    margin-top: 0 !important;
    font-size: 0.75rem !important;
}

.select2-selection__arrow {
    height: 42px !important;
    top: 0 !important;
    right: 10px !important;
}
</style>

<div class="w-full min-w-0 max-w-full">
    <div class="kempo-card">
        <!-- Header Kartu -->
        <div class="px-6 py-5 border-b border-[#ebebeb] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h4 class="font-heading font-bold text-lg text-[#000000] m-0 leading-tight">Input Teknik Pertandingan</h4>
                <p class="text-xs text-neutral-400 m-0 mt-0.5">Kegiatan: <span class="font-semibold text-neutral-800">{{ $kegiatan->nama }}</span></p>
            </div>
            <div class="flex items-center gap-2">
                @isset($_GET['pertandingan_id'])
                    <a href="{{ url('/teknik/'.$back) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-[#000000] bg-[#ffad9e] hover:bg-[#ff9c8a] transition-all shadow-2xs">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        <span>Kembali</span>
                    </a>
                @endisset
                @if(Auth::user())
                    <button type="button" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-[#000000] bg-[#fff0eb] border border-[#f2470c]/25 hover:bg-[#ffe2d6] transition-all cursor-pointer shadow-2xs" id="copy_link">
                        <i class="fas fa-copy text-[#f2470c]"></i>
                        <span>Salin Link Input Teknik</span>
                    </button>
                @endif
            </div>
        </div>

        @if (!isset($_GET['pertandingan_id']))
            <form action="" method="get" id="cari_peserta_proses" class="p-6 max-w-xl mx-auto space-y-5">
                <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">

                <div>
                    <label for="kabupaten_id" class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Kabupaten / Kota <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="kabupaten_id" id="kabupaten_id" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option selected disabled>Pilih Kabupaten</option>
                        @foreach($kabupatens as $a)
                            <option value="{{ $a->id }}" {{ old('kabupaten_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kategori_id" class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Kategori <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option selected disabled>Pilih Kategori</option>
                        @foreach($kategoris as $a)
                            <option value="{{ $a[0]->kategori->id }}" {{ old('kategori_id') == $a[0]->kategori->id ? 'selected' : '' }}>{{ $a[0]->kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pertandingan_id" class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Pertandingan <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="pertandingan_id" id="pertandingan_id" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option selected disabled>Pilih Pertandingan</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="edit-tanding-form inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl text-xs font-bold font-heading text-white bg-[#f2470c] hover:bg-[#d83c07] shadow-sm hover:shadow-md transition-all active:scale-[0.99] cursor-pointer tracking-wider uppercase">
                        <i class="fas fa-arrow-right"></i>
                        <span>Lanjut Input Teknik</span>
                    </button>
                </div>
            </form>
        @else
            <form action="{{ url('teknik/0') }}" method="post" id="form-peserta" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <!-- Panduan Keterangan -->
                <div class="p-4 rounded-2xl bg-[#fff0eb] border border-[#f2470c]/25 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-[#f2470c]/10 text-[#f2470c] flex items-center justify-center shrink-0 text-sm mt-0.5">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="text-xs">
                        <h5 class="font-heading font-bold text-sm text-[#000000] mb-1">Panduan Pengisian Urutan Komposisi</h5>
                        <ul class="space-y-1 text-neutral-600 m-0 pl-4 list-disc">
                            <li>Tanda <span class="text-[#e54b4f] font-bold">**</span> menandakan kolom wajib diisi tanda hubung / strip (-) bila tidak memiliki data.</li>
                            <li>Kabupaten: <span class="font-bold text-[#000000]">{{ $kabupaten->name }}</span></li>
                        </ul>
                    </div>
                </div>

                <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    @if ($pesertas->count() == 0)
                        <div class="lg:col-span-12 p-8 text-center bg-neutral-50 rounded-2xl border border-[#ebebeb]">
                            <p class="text-sm font-semibold text-neutral-500 m-0">Peserta tidak tersedia untuk pertandingan ini!</p>
                        </div>
                    @else
                        <!-- Kolom Kiri: Tabel Peserta -->
                        <div class="lg:col-span-5">
                            <div class="kempo-card">
                                <div class="px-5 py-3.5 border-b border-[#ebebeb] bg-neutral-50">
                                    <h5 class="font-heading font-bold text-xs text-[#000000] uppercase tracking-wider m-0">Daftar Atlet</h5>
                                </div>
                                <div class="kempo-table-wrapper">
                                    <table class="kempo-table">
                                        <thead>
                                            <tr>
                                                <th class="w-20 text-center tabular-nums">No Urut</th>
                                                <th>Nama Atlet</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pesertas as $peserta)
                                                @foreach ($peserta->PertandinganPeserta as $item)
                                                    <tr>
                                                        <td class="text-center font-bold text-[#f2470c] tabular-nums">
                                                            {{ $item->urutan }}
                                                        </td>
                                                        <td class="font-medium text-[#000000]">
                                                            {{ $peserta->nama }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Input Urutan Komposisi -->
                        <div class="lg:col-span-7 space-y-4">
                            <div class="p-5 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs space-y-4">
                                <h5 class="font-heading font-bold text-sm text-[#000000] border-b border-[#ebebeb] pb-2.5 mb-3 flex items-center gap-2">
                                    <i class="fas fa-list-ol text-[#f2470c]"></i>
                                    <span>Urutan Komposisi yang Dimainkan</span>
                                </h5>

                                @php
                                    $kategori_id = 0;
                                    $urutan = 0;
                                @endphp
                                @foreach ($pesertas as $peserta)
                                    @php
                                        $peserta_list = $loop->iteration;
                                    @endphp
                                    @foreach ($peserta->PertandinganPeserta as $item)
                                        <input type="hidden" name="pertandingan_peserta_id[]" value="{{ $item->id }}">
                                        <input type="hidden" name="komposisi_id[]" value="{{ @$item->UrutanKomposisiPeserta->id ?? "" }}">
                                        @if($peserta_list == 1)
                                            @php
                                                if($item->list_kategori->kategori->id != $kategori_id) {
                                                    echo '<div class="p-3 bg-[#fff0eb] rounded-xl border border-[#f2470c]/20 font-heading font-bold text-xs text-[#000000] uppercase tracking-wider">'.$item->list_kategori->kategori->nama.'</div>';
                                                    $kategori_id = $item->list_kategori->kategori->id;
                                                }
                                            @endphp
                                            <h6 class="font-heading font-bold text-xs text-neutral-700 mt-3 mb-2">{{ $item->list_kategori->pertandingan->nama }}</h6>

                                            <div class="hidden">
                                                <input type="text" hidden name="nama_tanding[{{ $urutan }}]" value="{{ @$item->UrutanKomposisiPeserta->nama ?? '' }}">
                                                <input type="text" hidden name="tingkat_tanding[{{ $urutan }}]" value="{{ @$item->UrutanKomposisiPeserta->tingkat ?? '' }}">
                                                <input type="text" hidden readonly value="{{ $item->urutan }}">
                                            </div>

                                            <div class="mb-4 urutan_{{$urutan}}">
                                                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                                                    Urutan komposisi yang dimainkan <span class="text-[#e54b4f]">**</span>
                                                </label>
                                                <div class="urutan_list space-y-2" data-id="{{ $urutan }}">
                                                    @if (@$item->UrutanKomposisiPeserta->urutan_komposisi)
                                                        @php
                                                            $kelas = explode(",",$item->UrutanKomposisiPeserta->urutan_komposisi);
                                                            $value_kelas = [];
                                                            for($i = 0; $i < 6; $i++) {
                                                                if(isset($kelas[$i])) {
                                                                    $value_kelas[] = $kelas[$i];
                                                                } else {
                                                                    $value_kelas[] = "";
                                                                }
                                                            }
                                                        @endphp
                                                        @foreach ($value_kelas as $a)
                                                            <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}" value="{{ $a }}" class="urutan_tandings dynamic-textarea w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" required placeholder="Urutan komposisi yang dimainkan">
                                                        @endforeach
                                                    @else
                                                        @for($i = 0; $i < 6; $i++)
                                                            <input type="text" name="urutan_tanding[{{ $urutan }}][]" ids="{{ $urutan }}" value="" class="urutan_tandings dynamic-textarea w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" required placeholder="Urutan komposisi yang dimainkan">
                                                        @endfor
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @php
                                            $urutan = $urutan + 1;
                                        @endphp
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if ($pesertas->count() > 0)
                    <div class="mt-8 pt-6 border-t border-[#ebebeb] flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl text-xs font-bold font-heading text-white bg-[#f2470c] hover:bg-[#d83c07] shadow-sm hover:shadow-md transition-all active:scale-[0.99] cursor-pointer tracking-wider uppercase">
                            <i class="fas fa-save"></i>
                            <span>Simpan Urutan Komposisi</span>
                        </button>
                    </div>
                @endif
            </form>
        @endif
    </div>
</div>
@endsection

@section('script')
    function copyText(texts) {
        let text = texts;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Tautan Berhasil Disalin!',
                    html: `<p class="text-xs text-neutral-600 mb-2">Tautan input teknik:</p><input type="text" readonly value="${text}" class="w-full text-xs p-2 rounded-lg border border-[#ebebeb] bg-neutral-50 text-center font-mono">`,
                    confirmButtonColor: '#f2470c',
                    confirmButtonText: 'Tutup'
                });
            });
        } else {
            let $temp = $("<textarea>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
            Swal.fire({
                icon: 'success',
                title: 'Tautan Berhasil Disalin!',
                html: `<p class="text-xs text-neutral-600 mb-2">Tautan input teknik:</p><input type="text" readonly value="${text}" class="w-full text-xs p-2 rounded-lg border border-[#ebebeb] bg-neutral-50 text-center font-mono">`,
                confirmButtonColor: '#f2470c',
                confirmButtonText: 'Tutup'
            });
        }
    }

    $('#copy_link').on('click', function() {
        copyText("{{ url()->current() }}");
    });

    $('#cari_peserta_proses').on('submit', function (e) {
        let kabupaten_id = $('#kabupaten_id').val();
        let kategori_id_val = $('#kategori_id').val();
        let pertandingan_id = $('#pertandingan_id').val();

        if (kabupaten_id === null) {
            e.preventDefault();
            Swal.fire("Warning", "Kabupaten masih kosong!", "error");
            return;
        }

        if (kategori_id_val === null) {
            e.preventDefault();
            Swal.fire("Warning", "Kategori masih kosong!", "error");
            return;
        }

        if (pertandingan_id === null) {
            e.preventDefault();
            Swal.fire("Warning", "Pertandingan masih kosong!", "error");
            return;
        }
    });

    $('#peserta_id').select2({
        theme: 'mytheme',
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0
    });

    function kategori_id(kegiatan_id, id) {
        $('#pertandingan_id').attr('status', 'true');
        $('#pertandingan_id').html('');
        $.ajax({
            url: `{{url("pertandingans")}}/${id}?type=pertandingan_id&kegiatan_id=${kegiatan_id}`,
            type: 'GET',
            dataType: 'json',
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

    $('#kategori_id').on('change', function (e) {
        kategori_id($("#kegiatan_id").val(), $(this).val());
    });

    $(document).on('submit', '#form-peserta', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin Simpan?',
            text: "Pastikan data sudah benar sebelum melanjutkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    });

    $(document).on("input", ".urutan_tanding", function () {
        var group = $(this).closest(".urutan_list");
        var ids = $(this).attr("ids");
        var inputs = group.find(".urutan_tanding");
        var kosong = inputs.filter(function() {
            return $(this).val().trim() === "";
        });

        if (kosong.length === 0) {
            var newInput = $(`
                <input type="text"
                    name="urutan_tanding[${ids}][]"
                    ids="${ids}"
                    value=""
                    class="urutan_tanding dynamic-textarea w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs mt-2"
                    placeholder="Urutan komposisi yang dimainkan">
            `);
            group.append(newInput);
        } else if (kosong.length > 1) {
            kosong.slice(1).remove();
        }
    });

    $(document).on("blur", ".urutan_tanding", function () {
        var group = $(this).closest(".urutan_list");
        var inputs = group.find(".urutan_tanding");
        if (inputs.length === 0) {
            group.append(`<input type="text" name="urutan_tanding[${$(this).attr('ids')}][]" class="urutan_tanding dynamic-textarea w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] outline-none">`);
        }
    });
@endsection
