<div class="max-w-6xl mx-auto my-6 px-4">
    <h1>Pendaftaran Peserta Kempo</h1><br>
    <hr><br>
    <form action="{{ url('pendaftaran') }}" method="post" enctype="multipart/form-data">
        @csrf
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
            <select name="jenis_kelamin"  class="form-control pilihan" id="">
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
                <option value="O" {{ old('golongan_darah', @$peserta->golongan_darah) == 'O' ? 'selected' : '' }} >O</option>S
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
            <label for="">Tingkat <span style="color: red;">*</span></label>
            <input type="text" name="tingkat" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('tingkat') }}" placeholder="Tingkat" id="">
            @error('tingkat')
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

                <input type="date" name="tanggal_lahir"
                    class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-1/2"
                    value="{{ old('tanggal_lahir') }}" id="">
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
            <textarea name="alamat" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" cols="30" rows="10">
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
            <label for="">Nomor dan Kelas Pertandingan yang akan diikuti : <span style="color: red;">*</span></label>
            <select name="pertandingan_id[]" class="form-control pilihan" multiple="multiple" status="false" id="pertandingan_id">
                <option value="" disabled>Pilih Pertandingan</option>
                @php
                    // if(isset($peserta)) {
                    //     $selectedKategori = in_array($a->id, old('kategori_id', $buku->KategoriBuku->pluck('kategori_id')->toArray())) ? 'selected' : '' ;
                    // } else {
                    //     $selectedKategori = (is_array(old('kategori_id')) && in_array($a->id, old('kategori_id'))) ? 'selected' : '' ;
                    // }
                    // $selectedpertandingan = (is_array(old('pertandingan_id')) && in_array($a->id, old('pertandingan_id'))) ? 'selected' : '' ;
                @endphp
                @foreach ($pertandingan as $a)
                    <option value="{{ $a->id }}">{{ $a->nama }}</option>
                @endforeach
            </select>
            @error('kategori_id')
                <span class="text-red" style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="">Foto Berwarna 4 x 6</label>
            <input type="file" name="foto" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="imageInput">
            <img src="" style="width: 50%;" id="preview" alt=""><br>
            @error('cover')
                <span class="text-red"  style="color: red;" >{{ $message }}</span>
            @enderror
        </div>
    </form>
</div>
<script>
    @section('script')
        $('#pertandingan_id').click(function() {
            alert("PACE");
            $(this).attr('status', 'true');
            $(this).html("");
        });
    @endsection
</script>
