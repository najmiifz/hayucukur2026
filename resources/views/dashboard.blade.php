@extends('layouts.pelanggan')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/images/gambar2.jpg" class="d-block w-100" style="height: 400px; object-fit: cover; filter: brightness(50%);" alt="Promo 1">
                <div class="carousel-caption d-none d-md-block" style="top: 50%; transform: translateY(-50%);">
                    <h5 style="font-size: 2.5rem; font-weight: bold;">Gaya Baru, Semangat Baru</h5>
                    <p>Temukan potongan rambut yang paling sesuai dengan kepribadian Anda.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="/images/gambar3.jpg" class="d-block w-100" style="height: 400px; object-fit: cover; filter: brightness(50%);" alt="Jadwalkan">
                <div class="carousel-caption d-none d-md-block" style="top: 50%; transform: translateY(-50%);">
                    <h5 style="font-size: 2.5rem; font-weight: bold;">Jadwalkan Kunjungan Anda</h5>
                    <p>Booking mudah dan cepat, tanpa perlu antre.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>

    <div class="container my-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Temukan Barbershop Terbaik</h2>
            <p class="text-white-50">Pilih berdasarkan kota dan layanan yang Anda inginkan.</p>
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="row g-3 mb-5 p-4 rounded" style="background-color: #1c1c1c; border: 1px solid #333;">
            <div class="col-md-6">
                <label for="kota" class="form-label">Kota</label>
                <select name="kota" id="kota" class="form-select" style="background-color: #2a2a2a; color: #f0d067; border-color: #444;">
                    <option value="">Semua Kota</option>
                    @foreach($locations as $location)
                        <option value="{{ $location }}" {{ request('kota') == $location ? 'selected' : '' }}>{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="layanan" class="form-label">Layanan</label>
                <select name="layanan" id="layanan" class="form-select" style="background-color: #2a2a2a; color: #f0d067; border-color: #444;">
                    <option value="">Semua Layanan</option>
                     @foreach($allServices as $service)
                        <option value="{{ $service }}" {{ request('layanan') == $service ? 'selected' : '' }}>{{ $service }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 text-end">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-danger">Reset Filter</a>
                <button type="submit" class="btn btn-gold"><i class="bi bi-search"></i> Cari Barbershop</button>
            </div>
        </form>

        <div class="row g-4">
            @forelse ($barbershops as $barbershop)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $barbershop->image) }}" class="card-img-top" alt="{{ $barbershop->name }}" style="height: 220px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $barbershop->name }}</h5>
                            <p class="card-text mb-2 text-white-50"><i class="bi bi-geo-alt-fill"></i> {{ $barbershop->location }}</p>
                            <p class="mb-2" style="color: #f0d067;">
                                <i class="bi bi-star-fill"></i>
                                {{ number_format($barbershop->average_rating, 1) }}
                                <span class="text-white-50">({{ $barbershop->ratings_count }} ulasan)</span>
                            </p>
                            <hr>
                            <div class="mt-auto">
                                <a href="{{ route('barbershop.show', $barbershop->id) }}" class="btn btn-gold w-100">Lihat Detail & Booking</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-shop-window fs-1 text-white-50"></i>
                    <h5 class="mt-3 text-white">Oops! Tidak ada barbershop yang cocok.</h5>
                    <p class="text-white-50">Coba ubah filter pencarian Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
