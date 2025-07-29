<div class="mb-4">
    <label for="">Nama Kategori <span style="color: red;">*</span></label>
    <input type="text" name="nama" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama', @$kategori->nama) }}" placeholder="Nama Kategori" id="">
    @error('nama')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Usia Awal <span style="color: red;">*</span></label>
    <input type="integer" name="usia_awal" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('usia_awal', @$kategori->usia_awal) }}" placeholder="Usia Awal" id="">
    @error('usia_awal')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Usia Akhir <span style="color: red;">*</span></label>
    <input type="integer" name="usia_akhir" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('usia_akhir', @$kategori->usia_akhir) }}" placeholder="Usia Akhir" id="">
    @error('usia_akhir')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Tahun Awal <span style="color: red;">*</span></label>
    <input type="integer" name="tahun_awal" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('tahun_awal', @$kategori->tahun_awal) }}" placeholder="Tahun Awal" id="">
    @error('usia_awal')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Tahun Akhir <span style="color: red;">*</span></label>
    <input type="integer" name="tahun_akhir" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('tahun_akhir', @$kategori->tahun_akhir) }}" placeholder="Tahun Akhir" id="">
    @error('usia_akhir')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
