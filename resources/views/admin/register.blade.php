<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
  <title>Registrasi | Pertandingan Kempo</title>

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Popper -->
  <script src="https://unpkg.com/@popperjs/core@2"></script>

  <!-- Main Styling -->
  <link href="{{ asset('assets/css/argon-dashboard-tailwind.css?v=1.0.1') }}" rel="stylesheet" />

  <style>
    :root {
      --color-primary: #f2470c;
      --color-primary-fg: #ffffff;
      --color-background: #fdfdfd;
      --color-foreground: #000000;
      --color-accent: #fff0eb;
      --color-input: #ebebeb;
      --color-destructive: #e54b4f;
      --font-heading: 'Plus Jakarta Sans', sans-serif;
      --font-body: 'Inter', sans-serif;
    }

    body {
      background-color: var(--color-background) !important;
      color: var(--color-foreground) !important;
      font-family: var(--font-body) !important;
    }

    .font-heading {
      font-family: var(--font-heading) !important;
      letter-spacing: -0.02em;
    }

    .font-body {
      font-family: var(--font-body) !important;
    }

    .kempo-input {
      background-color: #ffffff !important;
      border: 1.5px solid var(--color-input) !important;
      color: var(--color-foreground) !important;
      font-family: var(--font-body) !important;
      transition: all 0.2s ease-in-out;
    }

    .kempo-input:focus {
      border-color: var(--color-primary) !important;
      outline: none !important;
      box-shadow: 0 0 0 3px var(--color-accent) !important;
    }

    .kempo-btn-primary {
      background-color: var(--color-primary) !important;
      color: var(--color-primary-fg) !important;
      font-family: var(--font-body) !important;
      transition: all 0.2s ease-in-out;
    }

    .kempo-btn-primary:hover {
      background-color: #d83d09 !important;
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(242, 71, 12, 0.28);
    }

    .kempo-btn-primary:active {
      transform: translateY(0);
      opacity: 0.92;
    }

    .swal2-confirm {
      background-color: #f2470c !important;
      color: #fff !important;
    }

    .swal2-cancel {
      background-color: #e54b4f !important;
      color: #fff !important;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 antialiased">

  <main class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col lg:flex-row my-auto">
    
    <!-- Kolom Kiri: Area Form (~50%) -->
    <div class="w-full lg:w-1/2 p-6 sm:p-8 md:p-10 lg:p-12 flex flex-col justify-center">
      
      <!-- Header Form -->
      <div class="mb-6 text-left">
        <h4 class="font-heading font-bold text-2xl sm:text-3xl text-gray-900 mb-1.5">Register</h4>
        <p class="font-body text-sm sm:text-base text-gray-500 mb-0">Isi Form yang ada dibawah ini!</p>
      </div>

      <!-- Form Register Asli -->
      <form role="form" action="{{ url('register') }}" id="registerForm" method="POST" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
          <label class="block text-sm font-semibold text-gray-800 mb-1 font-body" for="" style="margin-bottom: 6px;">
            Nama Lengkap <span style="color: red;">*</span>
          </label>
          <input 
            type="text" 
            name="name" 
            placeholder="Nama Lengkap" 
            value="{{ old('name') }}" 
            class="kempo-input w-full px-4 py-3 text-sm rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none" 
          />
          @error('name')
            <span class="text-red text-xs mt-1 block font-medium" style="color: red;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-semibold text-gray-800 mb-1 font-body" for="" style="margin-bottom: 6px;">
            Email <span style="color: red;">*</span>
          </label>
          <input 
            type="email" 
            name="email" 
            placeholder="Email" 
            value="{{ old('email') }}" 
            class="kempo-input w-full px-4 py-3 text-sm rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none" 
          />
          @error('email')
            <span class="text-red text-xs mt-1 block font-medium" style="color: red;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Nomor Whatsapp -->
        <div>
          <label class="block text-sm font-semibold text-gray-800 mb-1 font-body" for="" style="margin-bottom: 6px;">
            Nomor Whatsapp <span style="color: red;">*</span>
          </label>
          <input 
            type="integer" 
            name="no_wa" 
            placeholder="Nomor Whatsapp" 
            value="{{ old('no_wa') }}" 
            class="kempo-input w-full px-4 py-3 text-sm rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none" 
          />
          @error('no_wa')
            <span class="text-red text-xs mt-1 block font-medium" style="color: red;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Aturan Validasi Password -->
        <div class="rounded-xl bg-[#fff0eb]/70 border border-[#ebebeb] px-4 py-3 text-xs text-gray-700" role="alert">
          <ul class="space-y-1.5">
            <li class="flex items-center gap-2">
              <span>
                <span id="rule-length">
                  <i class="icon text-red-600 inline-block align-middle mr-1">
                    <!-- silang -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </i>
                </span> Password minimal 8 karakter dan maksimal 20 karakter.
              </span>
            </li>
            <li class="flex items-center gap-2">
              <span>
                <span id="rule-complex">
                  <i class="icon text-red-600 inline-block align-middle mr-1">
                    <!-- silang -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </i>
                </span> Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.
              </span>
            </li>
            <li class="flex items-center gap-2">
              <span>
                <span id="rule-match">
                  <i class="icon text-red-600 inline-block align-middle mr-1">
                    <!-- silang -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </i>
                </span>
                Password harus sama dengan Konfirmasi Password!
              </span>
            </li>
          </ul>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-semibold text-gray-800 mb-1 font-body">
            Password <span class="text-red-500">*</span>
          </label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Password" 
            class="kempo-input w-full px-4 py-3 text-sm rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none" 
          />
        </div>

        <!-- Konfirmasi Password -->
        <div>
          <label class="block text-sm font-semibold text-gray-800 mb-1 font-body" for="" style="margin-bottom: 6px;">
            Konfirmasi Password <span style="color: red;">*</span>
          </label>
          <input 
            type="password" 
            name="konfirmasi_password" 
            id="confirm_password" 
            placeholder="Konfirmasi Password" 
            class="kempo-input w-full px-4 py-3 text-sm rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none" 
          />
          @error('konfirmasi_password')
            <span class="text-red text-xs mt-1 block font-medium" style="color: red;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Tautan Balik ke Login -->
        <div class="pt-1 text-sm text-gray-600">
          <span>Jika Akun anda sudah terdaftar? <a href="{{ url('') }}" class="font-semibold text-[#f2470c] hover:underline" style="color: #f2470c;">Bisa Login!</a></span>
        </div>

        <!-- Tombol Submit -->
        <div class="pt-2 text-center">
          <button 
            type="submit" 
            class="kempo-btn-primary w-full py-3.5 px-6 font-bold text-sm rounded-xl cursor-pointer shadow-md"
          >
            Register
          </button>
        </div>
      </form>

    </div>

    <!-- Kolom Kanan: Area Ilustrasi (~50%) -->
    <div class="hidden lg:flex lg:w-1/2 p-3 sm:p-4 bg-gray-50/50 self-stretch">
      <div class="relative w-full h-full min-h-[500px] rounded-2xl overflow-hidden bg-gray-100 shadow-inner">
        <!-- TODO: Ganti src dengan gambar ilustrasi dari user -->
        <img 
          src="{{ asset('assets/img/login-regis-ills.jpg') }}" 
          alt="Ilustrasi Kempo" 
          class="w-full h-full object-cover rounded-2xl" 
        />
      </div>
    </div>

  </main>

  <!-- SweetAlert Script -->
  <script src="{{ asset('js/sweetalert2.js') }}"></script>
  <script>
    @if(session('error'))
      Swal.fire({
        icon: "error",
        title: "Horee...",
        text: "{{ session('error') }}",
      });
    @endif
  </script>

  <!-- Script Validasi Password Real-time -->
  <script>
    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("confirm_password");

    const ruleLength = document.getElementById("rule-length");
    const ruleComplex = document.getElementById("rule-complex");
    const ruleMatch = document.getElementById("rule-match");

    const totalRules = 3; // jumlah aturan
    let totalChecklist = 0;
    function setIcon(li, isValid) {
      li.querySelector("i").innerHTML = isValid
        ? `<svg xmlns="http://www.w3.org/2000/svg" style="color:green;" class="h-4 w-4 text-green-600 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
           </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
           </svg>`;
    }

    function validatePassword() {
      const pass = passwordInput.value;
      const confirm = confirmInput.value;
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
      const validMatch = pass !== "" && pass === confirm;
      setIcon(ruleMatch, validMatch);
      if (validMatch) totalChecklist++;

      console.log(totalChecklist);
    }

    passwordInput.addEventListener("input", validatePassword);
    confirmInput.addEventListener("input", validatePassword);

    const form = document.getElementById("registerForm");
    form.addEventListener("submit", function(e) {
      validatePassword(); // cek dulu
      if (totalChecklist !== totalRules) {
        e.preventDefault(); // stop submit
        Swal.fire({
          icon: "error",
          title: "Maaf...",
          text: "❌ Password belum memenuhi semua aturan!",
        });
      }
    });
  </script>
</body>
</html>
