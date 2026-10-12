<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarbershopController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MitraDashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\MidtransController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


//Routes ~ Home
Route::get('/', [HomeController::class, 'index'])->name('beranda');

// Rute untuk Login/register + memilih akun
Route::middleware('guest')->group(function () {
    Route::get('/pilih-akun-pelanggan', function () {
        $registerLink = route('register', ['role' =>'pelanggan']);
        return view('pilih-akun-pelanggan', compact('registerLink'));
    })->name('pilih-akun-pelanggan');
    Route::get('/pilih-akun-mitra', function() {
        $registerLink = route('register', ['role' => 'mitra']);
        return view('pilih-akun-mitra', compact('registerLink'));
    })->name('pilih-akun-mitra');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register/{role?}', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

//Rute Terotentikasi
Route::middleware('auth')->group(function (){
    Route::post('/logout', function(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('beranda')->with('success', 'Berhasil Logout');
    })->name('logout');

    // Rute untuk Profile
    Route::get('/profile/pelanggan', [ProfileController::class, 'showPelangganProfile'])->name('profile.pelanggan.show');
    Route::get('/profile/mitra', [ProfileController::class, 'showMitraProfile'])->name('profile.mitra.show');

    // Rute untuk mengedit profile
    Route::get('profile/edit', [ProfileController::class, 'showEditForm'])->name('profile.edit');

    // Rute untuk mengupdate profile
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

//Rute Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/dashboard', [BarbershopController::class, 'index'])->name('dashboard');
    Route::get('/barbershop/{barbershop}', [BarbershopController::class, 'show'])->name('barbershop.show');

    // Rute untuk Booking
    Route::get('/booking/create/{barbershop}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Rute untuk riwayat booking dan pembatalan
    Route::get('/riwayat-booking', [BookingController::class, 'riwayat'])->name('riwayat-booking');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Rute untuk memberikan ulasan
    Route::post('/rating', [RatingController::class, 'store'])->name('rating.store');

    Route::post('/booking/{booking}/update-to-cod', [BookingController::class, 'updateToPayOnSite'])->name('booking.pay-on-site');
    Route::get('/payment/{booking}/payment', [PaymentController::class, 'create'])->name('payment.create');
});

//Rute Mitra
Route::middleware(['auth','role:mitra'])->group(function (){ // Added 'auth' for best practice
    Route::get('/dashboard-mitra', [MitraDashboardController::class, 'index'])->name('dashboard.mitra');

    // Rute Manajemen Barbershop
    Route::get('/mitra/barbershop/create', [MitraDashboardController::class, 'create'])->name('mitra.barbershop.create');
    Route::post('/mitra/barbershop', [MitraDashboardController::class,'store'])->name('mitra.barbershop.store');
    Route::get('/mitra/barbershop', [MitraDashboardController::class, 'barbershopIndex'])->name('mitra.barbershops.index');
    Route::get('/mitra/barbershop/{barbershop}/edit', [MitraDashboardController::class, 'edit'])->name('mitra.barbershop.edit');
    Route::put('/mitra/barbershop/{barbershop}', [MitraDashboardController::class, 'update'])->name('mitra.barbershop.update');
    Route::delete('/mitra/barbershop/{barbershop}', [MitraDashboardController::class, 'destroy'])->name('mitra.barbershop.destroy');

    // Rute Manajemen Booking
    Route::get('/mitra/bookings', [MitraDashboardController::class, 'showBookings'])->name('mitra.bookings.index');
    Route::post('/mitra/bookings/{booking}/status', [MitraDashboardController::class, 'updateBookingStatus'])->name('mitra.bookings.status.update');

    // Rute untuk melihat statistik booking secara real-time
    Route::get('/mitra/chart-data', [MitraDashboardController::class, 'getChartData'])->name('mitra.chart.data');
});

//Rute Email
Route::post('/midtrans/notification', [MidtransController::class, 'handleNotification'])->name('midtrans.notification');
