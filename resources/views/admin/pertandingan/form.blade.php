{{-- <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}"> pilihan --}}
<div class="mb-4">
    <label for="">Kategori <span style="color: red;">*</span></label>
    <select name="kategori_id"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
        <option value="">Pilih Kategori</option>
        @foreach ($kategori as $a)
            <option value="{{ $a->id }}" {{ old('kategori_id', @$pertandingan->kategori_id) == $a->id ? 'selected' : '' }} >{{ $a->nama }}</option>
        @endforeach
    </select>
    @error('kategori_id')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Nama Tanding <span style="color: red;">*</span></label>
    <input type="text" name="nama" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" value="{{ old('nama', @$pertandingan->nama) }}" placeholder="Nama Tanding" id="" />
    @error('nama')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="mb-4">
    <label for="">Kelas <span style="color: red;">*</span></label>
    <input type="text" name="kelas" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" value="{{ old('kelas', @$pertandingan->kelas) }}" placeholder="Kelas" id="" />
    @error('kelas')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Jenis Tanding <span style="color: red;">*</span></label>
    <select name="jenis_kelamin"  class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" id="">
        <option value="" selected disabled>Pilih Jenis Tanding </option>
        <option value="-">Campuran</option>
        <option value="Laki-Laki" {{ old('jenis_kelamin', @$pertandingan->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }} >Laki-Laki</option>
        <option value="Perempuan" {{ old('jenis_kelamin', @$pertandingan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }} >Perempuan</option>

    </select>
    @error('jenis_kelamin')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
{{-- <div class="mb-4">
    <label for="">Medali <span style="color: red;">*</span></label>
    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/3 px-2">
            <div class="p-1">
                <input type="number" name="emas" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('emas', @$pertandingan->emas) }}" placeholder="Emas" id="">
            </div>
        </div>
        <div class="w-full md:w-1/3 px-2">
            <div class="p-1">
                <input type="number" name="perak" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('perak', @$pertandingan->perak) }}" placeholder="Perak" id="">
            </div>
        </div>
        <div class="w-full md:w-1/3 px-2">
            <div class="p-1">
                <input type="number" name="perunggu" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('perunggu', @$pertandingan->perunggu) }}" placeholder="Perunggu" id="">
            </div>
        </div>
    </div>
    @error('jenis_kelamin')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div> --}}

