<div class="form-group">
    <label for="">Nama Kategori <span style="color: red;">*</span></label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', @$kategori->nama) }}" placeholder="Nama Kategori" id="">
    @error('nama')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
