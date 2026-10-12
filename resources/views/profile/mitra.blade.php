@extends('layouts.mitra')

@section('title', 'Profil Mitra')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Profil Mitra</h2>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-light">
            <i class="bi bi-pencil-square me-2"></i>Edit Profil
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background-color: #198754; color: white; border: none;">{{ session('success') }}</div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card p-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-white-50">Total Booking</h5>
                    <p class="display-4 fw-bold mt-2">{{ $stats['total_bookings'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card p-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-white-50">Total Pendapatan</h5>
                    <p class="display-4 fw-bold mt-2">Rp{{ number_format($stats['total_earnings'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4" style="background-color: #1c1c1c; border-color: #333;">
        <h4 class="mb-4 fw-bold">Informasi Akun</h4>
        <p class="mb-2"><strong>Nama:</strong><br>{{ $user->name }}</p>
        <p class="mb-0"><strong>Nomor Telepon:</strong><br>{{ $user->phone_number ?? 'Belum ditambahkan' }}</p>
        <p class="mb-0"><strong>Email:</strong><br>{{ $user->email }}</p>
    </div>
@endsection
