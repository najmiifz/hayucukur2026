<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403, 'Anda tidak memiliki akses ke pembayaran ini.');

        // konfigurasi kredential Midtrans dari file Config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // menyiapkan parameter untuk transaksi
        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $booking->id . '-' . time(), // Unique Order ID
                'gross_amount' => $booking->total_price,
            ],
            'item_details' => collect($booking->services ?? [])->map(function (array $service, int $index) {
                return [
                    'id' => 'service-' . $index,
                    'price' => $service['price'],
                    'quantity' => 1,
                    'name' => 'Layanan: ' . $service['name'],
                ];
            })->values()->all(),
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
            ],
        ];

        // Dapatkan Snap Token dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // Kirimkan Snap Token dan detail pemesanan ke tampilan
        return view('payment.checkout', compact('snapToken', 'booking'));
    }

    public function webhook(Request $request)
    {
        // Proses webhook dari Midtrans
        //Set Server Key
        Config::$serverKey = config('midtrans.server_key');

        //Membuat objek Notifikasi
        $notification = new \Midtrans\Notification();

        $order_id = $notification->order_id;
        $status_code = $notification->status_code;
        $gross_amount = $notification->gross_amount;

        //membuat Signature key dari order_id, staus_code, gross_amount
        $signature_key = hash('sha512', $order_id . $status_code . $gross_amount . config('midtrans.server_key'));

        //cek bila signatur key sesuai
        if (! hash_equals($signature_key, $notification->signature_key)) {
            return response()->json(['message' => 'Signature key tidak sesuai'], 403);
        }

        if (!preg_match('/^BOOKING-(\d+)-/', $order_id, $matches)) {
            return response()->json(['message' => 'Format order ID tidak valid'], 422);
        }

        $booking = Booking::find($matches[1]);

        if (!$booking){
            return response()->json(['message'=> 'Booking tidak ditemukan'], 404);
        }

        //meng handle status pembayaran
        $transaction = $notification->transaction_status;
        $fraud = $notification->fraud_status;
        if (($transaction === 'capture' || $transaction === 'settlement') && $fraud !== 'deny') {
            $booking->update(['payment_status' => 'Paid']);
        } elseif (in_array($transaction, ['cancel', 'deny', 'expire'], true)) {
            $booking->update([
                'payment_status' => 'Failed',
                'status' => 'Dibatalkan',
            ]);
        } elseif ($transaction === 'pending') {
            $booking->update(['payment_status' => 'Pending']);
        }

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }
}
