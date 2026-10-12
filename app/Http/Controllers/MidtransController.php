<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Mail\NewBookingNotification;
use Illuminate\Support\Facades\Mail;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        // Set Midtrans Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        // Buat notifkasi baru dari data POST Midtrans
        $notification = new \Midtrans\Notification();

        // Menetapkan variabel untuk akses yang lebih mudah
        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        // mencari booking berdasarkan order_id
        $bookingId = explode('-', $orderId)[0];
        $booking = Booking::find($bookingId);

        if ($booking) {
            // --- Handle different payment statuses ---

            // 1. jika pembayaran berhasil dan aman
            if (($transactionStatus == 'capture' && $fraudStatus == 'accept') || $transactionStatus == 'settlement') {
                // Update status booking untuk menunjukkan bahwa pembayaran berhasil dan sedang diproses
                $booking->payment_status = 'Success';
                $booking->status = 'Diproses';
                $booking->save();

                // kirim notifikasi email ke pemilik barbershop
                Mail::to($booking->barbershop->user->email)->send(new NewBookingNotification($booking));

            // 2. jika pembayaran tertunda
            } else if ($transactionStatus == 'pending') {
                $booking->payment_status = 'Pending';
                $booking->save();

            // 3. jika pembayaran ditolak, dibatalkan, atau kedaluwarsa
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $booking->payment_status = 'Failed';
                $booking->status = 'Dibatalkan';
                $booking->save();
            }

            // tampilkan pesan sukses
            return response()->json(['status' => 'success', 'message' => 'Notification processed.']);
        }

        // jika booking tidak ditemukan, tampilkan pesan error ke midtrans
        return response()->json(['status' => 'error', 'message' => 'Booking not found.'], 404);
    }
}
