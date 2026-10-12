<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hayu Cukur - Cukur Gampang & Kekinian</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Lato:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary: #f0d067;
            --primary-dark: #d4b95d;
            --dark: #121212;
            --darker: #0a0a0a;
            --light: #f8f9fa;
            --gray: #6c757d;
        }
        body {
            background-color: var(--dark);
            font-family: 'Lato', sans-serif;
            color: var(--light);
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, .navbar-brand, .btn {
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: var(--darker);
            padding: 15px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand img { height: 50px; transition: transform 0.3s ease; }
        .navbar-brand:hover img { transform: scale(1.05); }
        .nav-link { color: var(--primary) !important; font-weight: 600; position: relative; margin: 0 10px; padding: 8px 0 !important; }
        .nav-link::after { content: ''; position: absolute; bottom: 0; left: 0; width: 0; height: 2px; background-color: var(--primary); transition: width 0.3s ease; }
        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: #ffffff !important; }
        .hero {
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('images/tukangcukur.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 90vh;
            position: relative;
            color: var(--primary);
            display: flex;
            align-items: center;
            text-align: center;
        }
        .hero-content { max-width: 800px; margin: 0 auto; padding: 0 20px; }
        .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 1.5rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); line-height: 1.2; }
        .hero p { font-size: 1.25rem; margin-bottom: 2rem; }
        .section-padding { padding: 100px 20px; position: relative; }
        .section-title { position: relative; display: inline-block; margin-bottom: 50px; }
        .section-title::after { content: ''; position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background: var(--primary); }
        .feature-card, .barber-card { background: var(--darker); border-radius: 15px; padding: 30px; color: var(--light); border: 1px solid #333; transition: all 0.4s ease; height: 100%; position: relative; overflow: hidden; }
        .feature-card:hover, .barber-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(240,208,103,0.15); border-color: var(--primary); }
        .feature-icon { font-size: 3rem; color: var(--primary); margin-bottom: 1.5rem; transition: transform 0.3s ease; }
        .feature-card:hover .feature-icon { transform: scale(1.1) rotate(5deg); }
        .barber-card img { height: 220px; object-fit: cover; width: 100%; border-radius: 15px 15px 0 0; transition: transform 0.5s ease; }
        .barber-card:hover img { transform: scale(1.05); }
        .barber-rating { color: var(--primary); font-weight: 600; }
        .partner-section { background-color: var(--darker); color: var(--primary); position: relative; overflow: hidden; }
        .partner-section::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?q=80&w=2074&auto=format&fit=crop') center/cover; opacity: 0.1; z-index: 0; }
        .partner-content { position: relative; z-index: 1; }
        .btn-gradient { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: var(--dark); border: none; border-radius: 50px; padding: 12px 30px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(240, 208, 103, 0.3); }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(240, 208, 103, 0.4); color: var(--dark); }
        .footer { background-color: var(--darker); color: var(--gray); padding: 80px 20px 30px; position: relative; }
        .footer::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 2px; background: linear-gradient(90deg, transparent, var(--primary), transparent); }
        .footer h5 { color: var(--light); font-weight: 600; margin-bottom: 20px; position: relative; display: inline-block; }
        .footer h5::after { content: ''; position: absolute; bottom: -8px; left: 0; width: 40px; height: 2px; background: var(--primary); }
        .footer a { color: var(--gray); text-decoration: none; transition: color 0.3s ease; display: inline-block; margin-bottom: 8px; }
        .footer a:hover { color: var(--primary); transform: translateX(5px); }
        .social-icons a { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: rgba(240,208,103,0.1); border-radius: 50%; font-size: 1.2rem; margin-right: 10px; color: var(--primary); transition: all 0.3s ease; }
        .social-icons a:hover { background: var(--primary); color: var(--dark); transform: translateY(-3px); }
    </style>
</head>
<body>

@include('layouts.header')

<header class="hero">
    <div class="hero-content animate__animated animate__fadeIn">
        <h1 class="fw-bold mb-4">Gaya Rambut Keren <span style="color: #fff;">Tanpa Antre</span></h1>
        <p class="fs-4 mb-4">Temukan dan booking barbershop terbaik di sekitarmu dengan mudah.</p>
        <a href="{{ route('register') }}" class="btn btn-gradient btn-lg">Booking Sekarang</a>
    </div>
