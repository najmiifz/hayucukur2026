<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Mitra') - Hayu Cukur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
     @vite(['resources/css/mitra.css']) <!-- Menggunakan Vite untuk menampikan style -->
    <style>
        body {
            background-color: #121212;
            color: #f0d067;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 250px;
            background-color: #1f1f1f;
            min-height: 100vh;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #f0d067;
            padding: 15px 20px;
            font-weight: 600;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #121212 !important;
            background-color: #f0d067;
        }
        .main-content {
            flex: 1;
        }
        .form-control {
            background-color: #2a2a2a;
            color: #e9ecef;
            border-color: #444;
        }
        .form-control:focus {
            background-color: #2a2a2a;
            color: #e9ecef;
            border-color: #f0d067;
            box-shadow: 0 0 0 0.25rem rgba(240, 208, 103, 0.25);
        }
        .btn-gold {
            background-color: #f0d067;
            color: #121212;
            border: 1px solid #f0d067;
            font-weight: bold;
        }
        .btn-gold:hover {
            background-color: #d4b55a;
            border-color: #d4b55a;
            color: #121212;
        }
        .card h4, .card p > strong {
            color: #f0d067;
        }
        .btn-outline-gold {
            color: #f0d067;
            border-color: #f0d067;
        }
        .btn-outline-gold:hover {
            color: #121212;
            background-color: #f0d067;
            border-color: #f0d067;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <div class="sidebar d-flex flex-column flex-shrink-0 p-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard.mitra') }}" class="nav-link {{ request()->routeIs('dashboard.mitra') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.barbershops.index') }}" class="nav-link {{ request()->routeIs('mitra.barbershops.index') ? 'active' : '' }}">
                        <i class="bi bi-scissors me-2"></i>Kelola Barbershop
                    </a>
                </li>
                <li>
                    <a href="{{ route('mitra.bookings.index') }}" class="nav-link {{ request()->routeIs('mitra.bookings.index') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check-fill me-2"></i>Bookingan
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            @include('layouts.header')
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
