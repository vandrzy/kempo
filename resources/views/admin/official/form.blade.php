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
                    <input type="text" name="nama" value="{{ $peserta->nama }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama') }}" placeholder="Nama" id="">
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

                {{-- <div class="mb-4">
                    <label for="">Status <span style="color: red;">*</span></label>
                    <select name="status"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
                        <option value="P" {{ old('status', @$peserta->status) == 'P' ? 'selected' : '' }} >Peserta / Atlet</option>
                        <option value="O" {{ old('status', @$peserta->status) == 'O' ? 'selected' : '' }} >Official</option>
                    </select>
                    <span class="error text-sm text-red-600"></span>
                </div> --}}
                <div class="mb-4">
                    <label for="">Tingkat <span style="color: red;">*</span></label>
                    @php
                        $tingkat_sabuk = explode('-', $peserta->tingkatan_sabuk ?? "");
                    @endphp
                    {{-- <input type="text" name="status" value=" {{ old('status') }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" > --}}
                    <div class="flex gap-2">
                        <select name="tingkatan_sabuk_a"  class="w-1/2 pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
                            <option value="KYU" {{ old('tingkatan_sabuk_a', @$tingkat_sabuk[0]) == 'KYU' ? 'selected' : '' }} >KYU</option>
                            <option value="DAN" {{ old('tingkatan_sabuk_a', @$tingkat_sabuk[0]) == 'DAN' ? 'selected' : '' }} >DAN</option>
                        </select>
                        <input type="number" value="{{ @$tingkat_sabuk[1] }}" name="tingkatan_sabuk_b" class="w-1/2 px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500" value="{{ old('nama') }}" placeholder="Jumlah Tingkatan" id="">
                    </div>
                    <span class="error text-sm text-red-600"></span>
                </div>

                <label for="">Alamat Rumah <span style="color: red;">*</span></label>
                <div class="mb-4">
                    <label for="kabupaten_id" class="block text-sm font-medium text-gray-700">Kabupaten <span style="color: red;">*</span></label>
                    <select name="kabupaten_id" id="kabupaten_id" class="w-full border rounded px-3 py-2">
                        <option selected disabled>Pilih Kabupaten</option>
                        @foreach($kabupatens as $a)
                            <option value="{{ $a->id }}" {{ old('kabupaten_id', @$peserta->kabupaten_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
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
                    <textarea name="alamat" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" rows="5">{{ old('alamat', @$peserta->alamat) }}</textarea>
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
                    <input type="text" name="nik" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nik', @$peserta->nik) }}" placeholder="NIK" id=""   onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '');" onkeydown="if(['e','E','+','-','.'].includes(event.key)) event.preventDefault();">
                    <span class="error text-sm text-red-600"></span>
                </div>


                <div class="mb-4">
                    <label for="">Telepon <span style="color: red;">*</span></label>
                    <input type="text" name="telepon" value="{{ old('telepon', @$peserta->telepon) }}" placeholder="Nomor Telepon" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
                    <span class="error text-sm text-red-600"></span>
                </div>
                {{-- <div class="mb-4">
                    <label for="">Berat Badan (Kg) <span style="color: red;">*</span></label>
                    <input type="number" min="0" max="1000" name="berat_badan" placeholder="Berat Badan (Kg)" value="{{ old('berat_badan', @$peserta->berat_badan) }}" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" >
                    <span class="error text-sm text-red-600"></span>
                </div> --}}
                <div class="mb-4">
                    <label for="">Keterangan Khusus Official Prestasi Terbaik : <span style="color: red;">*</span></label>
                    <textarea name="keterangan" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="" cols="30" rows="4">{{ old('keterangan', @$peserta->keterangan) }}</textarea>
                    <span class="error text-sm text-red-600"></span>
                </div>

                <div class="mb-4">
                    <label for="">Tempat, Tanggal Lahir  <span style="color: red;">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" name="tempat"
                            class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-1/2"
                            value="{{ old('tempat', @$peserta->tempat) }}" placeholder="Tempat Lahir" id="">
                        <span class="error text-sm text-red-600"></span>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                            class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-1/2"
                            value="{{ old('tanggal_lahir', date('Y-m-d', strtotime(@$peserta->tanggal_lahir))) }}" id="">
                            <span class="error text-sm text-red-600"></span>
                    </div>
                </div>


            <div class="mb-4">
                <label for="">Kategori <span style="color: red;">*</span></label>
                <select name="kategori_id[]" class="pilihan w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" multiple="multiple" status="{{ (is_array(old('kategori_id')) && count(old('kategori_id'))) > 0 ? 'true' : 'false' }}" id="kategori_id">
                    @foreach ($kategori as $a)
                        <option value="{{ $a->kategori_id }}" {{ in_array($a->kategori_id, array_column(@$kategori_terpilih ?? [], 'id')) ? 'selected' : '' }}>{{ $a->kategori->nama }}</option>
                    @endforeach
                </select>
                <span class="error text-sm text-red-600"></span>
            </div>
            <div class="mb-4">
                <label for="">Foto Berwarna 4 x 6 <span style="color: red;">*</span></label>
                <input type="file" name="foto" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" id="imageInput">
                <br>
                <img src="{{ url($peserta->foto) }}" style="width: 50%;" id="preview" alt=""><br>
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
            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-5/12 lg:flex-none">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                    <h1 class="m-4">Pertandingan</h1>
                    <div class="kategori_show">
                    </div>
                </div>
            </div>
            <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                    <h1 class="m-4">Tahapan Teknik</h1>
                    <div class="kategori_pertandingan_show">

                    </div>
                </div>
            </div>
    </div>

    {{-- <h1>Tahapan Teknik</h1>
    <div class="tanding_list">

    </div> --}}
    <br>
    <div class="mb-4" style="text-align: center;">
        <button type="submit"  style="border: solid 2px green; color: green; font-size: 22px;"   class="edit-tanding-form inline-block px-8 py-2 mb-0 text-xs font-bold leading-normal text-center text-green-500 align-middle transition-all ease-in bg-transparent border  border-solid rounded-lg shadow-none cursor-pointer bg-150 active:opacity-85 hover:-translate-y-px tracking-tight-rem bg-x-25 hover:opacity-75">Simpan</button>
    </div>
</div>
