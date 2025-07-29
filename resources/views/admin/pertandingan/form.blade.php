<div class="mb-4">
    <label for="">Kategori <span style="color: red;">*</span></label>
    <select name="kategori_id"  class="form-control pilihan" id="">
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
    <label for="">Nama Pertandingan <span style="color: red;">*</span></label>
    <input type="text" name="nama" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama', @$pertandingan->nama) }}" placeholder="Nama Pertandingan" id="">
    @error('nama')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Jenis Kelamin <span style="color: red;">*</span></label>
    <select name="jenis_kelamin"  class="form-control pilihan" id="">
        <option value="">Pilih Jenis Kelamin</option>
        <option value="Laki-Laki" {{ old('jenis_kelamin', @$pertandingan->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }} >Laki-Laki</option>
        <option value="Perempuan" {{ old('jenis_kelamin', @$pertandingan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }} >Perempuan</option>

    </select>
    @error('jenis_kelamin')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

