<div class="form-group">
    <label for="">Cover Buku</label>
    <input type="file" name="cover" class="form-control" id="imageInput">
    <br>
    <img src="{{ @$buku ? url($buku->cover) : '' }}" style="width: 50%;" id="preview" alt=""><br>
    @error('cover')
        <span class="text-red"  style="color: red;" >{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    <label for="">Judul</label>
    <input type="text" name="judul" class="form-control" value="{{ old('judul', @$buku->judul) }}" placeholder="Judul Buku" id="">
    @error('judul')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    <label for="">Penulis</label>
    <select name="penulis_id" class="form-control pilihan" id="">
        <option value="">Pilih Penulis</option>
        @foreach ($penulis as $a)
            <option value="{{ $a->id }}" {{ old('penulis_id', @$buku->penulis_id) == $a->id ? 'selected' : '' }} >{{ $a->nama }}</option>
        @endforeach
    </select>
    @error('penulis_id')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    <label for="">Kategori</label>
    <select name="kategori_id[]" class="form-control pilihan" multiple="multiple" id="">
        <option value="">Pilih kategori</option>
        @php
            if(isset($buku)) {
                $selectedKategori = in_array($a->id, old('kategori_id', $buku->KategoriBuku->pluck('kategori_id')->toArray())) ? 'selected' : '' ;
            } else {
                $selectedKategori = (is_array(old('kategori_id')) && in_array($a->id, old('kategori_id'))) ? 'selected' : '' ;
            }
        @endphp
        @foreach ($kategori as $a)
            <option value="{{ $a->id }}" {{ $selectedKategori }}>{{ $a->nama }}</option>
        @endforeach
    </select>
    @error('kategori_id')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    <label for="">Publisher</label>
    <input type="text" name="publisher" class="form-control" value="{{ old('publisher', @$buku->publisher) }}" placeholder="Publisher Buku" id="">
    @error('publisher')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    <label for="">Tahun</label>
    <input type="number" name="tahun" class="form-control" value="{{ old('tahun', @$buku->tahun) }}" placeholder="Tahun Rilis Buku" id="">
    @error('tahun')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    <label for="">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" id="" cols="30" rows="10">
        {{ old('deskripsi', @$buku->deskripsi) }}
    </textarea>
    @error('deskripsi')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
