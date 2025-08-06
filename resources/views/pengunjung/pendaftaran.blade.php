<div class="max-w-6xl mx-auto my-6 px-4">
    <h1>Pendaftaran Peserta {{ $kegiatan->nama }}</h1><br>
    <hr><br>
    <form action="{{ url('daftar') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">
        {{-- <div class="mb-4">
            <label for="">Nomor <span style="color: red;">*</span></label>
            <input type="number" name="nomor" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nomor') }}" placeholder="Nomor" id="">
            @error('nomor')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div> --}}
        <div class="mb-4">
            <label for="">Nama <span style="color: red;">*</span></label>
            <input type="text" name="nama" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama') }}" placeholder="Nama" id="">
            @error('nama')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Jenis Kelamin <span style="color: red;">*</span></label>
            <select name="jenis_kelamin"  class="form-control pilihan" id="jenis_kelamin">
                <option value="Laki-Laki" {{ old('jenis_kelamin', @$peserta->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }} >Laki-Laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin', @$peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }} >Perempuan</option>

            </select>
            @error('jenis_kelamin')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Golongan Darah <span style="color: red;">*</span></label>
            <select name="golongan_darah"  class="form-control pilihan" id="">
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
            <label for="">NIK <span style="color: red;">*</span></label>
            <input type="text" name="nik" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nik') }}" placeholder="NIK" id="">
            @error('nik')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Status <span style="color: red;">*</span></label>
            <input type="text" name="status" value=" {{ old('status') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
            @error('status')
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
            <label for="">Alamat Rumah <span style="color: red;">*</span></label>
            {{-- <div class="flex flex-wrap gap-4">
                <div class="w-full md:w-1/4">
                    <label for="kabupaten_id" class="block text-sm font-medium text-gray-700">Kabupaten</label>
                    <select name="kabupaten_id" id="kabupaten_id" class="w-full border rounded px-3 py-2">
                        <option selected disabled>Pilih Kabupaten</option>
                        @foreach($kabupatens as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-1/3">
                    <label for="kecamatan_id" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                    <select name="kecamatan_id" id="kecamatan_id" class="w-full border rounded px-3 py-2">
                        <option selected disabled>Pilih Kecamatan</option>
                    </select>
                </div>

                <div class="w-full md:w-1/3">
                    <label for="kelurahan_id" class="block text-sm font-medium text-gray-700">Kelurahan</label>
                    <select name="kelurahan_id" id="kelurahan_id" class="w-full border rounded px-3 py-2">
                        <option selected disabled>Pilih Kelurahan</option>
                    </select>
                </div>
            </div><br> --}}



            <textarea name="alamat" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" rows="10">
                {{ old('alamat') }}
            </textarea>
            @error('alamat')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Telepon <span style="color: red;">*</span></label>
            <input type="text" name="telepon" value=" {{ old('telepon') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
            @error('telepon')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Keterangan Khusus Peserta Prestasi Terbaik : <span style="color: red;">*</span></label>
            <textarea name="keterangan" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" cols="30" rows="10">
                {{ old('keterangan') }}
            </textarea>
            @error('keterangan')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Tingkat <span style="color: red;">*</span></label>
            <input type="text" name="tingkat" readonly class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('tingkat') }}" placeholder="Tingkat" id="tingkat">
            @error('tingkat')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Nomor dan Kelas Pertandingan yang akan diikuti : <span style="color: red;">*</span></label>
            <select name="pertandingan_id[]" class="form-control pilihan" multiple="multiple" status="{{ (is_array(old('pertandingan_id')) && count(old('pertandingan_id'))) > 0 ? 'true' : 'false' }}" id="pertandingan_id">
                {{-- <option value="" disabled>Pilih Pertandingan</option> --}}
                @php
                    // if(isset($peserta)) {
                    //     $selectedKategori = in_array($a->id, old('kategori_id', $buku->KategoriBuku->pluck('kategori_id')->toArray())) ? 'selected' : '' ;
                    // } else {
                    //     $selectedKategori = (is_array(old('kategori_id')) && in_array($a->id, old('kategori_id'))) ? 'selected' : '' ;
                    // }
                    // $selectedpertandingan = (is_array(old('pertandingan_id')) && in_array($a->id, old('pertandingan_id'))) ? 'selected' : '' ;
                @endphp
                {{-- @foreach ($pertandingan as $a)
                    <option value="{{ $a->id }}" {{ (is_array(old('pertandingan_id')) && in_array($a->id, old('pertandingan_id'))) ? 'selected' : '' }}>{{ $a->nama }}</option>
                @endforeach --}}
            </select>
            @error('pertandingan_id')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Foto Berwarna 4 x 6</label>
            <input type="file" name="foto" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="imageInput">
            <img src="" style="width: 50%;" id="preview" alt=""><br>
            @error('foto')
                <span class="text-red"  style="color: red;" >{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <center>
                <button type="submit"  class="bg-green-500 text-white px-4 py-2 rounded">Daftar</button>
            </center>
        </div>
    </form>
</div>
<script>
    @section('script')
        function getWilayah(type, spawning) {
            let urls = "";
            let value = $(`#${type}`).val();
            if(type == "kabupaten_id") {
                urls = `{{ url("getWilayah") }}/${type}/{value}`;
            }
            $.ajax({
                url: urls, // URL API
                type: 'GET',
                dataType: 'json', // bisa 'html', 'text', dll tergantung respon
                success: function(response) {
                    let data = "";

                    console.log(response);

                    if(response.data.length > 0) {
                        data += `<option value="" disabled>Data Tidak Tersedia!</option>`;

                        $.each(response.data, function(index, value) {
                            // data += `<option value="${value.id}">${value.pertandingan.nama}</option>`;
                        });
                    } else {
                        data += `<option value="" disabled>Data Tidak Tersedia!</option>`;
                    }

                    // data += `<option value="" disabled>Pilih Pertandingan</option>`;

                    $(`#${spawning}`).html(data);


                    // alert('Judul: ' + response.title);
                },
                error: function(xhr, status, error) {
                    console.error('Gagal:', error);
                    alert('Gagal mengambil data!');
                }
            });
        }

        $('#kabupaten_id').on('change', function() {
            getWilayah('kabupaten_id', 'kecamatan_id');
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

        $('#jenis_kelamin').on('change', function (e) {
            AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val());
        });

        $('#tanggal_lahir').on('change', function (e) {
            AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val(), $('#kegiatan_id').val());
        });



        $('#pertandingan_id').on('select2:opening', function (e) {
            if($(this).attr('status') != 'true') {
                AmbilDataPertandingan($('#tanggal_lahir').val(), $('#jenis_kelamin').val());
            }
        });
    @endsection
</script>
