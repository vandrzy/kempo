@extends('tamplate')
@section('title', 'Dashboard')
@section('content')
    {{-- <h2 class="text-2xl font-bold mb-2"></h2> --}}
    <p class="text-gray-700"></p>
    <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
            <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                <h4 class="capitalize dark:text-white">Dashboard</h4>
                <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                  <i class="fa fa-arrow-up text-emerald-500"></i>
                  Selamat Datang <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span> di Aplikasi Pendaftaran Kempo
                </p>
                <br><br>
              </div>
              <div class="flex-auto p-4"></div>
              {{-- <div class="flex-auto p-4">
                <div>
                  <canvas id="chart-line" height="300"></canvas>
                </div>
              </div> --}}
            </div>
          </div>
    </div>
              <br><br>

@endsection
