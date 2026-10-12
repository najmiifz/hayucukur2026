<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking di {{ $barbershop->name }} - Hayu Cukur</title>
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
        .step-progress {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin: 20px 0;
        }
        .step-progress div {
            font-weight: 600;
            color: gray;
        }
        .step-progress .active {
            color: #f0d067;
        }
        .card {
            background-color: #1c1c1c;
            border: 1px solid #333;
            border-radius: 15px;
        }
        .card h4, .card h5, .form-label, .form-check-label {
            color: #f0d067;
        }
        .form-control, .form-select, .form-check-input {
            color: #f0d067;
            background-color: #2a2a2a;
            border-color: #444;
        }
        /* **FIX 2:** Style for the date and time picker icon */
        .form-control::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
        .form-check-input:checked {
            background-color: #f0d067;
            border-color: #f0d067;
        }
        .form-control:focus, .form-select:focus {
            background-color: #2a2a2a;
            color: #f0d067;
            border-color: #f0d067;
            box-shadow: 0 0 0 0.25rem rgba(240, 208, 103, 0.25);
        }
        .btn-danger {
            background-color: #f0d067;
            color: #121212;
            border: none;
            font-weight: bold;
        }
        .price { color: #aaa; }
        hr { border-color: #444; }
    </style>
</head>
<body>

@include('layouts.header')

<!-- Step Progress -->
<div class="step-progress mt-4">
    <div class="active">1. Pilih Layanan & Jadwal</div>
    <div>2. Konfirmasi & Bayar</div>
    <div>3. Booking Berhasil</div>
</div>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4">Formulir Booking untuk {{ $barbershop->name }}</h2>
    <form action="{{ route('booking.store') }}" method="POST">
        @csrf
        <input type="hidden" name="barbershop_id" value="{{ $barbershop->id }}">
        <input type="hidden" name="total_price" id="total_price_input" value="0">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4">
                    <h4 class="mb-4">Pilih Layanan</h4>
                    <div class="mb-3">
                        @forelse($barbershop->services as $service)
                            <div class="form-check mb-2">
                                <input class="form-check-input layanan" name="services[]" type="checkbox" value="{{ $service['name'] }}" data-price="{{ $service['price'] }}" id="service-{{ $loop->index }}">
                                <label class="form-check-label" for="service-{{ $loop->index }}">
                                    {{ $service['name'] }} <span class="price">(Rp{{ number_format($service['price'], 0, ',', '.') }})</span>
                                </label>
                            </div>
                        @empty
                            <p class="text-muted">Barbershop ini belum memiliki layanan.</p>
                        @endforelse
                        @error('services') <div class="text-danger mt-2"><small>{{ $message }}</small></div> @enderror
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-4">Pilih Jadwal</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="booking_date" class="form-label">Tanggal</label>
                            <input type="date" id="booking_date" name="booking_date" class="form-control" required>
                             @error('booking_date') <div class="text-danger mt-2"><small>{{ $message }}</small></div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="booking_time" class="form-label">Jam</label>
                            <input type="time" id="booking_time" name="booking_time" class="form-control" required>
                             @error('booking_time') <div class="text-danger mt-2"><small>{{ $message }}</small></div> @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total Biaya: <span id="totalHarga">Rp0</span></h5>
                        <button type="submit" class="btn btn-danger btn-lg">
                            Lanjut ke Pembayaran <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <h5 class="alert-heading">Oops! Something went wrong.</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.layanan');
        const totalHargaSpan = document.getElementById('totalHarga');
        const totalHargaInput = document.getElementById('total_price_input');

        function calculateTotal() {
            let total = 0;
            checkboxes.forEach(c => {
                if (c.checked) {
                    total += parseInt(c.dataset.price);
                }
            });
            totalHargaSpan.innerText = 'Rp' + total.toLocaleString('id-ID');
            totalHargaInput.value = total;
        }

        checkboxes.forEach(item => {
            item.addEventListener('change', calculateTotal);
        });
    });
</script>

</body>
</html>
