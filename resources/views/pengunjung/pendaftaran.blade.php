<div class="max-w-6xl mx-auto my-6 px-4">
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="dark:text-white">Pendaftaran Peserta {{ $kegiatan->nama }}</h4>
                        @if(Auth::user())
                            <button style="background-color: rgb(53, 53, 53);" class="text-white px-4 py-2 rounded" id="copy_link">Salin Link Pendaftaran</button>
                        @endif
                    </div>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <form action="{{ url('daftar') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="p-3 overflow-x-auto">
                            <div class="flex flex-wrap mt-4 -mx-3">
                                <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                        <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">
                                        <div class="mb-4">
                                            <label for="">Nama <span style="color: red;">*</span></label>
                                            <input type="text" name="nama" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama') }}" placeholder="Nama" id="">
                                            @error('nama')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="">Golongan Darah <span style="color: red;">*</span></label>
                                            <select name="golongan_darah"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
                                                <option value="A" {{ old('golongan_darah', @$peserta->golongan_darah) == 'A' ? 'selected' : '' }} >A</option>
                                                <option value="B" {{ old('golongan_darah', @$peserta->golongan_darah) == 'B' ? 'selected' : '' }} >B</option>
                                                <option value="AB" {{ old('golongan_darah', @$peserta->golongan_darah) == 'AB' ? 'selected' : '' }} >AB</option>
                                                <option value="O" {{ old('golongan_darah', @$peserta->golongan_darah) == 'O' ? 'selected' : '' }} >O</option>
                                            </select>
                                            @error('golongan_darah')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="">Status <span style="color: red;">*</span></label>
                                            {{-- <input type="text" name="status" value=" {{ old('status') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" > --}}
                                            <select name="status"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
                                                <option value="P" {{ old('status', @$peserta->status) == 'P' ? 'selected' : '' }} >Peserta / Atlet</option>
                                                <option value="O" {{ old('status', @$peserta->status) == 'O' ? 'selected' : '' }} >Official</option>
                                            </select>
                                            @error('status')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="">Tingkat <span style="color: red;">*</span></label>
                                            {{-- <input type="text" name="status" value=" {{ old('status') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" > --}}
                                            <div class="flex gap-2">
                                                <select name="tingkatan_sabuk_a"  class="w-1/2 pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
                                                    <option value="KYU" {{ old('tingkatan_sabuk_a') == 'KYU' ? 'selected' : '' }} >KYU</option>
                                                    <option value="DAN" {{ old('tingkatan_sabuk_a') == 'DAN' ? 'selected' : '' }} >DAN</option>
                                                </select>
                                                <input type="number" name="tingkatan_sabuk_b" class="w-1/2 px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500" value="{{ old('nama') }}" placeholder="Jumlah Tingkatan" id="">
                                            </div>
                                            @error('tingkatan_sabuk_a')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                            @error('tingkatan_sabuk_b')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <label for="">Alamat Rumah <span style="color: red;">*</span></label>
                                        <div class="mb-4">
                                            <label for="kabupaten_id" class="block text-sm font-medium text-gray-700">Kabupaten <span style="color: red;">*</span></label>
                                            <select name="kabupaten_id" id="kabupaten_id" class="w-full border rounded px-3 py-2">
                                                <option selected disabled>Pilih Kabupaten</option>
                                                @foreach($kabupatens as $a)
                                                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('kabupaten_id')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="kecamatan_id" class="block text-sm font-medium text-gray-700">Kecamatan <span style="color: red;">*</span></label>
                                            <select name="kecamatan_id" id="kecamatan_id" class="w-full border rounded px-3 py-2">
                                                <option selected disabled>Pilih Kecamatan</option>
                                            </select>
                                            @error('kecamatan_id')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="kelurahan_id" class="block text-sm font-medium text-gray-700">Kelurahan <span style="color: red;">*</span></label>
                                            <select name="kelurahan_id" id="kelurahan_id" class="w-full border rounded px-3 py-2">
                                                <option selected disabled>Pilih Kelurahan</option>
                                            </select>
                                            @error('kelurahan_id')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <textarea name="alamat" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" rows="5">
                                                {{ old('alamat') }}
                                            </textarea>
                                            @error('alamat')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
                                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                        <div class="mb-4">
                                            <label for="">Jenis Kelamin <span style="color: red;">*</span></label>
                                            <select name="jenis_kelamin"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="jenis_kelamin">
                                                <option value="Laki-Laki" {{ old('jenis_kelamin', @$peserta->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }} >Laki-Laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin', @$peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }} >Perempuan</option>

                                            </select>
                                            @error('jenis_kelamin')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="">NIK <span style="color: red;">*</span></label>
                                            <input type="text" name="nik" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nik') }}" placeholder="NIK" id="">
                                            @error('nik')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="mb-4">
                                            <label for="">Telepon <span style="color: red;">*</span></label>
                                            <input type="text" name="telepon" value=" {{ old('telepon') }}" placeholder="Nomor Telepon" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
                                            @error('telepon')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="">Berat Badan <span style="color: red;">*</span></label>
                                            <input type="number" min="0" max="1000" name="berat_badan" placeholder="Berat Badan (Kg)" value=" {{ old('berat_badan') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
                                            @error('berat_badan')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="">Keterangan Khusus Peserta Prestasi Terbaik : <span style="color: red;">*</span></label>
                                            <textarea name="keterangan" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" cols="30" rows="4">
                                                {{ old('keterangan') }}
                                            </textarea>
                                            @error('keterangan')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="">Tempat, Tanggal Lahir  <span style="color: red;">*</span></label>
                                            <div class="flex gap-2">
                                                <input type="text" name="tempat"
                                                    class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-1/2"
                                                    value="{{ old('tempat') }}" placeholder="Tempat Lahir" id="">

                                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                    class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-1/2"
                                                    value="{{ old('tanggal_lahir', date('Y-m-d')) }}" id="">
                                            </div>
                                            @error('tempat')
                                                <span class="text-red" style="color: red;">{{ $message }}</span><br>
                                            @enderror
                                            @error('tanggal_lahir')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>



                                        <div class="mb-4">
                                            <label for="">Kategori <span style="color: red;">*</span></label>
                                            <input type="text" name="tingkat" readonly class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('tingkat') }}" placeholder="Kategori" id="tingkat">
                                            @error('tingkat')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label for="">Nomor dan Kelas Pertandingan yang akan diikuti : <span style="color: red;">*</span></label>
                                            <select name="pertandingan_id[]" class="pilihan w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" multiple="multiple" status="{{ (is_array(old('pertandingan_id')) && count(old('pertandingan_id'))) > 0 ? 'true' : 'false' }}" id="pertandingan_id">
                                            </select>
                                            @error('pertandingan_id')
                                                <span class="text-red" style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 overflow-x-auto">
                            <div class="mb-4">
                                <label for="">Foto Berwarna 4 x 6 <span style="color: red;">*</span></label>
                                <input type="file" name="foto" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="imageInput">
                                <img src="" style="width: 50%;" id="preview" alt=""><br>
                                @error('foto')
                                    <span class="text-red"  style="color: red;" >{{ $message }}</span>
                                @enderror
                            </div>
                            <h1>Tahapan Teknik</h1>
                            <div class="tanding_list">
                                {{-- @for($i = 0; $i < 2; $i++)

                                @endfor --}}
                            </div>
                            <div class="mb-4" style="text-align: center;">
                                <button type="submit"  style="border: solid 2px green; color: green; font-size: 22px;"   class="inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Daftar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
