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
                    <form action="{{ url('daftar') }}" method="post" id="form-peserta" enctype="multipart/form-data">
                        @csrf
                        <div class="max-w-md mx-auto bg-white shadow-md p-5 border border-gray-200 p-2">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Keterangan</h2>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 text-sm">
                            <li>Jika ada simbol "<span style="color: red;">*</span><span style="color: red;">*</span>" Harus diisi garis strip atau tanda hubung.</li>
                            <li>Jika ada simbol "<span style="color: red;">*</span>" Wajib diisi.</li>
                        </ul>
                        </div>

                        <div class="p-3 overflow-x-auto">
                            <div class="flex flex-wrap mt-4 -mx-3">
                                <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                        <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">
                                        <div class="mb-4">
                                            <label for="">Nama <span style="color: red;">*</span></label>
                                            <input type="text" name="nama" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama') }}" placeholder="Nama" id="">
                                            <span class="error text-sm text-red-600"></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="">Golongan Darah <span style="color: red;">*</span></label>
                                            <select name="golongan_darah"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
                                                <option value="A" {{ old('golongan_darah', @$peserta->golongan_darah) == 'A' ? 'selected' : '' }} >A</option>
                                                <option value="B" {{ old('golongan_darah', @$peserta->golongan_darah) == 'B' ? 'selected' : '' }} >B</option>
                                                <option value="AB" {{ old('golongan_darah', @$peserta->golongan_darah) == 'AB' ? 'selected' : '' }} >AB</option>
                                                <option value="O" {{ old('golongan_darah', @$peserta->golongan_darah) == 'O' ? 'selected' : '' }} >O</option>
                                            </select>
                                            <span class="error text-sm text-red-600"></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="">Status <span style="color: red;">*</span></label>
                                            {{-- <input type="text" name="status" value=" {{ old('status') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" > --}}
                                            <select name="status"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
                                                <option value="P" {{ old('status', @$peserta->status) == 'P' ? 'selected' : '' }} >Peserta / Atlet</option>
                                                {{-- <option value="O" {{ old('status', @$peserta->status) == 'O' ? 'selected' : '' }} >Official</option> --}}
                                            </select>
                                            <span class="error text-sm text-red-600"></span>
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
                                            <span class="error text-sm text-red-600"></span>
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
                                            <span class="error text-sm text-red-600"></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="kecamatan_id" class="block text-sm font-medium text-gray-700">Kecamatan <span style="color: red;">*</span></label>
                                            <select name="kecamatan_id" id="kecamatan_id" class="w-full border rounded px-3 py-2">
                                                <option selected disabled>Pilih Kecamatan</option>
                                            </select>
                                            <span class="error text-sm text-red-600"></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="kelurahan_id" class="block text-sm font-medium text-gray-700">Kelurahan <span style="color: red;">*</span></label>
                                            <select name="kelurahan_id" id="kelurahan_id" class="w-full border rounded px-3 py-2">
                                                <option selected disabled>Pilih Kelurahan</option>
                                            </select>
                                            <span class="error text-sm text-red-600"></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="">Alamat Lengkap</label>
                                            <textarea name="alamat" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" rows="5">{{ old('alamat') }}</textarea>
                                            <span class="error text-sm text-red-600"></span>
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
                                            <span class="error text-sm text-red-600"></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="">NIK <span style="color: red;">*</span></label>
                                            <input type="text" name="nik" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nik') }}" placeholder="NIK" id=""   onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '');" onkeydown="if(['e','E','+','-','.'].includes(event.key)) event.preventDefault();">
                                            <span class="error text-sm text-red-600"></span>
                                        </div>


                                        <div class="mb-4">
                                            <label for="">Telepon <span style="color: red;">*</span></label>
                                            <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="Nomor Telepon" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
                                            <span class="error text-sm text-red-600"></span>
                                        </div>
                                        <div class="mb-4">
                                            <label for="">Berat Badan (Kg) <span style="color: red;">*</span></label>
                                            <input type="number" min="0" max="1000" name="berat_badan" placeholder="Berat Badan (Kg)" value="{{ old('berat_badan') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
                                            <span class="error text-sm text-red-600"></span>
                                        </div>
                                        <div class="mb-4">
                                            <label for="">Keterangan Khusus Peserta Prestasi Terbaik : <span style="color: red;">*</span></label>
                                            <textarea name="keterangan" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" cols="30" rows="4">{{ old('keterangan') }}</textarea>
                                            <span class="error text-sm text-red-600"></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="">Tempat, Tanggal Lahir  <span style="color: red;">*</span></label>
                                            <div class="flex gap-2">
                                                <input type="text" name="tempat"
                                                    class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-1/2"
                                                    value="{{ old('tempat') }}" placeholder="Tempat Lahir" id="">
                                                <span class="error text-sm text-red-600"></span>
                                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                                    class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-1/2"
                                                    value="{{ old('tanggal_lahir', date('Y-m-d')) }}" id="">
                                                    <span class="error text-sm text-red-600"></span>
                                            </div>
                                        </div>


                                    <div class="mb-4">
                                        <label for="">Kategori <span style="color: red;">*</span></label>
                                        <select name="kategori_id[]" class="pilihan w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" multiple="multiple" status="{{ (is_array(old('kategori_id')) && count(old('kategori_id'))) > 0 ? 'true' : 'false' }}" id="kategori_id">
                                            @foreach ($kategori as $a)
                                                <option value="{{ $a->kategori_id }}">{{ $a->kategori->nama }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error text-sm text-red-600"></span>
                                    </div>
                                    <div class="mb-4">
                                        <label for="">Foto Berwarna 4 x 6 <span style="color: red;">*</span></label>
                                        <input type="file" name="foto" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="imageInput">
                                        <img src="" style="width: 50%;" id="preview" alt=""><br>
                                        <span class="error text-sm text-red-600"></span>
                                    </div>
                                        {{-- <div class="mb-4">
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
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 overflow-x-auto">
                            <div class="flex flex-wrap mt-4 -mx-3">
                                    <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-12/12 lg:flex-none">
                                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                            <h1 class="m-4">Pertandingan</h1>
                                            <div class="kategori_show">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: none;" class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                                        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                            <h1 class="m-4">Tahapan Teknik</h1>
                                            <div class="kategori_pertandingan_show">

                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <br><br>

                            {{-- <h1>Tahapan Teknik</h1>
                            <div class="tanding_list">

                            </div> --}}
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
            document.querySelector('input[name="berat_badan"]').addEventListener('keydown', function(e) {
            if (['e', 'E', '+', '-', '.'].includes(e.key)) e.preventDefault();
            });

        document.getElementById('form-peserta').addEventListener('submit', function(e) {
        e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin mendaftar?',
                text: "Pastikan data sudah benar sebelum melanjutkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a', // hijau
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, daftar sekarang!',
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
                        status: 'Status Wajib Diisi!',
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
                        berat_badan: 'Berat Badan Peserta Wajib Diisi!',
                        keterangan: 'Keterangan Wajib Diisi!',
                        kabupaten_id: 'Kabupaten Wajib Diisi!',
                        kecamatan_id: 'Kecamatan Wajib Diisi!',
                        kelurahan_id: 'Kelurahan Wajib Diisi!',
                        'kategori_id[]': () => {
                        const select = form.querySelector('[name="kategori_id[]"]');
                        const selected = Array.from(select.options).filter(opt => opt.selected);
                        if (selected.length === 0) return 'Minimal pilih satu kategori!';
                        },
                        foto: file => {
                        if (!file) return 'Foto Peserta Wajib Diisi!';
                        const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml'];
                        if (!allowed.includes(file.type)) return 'Foto hanya boleh JPEG, PNG, JPG, atau SVG!';
                        if (file.size > 1024 * 1024) return 'Foto tidak boleh lebih dari 1MB, mohon dikompres!';
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
            });
        });

        $('#kecamatan_id').on('change', function() {
            getWilayah('kecamatan_id', 'kelurahan_id');
        });

        $('#copy_link').on('click', function() {
            copyText("{{ url()->current() }}")
        });



        function AmbilDataPertandingan(tanggal_lahir, jenis_kelamin, kegiatan_id, kategori_id) {
            $('#pertandingan_id').attr('status', 'true');
            $('#pertandingan_id').html('');
            $.ajax({
                url: `{{url("pendaftarans")}}/${tanggal_lahir}/${jenis_kelamin}?kegiatan_id=${kegiatan_id}&kategori_id=${kategori_id}`, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    let data = "";

                    console.log(response);

                    if(response.data.length > 0) {
                        $.each(response.data, function(index, value) {
                            $('#tingkat').val(value.kategori.nama);
                            data += `<option value="${value.id}">${value.pertandingan.nama} | Urutan ${response.urutan[index]}</option>`;
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
                                        <input type="text" name="nama_tanding[]" value="-" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
                                    </div>
                                </div>
                            </div>
                            <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                    <div class="mb-4">
                                        <label for="">Tingkat <span style="color: red;">*</span></label>
                                        <input type="text"  name="tingkat_tanding[]" value="-" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Tingkat" id="">
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
                                <input type="text" name="urutan_tanding[${i}][]" value="-" id="urutan_tanding" class="urutan_tanding_${i} urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">
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


        function AmbilDataPertandinganKategori(tanggal_lahir, jenis_kelamin, kegiatan_id, kategori_id) {
                                            // <h1 class="mt-4 ml-4">Pertandingan kategori : </h1>
                                            // <div class="mt-4 ml-4">
                                            //     <label for="">Kategori</label>
                                            //     <select name="pertandingan_id[]" class="pilihan w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" multiple="multiple" status="{{ (is_array(old('pertandingan_id')) && count(old('pertandingan_id'))) > 0 ? 'true' : 'false' }}" id="pertandingan_id">
                                            //     </select>
                                            // </div>
            $.ajax({
                url: `{{url("pendaftarans")}}/${tanggal_lahir}/${jenis_kelamin}?kegiatan_id=${kegiatan_id}&kategori_id=${kategori_id}`, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    console.log(response);
                    let html = "";
                    let html2 = "";
                    let option = "";
                    if(response.data.length > 0) {
                        $.each(response.data, function(index, value) {
                            option += `<option value="${value.id}">${value.pertandingan.nama} | Urutan ${response.urutan[index]}</option>`;
                        });
                    } else {
                        option += `<option value="" selected>Usia anda tidak tersedia untuk pertandingan ini!</option>`
                    }
                    html += `
                            <div class="mt-4 ml-4">
                                <label for="">${response.kategori.nama}</label>
                                <select name="pertandingan_id[]" ${response.data.length == 0 ? 'disabled' : ''}  class="pertandingan_kategori w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" multiple="multiple" status="{{ (is_array(old('pertandingan_id')) && count(old('pertandingan_id'))) > 0 ? 'true' : 'false' }}" kategori_id="${response.kategori.id}" id="pilihan_${response.kategori.id}">
                                    ${option}
                                </select>
                            </div>

                    `;

                    html2 += `
                        <div class="mt-4 ml-4">
                            <h5>${response.kategori.nama}</h5>
                            <div id="pertandingan_tahapan_teknik_${response.kategori.id}">

                            </div>
                        </div>
                    `;


                    $('.kategori_show').append(html);
                    $('.kategori_pertandingan_show').append(html2);
                    $(`#pilihan_${response.kategori.id}`).select2({
                        theme: 'mytheme', // atau 'bootstrap5' jika pakai tema lain
                        allowClear: true,
                        width: '100%',
                        minimumResultsForSearch: 0 /// biar full lebar // Biar serasi dengan Bootstrap form-control
                    });
                    // alert('Judul: ' + response.title);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal:', error);
                    alert('Gagal mengambil data!');
                }
            });
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
            // console.log($(this).attr('kategori_id'));

            const selected = $(this).val();
            let kategori_id = $(this).attr('kategori_id');


            // console.log()
            if (selected && selected.length > 4) {
                alert('Maksimal hanya bisa memilih 4 Pertandingan!');
                // $('#pertandingan_id').val(null).trigger('change');
                $(this).val(pertandingan_choose[$(this).attr('kategori_id')]).trigger('change');
                return; // stop function
            }

            pertandingan_choose[$(this).attr('kategori_id')] = selected;

            let data = $(this).select2('data'); // ambil semua data select2
            let hasil = data.map(item => {
                return {
                    value: item.id,   // value option
                    text: item.text   // text option
                };
            });


            let data_urutan_list = "";
            $(`#pertandingan_tahapan_teknik_${kategori_id}`).html("");
            for(var i = 0; i < hasil.length; i++) {
                let arr = hasil[i].text.split("|");
                data_urutan_list += `
                    <h3>${arr[0]}</h3>
                    <div class="flex flex-wrap mt-4 -mx-3">
                        <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-4/12 lg:flex-none">
                            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                <div class="mb-4">
                                    <label for="">Nama <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                    <input type="text" name="nama_tanding[${hasil[i].value}]" value="-" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Nama" id="">
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                <div class="mb-4">
                                    <label for="">Tingkat <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                    <input type="text"  name="tingkat_tanding[${hasil[i].value}]" value="-" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Tingkat" id="">
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 mt-0 lg:w-4/12 lg:flex-none">
                            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                                <div class="mb-4">
                                    <label for="">No. Tanding <span style="color: red;">*</span><span style="color: red;">*</span></label>
                                    <input type="text" readonly  class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full"  value="${arr[1]}" placeholder="No Tanding" id="">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- kelas --}}
                    <div class="mb-4 urutan_${i}">
                        <label for="">Urutan komposisi yang dimainkan : <span style="color: red;">*</span><span style="color: red;">*</span></label>
                        <div class="urutan_list" data-id="${i}">
                            <input type="text" name="urutan_tanding[${hasil[i].value}][]" value="-" ids="${hasil[i].value}" value="" id="urutan_tanding" class="urutan_tanding_${i} urutan_tanding dynamic-textarea px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" required placeholder="Urutan komposisi yang dimainkan" id="">
                        </div>
                    </div>
                `;
            }
            // console.log(hasil);
            $(`#pertandingan_tahapan_teknik_${kategori_id}`).html(data_urutan_list);
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

        document.getElementById('imageInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('preview').src = URL.createObjectURL(file);
            }
        });


    @endsection
</script>
