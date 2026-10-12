<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Hayu Cukur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #f0d067;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px 0;
        }
        .auth-box {
            width: 100%;
            max-width: 500px;
            background-color: #1c1c1c;
            padding: 40px;
            border-radius: 20px;
            border: 1px solid #333;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .form-label { color: #f0d067; }
        .form-control {
            color: #f0d067;
            background-color: #2a2a2a;
            border-color: #444;
            padding: 12px 15px;
        }
        .form-control:focus {
            background-color: #2a2a2a;
            color: #f0d067;
            border-color: #f0d067;
            box-shadow: 0 0 0 0.25rem rgba(240, 208, 103, 0.25);
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
        }
        .btn-submit {
            background-color: #f0d067;
            color: #121212;
            border: none;
            font-weight: bold;
            padding: 12px;
        }
        .auth-link {
            color: #f0d067;
            text-decoration: none;
        }
        .auth-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-box">
        <div class="text-center mb-4">
            <a href="{{ route('beranda') }}">
                <img src="/images/logocukur.png" alt="Logo" style="height: 60px;">
            </a>
        </div>
        @php
            $isMitra = isset($role) && $role === 'mitra';
            $title = $isMitra ? 'Daftar Sebagai Mitra' : 'Buat Akun Baru';
            $loginLink = $isMitra ? route('login', ['as' => 'mitra']) : route('login');
        @endphp

        <h3 class="text-center mb-4 fw-bold">{{ $title }}</h3>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <input type="hidden" name="role" value="{{ $isMitra ? 'mitra' : 'pelanggan' }}">

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Nomor Telepon</label>
                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}">
                @error('phone_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-submit">Daftar</button>
            </div>
            <div class="text-center mt-3">
                <small class="text-white-50">Sudah punya akun? <a href="{{ $loginLink }}" class="auth-link fw-bold">Login di sini</a></small>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
