<input type="hidden" name="status_page" id="status_page" value="{{ @$user ? 'true' : 'false' }}">

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Nama Pengguna <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="text" name="name" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('name', @$user->name) }}" placeholder="Nama Lengkap" id="name" />
        @error('name')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Email Pengguna <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="email" name="email" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" value="{{ old('email', @$user->email) }}" placeholder="email@contoh.com" id="email" />
        @error('email')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Nomor WhatsApp <span class="text-[#e54b4f]">*</span>
        </label>
        <input type="number" name="no_wa" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs tabular-nums" value="{{ old('no_wa', @$user->no_wa) }}" placeholder="Contoh: 081234567890" id="no_wa" />
        @error('no_wa')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Role Pengguna <span class="text-[#e54b4f]">*</span>
        </label>
        <select name="role" id="role" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
            <option value="" disabled {{ !isset($user) && !old('role') ? 'selected' : '' }}>Pilih Role Pengguna</option>
            <option value="admin" {{ (old('role', @$user->role) == 'admin' || old('role', @$user->role) == '1') ? 'selected' : '' }}>Super Admin</option>
            <option value="operator" {{ (in_array(old('role', @$user->role), ['operator', '2', 'user', '0'])) ? 'selected' : '' }}>Operator</option>
            <option value="wasit" {{ old('role', @$user->role) == 'wasit' ? 'selected' : '' }}>Wasit</option>
        </select>
        @error('role')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Kotak Aturan Password -->
<div class="mb-5 p-4 rounded-2xl bg-[#fff0eb] border border-[#f2470c]/20" role="alert">
    <div class="flex items-center gap-2 mb-2 font-heading font-bold text-xs text-[#000000]">
        <i class="fas fa-shield-alt text-[#f2470c]"></i>
        <span>Ketentuan Keamanan Kata Sandi {{ isset($user) ? '(Hanya diisi jika ingin mengganti password)' : '' }}</span>
    </div>
    <ul class="space-y-1.5 text-xs text-neutral-700 m-0 pl-0 list-none">
        <li class="flex items-center gap-2">
            <span id="rule-length" class="inline-flex items-center">
                <i class="icon text-[#e54b4f] text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </i>
            </span>
            <span>Panjang kata sandi antara 8 hingga 20 karakter.</span>
        </li>
        <li class="flex items-center gap-2">
            <span id="rule-complex" class="inline-flex items-center">
                <i class="icon text-[#e54b4f] text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </i>
            </span>
            <span>Wajib memuat kombinasi huruf besar, huruf kecil, angka, dan simbol unik (@$!%*?&amp;).</span>
        </li>
    </ul>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-2">
    <div>
        @php
            $password_required = isset($user) ? '' : '<span class="text-[#e54b4f]">*</span>';
        @endphp
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Password Pengguna {!! $password_required !!}
        </label>
        <input type="password" name="password" id="password" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] placeholder:text-neutral-400 focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs" placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}" />
        @error('password')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-bold text-[#000000] uppercase tracking-wider font-heading mb-2">
            Status Verifikasi Email <span class="text-[#e54b4f]">*</span>
        </label>
        <select name="verifikasi" id="verifikasi" class="w-full px-4 py-2.5 text-xs font-medium rounded-xl bg-white border border-[#ebebeb] text-[#000000] focus:border-[#f2470c] focus:ring-2 focus:ring-[#f2470c]/20 outline-none transition-all shadow-2xs">
            <option value="1" {{ old('verifikasi', @$user->email_verified_at ? '1' : '0') == '1' ? 'selected' : '' }}>Terverifikasi (Ya)</option>
            <option value="0" {{ old('verifikasi', @$user->email_verified_at ? '1' : '0') == '0' ? 'selected' : '' }}>Belum Verifikasi (Tidak)</option>
        </select>
        @error('verifikasi')
            <span class="text-xs font-semibold text-[#e54b4f] mt-1.5 inline-block">{{ $message }}</span>
        @enderror
    </div>
</div>

<script>
    const passwordInput = document.getElementById("password");
    const status_page = document.getElementById("status_page");

    const ruleLength = document.getElementById("rule-length");
    const ruleComplex = document.getElementById("rule-complex");

    const totalRules = 2;
    let totalChecklist = 0;

    function setIcon(li, isValid) {
        if (!li) return;
        li.querySelector("i").innerHTML = isValid
            ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
               </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#e54b4f] inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
               </svg>`;
    }

    function validatePassword() {
        if (!passwordInput) return;
        const pass = passwordInput.value;
        totalChecklist = 0;

        const validLength = pass.length >= 8 && pass.length <= 20;
        setIcon(ruleLength, validLength);
        if (validLength) totalChecklist++;

        const complexRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/;
        const validComplex = complexRegex.test(pass);
        setIcon(ruleComplex, validComplex);
        if (validComplex) totalChecklist++;
    }

    if (passwordInput) {
        passwordInput.addEventListener("input", validatePassword);
    }

    const formUser = document.getElementById("registerForm");
    if (formUser) {
        formUser.addEventListener("submit", function(e) {
            if(status_page && status_page.value == "true") {
                if(passwordInput && passwordInput.value) {
                    validatePassword();
                    if (totalChecklist !== totalRules) {
                        e.preventDefault();
                        Swal.fire({
                            icon: "error",
                            title: "Password Belum Memenuhi Syarat",
                            text: "Pastikan password memenuhi aturan panjang dan kompleksitas karakter!",
                            confirmButtonColor: '#f2470c'
                        });
                    }
                }
            } else {
                validatePassword();
                if (totalChecklist !== totalRules) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "error",
                        title: "Password Belum Memenuhi Syarat",
                        text: "Pastikan password memenuhi aturan panjang dan kompleksitas karakter!",
                        confirmButtonColor: '#f2470c'
                    });
                }
            }
        });
    }
</script>
