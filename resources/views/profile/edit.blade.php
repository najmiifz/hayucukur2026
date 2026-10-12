<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - Hayu Cukur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #f0d067; font-family: 'Poppins', sans-serif; }
        .navbar { background-color: #1f1f1f !important; border-bottom: 1px solid #333; }
        .navbar .nav-link, .navbar .navbar-brand, .dropdown-item, .nav-link span { color: #f0d067 !important; }
        .card { background-color: #1c1c1c; border: 1px solid #333; border-radius: 15px; color: #f0d067; }
        .card h4, .card h5, .form-label, .form-check-label { color: #f0d067; }
        .form-label { color: #f0d067; }
        .form-control { color: #f0d067; background-color: #2a2a2a; border-color: #444; }
        .form-control:focus { background-color: #2a2a2a; color: #f0d067; border-color: #f0d067; box-shadow: 0 0 0 0.25rem rgba(240, 208, 103, 0.25); }
        .btn-danger { background-color: #f0d067; color: #121212; border: none; font-weight: bold; }
        .back-link { color: #f0d067; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

@include('layouts.header')

<div class="container my-5">
    <div class="mb-4">
        {{-- FIX: Conditional back link based on role --}}
        <a href="{{ Auth::user()->role == 'mitra' ? route('profile.mitra.show') : route('profile.pelanggan.show') }}" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Profil</a>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h3 class="mb-4 fw-bold">Edit Informasi Akun</h3>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Use PUT method for updates --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    {{-- ADD THE PHONE NUMBER INPUT FIELD --}}
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}" required>
                        @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <hr style="border-color: #444;">
                    <p class="text-white-50">Ubah Kata Sandi (opsional)</p>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Kata Sandi Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror">
                        @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-danger w-100 mt-3">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
