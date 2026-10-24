<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
  <title>Login | Pertandingan Kempo</title>

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
    <div class="w-full lg:w-1/2 p-8 sm:p-10 md:p-12 lg:p-14 flex flex-col justify-center">
      
      <!-- Header Form -->
      <div class="mb-8 text-left">
        <h4 class="font-heading font-bold text-2xl sm:text-3xl text-gray-900 mb-2">Login</h4>
        <p class="font-body text-sm sm:text-base text-gray-500 mb-0">Masukan email dan password anda!</p>
      </div>

      <!-- Pesan Error Validasi -->
      @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-[#e54b4f] text-sm flex items-start gap-3" role="alert">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 1118 0z" />
          </svg>
          <span class="font-medium">{{ $errors->first() }}</span>
        </div>
      @endif

      <!-- Form Login Asli -->
      <form role="form" action="{{ url('login') }}" method="POST" class="space-y-5">
        @csrf

        <div>
          <input 
            type="email" 
            name="email" 
            placeholder="Email" 
            class="kempo-input w-full px-4 py-3.5 text-sm rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none" 
          />
        </div>

        <div>
          <input 
            type="password" 
            name="password" 
            placeholder="Password" 
            class="kempo-input w-full px-4 py-3.5 text-sm rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none" 
          />
        </div>

        <div class="pt-2">
          <button 
            type="submit" 
            class="kempo-btn-primary w-full py-3.5 px-6 font-bold text-sm rounded-xl cursor-pointer shadow-md"
          >
            Log In
          </button>
        </div>

        <!-- Footer Form (Tautan ke Halaman Register) -->
        <div class="text-center pt-3 text-sm text-gray-600">
          <span>Anda tidak memiliki akun? <a href="{{ url('register') }}" class="font-semibold text-[#f2470c] hover:underline" style="color: #f2470c;">Registrasi dulu!</a></span>
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
