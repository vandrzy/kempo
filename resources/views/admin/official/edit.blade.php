@extends('tamplate')
@section('title', 'Edit Official')
@section('content')
    {{-- <h1>Edit Data Peserta</h1> --}}
    {{-- <hr> --}}
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="dark:text-white">Edit Data Official</h4>
                    </div>
                    <form action="{{ url('official/'.$peserta->id) }}" method="post" id="form-peserta" enctype="multipart/form-data">
                        <div class="flex justify-between items-center p-1">
                            <div>
                                <a href="{{ url('official') }}" class="bg-black text-white px-4 py-3 rounded">Kembali</a>
                            </div>
                            <h4>{{ $kegiatan->nama }}</h4>
                        </div>
                        <br>
                        @csrf
                        @method('PUT')
                        @include('admin.official.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    @section('script')
        // const komposisi = @json($peserta->PertandinganPeserta);
        // console.log(komposisi[0]);
            // document.querySelector('input[name="berat_badan"]').addEventListener('keydown', function(e) {
            // if (['e', 'E', '+', '-', '.'].includes(e.key)) e.preventDefault();
            // });

        document.getElementById('form-peserta').addEventListener('submit', function(e) {
        e.preventDefault();
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
                    $('.error').html("");
                    const form = e.target;
                    const data = new FormData(form);
                    const errors = [];

                    const rules = {
                        nama: 'Nama Peserta Wajib Diisi!',
                        jenis_kelamin: 'Jenis Kelamin Wajib Diisi!',
                        golongan_darah: 'Golongan Darah Wajib Diisi!',
                        nik: val => {
                        if (!val) return 'NIK Wajib Diisi!';
                        if (isNaN(val)) return 'NIK Wajib Diisi dengan Angka!';
                        if (val.length > 16) return 'NIK Maksimal 16 digit!';
                        },
                        // status: 'Status Wajib Diisi!',
                        tingkatan_sabuk_a: 'Tingkat Wajib Diisi!',
                        tingkatan_sabuk_b: 'Tingkat Wajib Diisi!',
                        tempat: 'Tempat Lahir Wajib Diisi!',
                        tanggal_lahir: 'Tanggal Lahir Wajib Diisi!',
                        alamat: 'Alamat Peserta Wajib Diisi!',
                        telepon: val => {
                        if (!val) return 'Nomor Telepon Peserta Wajib Diisi!';
                        if (isNaN(val)) return 'Nomor Telepon harus angka!';
                        if (val.length > 13) return 'Nomor Telepon tidak boleh melebihi 13 digit!';
                        },
                        // berat_badan: 'Berat Badan Peserta Wajib Diisi!',
                        keterangan: 'Keterangan Wajib Diisi!',
                        kabupaten_id: 'Kabupaten Wajib Diisi!',
                        // kecamatan_id: 'Kecamatan Wajib Diisi!',
                        // kelurahan_id: 'Kelurahan Wajib Diisi!',
                        // 'kategori_id[]': () => {
                        // const select = form.querySelector('[name="kategori_id[]"]');
                        // const selected = Array.from(select.options).filter(opt => opt.selected);
                        // if (selected.length === 0) return 'Minimal pilih satu kategori!';
                        // },
                        foto: file => {
                            if (!file) {
                                return 'Foto Peserta Wajib Diisi!';
                            } else {
                                if (!file) {
                                    const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
                                    if (!allowed.includes(file.type)) return 'Foto hanya boleh JPEG, PNG, JPG, atau SVG!';
                                    if (file.size > 1024 * 1024) return 'Foto tidak boleh lebih dari 1MB, mohon dikompres!';
                                }
                            }
                        }
                    };

                    for (const [field, message] of Object.entries(rules)) {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (typeof message === 'function') {
                            const val = field === 'foto' ? data.get(field) : data.get(field)?.trim();
                            const error = message(val);
                            if (error) {
                                errors.push(error);
                                const errorEl = input.closest('.mb-4').querySelector('.error');
                                if (errorEl) {
                                    errorEl.textContent = error;

                                }
                            }
                        } else {
                            const val = data.get(field);
                            if (!val || (val instanceof File && !val.name)) {
                                errors.push(message);
                                const errorEl = input.closest('.mb-4').querySelector('.error');
                                if (errorEl) {
                                    errorEl.textContent = message;
                                }
                            }
                        }
                    }

                    if (errors.length > 0) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Lengkapi form yang belum terisi!",
                        });
                        return false;
                    }

                    // Kalau semua valid → bisa lanjut submit form
                    form.submit();
                }
            });

        });

        function copyText(texts) {
            let text = texts;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    alert("Teks berhasil disalin: " + text);
                });
            } else {
                // fallback execCommand
                let $temp = $("<textarea>");
                $("body").append($temp);
                $temp.val(text).select();
                document.execCommand("copy");
                $temp.remove();
                alert("Url berhasil disalin!");
            }
        }

        function getWilayah(type, spawning, callback) {
            let value = $(`#${type}`).val();
            let urls = `{{ url("getWilayah") }}/${type}/${value}`;

            $.ajax({
                url: urls, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    let data = "";

                    // console.log(response);

                    if(response.data.length > 0) {
                        // data += `<option value="" disabled>Data Tidak Tersedia!</option>`;

                        $.each(response.data, function(index, value) {
                            data += `<option value="${value.id}">${value.name}</option>`;
                        });
                    } else {
                        data += `<option value="" disabled>Data Tidak Tersedia!</option>`;
                    }

                    // data += `<option value="" disabled>Pilih Pertandingan</option>`;

                    $(`#${spawning}`).html(data);
                    if (callback) callback(); // panggil callback setelah selesai


                    // alert('Judul: ' + response.title);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal:', error);
                    alert('Gagal mengambil data!');
                }
            });
        }

        $('#kabupaten_id').on('change', function() {
            getWilayah('kabupaten_id', 'kecamatan_id', function() {
                $(`#kelurahan_id`).html("");
                getWilayah('kecamatan_id', 'kelurahan_id');
                @isset($peserta)
                    $('#kecamatan_id').val('{{ $peserta->kecamatan_id }}').trigger('change');
                @endisset

            });
        });

        $('#kecamatan_id').on('change', function() {
            getWilayah('kecamatan_id', 'kelurahan_id');
            @isset($peserta)
                $('#kelurahan_id').val('{{ $peserta->kelurahan_id }}').trigger('change');
            @endisset
        });

        $('#copy_link').on('click', function() {
            copyText("{{ url()->current() }}")
        });




        let lastValid = [];
        $('#pertandingan_id').on('change', function () {
            // let selected = $(this).val();
            // console.log("Dipilih:", selected);

                const selected = $(this).val();
                lastValid = selected;


                if (selected && selected.length > 3) {
                    lastValid.splice(3, 1);
                    alert('Maksimal hanya bisa memilih 3 pertandingan!');
                    // $('#pertandingan_id').val(null).trigger('change');
                    $('#pertandingan_id').val(lastValid).trigger('change');
                    return; // stop function
                }

                // simpan pilihan terakhir yang valid
                lastValid = selected;

                let data = $(this).select2('data'); // ambil semua data select2
                let hasil = data.map(item => {
                    return {
                        value: item.id,   // value option
                        text: item.text   // text option
                    };
                });


                let data_urutan_list = "";
                for(var i = 0; i < hasil.length; i++) {
                    let arr = hasil[i].text.split("|");
                    data_urutan_list += `
                        <h3>${arr[0]}</h3>
                        <div class="flex flex-wrap mt-4 -mx-3">
                            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-4/12 lg:flex-none">
                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                    <div class="mb-4">
                                        <label for="">Nama <span style="color: red;">*</span></label>
                                        <input type="text" name="nama_tanding[]" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
                                    </div>
                                </div>
                            </div>
                            <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                    <div class="mb-4">
                                        <label for="">Tingkat <span style="color: red;">*</span></label>
                                        <input type="text"  name="tingkat_tanding[]" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Tingkat" id="">
                                    </div>
                                </div>
                            </div>
                            <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                    <div class="mb-4">
                                        <label for="">No. Tanding <span style="color: red;">*</span></label>
                                        <input type="text" readonly  class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full"  value="${arr[1]}" placeholder="No Tanding" id="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- kelas --}}
                        <div class="mb-4 urutan_${i}">
                            <label for="">Urutan komposisi yang dimainkan : <span style="color: red;">*</span></label>
                            <div class="urutan_list" data-id="${i}">
                                <input type="text" name="urutan_tanding[${i}][]" value="" id="urutan_tanding" class="urutan_tanding_${i} urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">
                            </div>
                        </div>
                    `;
                }
                // console.log(hasil);
                $('.tanding_list').html(data_urutan_list);
        });

        $('#jenis_kelamin').on('change', function (e) {

            $('.kategori_show').html('');
            $('.kategori_pertandingan_show').html('');
            for(var i = 0; i < $("#kategori_id").val().length; i++) {
                AmbilDataPertandinganKategori($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val(), $("#kategori_id").val()[i]);
            }
            // AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val());
        });

        $('#tanggal_lahir').on('change', function (e) {

            $('.kategori_show').html('');
            $('.kategori_pertandingan_show').html('');
            for(var i = 0; i < $("#kategori_id").val().length; i++) {
                AmbilDataPertandinganKategori($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val(), $("#kategori_id").val()[i]);
            }
            // AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val());
        });

        function PertandinganKategori(selected_value, kategori_id_value, element, exist = null) {
            // console.log($(this).attr('kategori_id'));

            const selected = selected_value;
            // let kategori_id = $(this).attr('kategori_id');
            let kategori_id = kategori_id_value;
            console.log(kategori_id);



            // console.log()
            if (selected && selected.length > 4) {
                alert('Maksimal hanya bisa memilih 4 Pertandingan!');
                // $('#pertandingan_id').val(null).trigger('change');
                // $(this).val(pertandingan_choose[kategori_id]).trigger('change');
                $(element).val(pertandingan_choose[kategori_id]).trigger('change');

                return; // stop function
            }

            pertandingan_choose[kategori_id] = selected;

            let data = $(element).select2('data'); // ambil semua data select2
            console.log(data);
            // let data = $(this).select2('data'); // ambil semua data select2

            let hasil = data.map(item => {
                return {
                    value: item.id,   // value option
                    text: item.text   // text option
                };
            });


            let data_urutan_list = "";
            $(`#pertandingan_tahapan_teknik_${kategori_id}`).html("");
            for(var i = 0; i < hasil.length; i++) {
                let data_saat_ini = [];
                let arr = hasil[i].text.split("|");
                let komposisi_html = '';
                if(exist == true) {
                    for(var j = 0; j < komposisi.length; j++) {
                        if(komposisi[j]['list_kategori_id'] == hasil[i].value) {
                            data_saat_ini = komposisi[j];
                            break;
                        }
                    }
                    console.log(data_saat_ini);
                    let komposisi_list = data_saat_ini?.urutan_komposisi_peserta?.urutan_komposisi?.split(",") || [];
                    console.log(komposisi_list);
                    for (let o = 0; o < komposisi_list.length; o++) {
                        if(komposisi_list[o] != '') {
                            komposisi_html += `
                                <input type="text"
                                    name="urutan_tanding[${hasil[i].value}][]"
                                    ids="${hasil[i].value}"
                                    value="${komposisi_list[o] ?? '-'}"
                                    id="urutan_tanding"
                                    class="urutan_tanding_${i} urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full"

                                    placeholder="Urutan komposisi yang dimainkan">`;
                        }

                    }
                }
                data_urutan_list += `
                    <h3>${arr[0]} </h3>
                    <div class="flex flex-wrap mt-4 -mx-3">
                        <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-4/12 lg:flex-none">
                            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                <div class="mb-4">
                                    <label for="">Nama <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                    <input type="text" name="nama_tanding[${hasil[i].value}]" value="${data_saat_ini?.urutan_komposisi_peserta?.nama ?? ""}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                <div class="mb-4">
                                    <label for="">Tingkat <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                    <input type="text"  name="tingkat_tanding[${hasil[i].value}]" value="${data_saat_ini?.urutan_komposisi_peserta?.tingkat ?? ""}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Tingkat" id="">
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                <div class="mb-4">
                                    <label for="">No. Tanding <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                    <input type="text" readonly value="${data_saat_ini?.urutan ?? ""}"  class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full"  value="${arr[1]}" placeholder="No Tanding" id="">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- kelas --}}
                    <div class="mb-4 urutan_${i}">
                        <label for="">Urutan komposisi yang dimainkan : <span style="color: red;">*</span><span style="color: red;">*</span></label>
                        <div class="urutan_list" data-id="${i}">
                            ${exist == true ? komposisi_html : `<input type="text" name="urutan_tanding[${hasil[i].value}][]" ids="${hasil[i].value}" value="${data_saat_ini?.urutan_komposisi_peserta?.urutan_komposisi ?? ""}" id="urutan_tanding" class="urutan_tanding_${i} urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">`}
                        </div>
                    </div>
                `;
                $(`.urutan_tanding_${i}`).val(data_saat_ini?.urutan_komposisi_peserta?.urutan_komposisi).trigger('change');
            }
            // console.log(hasil);
            $(`#pertandingan_tahapan_teknik_${kategori_id}`).html(data_urutan_list);
        }




        let kategori_choose = [];
        let pertandingan_choose = [];
        $('#kategori_id').on('change', function (e) {
            console.log($(this).val());

            $('.kategori_show').html('');
            $('.kategori_pertandingan_show').html('');

            const selected = $(this).val();

            // console.log()
            if (selected && selected.length > 4) {
                alert('Maksimal hanya bisa memilih 4 Kategori!');
                // $('#pertandingan_id').val(null).trigger('change');
                $('#kategori_id').val(kategori_choose).trigger('change');
                return; // stop function
            }

            kategori_choose = selected;

            pertandingan_choose = {};;
            for(var i = 0; i < $(this).val().length; i++) {
                pertandingan_choose[$(this).val()[i]] = [];
                AmbilDataPertandinganKategori($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val(), $(this).val()[i]);
            }

            console.log(pertandingan_choose);

            // AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val());
        });



        $(document).on('change', '.pertandingan_kategori',function(e) {
            PertandinganKategori($(this).val(), $(this).attr('kategori_id'), this);
        });

        // document.getElementById('urutan_tanding').addEventListener('change', function() {
        //     let arr = this.value.split(/\n|,/); // pisahkan enter atau koma
        //     console.log(arr); // array hasil input
        // });



        // $('#pertandingan_id').on('select2:opening', function (e) {
        //     if($(this).attr('status') != 'true') {
        //         AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val());
        //     }
        // });


        $(document).on("input", ".urutan_tanding", function () {
                var group = $(this).closest(".urutan_list"); // ambil group tempat input ini
                var inputs = group.find(".urutan_tanding");

                // cari semua yang kosong
                var kosong = inputs.filter(function() {
                    return $(this).val().trim() === "";
                });

                // kalau tidak ada kosong → tambahin 1
                if (kosong.length === 0) {
                    var newInput = $(`
                        <input type="text" name="urutan_tanding[${$(this).attr('ids')}][]" value="-" class="urutan_tanding_${$(this).attr('ids')} urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" placeholder="Urutan komposisi yang dimainkan" id="">
                    `);
                    group.append(newInput);
                } else {
                    // kalau ada lebih dari 1 kosong → sisakan 1
                    kosong.slice(1).remove();
                }

                // tambahInput($(this).attr('ids'));
        });

        $(document).on("blur", ".urutan_tanding", function (e) {
            $(".urutan_list").each(function() {
                // cari input kosong di group ini
                var kosong = $(this).find("input[name='urutan_tanding[]']").filter(function() {
                    return $(this).val().trim() === "";
                });

                // hapus semua kecuali 1 kosong pertama
                kosong.slice(1).remove();
            });
        });


    @endsection
</script>
@endsection
