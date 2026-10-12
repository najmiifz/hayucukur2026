@extends('layouts.mitra')

@section('title', 'Dashboard Mitra')

@section('content')
    @if(session('success'))
        <div class="alert alert-success mt-4" style="background-color: #198754; color: white; border: none;">
            {{ session('success') }}
        </div>
    @endif
    <h2 class="fw-bold">Halo, Mitra!</h2>
    <p class="text-white-50">Selamat datang di Dashboard HayuCukur. Berikut adalah ringkasan bisnis Anda hari ini.</p>

    {{-- Stats Section --}}
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-journal-text fs-1 me-4"></i>
                        <div>
                            <h5 class="card-title">Total Booking</h5>
                            <p class="card-text fs-4 fw-bold">{{ $totalBookings }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-day fs-1 me-4"></i>
                        <div>
                            <h5 class="card-title">Booking Hari Ini</h5>
                            <p class="card-text fs-4 fw-bold">{{ $todayBookings }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-coin fs-1 me-4"></i>
                        <div>
                            <h5 class="card-title">Total Pendapatan Hari Ini</h5>
                            <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4" style="background-color: #1c1c1c; border-color: #333;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Statistik Booking</h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('dashboard.mitra', ['range' => 'weekly']) }}" class="btn btn-sm {{ request('range', 'weekly') == 'weekly' ? 'btn-gold' : 'btn-outline-gold' }}">7 Hari</a>
                        <a href="{{ route('dashboard.mitra', ['range' => 'monthly']) }}" class="btn btn-sm {{ request('range') == 'monthly' ? 'btn-gold' : 'btn-outline-gold' }}">30 Hari</a>
                        <a href="{{ route('dashboard.mitra', ['range' => 'yearly']) }}" class="btn btn-sm {{ request('range') == 'yearly' ? 'btn-gold' : 'btn-outline-gold' }}">1 Tahun</a>
                    </div>
                </div>
                <canvas id="bookingChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Main Action Cards --}}
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <a href="{{ route('mitra.bookings.index') }}" class="card-link">
                <div class="card stat-card p-4 h-100">
                    <h5 class="fw-bold"><i class="bi bi-calendar-check-fill me-2"></i>Bookingan Pelanggan</h5>
                    <p>Lihat semua daftar bookingan pelanggan yang masuk ke barbershop kamu.</p>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-4">
            <a href="{{ route('mitra.barbershops.index') }}" class="card-link">
                <div class="card stat-card p-4 h-100">
                    <h5 class="fw-bold"><i class="bi bi-scissors me-2"></i>Kelola Barbershop</h5>
                    <p>Edit nama, alamat, jam operasional dan info lainnya dari barbershop kamu.</p>
                </div>
            </a>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const initialData = {!! json_encode($initialChartData) !!};
            const ctx = document.getElementById('bookingChart').getContext('2d');

            // --- 1. Create the Chart Instance ---
            const bookingChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initialData.labels,
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: initialData.data,
                        backgroundColor: 'rgba(240, 208, 103, 0.2)',
                        borderColor: 'rgba(240, 208, 103, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: { /* ... Your chart options from before ... */ }
            });

            // --- 2. Add Event Listeners to Filter Buttons ---
            document.querySelectorAll('.btn-group .btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent page reload

                    // Update active button style
                    document.querySelectorAll('.btn-group .btn').forEach(btn => {
                        btn.classList.remove('btn-gold');
                        btn.classList.add('btn-outline-gold');
                    });
                    this.classList.remove('btn-outline-gold');
                    this.classList.add('btn-gold');

                    const url = this.href;

                    // --- 3. Fetch New Data and Update Chart ---
                    fetch(url.replace('dashboard-mitra', 'mitra/chart-data'))
                        .then(response => response.json())
                        .then(data => {
                            bookingChart.data.labels = data.labels;
                            bookingChart.data.datasets[0].data = data.data;
                            bookingChart.update(); // Redraw the chart
                        });
                });
            });
        });
    </script>
@endpush
