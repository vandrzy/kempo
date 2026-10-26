<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo-removebg.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-removebg.png') }}" />
    <title>@yield('title') | Shorinji Kempo</title>

    <!-- Google Fonts: Inter & Plus Jakarta Sans Sesuai Spesifikasi Desain -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/select2-mytheme.css') }}" rel="stylesheet" />

    <!-- Tailwind CSS v4 & Shorinji Kempo Design System via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="m-0 font-body text-base antialiased font-normal bg-[#fdfdfd] text-[#000000] leading-default min-h-screen flex flex-col">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleMobileSidebar()"
        class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-xs transition-opacity xl:hidden"></div>

    @if (Auth::user())
    <!-- ==================== FLOATING SIDEBAR (ROUNDED & ELEVATED) ==================== -->
    <aside id="sidenav-main"
        class="fixed top-4 bottom-4 left-4 z-50 flex flex-col w-64 bg-white border border-[#ebebeb] rounded-3xl shadow-sm transition-transform duration-300 ease-in-out -translate-x-[calc(100%+2rem)] xl:translate-x-0 select-none overflow-hidden">

        <!-- Brand Header -->
        <div class="flex items-center justify-between h-16 px-5 border-b border-[#ebebeb] shrink-0 bg-white">
            <a class="flex items-center gap-3 text-decoration-none no-underline" href="{{ url('dashboard') }}">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl overflow-hidden bg-white border border-[#ebebeb] shrink-0 shadow-2xs">
                    <img src="{{ asset('assets/img/logo.png') }}" class="h-full w-full object-cover" alt="Logo" />
                </div>
                <div class="flex flex-col">
                    <span class="font-heading font-bold text-[15px] tracking-tight text-[#000000] leading-none">
                        Shorinji Kempo
                    </span>
                    <span class="text-[11px] font-medium text-neutral-400 mt-1 leading-none">
                        Tournament System
                    </span>
                </div>
            </a>

            <!-- Close Button (Mobile Only) -->
            <button type="button" onclick="toggleMobileSidebar()"
                class="xl:hidden p-1.5 rounded-lg text-neutral-400 hover:text-[#000000] hover:bg-neutral-100 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Navigation List (Full Height, No Footer) -->
        <div class="flex-1 overflow-y-auto kempo-scrollbar px-3 py-4 space-y-6">

            <!-- Group: MENU UTAMA -->
            @if(Auth::user()->role !== 'wasit')
            <div>
                <div class="px-3 mb-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-400">
                        Menu Utama
                    </span>
                </div>

                <ul class="space-y-1 list-none p-0 m-0">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ url('dashboard') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('dashboard*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-house text-sm w-5 text-center {{ request()->is('dashboard*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            <!-- Group: MASTER DATA -->
            @if(Auth::user()->role !== 'wasit')
            <div>
                <div class="px-3 mb-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-400">
                        Master Data
                    </span>
                </div>

                <ul class="space-y-1 list-none p-0 m-0">
                    {{-- MENU PENGGUNA (Khusus Super Admin) --}}
                    @if(in_array(Auth::user()->role, ['admin', 1, '1']))
                    <li>
                        <a href="{{ url('users') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('users*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-user-shield text-sm w-5 text-center {{ request()->is('users*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>
                    @endif

                    <!-- Kategori -->
                    <li>
                        <a href="{{ url('kategori') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('kategori*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-layer-group text-sm w-5 text-center {{ request()->is('kategori*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Kategori</span>
                        </a>
                    </li>

                    <!-- Tanding -->
                    <li>
                        <a href="{{ url('pertandingan') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('pertandingan*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-trophy text-sm w-5 text-center {{ request()->is('pertandingan*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Tanding</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            <!-- Group: DATA UTAMA -->
            <div>
                <div class="px-3 mb-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-400">
                        Data Utama
                    </span>
                </div>

                <ul class="space-y-1 list-none p-0 m-0">
                    {{-- MENU KEGIATAN --}}
                    @if(in_array(Auth::user()->role, ['admin', 'wasit', 'user', 'operator', 1, '1', 0, '0', 2, '2']))
                    <li>
                        <a href="{{ url('kegiatan') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('kegiatan*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-calendar-check text-sm w-5 text-center {{ request()->is('kegiatan*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Kegiatan</span>
                        </a>
                    </li>
                    @endif

                    {{-- MENU LAINNYA (Dilarang untuk Wasit) --}}
                    @if(Auth::user()->role !== 'wasit')
                    <!-- Peserta -->
                    <li>
                        <a href="{{ url('peserta') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('peserta*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-users text-sm w-5 text-center {{ request()->is('peserta*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Peserta</span>
                        </a>
                    </li>

                    <!-- Official -->
                    <li>
                        <a href="{{ url('official') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('official*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-user-tie text-sm w-5 text-center {{ request()->is('official*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Official</span>
                        </a>
                    </li>

                    <!-- Kontingen -->
                    <li>
                        <a href="{{ url('kontingen') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('kontingen*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-flag text-sm w-5 text-center {{ request()->is('kontingen*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Kontingen</span>
                        </a>
                    </li>

                    <!-- Laporan Kegiatan -->
                    <li>
                        <a href="{{ route('laporan.kegiatan') }}"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 {{ request()->is('laporan-kegiatan*') ? 'bg-[#f2470c]/15 text-[#f2470c] font-bold shadow-xs' : 'text-neutral-600 font-medium hover:bg-[#f2470c]/5 hover:text-[#f2470c]' }}">
                            <i class="fa-solid fa-file-lines text-sm w-5 text-center {{ request()->is('laporan-kegiatan*') ? 'text-[#f2470c]' : 'text-neutral-400' }}"></i>
                            <span>Laporan Kegiatan</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

        </div>
    </aside>
    <!-- ==================== END FLOATING SIDEBAR ==================== -->
    @endif


    <!-- ==================== MAIN CONTENT WRAPPER ==================== -->
    <main class="relative min-h-screen flex flex-col min-w-0 {{ Auth::user() ? 'xl:ml-[288px]' : '' }} transition-all duration-200 bg-[#fdfdfd] overflow-x-hidden">

        <!-- ==================== FLOATING NAVBAR (ROUNDED, ELEVATED & FIXED) ==================== -->
        <header class="fixed top-4 left-4 sm:left-6 {{ Auth::user() ? 'xl:left-[312px]' : '' }} right-4 sm:right-6 z-30 px-5 sm:px-6 py-3.5 rounded-2xl bg-white/90 backdrop-blur-md border border-[#ebebeb] shadow-sm transition-all duration-200">
            <div class="flex items-center justify-between gap-4">

                <!-- Left: Mobile Hamburger & Page Title / Breadcrumb -->
                <div class="flex items-center gap-3 sm:gap-4">
                    @if (Auth::user())
                    <button type="button" onclick="toggleMobileSidebar()"
                        class="xl:hidden inline-flex items-center justify-center w-9 h-9 rounded-xl border border-[#ebebeb] bg-white text-[#000000] hover:bg-[#fff0eb] hover:text-[#f2470c] transition-colors shrink-0">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                    @endif

                    <div>
                        <nav class="flex items-center gap-1.5 text-xs text-neutral-400 mb-0.5">
                            <span>Pages</span>
                            <span>/</span>
                            <span class="text-[#f2470c] font-medium">@yield('title', 'Dashboard')</span>
                        </nav>
                        <h1 class="font-heading font-bold text-base sm:text-lg text-[#000000] m-0 leading-tight">
                            @yield('title', 'Dashboard')
                        </h1>
                    </div>
                </div>

                <!-- Right: Active Kegiatan Badge & User Account Dropdown -->
                <div class="flex items-center gap-3">
                    @if (Session::get('main_kegiatan_id') != null)
                        <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#fff0eb] border border-[#f2470c]/20">
                            <span class="w-2 h-2 rounded-full bg-[#f2470c] animate-pulse"></span>
                            <span class="text-xs font-semibold text-[#f2470c]">
                                Kegiatan Aktif: #{{ Session::get('main_kegiatan_id') }}
                            </span>
                        </div>
                    @endif

                    @if (Auth::user())
                        <!-- Tombol Akun dengan Dropdown Opsi Logout -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                            <button @click="open = !open" type="button"
                                class="flex items-center gap-2.5 pl-1.5 pr-3 py-1.5 rounded-full bg-white border border-[#ebebeb] hover:border-[#f2470c] hover:bg-[#fff0eb]/30 transition-all cursor-pointer shadow-xs focus:outline-none">
                                <div class="w-7 h-7 rounded-full bg-[#fff0eb] text-[#f2470c] font-heading font-bold text-xs flex items-center justify-center border border-[#f2470c]/20 shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="hidden sm:flex flex-col text-left">
                                    <span class="text-xs font-bold text-[#000000] leading-none">
                                        {{ Auth::user()->name }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-neutral-400 uppercase tracking-wide leading-none mt-1">
                                        {{ Auth::user()->role ?? 'User' }}
                                    </span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-neutral-400 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown Menu Opsi Logout -->
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                style="display: none;"
                                class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-[#ebebeb] shadow-xl py-2 z-50">
                                
                                <!-- User Info Header -->
                                <div class="px-4 py-2.5 border-b border-[#ebebeb]">
                                    <p class="text-xs font-bold text-[#000000] truncate m-0">
                                        {{ Auth::user()->name }}
                                    </p>
                                    <p class="text-[11px] text-neutral-400 truncate m-0 mt-0.5">
                                        {{ Auth::user()->email ?? (Auth::user()->role . ' Account') }}
                                    </p>
                                </div>

                                <!-- Opsi Logout -->
                                <div class="p-1.5">
                                    <a href="{{ url('logout') }}"
                                        class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold text-[#e54b4f] hover:bg-red-50 hover:text-red-700 transition-colors no-underline">
                                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </header>
        <!-- ==================== END FLOATING NAVBAR ==================== -->

        <!-- ==================== ISI TIAP HALAMAN (TIDAK DIUBAH) ==================== -->
        <div class="flex-1 w-full min-w-0 max-w-full px-4 sm:px-6 pb-6 mx-auto pt-28">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="mx-4 sm:mx-6 mb-4 px-6 py-4 rounded-2xl border border-[#ebebeb] text-center sm:text-left bg-white/70 shadow-2xs shrink-0">
            <div class="text-xs text-neutral-400">
                © <script>document.write(new Date().getFullYear())</script>
                <span class="font-semibold text-[#000000]">GreenNusa</span>. All rights reserved.
            </div>
        </footer>
    </main>

    <!-- ==================== SCRIPTS (PERTAHANKAN SEMUA FUNGSI) ==================== -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        // Fungsi Buka/Tutup Sidebar di Mobile (Floating Animation)
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidenav-main');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('-translate-x-[calc(100%+2rem)]');
            if (backdrop) backdrop.classList.toggle('hidden');
        }

        // Inisialisasi Select2 Tema Kempo
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.pilihan').select2({
                    theme: 'mytheme',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0
                });
            }
        });

        // Form confirmation listeners
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');
            const tandingForms = document.querySelectorAll('.tanding-form');

            deleteForms.forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e54b4f',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            tandingForms.forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah anda yakin ?',
                        text: "Sistem ini akan memilih peserta lawan peserta secara otomatis!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f2470c',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, jalankan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "Horee...",
                text: "{{ session('success') }}",
                confirmButtonColor: '#f2470c'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Maaf...",
                text: "{{ session('error') }}",
                confirmButtonColor: '#e54b4f'
            });
        </script>
    @endif

    @if(session('gagal'))
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session('gagal') }}",
                confirmButtonColor: '#e54b4f'
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": tokenMeta.getAttribute('content')
                    }
                });
            }
            @yield('script')
        });
    </script>

    @yield('scripts')
</body>
</html>
