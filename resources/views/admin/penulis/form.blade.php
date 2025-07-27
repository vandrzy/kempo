<div class="mb-4">
    <label for="">Nama Penulis <span style="color: red;">*</span></label>
    <input type="text" name="nama" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('nama', @$penulis->nama) }}" placeholder="Nama Penulis" id="">
    @error('nama')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
