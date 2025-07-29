
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
      <title> @yield('title') | M. Data Buku</title>

      <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- Header -->
  <header class="bg-gray-800 text-white p-4 shadow">
    <div class="flex justify-between items-center p-2">
        <div>
            <h1 class="text-2xl font-bold">Pertandingan Kempo</h1>
        </div>
        <div>
            @if (Auth::user())
                <span class="px-4 py-1 text-2xl text-white rounded-md ">{{ Auth::user()->name }}</span>

            @else
                <a href="{{ url('login') }}" class="px-4 py-1 text-2xl text-white rounded-md hover:bg-blue-600 transition">Login</a>
            @endif
        </div>
    </div>
  </header>


    <div class="flex flex-1">
        <!-- Sidebar -->
        @if (Auth::user())
            <aside class="w-64 bg-white p-4 shadow-lg hidden md:block">
            <nav class="space-y-2">
                <div x-data="{ open: false }" class="w-full">
                    <a href="{{ url('dashboard') }}" class="block p-2 rounded {{ request()->is('dashboard*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-blue-100' }}">Dashboard</a>

                    <!-- Tombol Dropdown -->
                    <button @click="open = !open" class="flex items-center gap-2 px-2 py-2  text-black rounded hover:bg-blue-300 w-full">
                        Master Data
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Menu Dropdown yang mendorong konten di bawah -->
                    <div x-show="open" x-transition @click.away="open = false" class="mt-2 w-full bg-white border border-gray-200 rounded shadow">
                        <a href="{{ url('users') }}"
                        class="block px-4 py-2 rounded {{ request()->is('users*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-blue-100' }}">
                        Users
                        </a>
                        <a href="{{ url('kategori') }}"
                        class="block px-4 py-2 rounded {{ request()->is('kategori*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-blue-100' }}">
                        Kategori
                        </a>
                        <a href="{{ url('pertandingan') }}"
                        class="block px-4 py-2 rounded {{ request()->is('pertandingan*') ? 'bg-blue-100 text-blue-700 font-semibold' : 'hover:bg-blue-100' }}">
                        Pertandingan
                        </a>
                    </div>

                    <!-- Elemen di bawah dropdown -->
                    <div class="mt-4 p-4 bg-gray-100 rounded">
                        <hr>
                        <a class="block p-2 rounded text-red-800 hover:bg-blue-100" href="{{ url('logout') }}">Log Out</a>
                    </div>
                </div>

            </nav>
            </aside>
        @endif

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-50">
            @yield('content')
        </main>
    </div>

  <!-- Footer -->
  <footer class="bg-gray-200 text-black text-center p-4">
    &copy; 2025 - All rights reserved
  </footer>
    <script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="{{ asset('js/popper.min.js') }}" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.pilihan').select2({
                theme: 'bootstrap4', // atau 'bootstrap5' jika pakai tema lain
                width: '100%' // Biar serasi dengan Bootstrap form-control
            });
        });
    </script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "Horee...",
                text: "{{ session('success') }}",
            });
        @endif

        @if(session('gagal'))
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ session('gagal') }}",
            });
        @endif
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Cegah langsung submit

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit jika disetujui
                        }
                    });
                });
            });
        });

        document.getElementById('imageInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('preview').src = URL.createObjectURL(file);
            }
        });
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        $(document).ready(function() {
            @yield('script')
        });
    </script>

</body>
</html>
