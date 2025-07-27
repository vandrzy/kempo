<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <form method="POST" action="{{ url('login') }}" class="bg-white p-4 rounded shadow-sm w-25">
        @csrf

        <center>
            <h2>M. Data Buku</h2>
            <hr>
            <h4 class="mb-3">Login</h3>
        </center>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <a href="{{ url('/') }}">< Kembali</a>
        <br><br>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</body>
</html>
