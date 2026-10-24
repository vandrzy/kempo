<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
  <title>Registrasi | Pertandingan Kempo</title>

  <!-- Google Fonts: Inter & Plus Jakarta Sans Sesuai Spesifikasi Desain -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Tailwind CSS & Shorinji Kempo Design System via Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    @media (min-width: 1024px) {
      body {
        height: 100vh !important;
        overflow: hidden !important;
        padding: 1rem !important;
      }
      .kempo-screen-layout {
        height: calc(100vh - 2rem) !important;
        display: flex !important;
        flex-direction: row !important;
        align-items: stretch !important;
      }
      .kempo-auth-img-wrapper {
        display: flex !important;
      }
      .kempo-auth-img {
        height: calc(100vh - 2rem) !important;
        aspect-ratio: 1752 / 2432 !important;
        width: auto !important;
        max-width: 48vw !important;
      }
    }

    @media (max-width: 1023px) {
      body {
        min-height: 100vh;
        padding: 1rem;
        overflow-y: auto !important;
      }
      .kempo-screen-layout {
        display: flex !important;
        flex-direction: column !important;
      }
      .kempo-auth-img-wrapper {
        display: none !important;
      }
      .kempo-auth-img {
        display: none !important;
      }
    }

    .kempo-screen-container {
      max-width: 660px !important;
      width: 100% !important;
    }
  </style>
