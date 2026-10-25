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
                <span>Identitas Utama Official</span>
            </h5>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Nama <span class="text-[#e54b4f]">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama', @$peserta->nama) }}" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" placeholder="Nama lengkap official" id="nama" autocomplete="off">
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>

                <!-- Container Konfirmasi Data Lama (Auto-Fill) -->
                <div id="existing-data-confirmation" class="hidden mt-2.5 space-y-2"></div>
                <input type="hidden" name="existing_reference_id" id="existing-reference-id" value="">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Golongan Darah <span class="text-[#e54b4f]">*</span>
                </label>
                <select name="golongan_darah" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                    <option value="A" {{ old('golongan_darah', @$peserta->golongan_darah) == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('golongan_darah', @$peserta->golongan_darah) == 'B' ? 'selected' : '' }}>B</option>
                    <option value="AB" {{ old('golongan_darah', @$peserta->golongan_darah) == 'AB' ? 'selected' : '' }}>AB</option>
                    <option value="O" {{ old('golongan_darah', @$peserta->golongan_darah) == 'O' ? 'selected' : '' }}>O</option>
                </select>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Tingkat <span class="text-[#e54b4f]">*</span>
                </label>
                @php
                    $tingkat_sabuk = explode('-', $peserta->tingkat ?? "");
                @endphp
                <div class="grid grid-cols-2 gap-3">
                    <select name="tingkatan_sabuk_a" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                        <option value="KYU" {{ old('tingkatan_sabuk_a', @$tingkat_sabuk[0]) == 'KYU' ? 'selected' : '' }}>KYU</option>
                        <option value="DAN" {{ old('tingkatan_sabuk_a', @$tingkat_sabuk[0]) == 'DAN' ? 'selected' : '' }}>DAN</option>
                    </select>
                    <input type="number" name="tingkatan_sabuk_b" value="{{ @$tingkat_sabuk[1] }}" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" placeholder="Jumlah Tingkatan">
                </div>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs space-y-4">
            <h5 class="font-heading font-bold text-sm text-[#000000] border-b border-[#ebebeb] pb-2.5 mb-3 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-[#f2470c]"></i>
                <span>Alamat & Tempat Lahir</span>
            </h5>

            <div class="mb-4">
                <label for="kabupaten_id" class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Kabupaten / Kota <span class="text-[#e54b4f]">*</span>
                </label>
                <select name="kabupaten_id" id="kabupaten_id" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
                    <option selected disabled>Pilih Kabupaten</option>
                    @foreach($kabupatens as $a)
                        <option value="{{ $a->id }}" {{ old('kabupaten_id', @$peserta->kabupaten_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                    @endforeach
                </select>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Alamat Lengkap <span class="text-[#e54b4f]">*</span>
                </label>
                <textarea name="alamat" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" rows="3" placeholder="Alamat lengkap official">{{ old('alamat', @$peserta->alamat) }}</textarea>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Tempat, Tanggal Lahir <span class="text-[#e54b4f]">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" name="tempat" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('tempat', @$peserta->tempat) }}" placeholder="Tempat Lahir">
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('tanggal_lahir', @$peserta->tanggal_lahir ? date('Y-m-d', strtotime($peserta->tanggal_lahir)) : '') }}">
                </div>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Data Pribadi & Sertifikasi & Foto -->
    <div class="lg:col-span-5 space-y-5">
        <div class="p-5 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs space-y-4">
            <h5 class="font-heading font-bold text-sm text-[#000000] border-b border-[#ebebeb] pb-2.5 mb-3 flex items-center gap-2">
                <i class="fas fa-user-check text-[#f2470c]"></i>
                <span>Data Pribadi & Sertifikasi</span>
            </h5>

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
                    NIK <span class="text-[#e54b4f]">*</span>
                </label>
                <input type="text" name="nik" value="{{ old('nik', @$peserta->nik) }}" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" placeholder="16 digit NIK" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '');" onkeydown="if(['e','E','+','-','.'].includes(event.key)) event.preventDefault();">
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Nomor Telepon / WhatsApp <span class="text-[#e54b4f]">*</span>
                </label>
                <input type="text" name="telepon" value="{{ old('telepon', @$peserta->telepon) }}" placeholder="Contoh: 08123456789" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums">
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Keterangan Prestasi Terbaik Official
                </label>
                <textarea name="keterangan" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" rows="3" placeholder="Tuliskan prestasi terbaik official">{{ old('keterangan', @$peserta->keterangan) }}</textarea>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Sertifikat Pelatih yang Diikuti <span class="text-[#e54b4f]">*</span>
                </label>
                <textarea name="sertifikat" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" rows="3" placeholder="Tuliskan sertifikat kepelatihan yang dimiliki">{{ old('sertifikat', @$peserta->sertifikat) }}</textarea>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-[#ebebeb] shadow-2xs space-y-4">
            <h5 class="font-heading font-bold text-sm text-[#000000] border-b border-[#ebebeb] pb-2.5 mb-3 flex items-center gap-2">
                <i class="fas fa-camera text-[#f2470c]"></i>
                <span>Pas Foto Official (4 x 6)</span>
            </h5>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
                    Upload Foto Berwarna <span class="text-[#e54b4f]">*</span>
                </label>
                <input type="hidden" name="existing_foto" id="existing_foto" value="{{ old('existing_foto', @$peserta->foto) }}">
                <input type="file" name="foto" id="imageInput" class="w-full px-4 py-2 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#fff0eb] file:text-[#f2470c] hover:file:bg-[#ffe2d6] file:cursor-pointer transition-all">
                <p class="text-[11px] text-neutral-400 mt-1.5">Format: JPG, JPEG, PNG (Maks 1MB)</p>
                <div class="mt-3">
                    <img src="{{ !empty($peserta->foto) ? url($peserta->foto) : '' }}" id="preview" class="w-28 h-36 object-cover rounded-xl border border-[#ebebeb] shadow-2xs bg-neutral-100 {{ empty($peserta->foto) ? 'hidden' : '' }}" alt="Preview Foto">
                </div>
                <span class="error text-xs font-semibold text-[#e54b4f] mt-1 inline-block"></span>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 pt-6 border-t border-[#ebebeb] flex justify-end">
    <button type="submit" class="edit-tanding-form inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl text-xs font-bold font-heading text-white bg-[#f2470c] hover:bg-[#d83c07] shadow-sm hover:shadow-md transition-all active:scale-[0.99] cursor-pointer tracking-wider uppercase">
        <i class="fas fa-save"></i>
        <span>Simpan Data Official</span>
    </button>
</div>
