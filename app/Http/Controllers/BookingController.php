<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Barbershop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function create(Barbershop $barbershop)
    {
        return view('booking.create', compact ('barbershop'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'barbershop_id' => 'required|exists:barbershops,id',
            'services' => 'required|array|min:1',
            'services.*' => 'string',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
        ]);

        $barbershop = Barbershop::findOrFail($validatedData['barbershop_id']);
        $availableServices = collect($barbershop->services ?? [])->keyBy('name');
        $requestedServices = collect($validatedData['services']);
        if ($requestedServices->diff($availableServices->keys())->isNotEmpty()) {
            return back()->withInput()->withErrors([
                'services' => 'Salah satu layanan yang dipilih sudah tidak tersedia.',
            ]);
        }
        $selectedServices = $requestedServices->map(fn (string $serviceName) => $availableServices->get($serviceName))
            ->values()
            ->all();
        $totalPrice = collect($selectedServices)->sum(fn (array $service) => (float) ($service['price'] ?? 0));
        $bookingDateTime = Carbon::parse($validatedData['booking_date']. ' ' . $validatedData['booking_time']);

        $openTime = Carbon::parse($validatedData['booking_date'] . ' ' . $barbershop->open_time);
        $closeTime = Carbon::parse($validatedData['booking_date'] . ' ' . $barbershop->close_time);
        if (!$bookingDateTime->between($openTime, $closeTime)) {
            return back()->withInput()->withErrors([
                'booking_time' => 'Jam booking berada di luar jam operasional barbershop.',
            ]);
        }

        $alreadyBooked = Booking::where('barbershop_id', $barbershop->id)
            ->where('booking_time', $bookingDateTime)
            ->whereNotIn('status', ['Dibatalkan'])
            ->exists();
        if ($alreadyBooked) {
            return back()->withInput()->withErrors([
                'booking_time' => 'Jadwal tersebut sudah dipesan. Silakan pilih waktu lain.',
            ]);
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'barbershop_id' => $validatedData['barbershop_id'],
            'services' => $selectedServices,
            'name' => Auth::user()->name, // Mengambil nama pengguna yang sedang login
            'booking_time' => $bookingDateTime,
            'total_price' => $totalPrice,
            'status' => 'Menunggu',
            'payment_status' => 'Pending',
            'payment_method' => 'Online', // Default payment method
        ]);
        //generate Midtrans Snap Token
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params =[
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $booking->id . '-' . time(),
                'gross_amount' => $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->name,
                'email' => Auth::user()->email,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);
        $booking->update([
            'order_id' => $params['transaction_details']['order_id'],
            'snap_token' => $snapToken,
        ]);

        return view('booking.payment', compact('booking'));
    }
    public function showPayment(Request $request)
    {
        $details = $request->session()->get('booking_details');
        // Cek apakah detail booking ada di session
        if(!$details){
            return redirect()->route('dashboard');
        }
        $barbershop = Barbershop::findOrFail($details['barbershop_id']);

        // Konfigurasi kredensial Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Menyiapkan parameter untuk transaksi
        $params = [
            'transaction_details' => [
                'order_id' => 'BOOK-' . time(), // A unique order ID
                'gross_amount' => $details['total_price'],
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];
        //Dapatkan Snap Token dari Midtrans
        $snapToken = Snap::getSnapToken($params);
        return view('booking.payment', compact('details', 'barbershop', 'snapToken'));
    }
    public function confirm(Request $request)
    //digunakan untuk pembayaran ditempat
    {
        $details = $request->session()->get('booking_details');

        if (!$details) {
            return redirect()->route('dashboard')->with('error', 'Sesi booking Anda telah berakhir.');
        }
        $bookingDateTime = Carbon::parse($details['booking_date'] . ' ' . $details['booking_time']);
        Booking::create([
            'user_id' => Auth::id(),
            'barbershop_id' => $details['barbershop_id'],
            'name' => Auth::user()->name, // Mengambil nama pengguna yang sedang login
            'booking_time' => $bookingDateTime,
            'total_price' => $details['total_price'],
            'status' => 'Menunggu',
            'payment_status' => 'Pending',
            'payment_method' => 'Bayar Ditempat', // Metode pembayaran ditempat
        ]);

        $request->session()->forget('booking_details'); // Hapus detail booking dari session setelah konfirmasi
        return redirect()->route('riwayat-booking')->with('success', 'Booking berhasil dibuat, Booking Berhasil dibuat, silahkan datang ke barbershop pada waktu yang telah ditentukan.');
    }

    public function riwayat(){
        $bookings = Booking::where('user_id', Auth::id())
                            ->with('barbershop') // Mengambil relasi barbershop untuk informasi lebih lengkap
                            ->latest()
                            ->get();

        return view('riwayat-booking', ['bookings' => $bookings]);
    }

    public function cancel(Booking $booking)
    {
        // Cek apakah booking yang ingin dibatalkan milik pengguna yang sedang login
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk membatalkan booking ini.');
        }
        // Menolak pembatalan jika status booking bukan 'Menunggu'
        if($booking->status !== 'Menunggu'){
            return back()->with('error', 'tidak bisa dibatalkan karena status booking sudah ');
        }
        //update status booking menjadi 'Dibatalkan'
        $booking->update(['status'=>'Dibatalkan']);
        return back()->with('success', 'Booking berhasil dibatalkan.');


    }
    public function updateToPayOnSite(Booking $booking)
        {
            if(Auth::id() !== $booking->user_id){
                abort(403, 'Anda tidak memiliki izin untuk mengubah metode pembayaran booking ini.');
            }
            $booking->update([
                'payment_method' => 'Bayar Ditempat', // Update metode pembayaran menjadi Bayar Ditempat
            ]);

            return redirect()->route('riwayat-booking')->with('success', 'Metode pembayaran berhasil diubah menjadi Bayar Ditempat.');
        }
}