</head>
<body class="antialiased flex items-center justify-center">

  <main class="kempo-screen-layout w-full max-w-[1536px] flex flex-col lg:flex-row items-stretch mx-auto">
    
    <!-- Kolom Kiri: Area Ilustrasi (Rasio Asli 1752:2432 Pas Layar Tanpa Terpotong) -->
    <div class="kempo-auth-img-wrapper hidden lg:flex flex-shrink-0 items-center justify-center">
      <img 
        src="{{ asset('assets/img/login-regis-ills.jpg') }}" 
        alt="Ilustrasi Shorinji Kempo" 
        class="kempo-auth-img select-none block shadow-sm"
        style="height: calc(100vh - 2rem); aspect-ratio: 1752 / 2432; width: auto; max-width: 48vw; object-fit: cover; border-radius: 28px;"
      />
    </div>

    <!-- Kolom Kanan: Area Form (Fleksibel, Terstruktur, & Mematuhi Desain) -->
    <div class="flex-1 min-w-0 flex flex-col justify-between px-6 sm:px-10 lg:px-12 xl:px-16 py-4 sm:py-6 overflow-y-auto custom-scrollbar">
      
      <!-- Top Spacer -->
      <div></div>

      <!-- Main Form Container: Batas Maksimal Width 660px -->
      <div class="kempo-screen-container my-auto w-full max-w-[660px] mx-auto py-1" style="max-width: 660px;">
        
        <!-- Header Form -->
        <div class="mb-5 text-left">
          <h2 class="font-heading font-bold text-2xl sm:text-3xl tracking-tight mb-1" style="color: var(--color-foreground);">
            Register
          </h2>
          <p class="font-body text-sm sm:text-base mb-0">Isi Form yang ada dibawah ini!</p>
        </div>

        <!-- Form Register Asli -->
        <form role="form" action="{{ url('register') }}" id="registerForm" method="POST" class="space-y-3.5">
          @csrf

          <!-- Nama Lengkap (Lebar Penuh) -->
          <div class="mb-4">
            <label class="block text-xs font-semibold mb-1 font-body" for="" style="color: var(--color-foreground); margin-bottom: 4px;">
              Nama Lengkap <span style="color: var(--color-destructive);">*</span>
            </label>
            <div class="ref-input-group flex items-center px-4 py-0.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <input 
                type="text" 
                name="name" 
                placeholder="Nama Lengkap" 
                value="{{ old('name') }}" 
                class="w-full py-3 text-sm placeholder:text-gray-400 focus:outline-none" 
              />
            </div>
            @error('name')
              <span class="text-xs mt-1 block font-medium" style="color: var(--color-destructive);">{{ $message }}</span>
            @enderror
          </div>

          <!-- 2 Kolom: Email & Nomor Whatsapp -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <!-- Email -->
            <div class="mb-4">
              <label class="block text-xs font-semibold mb-1 font-body" for="" style="color: var(--color-foreground); margin-bottom: 4px;">
                Email <span style="color: var(--color-destructive);">*</span>
              </label>
              <div class="ref-input-group flex items-center px-4 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <input 
                  type="email" 
                  name="email" 
                  placeholder="Email" 
                  value="{{ old('email') }}" 
                  class="w-full py-3 text-sm placeholder:text-gray-400 focus:outline-none" 
                />
              </div>
              @error('email')
                <span class="text-xs mt-1 block font-medium" style="color: var(--color-destructive);">{{ $message }}</span>
              @enderror
            </div>

            <!-- Nomor Whatsapp -->
            <div class="mb-4">
              <label class="block text-xs font-semibold mb-1 font-body" for="" style="color: var(--color-foreground); margin-bottom: 4px;">
                Nomor Whatsapp <span style="color: var(--color-destructive);">*</span>
              </label>
              <div class="ref-input-group flex items-center px-4 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <input 
                  type="integer" 
                  name="no_wa" 
                  placeholder="Nomor Whatsapp" 
                  value="{{ old('no_wa') }}" 
                  class="w-full py-3 text-sm placeholder:text-gray-400 focus:outline-none" 
                />
              </div>
              @error('no_wa')
                <span class="text-xs mt-1 block font-medium" style="color: var(--color-destructive);">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- 2 Kolom: Password & Konfirmasi Password (Sejajar Berdampingan) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <!-- Password -->
            <div class="mb-4">
              <label class="block text-xs font-semibold mb-1 font-body" style="color: var(--color-foreground);">
                Password <span style="color: var(--color-destructive);">*</span>
              </label>
              <div class="ref-input-group flex items-center px-4 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <input 
                  type="password" 
                  id="password" 
                  name="password" 
                  placeholder="Password" 
                  class="w-full py-3 text-sm placeholder:text-gray-400 focus:outline-none" 
                />
              </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
              <label class="block text-xs font-semibold mb-1 font-body" for="" style="color: var(--color-foreground); margin-bottom: 4px;">
                Konfirmasi Password <span style="color: var(--color-destructive);">*</span>
              </label>
              <div class="ref-input-group flex items-center px-4 py-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <input 
                  type="password" 
                  name="konfirmasi_password" 
                  id="confirm_password" 
                  placeholder="Konfirmasi Password" 
                  class="w-full py-3 text-sm placeholder:text-gray-400 focus:outline-none" 
                />
              </div>
              @error('konfirmasi_password')
                <span class="text-xs mt-1 block font-medium" style="color: var(--color-destructive);">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Aturan Validasi Password (Mengikuti Token Accent, Soft Border & Destructive) -->
          <div class="rounded-2xl mb-4 px-4 py-2.5 text-xs shadow-sm" style="background-color: var(--color-accent); border: 1px solid var(--btn-2); color: var(--color-foreground);" role="alert">
            <ul class="space-y-1">
              <li class="flex items-center gap-2">
                <span>
                  <span id="rule-length">
                    <i class="icon inline-block align-middle mr-1" style="color: var(--color-destructive);">
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
                    <i class="icon inline-block align-middle mr-1" style="color: var(--color-destructive);">
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
                    <i class="icon inline-block align-middle mr-1" style="color: var(--color-destructive);">
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

          <!-- Tombol Submit Primary Action (btn-kempo-1: #f2470c) -->
          <div class="pt-1.5">
            <button 
              type="submit" 
              class="btn-kempo-1 w-full py-4 px-6 font-bold text-sm sm:text-base cursor-pointer shadow-md"
            >
              Register
            </button>
          </div>

          <!-- Tautan Pindah Halaman di Bawah Button Mengikuti Warna Primary -->
          <div class="text-center pt-3 text-sm text-gray-600">
            <span>Jika Akun anda sudah terdaftar? <a href="{{ url('') }}" class="font-semibold hover:underline ml-1" style="color: var(--color-primary);">Bisa Login!</a></span>
          </div>
        </form>

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

  <!-- Script Validasi Password Real-time Mengikuti Token Warna -->
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
        ? `<svg xmlns="http://www.w3.org/2000/svg" style="color: #16a34a;" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
           </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" style="color: var(--color-destructive);" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
