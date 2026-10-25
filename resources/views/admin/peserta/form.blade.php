<!-- Informasi Keterangan Form -->
<div class="p-4 mb-6 rounded-2xl bg-[#fff0eb] border border-[#f2470c]/25 flex items-start gap-3">
    <div class="w-8 h-8 rounded-xl bg-[#f2470c]/10 text-[#f2470c] flex items-center justify-center shrink-0 text-sm mt-0.5">
        <i class="fas fa-info-circle"></i>
    </div>
    <div class="text-xs">
        <h5 class="font-heading font-bold text-sm text-[#000000] mb-1">Panduan Pengisian Formulir</h5>
        <ul class="space-y-1 text-neutral-600 m-0 pl-4 list-disc">
            <li>Tanda <span class="text-[#e54b4f] font-bold">*</span> menandakan kolom wajib diisi.</li>
            <li>Tanda <span class="text-[#e54b4f] font-bold">**</span> menandakan kolom wajib diisi tanda hubung / strip (-) bila tidak memiliki data.</li>
        </ul>
    </div>
</div>

<input type="hidden" name="kegiatan_id" id="kegiatan_id" value="{{ $kegiatan->id }}">

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Kolom Kiri: Biodata & Alamat -->
    <div class="lg:col-span-7 space-y-5">
        <div class="p-5 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs space-y-4">
            <h5 class="font-heading font-bold text-sm text-[#000000] border-b border-[#ebebeb] pb-2.5 mb-3 flex items-center gap-2">
                <i class="fas fa-id-card text-[#f2470c]"></i>
                <span>Identitas Utama Atlet</span>
            </h5>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Nama Lengkap Atlet <span class="text-[#e54b4f]">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama', @$peserta->nama) }}" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" placeholder="Nama lengkap atlet" id="nama" autocomplete="off">
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>

                <!-- Container Konfirmasi Data Lama (Auto-Fill) -->
                <div id="existing-data-confirmation" class="hidden mt-2.5 space-y-2"></div>
                <input type="hidden" name="existing_reference_id" id="existing-reference-id" value="">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Golongan Darah <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="golongan_darah" id="golongan_darah" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option value="A" {{ old('golongan_darah', @$peserta->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('golongan_darah', @$peserta->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="AB" {{ old('golongan_darah', @$peserta->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                        <option value="O" {{ old('golongan_darah', @$peserta->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                    </select>
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Status Keikutsertaan <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="status" id="status" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option value="P" {{ old('status', @$peserta->status) == 'P' ? 'selected' : '' }}>Peserta / Atlet</option>
                    </select>
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Tingkatan Sabuk (Grade) <span class="text-[#e54b4f]">*</span>
                </label>
                @php
                    $tingkat_sabuk = explode('-', $peserta->tingkatan_sabuk ?? "");
                @endphp
                <div class="grid grid-cols-2 gap-3">
                    <select name="tingkatan_sabuk_a" id="tingkatan_sabuk_a" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option value="KYU" {{ old('tingkatan_sabuk_a', @$tingkat_sabuk[0]) == 'KYU' ? 'selected' : '' }}>KYU</option>
                        <option value="DAN" {{ old('tingkatan_sabuk_a', @$tingkat_sabuk[0]) == 'DAN' ? 'selected' : '' }}>DAN</option>
                    </select>
                    <input type="number" value="{{ @$tingkat_sabuk[1] }}" name="tingkatan_sabuk_b" id="tingkatan_sabuk_b" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" placeholder="Tingkat (1, 2, dst.)">
                </div>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>
        </div>

        <!-- Wilayah & Alamat -->
        <div class="p-5 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs space-y-4">
            <h5 class="font-heading font-bold text-sm text-[#000000] border-b border-[#ebebeb] pb-2.5 mb-3 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-[#f2470c]"></i>
                <span>Domisili &amp; Alamat Atlet</span>
            </h5>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="mb-4">
                    <label for="kabupaten_id" class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Kabupaten / Kota <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="kabupaten_id" id="kabupaten_id" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option selected disabled>Pilih Kabupaten</option>
                        @foreach($kabupatens as $a)
                            <option value="{{ $a->id }}" {{ old('kabupaten_id', @$peserta->kabupaten_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>

                <div class="mb-4">
                    <label for="kecamatan_id" class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Kecamatan <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="kecamatan_id" id="kecamatan_id" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option selected disabled>Pilih Kecamatan</option>
                    </select>
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>

                <div class="mb-4">
                    <label for="kelurahan_id" class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Kelurahan <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="kelurahan_id" id="kelurahan_id" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option selected disabled>Pilih Kelurahan</option>
                    </select>
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Alamat Lengkap Rumah
                </label>
                <textarea name="alamat" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" rows="3" placeholder="Jalan, RT/RW, Nomor Rumah">{{ old('alamat', @$peserta->alamat) }}</textarea>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Fisik, Kategori & Foto -->
    <div class="lg:col-span-5 space-y-5">
        <div class="p-5 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs space-y-4">
            <h5 class="font-heading font-bold text-sm text-[#000000] border-b border-[#ebebeb] pb-2.5 mb-3 flex items-center gap-2">
                <i class="fas fa-user-check text-[#f2470c]"></i>
                <span>Data Personal &amp; Fisik</span>
            </h5>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Jenis Kelamin <span class="text-[#e54b4f]">*</span>
                    </label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option value="Laki-Laki" {{ old('jenis_kelamin', @$peserta->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', @$peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Berat Badan (Kg) <span class="text-[#e54b4f]">*</span>
                    </label>
                    <input type="number" min="0" max="1000" name="berat_badan" id="berat_badan" placeholder="Contoh: 55" value="{{ old('berat_badan', @$peserta->berat_badan) }}" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums">
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        NIK (KTP/KIA) <span class="text-[#e54b4f]">*</span>
                    </label>
                    <input type="text" name="nik" id="nik" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" value="{{ old('nik', @$peserta->nik) }}" placeholder="16 Digit NIK" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '');" onkeydown="if(['e','E','+','-','.'].includes(event.key)) event.preventDefault();">
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                        Nomor Telepon / WA <span class="text-[#e54b4f]">*</span>
                    </label>
                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon', @$peserta->telepon) }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums">
                    <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Tempat &amp; Tanggal Lahir <span class="text-[#e54b4f]">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="tempat" id="tempat" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('tempat', @$peserta->tempat) }}" placeholder="Tempat Lahir">
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" value="{{ old('tanggal_lahir', @$peserta->tanggal_lahir ? date('Y-m-d', strtotime(@$peserta->tanggal_lahir)) : '') }}">
                </div>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Keterangan Prestasi Terbaik <span class="text-[#e54b4f]">*</span>
                </label>
                <textarea name="keterangan" id="keterangan" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" rows="3" placeholder="Prestasi kejuaraan sebelumnya">{{ old('keterangan', @$peserta->keterangan) }}</textarea>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Pilihan Kategori Tanding <span class="text-[#e54b4f]">*</span>
                </label>
                <select name="kategori_id[]" class="pilihan w-full" multiple="multiple" status="{{ (is_array(old('kategori_id')) && count(old('kategori_id'))) > 0 ? 'true' : 'false' }}" id="kategori_id">
                    @foreach ($kategori as $a)
                        <option value="{{ $a->kategori_id }}" {{ in_array($a->kategori_id, array_column(@$kategori_terpilih ?? [], 'id')) ? 'selected' : '' }}>{{ $a->kategori->nama }}</option>
                    @endforeach
                </select>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <!-- Upload Foto -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Foto Berwarna 4x6 (Pasfoto) <span class="text-[#e54b4f]">*</span>
                </label>
                <input type="file" name="foto" id="imageInput" class="w-full px-3 py-2 text-xs font-medium rounded-xl bg-neutral-50 border border-[#ebebeb] text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#f2470c] file:text-white hover:file:bg-[#d83d09] file:cursor-pointer">
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
                <div class="mt-3">
                    <img src="{{ url($peserta->foto ?? '') }}" id="preview" alt="Preview Foto" class="w-32 h-40 object-cover rounded-xl border border-[#ebebeb] shadow-2xs {{ empty($peserta->foto) ? 'hidden' : '' }}">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Container Pertandingan & Urutan Komposisi -->
<div class="mt-8 space-y-6">
    <div class="p-6 rounded-3xl bg-white border border-[#ebebeb] shadow-sm">
        <h4 class="font-heading font-bold text-base text-[#000000] border-b border-[#ebebeb] pb-3 mb-4 flex items-center gap-2">
            <i class="fas fa-sitemap text-[#f2470c]"></i>
            <span>Daftar Pertandingan &amp; Nomor Tanding</span>
        </h4>
        <div class="kategori_show">
            <!-- Diisi secara dinamis oleh jQuery saat kategori dipilih -->
        </div>
    </div>

    <div class="p-6 rounded-3xl bg-white border border-[#ebebeb] shadow-sm" style="display: none;">
        <h4 class="font-heading font-bold text-base text-[#000000] border-b border-[#ebebeb] pb-3 mb-4">
            Tahapan Teknik
        </h4>
        <div class="kategori_pertandingan_show">
            <!-- Diisi secara dinamis oleh jQuery -->
        </div>
    </div>

    <div class="pt-4 border-t border-[#ebebeb] flex items-center justify-center">
        <button type="submit" class="edit-tanding-form inline-flex items-center justify-center gap-2 px-8 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider text-white bg-[#f2470c] hover:bg-[#d83d09] shadow-xs hover:-translate-y-px transition-all cursor-pointer">
            <i class="fas fa-save text-sm"></i>
            <span>Simpan Data Peserta</span>
        </button>
    </div>
</div>
