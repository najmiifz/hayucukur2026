<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Barbershop - {{ $barbershop->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #121212;
            color: #f0d067;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: #1f1f1f !important;
            border-bottom: 1px solid #333;
        }
        .navbar .nav-link, .navbar .navbar-brand, .dropdown-item, .nav-link span {
            color: #f0d067 !important;
        }
        .card-detail {
            background-color: #1c1c1c;
            border: 1px solid #333;
            color: #f0d067;
            border-radius: 15px;
        }
        .btn-danger {
            background-color: #f0d067;
            color: #121212;
            border: none;
            font-weight: bold;
        }
        .btn-danger:hover {
            background-color: #e6c856;
        }
        .back-link {
            color: #f0d067;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .list-group-item {
            background-color: #2a2a2a;
            border-color: #444;
            color: #f0d067;
        }
    </style>
</head>
<body>

@include('layouts.header')

<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
    <div class="row">
        <div class="col-md-7 mb-4">
            <img src="{{ asset('storage/' . $barbershop->image) }}" class="img-fluid rounded shadow-lg" alt="{{ $barbershop->name }}">
        </div>
        <div class="col-md-5">
            <div class="card card-detail p-4">
                <h2 class="fw-bold mb-3">{{ $barbershop->name }}</h2>
                <p><i class="bi bi-geo-alt-fill me-2"></i>{{ $barbershop->location }}</p>
                <p><i class="bi bi-geo me-2"></i>{{ $barbershop->address }}</p>
                <p><i class="bi bi-clock-fill me-2"></i>Buka: {{ date('H:i', strtotime($barbershop->open_time)) }} - {{ date('H:i', strtotime($barbershop->close_time)) }}</p>

                <hr class="my-3">

                <p><strong>Deskripsi:</strong></p>
                <p>{{ $barbershop->description }}</p>

                <h5 class="mt-3">Layanan Tersedia:</h5>
                <ul class="list-group">
                    @forelse($barbershop->services as $service)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $service['name'] }}
                            <span>Rp{{ number_format($service['price'], 0, ',', '.') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item">Belum ada layanan yang ditambahkan.</li>
                    @endforelse
                </ul>
                <div class="mt-4">
                    {{-- This section checks if a phone number exists and creates the WhatsApp link --}}
                    {{-- @if ($barbershop->phone_number)
                        @php
                            // Prepares the phone number for the WhatsApp URL
                            $waNumber = preg_replace('/[^0-9]/', '', $barbershop->phone_number);
                            if (substr($waNumber, 0, 1) === '0') {
                                $waNumber = '62' . substr($waNumber, 1);
                            }
                        @endphp

                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn w-100 mb-2" style="background-color: #25D366; color: white;">
                            <i class="bi bi-whatsapp me-2"></i> Chat di WhatsApp
                        </a>
                    @endif --}}

                    {{-- Your original booking button is still here --}}
                    <a href="{{ route('booking.create', $barbershop->id) }}" class="btn btn-danger w-100 p-2">
                        <i class="bi bi-calendar-check"></i> Booking Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
