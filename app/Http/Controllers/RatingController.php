<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Barbershop;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id|unique:ratings,booking_id',
            'barbershop_id' => 'required|exists:barbershops,id',
            'rating' => 'required|integer|min:1|max:5', // Validasi rating antara 1 sampai 5
            'comment' => 'nullable|string|max:500',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        if ($booking->user_id !== Auth::id()
            || $booking->barbershop_id !== (int) $request->barbershop_id
            || $booking->status !== 'Selesai') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk memberikan rating pada booking ini.');
        }
        Rating::create([
            'user_id'=> Auth::id(),
            'barbershop_id' => $request->barbershop_id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        //menemukan barbershop yang sudah di-rating
        $barbershop = Barbershop::find($request->barbershop_id);

        if($barbershop) {
            //hitung rata-rata rating baru
            $newAverage = $barbershop->ratings()->avg('rating');

            //Update Rata-rata rating barbershop
            $barbershop->update([
                'average_rating' => $newAverage
            ]);
        }

        return back()->with('success', 'Terima kasih telah memberikan rating pada barbershop ini.');
    }
}
