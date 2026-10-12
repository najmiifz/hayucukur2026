<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - Hayu Cukur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #f0d067; font-family: 'Poppins', sans-serif; }
        .navbar { background-color: #1f1f1f !important; border-bottom: 1px solid #333; }
        .navbar .nav-link, .navbar .navbar-brand, .dropdown-item, .nav-link span { color: #f0d067 !important; }
        .card { background-color: #1c1c1c; border: 1px solid #333; border-radius: 15px; color: #f0d067; }
        .card h4, .card h5, .form-label, .form-check-label { color: #f0d067; }
        .btn-outline-light { border-color: #f0d067; color: #f0d067; }
        .btn-outline-light:hover { background-color: #f0d067; color: #121212; }
        .list-group-item { background-color: #2a2a2a; border-color: #444; color: #f0d067; }
    </style>
</head>
<body>

@include('layouts.header')

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Profil Saya</h2>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-light">
            <i class="bi bi-pencil-square me-2"></i>Edit Profil
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background-color: #198754; color: white; border: none;">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <!-- Profile Info -->
        <div class="col-lg-7">
            <div class="card p-4 h-100">
                <h4 class="mb-4">Informasi Akun</h4>
                <p class="mb-2"><strong>Nama:</strong><br>{{ $user->name }}</p>
                <p class="mb-0"><strong>Nomor Telepon:</strong><br>{{ $user->phone_number ?? 'Belum ditambahkan' }}</p>
                <p class="mb-0"><strong>Email:</strong><br>{{ $user->email }}</p>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-lg-5">
            <div class="card p-4">
                <h4 class="mb-4">5 Booking Terakhir</h4>
                <ul class="list-group list-group-flush">
                    @forelse($recentBookings as $booking)
                        <li class="list-group-item">
                            <div class="fw-bold">{{ $booking->barbershop->name }}</div>
                            <small class="text-white-50">{{ $booking->booking_time->format('d M Y, H:i') }} - <span class="fw-bold">{{ $booking->status }}</span></small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Anda belum memiliki riwayat booking.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
