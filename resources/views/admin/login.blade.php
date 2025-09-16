<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" />
    <title>Login | Pertandingan Kempo</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <style>
        .swal2-confirm {
            background-color: #2563eb !important; /* biru misalnya */
            color: #fff !important;
        }

        .swal2-cancel {
            background-color: #dc2626 !important; /* merah */
            color: #fff !important;
        }
    </style>
    <!-- Main Styling -->
    <link href="{{ asset('assets/css/argon-dashboard-tailwind.css?v=1.0.1') }}" rel="stylesheet" />
  </head>
<body>

  {{-- <div class="w-full max-w-md bg-white rounded-xl shadow-md p-6">
    <h1 class="text-4xl font-semibold text-center text-gray-700 mb-6">{{ env('APP_NAME') }}</h1>
    <hr>
    <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Login</h2>
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif
    <form action="{{ url('login') }}" method="POST" class="space-y-4">
        @csrf

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          type="email"
          name="email"
          id="email"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500"
          required
        />
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          type="password"
          name="password"
          id="password"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500"
          required
        />
      </div>

      <!-- Submit -->
      <div>
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-150"
        >
          Masuk
        </button>
      </div>
    </form>

  </div> --}}
<main class="mt-0 transition-all duration-200 ease-in-out">
    <section>
    <div class="relative flex items-center min-h-screen p-0 overflow-hidden bg-center bg-cover">
        <div class="container z-1">
        <div class="flex flex-wrap -mx-3">
            <div class="flex flex-col w-full max-w-full px-3 mx-auto lg:mx-0 shrink-0 md:flex-0 md:w-7/12 lg:w-5/12 xl:w-4/12">
            <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none lg:py4 dark:bg-gray-950 rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0">
                    <h4 class="font-bold">Login</h4>
                    <p class="mb-0">Masukan email dan password anda!</p>

                </div>
                @if ($errors->any())
                    <div style="color: red;" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded ml-4">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div class="flex-auto p-6">
                    <form role="form" action="{{ url('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                        <input type="email" name="email" placeholder="Email" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                        </div>
                        <div class="mb-4">
                        <input type="password" name="password" placeholder="Password" class="focus:shadow-primary-outline dark:bg-gray-950 dark:placeholder:text-white/80 dark:text-white/80 text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding p-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none" />
                        </div>
                        {{-- <div class="flex items-center pl-12 mb-0.5 text-left min-h-6">
                        <input id="rememberMe" class="mt-0.5 rounded-10 duration-250 ease-in-out after:rounded-circle after:shadow-2xl after:duration-250 checked:after:translate-x-5.3 h-5 relative float-left -ml-12 w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-zinc-700/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-blue-500/95 checked:bg-blue-500/95 checked:bg-none checked:bg-right" type="checkbox" />
                        <label class="ml-2 font-normal cursor-pointer select-none text-sm text-slate-700" for="rememberMe">Remember me</label>
                        </div> --}}
                        <div class="text-center">
                        <button type="submit" class="inline-block w-full px-16 py-3.5 mt-6 mb-0 font-bold leading-normal text-center text-white align-middle transition-all bg-blue-500 border-0 rounded-lg cursor-pointer hover:-translate-y-px active:opacity-85 hover:shadow-xs text-sm ease-in tracking-tight-rem shadow-md bg-150 bg-x-25">Log In</button>
                        </div>
                    </form>
                </div>
                {{-- <div class="border-black/12.5 rounded-b-2xl border-t-0 border-solid p-6 text-center pt-0 px-1 sm:px-6">
                    <p class="mx-auto mb-6 leading-normal text-sm">Don't have an account? <a href="../pages/sign-up.html" class="font-semibold text-transparent bg-clip-text bg-gradient-to-tl from-blue-500 to-violet-500">Sign up</a></p>
                </div> --}}
            </div>
            </div>
            <div class="absolute top-0 right-0 flex-col justify-center hidden w-6/12 h-full max-w-full px-3 pr-0 my-auto text-center flex-0 lg:flex">
            <div class="relative flex flex-col justify-center h-full bg-cover px-24 m-4 overflow-hidden rounded-xl " style="background-image: url('{{ asset('assets/img/kempo.png') }}');">
                <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-blue-500 to-violet-500 opacity-60"></span>
                <h4 class="z-20 mt-12 font-bold text-white">"Aplikasi Pendaftaran Kempo"</h4>
                <p class="z-20 text-white ">Aplikasi untuk mengelola data peserta dan pertandingan Kempo.</p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </section>
</main>

</body>
</html>
