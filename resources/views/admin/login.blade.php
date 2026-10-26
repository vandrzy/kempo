<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo-removebg.png') }}" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-removebg.png') }}" />
  <title>Login | Pertandingan Kempo</title>

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
      <div class="kempo-screen-container my-auto w-full max-w-[660px] mx-auto py-2" style="max-width: 660px;">
        
        <!-- Header Form -->
        <div class="mb-8 text-left">
          <h1 class="font-heading font-bold text-2xl sm:text-3xl tracking-tight mb-2" style="color: var(--color-foreground);">
            Aplikasi Pendaftaran <span style="color: var(--color-primary);"> Shorinji Kempo </span>
          </h1>
          <p class="font-body text-sm sm:text-base mb-5 leading-relaxed">
            Aplikasi untuk mengelola data peserta dan pertandingan Shorinji Kempo.
          </p>

          <div class="pt-4 border-t" style="border-color: var(--color-input);">
            <p class="font-body text-sm sm:text-base mb-5 leading-relaxed">
              Masuk ke akun anda
            </p>
          </div>
        </div>

        <!-- Pesan Error Validasi Mengikuti Token Destructive & Accent -->
        @if ($errors->any())
          <div class="mb-6 p-4 rounded-2xl text-sm flex items-start gap-3" style="background-color: var(--color-accent); border: 1px solid var(--btn-2); color: var(--color-destructive);" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 1118 0z" />
            </svg>
            <span class="font-medium">{{ $errors->first() }}</span>
          </div>
        @endif

        <!-- Form Login Asli -->
        <form role="form" action="{{ url('login') }}" method="POST" class="space-y-6">
          @csrf

          <!-- Input Email dengan Ikon -->
          <div class="ref-input-group flex items-center mb-4 px-4 py-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <input 
              type="email" 
              name="email" 
              placeholder="Email" 
              class="w-full py-3.5 text-sm placeholder:text-gray-400 focus:outline-none" 
            />
          </div>

          <!-- Input Password dengan Ikon -->
          <div class="ref-input-group flex items-center mb-4 px-4 py-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            <input 
              type="password" 
              name="password" 
              placeholder="Password" 
              class="w-full py-3.5 text-sm placeholder:text-gray-400 focus:outline-none" 
            />
          </div>

          <!-- Tombol Submit Primary Action (btn-kempo-1: #f2470c) -->
          <div class="pt-3">
            <button 
              type="submit" 
              class="btn-kempo-1 w-full py-4 px-6 font-bold text-sm sm:text-base cursor-pointer shadow-md"
            >
              Log In
            </button>
          </div>

          <!-- Tautan Pindah Halaman di Bawah Button Mengikuti Warna Primary -->
          <div class="text-center pt-3 text-sm text-gray-600">
            <span>Anda tidak memiliki akun? <a href="{{ url('register') }}" class="font-semibold hover:underline ml-1" style="color: var(--color-primary);">Registrasi dulu!</a></span>
          </div>
        </form>

      </div>


    </div>

  </main>

  <!-- SweetAlert & Notifikasi -->
  <script src="{{ asset('js/sweetalert2.js') }}"></script>
  <script>
    @if(session('success'))
      Swal.fire({
        icon: "success",
        title: "Horee...",
        text: "{{ session('success') }}",
      });
    @endif
  </script>
  <script>
    @if(session('error'))
      Swal.fire({
        icon: "error",
        title: "Maaf...",
        text: "{{ session('error') }}",
      });
    @endif
  </script>
</body>
</html>
