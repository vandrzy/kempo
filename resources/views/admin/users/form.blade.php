<input type="hidden" name="status_page" id="status_page" value="{{ @$user ? 'true' : 'false' }}">
<div class="mb-4">
    <label for="">Nama Pengguna : <span style="color: red;">*</span></label>
    <input type="text" name="name" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" value="{{ old('name', @$user->name) }}" placeholder="Nama Pengguna" id="" />
    @error('name')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Email Pengguna : <span style="color: red;">*</span></label>
    <input type="email" name="email" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" value="{{ old('email', @$user->email) }}" placeholder="Email Pengguna" id="" />
    @error('email')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="mb-4">
    <label for="">Nomor Whatsapp Pengguna : <span style="color: red;">*</span></label>
    <input type="integer" name="no_wa" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" value="{{ old('no_wa', @$user->no_wa) }}" placeholder="Nomor Whatsapp Pengguna" id="" />
    @error('no_wa')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="mb-4">
    <label for="">Role Pengguna : <span style="color: red;">*</span></label>
    <select name="role" id="" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow">
        <option value="" disabled {{ !isset($user) && !old('role') ? 'selected' : '' }}>Pilih Role Pengguna</option>
        <option value="admin" {{ (old('role', @$user->role) == 'admin' || old('role', @$user->role) == '1') ? 'selected' : '' }}>Super Admin</option>
        <option value="operator" {{ (old('role', @$user->role) == 'operator' || old('role', @$user->role) == '2') ? 'selected' : '' }}>Operator</option>
        <option value="wasit" {{ old('role', @$user->role) == 'wasit' ? 'selected' : '' }}>Wasit</option>
    </select>
    @error('role')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3" role="alert">
    <ul>
        @if(isset($user))
        <li class="flex items-center gap-2">
            Jika ingin merubah kata sandi penuhin persyaratan berikut!
        </li>
        @endif
        <li class="flex items-center gap-2">
            <span>
                <span id="rule-length">
                    <i class="icon text-red-600">
                        <!-- silang -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </i>
                </span> Password minimal 8 karakter dan maksimal 20 karakter.
            </span>
        </li>
        <li class="flex items-center gap-2">
            <span>
                <span id="rule-complex" >
                    <i class="icon text-red-600">
                        <!-- silang -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </i>
                </span> Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.
            </span>
        </li>
        {{-- <li class="flex items-center gap-2">
            <span>
                <span id="rule-match">
                    <i class="icon text-red-600">
                        <!-- silang -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </i>
                </span>
                Password harus sama dengan Konfirmasi Password!
            </span>
        </li> --}}
    </ul>
</div>
<div class="mb-4">
    @php
        if(isset($user)) {
            $password_required = '';
        } else {
            $password_required = '<span style="color: red;">*</span>';

        }
    @endphp
    <label for="">Password Pengguna : <?php echo $password_required ?> </label>
    <input type="password" name="password" id="password" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" placeholder="Password Pengguna" id="" />
    @error('password')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="mb-4">
    <label for="">Verifikasi Pengguna : <span style="color: red;">*</span></label>
    <select name="verifikasi" id="" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow">
        <option value="0" {{ old('verifikasi', @$user->email_verified_at ? '1' : '0') == '0' ? 'selected' : '' }}>Tidak</option>
        <option value="1" {{ old('verifikasi', @$user->email_verified_at ? '1' : '0') == '1' ? 'selected' : '' }}>Ya</option>
    </select>
    @error('verifikasi')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<script src="{{ asset('js/sweetalert2.js') }}"></script>

<script>
  const passwordInput = document.getElementById("password");
  const status_page = document.getElementById("status_page");
//   const confirmInput = document.getElementById("confirm_password");

  const ruleLength = document.getElementById("rule-length");
  const ruleComplex = document.getElementById("rule-complex");
  const ruleMatch = document.getElementById("rule-match");

  const totalRules = 2; // jumlah aturan
  let totalChecklist = 0;
  function setIcon(li, isValid) {
    li.querySelector("i").innerHTML = isValid
      ? `<svg xmlns="http://www.w3.org/2000/svg" style="color:green;" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
         </svg>`
      : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
         </svg>`;
  }

  function validatePassword() {
    const pass = passwordInput.value;
    // const confirm = confirmInput.value;
    totalChecklist = 0;

    // 1. Panjang
    const validLength = pass.length >= 8 && pass.length <= 20;
    setIcon(ruleLength, validLength);
    if (validLength) totalChecklist++;

    // 2. Kompleksitas
    const complexRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/;
    const validComplex = complexRegex.test(pass);
    setIcon(ruleComplex, validComplex);
    if (validComplex) totalChecklist++;

    // 3. Konfirmasi
    // const validMatch = pass !== "" && pass === confirm;
    // setIcon(ruleMatch, validMatch);
    // if (validMatch) totalChecklist++;

    // console.log(totalChecklist);
  }

  passwordInput.addEventListener("input", validatePassword);
//   confirmInput.addEventListener("input", validatePassword);

  const form = document.getElementById("registerForm");
  form.addEventListener("submit", function(e) {
    if(status_page.value == "true") {
        if(passwordInput.value) {
            validatePassword(); // cek dulu
            if (totalChecklist !== totalRules) {
            e.preventDefault(); // stop submit
                Swal.fire({
                    icon: "error",
                    title: "Maaf...",
                    text: "❌ Password belum memenuhi semua aturan!",
                });
            }
        }
    } else {
        validatePassword(); // cek dulu
        if (totalChecklist !== totalRules) {
        e.preventDefault(); // stop submit
            Swal.fire({
                icon: "error",
                title: "Maaf...",
                text: "❌ Password belum memenuhi semua aturan!",
            });
        }
    }
  });
</script>
