<div class="mb-5">
    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
        Kategori <span class="text-[#e54b4f]">*</span>
    </label>
    <select name="kategori_id" id="kategori_id" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
        <option value="">Pilih Kategori</option>
        @foreach ($kategori as $a)
            <option value="{{ $a->id }}" {{ old('kategori_id', @$pertandingan->kategori_id) == $a->id ? 'selected' : '' }}>{{ $a->nama }}</option>
        @endforeach
    </select>
    @error('kategori_id')
        <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Nama Tanding <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="text" name="nama" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('nama', @$pertandingan->nama) }}" placeholder="Contoh: Randori Putra / Embu Berpasangan" id="nama" />
        @error('nama')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Kelas <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="text" name="kelas" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('kelas', @$pertandingan->kelas) }}" placeholder="Contoh: Kelas 50-55 Kg / Kyu 1" id="kelas" />
        @error('kelas')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-2">
    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Jenis Tanding <span class="text-[#e54b4f]">*</span>
        </label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
            <option value="" selected disabled>Pilih Jenis Tanding</option>
            <option value="-" {{ old('jenis_kelamin', @$pertandingan->jenis_kelamin) == '-' ? 'selected' : '' }}>Campuran</option>
            <option value="Laki-Laki" {{ old('jenis_kelamin', @$pertandingan->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="Perempuan" {{ old('jenis_kelamin', @$pertandingan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Status Bagan <span class="text-[#e54b4f]">*</span>
        </label>
        <select name="bagan" id="bagan" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
            <option value="" disabled {{ old('bagan', isset($pertandingan) ? (string)$pertandingan->bagan : '') === '' ? 'selected' : '' }}>Pilih Status Bagan</option>
            <option value="1" {{ old('bagan', isset($pertandingan) ? (string)$pertandingan->bagan : '') === '1' ? 'selected' : '' }}>Ya (Gunakan Bagan Undian)</option>
            <option value="0" {{ old('bagan', isset($pertandingan) ? ($pertandingan->bagan ? '1' : '0') : '') === '0' ? 'selected' : '' }}>Tidak</option>
        </select>
        @error('bagan')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>
</div>