@php
    $url_saatini =  url()->current();
@endphp
<script>
    @section('script')
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
            });
        });

        $('#kecamatan_id').on('change', function() {
            getWilayah('kecamatan_id', 'kelurahan_id');
        });

        $('#copy_link').on('click', function() {
            copyText("{{ url()->current() }}")
        });



        function AmbilDataPertandingan(tanggal_lahir, jenis_kelamin, kegiatan_id) {
            $('#pertandingan_id').attr('status', 'true');
            $('#pertandingan_id').html('');
            $.ajax({
                url: `{{url("pendaftarans")}}/${tanggal_lahir}/${jenis_kelamin}?kegiatan_id=${kegiatan_id}`, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    let data = "";

                    // console.log(response.data);

                    if(response.data.length > 0) {
                        $.each(response.data, function(index, value) {
                            $('#tingkat').val(value.kategori.nama);
                            data += `<option value="${value.id}">${value.pertandingan.nama}</option>`;
                        });
                    } else {
                        data += `<option value="" disabled>Pertandingan Tidak Tersedia!</option>`;
                    }

                    // data += `<option value="" disabled>Pilih Pertandingan</option>`;

                    $('#pertandingan_id').html(data);


                    // alert('Judul: ' + response.title);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal:', error);
                    alert('Gagal mengambil data!');
                }
            });
        }

        $('#pertandingan_id').on('change', function () {
            // let selected = $(this).val();
            // console.log("Dipilih:", selected);

                let data = $(this).select2('data'); // ambil semua data select2
                let hasil = data.map(item => {
                    return {
                        value: item.id,   // value option
                        text: item.text   // text option
                    };
                });

                let data_urutan_list = "";
                for(var i = 0; i < hasil.length; i++) {
                    data_urutan_list += `
                        <h3>${hasil[i].text}</h3>
                        <div class="flex flex-wrap mt-4 -mx-3">
                            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                    <div class="mb-4">
                                        <label for="">Nama <span style="color: red;">*</span></label>
                                        <input type="text" name="nama_tanding[]" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
                                    </div>
                                </div>
                            </div>
                            <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                    <div class="mb-4">
                                        <label for="">Tingkat <span style="color: red;">*</span></label>
                                        <input type="text"  name="tingkat_tanding[]" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
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
            AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val());
        });

        $('#tanggal_lahir').on('change', function (e) {
            AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val());
        });

        // document.getElementById('urutan_tanding').addEventListener('change', function() {
        //     let arr = this.value.split(/\n|,/); // pisahkan enter atau koma
        //     console.log(arr); // array hasil input
        // });



        $('#pertandingan_id').on('select2:opening', function (e) {
            if($(this).attr('status') != 'true') {
                AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val());
            }
        });


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
                        <input type="text" name="urutan_tanding[${$(this).attr('ids')}][]" value="" class="urutan_tanding_${$(this).attr('ids')} urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" placeholder="Urutan komposisi yang dimainkan" id="">
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
