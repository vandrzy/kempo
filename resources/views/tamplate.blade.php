<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> @yield('title') | M. Data Buku</title>
  {{-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" /> --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

  <style>
    body {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .main-wrapper {
      flex: 1;
      display: flex;
    }
    .sidebar {
      width: 250px;
      background-color: #f8f9fa;
    }
    .content {
      flex: 1;
      padding: 20px;
    }
    footer {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>
  <!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <a class="navbar-brand text-white" href="#">M. Data Buku</a>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

        </ul>
    @if (Auth::user())
        <a href="#" class="ml-2" style="color: white;">{{ Auth::user()->name }}</a>
    @else
        {{-- <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form> --}}
        <a href="{{ url('login') }}" class="btn btn-outline-light ml-2">Login</a>

    @endif

  </div>
</nav>

  <!-- Main Wrapper -->
  <div class="main-wrapper">
    <!-- Sidebar -->
    @if(Auth::user())
        <aside class="sidebar p-3">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item"><a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}"  href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ url('users') }}">Users</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('kategori*') ? 'active' : '' }}" href="{{ url('kategori') }}">Kategori</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('penulis*') ? 'active' : '' }}" href="{{ url('penulis') }}">Penulis</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('buku*') ? 'active' : '' }}" href="{{ url('buku') }}">Buku</a></li>
                @if (Auth::user())
                    <li class="nav-item"><hr></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('logout') }}">Log Out</a>
                    </li>
                @endif

            </ul>
        </aside>
    @endif
    <!-- Content -->
    <main class="content">
        @yield('content')
    </main>
  </div>

  <!-- Footer -->
  <footer class="text-center p-3">
    <small>Copyright &copy; 2025 - Jenny Nur Rahmawati</small>
  </footer>

  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.pilihan').select2({
                theme: 'bootstrap4', // atau 'bootstrap5' jika pakai tema lain
                width: '100%' // Biar serasi dengan Bootstrap form-control
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</body>
</html>
