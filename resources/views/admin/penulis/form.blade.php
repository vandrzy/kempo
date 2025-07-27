<div class="form-group">
    <label for="">Nama Penulis</label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', @$penulis->nama) }}" placeholder="Nama Penulis" id="">
    @error('nama')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
