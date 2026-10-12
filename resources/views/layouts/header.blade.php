<nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="background-color: #1f1f1f;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('beranda') }}">
            <img src="/images/logocukur.png" alt="Logo" style="height: 60px; width: auto;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            {{-- Custom Toggler Icon for dark theme --}}
            <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 30 30%27%3e%3cpath stroke=%27%23f0d067%27 stroke-linecap=%27round%27 stroke-miterlimit=%2710%27 stroke-width=%272%27 d=%27M4 7h22M4 15h22M4 23h22%27/%3e%3c/svg%3e');"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                @guest
                    {{-- Links for GUESTS (Not Logged In) --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pilih-akun-mitra') }}">Jadi Mitra</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pilih-akun-pelanggan') }}" class="btn btn-danger ms-2">Register</a>
                    </li>
                @endguest

                @auth
                    {{-- Links for LOGGED IN USERS --}}

                    @if(Auth::user()->role == 'pelanggan')
                        {{-- Customer-specific Links --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('riwayat-booking') }}"><i class="bi bi-clock-history me-1"></i>Riwayat Booking</a>
                        </li>
                    @endif

                    @if(Auth::user()->role == 'mitra')
                        {{-- Partner-specific Links --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.mitra') }}"><i class="bi bi-speedometer2 me-1"></i>Dashboard Mitra</a>
                        </li>
                    @endif

                    {{-- User Dropdown Menu (for both roles) --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="background-color: #1f1f1f; border-color: #333;">
                            {{-- **THE FIX:** This link now correctly points to the profile page route. --}}
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                            <li><hr class="dropdown-divider" style="border-top-color: #444;"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