</header>

<section class="section-padding text-center">
    <div class="container">
        <h2 class="fw-bold mb-5 section-title">Kenapa Pilih HayuCukur?</h2>
        <div class="row">
            <div class="col-md-4 mb-4"><div class="feature-card"><i class="bi bi-calendar2-check feature-icon"></i><h4 class="fw-bold">Booking Mudah</h4><p>Pilih jadwal, kapster, dan layanan sesuai keinginanmu hanya dalam beberapa klik.</p></div></div>
            <div class="col-md-4 mb-4"><div class="feature-card"><i class="bi bi-scissors feature-icon"></i><h4 class="fw-bold">Banyak Pilihan</h4><p>Temukan barbershop dari potong rambut, jenggot, hingga creambath.</p></div></div>
            <div class="col-md-4 mb-4"><div class="feature-card"><i class="bi bi-qr-code-scan feature-icon"></i><h4 class="fw-bold">Bayar Praktis</h4><p>Pembayaran aman dan cepat di lokasi atau langsung dari aplikasi.</p></div></div>
        </div>
    </div>
</section>

<section class="section-padding text-center" style="background-color: var(--darker);">
    <div class="container">
        <h2 class="fw-bold mb-5 section-title"><i class="bi bi-geo-alt-fill me-2"></i>Pilihan Barber Kita</h2>
        <p class="mb-5 fs-5">Barbershop terbaik dengan rating tinggi dari para pelanggan.</p>
        <div class="row">
            @forelse ($popularBarbershops as $barbershop)
                <div class="col-md-4 mb-4">
                    <div class="barber-card">
                        <img src="{{ asset('storage/' . $barbershop->image) }}" alt="{{ $barbershop->name }}">
                        <div class="p-4">
                            <h5 class="fw-bold">{{ $barbershop->name }}</h5>
                            <p><i class="bi bi-pin-map-fill"></i> {{ $barbershop->location }}</p>
                            @if($barbershop->ratings_count > 0)
                                <p class="barber-rating"><i class="bi bi-star-fill"></i> {{ $barbershop->averageRating() }} ({{ $barbershop->ratings_count }}+)</p>
                            @else
                                <p class="barber-rating text-muted">Belum ada rating</p>
                            @endif
                            <a href="{{ route('barbershop.show', $barbershop->id) }}" class="btn btn-gradient btn-sm mt-2"><span>Lihat Detail</span></a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">Belum ada barbershop yang terdaftar.</p>
                </div>
            @endforelse
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-gradient mt-4">Lihat Semua Barber</a>
    </div>
</section>

<section class="section-padding partner-section text-center">
    <div class="partner-content">
        <h2 class="fw-bold mb-3">Punya Barbershop?</h2>
        <p class="lead mb-4">Gabung jadi mitra dan kelola jadwal dengan lebih efisien.</p>
        <a href="{{ route('pilih-akun-mitra') }}" class="btn btn-gradient btn-lg">Daftar Jadi Mitra</a>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5 mb-lg-0">
                <h5>HayuCukur</h5>
                <p class="mt-3">Booking barbershop terdepan untuk gaya rambut modern tanpa ribet.</p>
                <div class="social-icons mt-4">
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5>Navigasi</h5>
                <ul class="list-unstyled mt-3">
                    <li><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li><a href="{{ route('login') }}">Booking</a></li>
                    <li><a href="{{ route('dashboard') }}">Pilihan Barber</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h5>Kontak Kami</h5>
                <ul class="list-unstyled mt-3">
                    <li><i class="bi bi-envelope me-2"></i> hello@hayucukur.com</li>
                    <li><i class="bi bi-telephone me-2"></i> +62 812 3456 7890</li>
                    <li><i class="bi bi-geo-alt me-2"></i> Jl. Teknologi No. 123, Jakarta</li>
                </ul>
            </div>
        </div>
        <hr class="my-5" />
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p>&copy; 2025 HayuCukur. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
