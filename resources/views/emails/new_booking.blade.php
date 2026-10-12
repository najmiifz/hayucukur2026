{{-- In resources/views/emails/new-booking.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Booking Baru</title>
</head>
<body>
    <h2>Halo, {{ $booking->barbershop->user->name }}!</h2>
    <p>Anda telah menerima booking baru di barbershop Anda, <strong>{{ $booking->barbershop->name }}</strong>.</p>
    <hr>
    <h3>Detail Booking:</h3>
    <ul>
        <li><strong>Nama Pelanggan:</strong> {{ $booking->user->name }}</li>
        <li><strong>Nomor Telepon:</strong> {{ $booking->user->phone_number ?? 'Tidak ada' }}</li>
        <li><strong>Waktu Booking:</strong> {{ $booking->booking_time->format('d F Y, H:i') }}</li>
        <li><strong>Total Harga:</strong> Rp{{ number_format($booking->total_price, 0, ',', '.') }}</li>
    </ul>
    <hr>
    <p>Silakan periksa dashboard mitra Anda untuk detail lebih lanjut dan untuk mengelola booking ini.</p>
    <p>Terima kasih!</p>
</body>
</html>
