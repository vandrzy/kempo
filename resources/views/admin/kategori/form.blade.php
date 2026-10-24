<div class="mb-5">
    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
        Nama Kategori <span class="text-[#e54b4f]">*</span>
    </label>
    <input type="text" name="nama" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('nama', @$kategori->nama) }}" placeholder="Contoh: Pemula / Remaja / Dewasa" id="nama" />
    @error('nama')
        <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Usia Awal (Tahun) <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="number" name="usia_awal" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" value="{{ old('usia_awal', @$kategori->usia_awal) }}" placeholder="Contoh: 12" id="usia_awal" />
        @error('usia_awal')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Usia Akhir (Tahun) <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="number" name="usia_akhir" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" value="{{ old('usia_akhir', @$kategori->usia_akhir) }}" placeholder="Contoh: 15" id="usia_akhir" />
        @error('usia_akhir')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-2">
    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Tahun Awal Kelahiran <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="number" name="tahun_awal" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" value="{{ old('tahun_awal', @$kategori->tahun_awal) }}" placeholder="Contoh: 2011" id="tahun_awal" />
        @error('tahun_awal')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Tahun Akhir Kelahiran <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="number" name="tahun_akhir" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" value="{{ old('tahun_akhir', @$kategori->tahun_akhir) }}" placeholder="Contoh: 2014" id="tahun_akhir" />
        @error('tahun_akhir')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>
</div>
