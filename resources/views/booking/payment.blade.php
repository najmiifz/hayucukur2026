@extends('layouts.pelanggan')

@section('title', 'Konfirmasi & Pembayaran')

@section('content')
    <div class="step-progress mb-5 text-center">
        <div class="d-inline-block mx-3">1. Pilih Layanan & Jadwal</div>
        <div class="d-inline-block mx-3 active"><strong>2. Konfirmasi & Bayar</strong></div>
        <div class="d-inline-block mx-3">3. Booking Berhasil</div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">
            <div class="card p-4">
                <h4 class="fw-bold text-center">Konfirmasi Booking Anda</h4>
                <hr>
                <div class="d-flex align-items-center gap-3 mb-3">
                    {{-- FIX: Use the relationship to get barbershop details --}}
                    <img src="{{ asset('storage/' . $booking->barbershop->image) }}" class="rounded" alt="{{ $booking->barbershop->name }}" style="width: 100px; height: 100px; object-fit: cover;">
                    <div>
                        <h6 class="mb-0">{{ $booking->barbershop->name }}</h6>
                        <small class="text-white-50">{{ $booking->barbershop->address }}</small>
                        @if ($booking->barbershop->phone_number)
                            <small class="text-white-50"><i class="bi bi-whatsapp"></i> {{ $booking->barbershop->phone_number }}</small>
                        @endif
                    </div>
                </div>
                {{-- FIX: Get all info from the $booking object --}}
                <p class="mb-1"><strong>Nama Pemesan:</strong> {{ $booking->name }}</p>
                {{-- Note: For services, a better long-term solution is to save them as JSON on the booking record. This is a simplified placeholder. --}}
                <p class="mb-1"><strong>Layanan:</strong> Pemesanan Jasa Barbershop</p>
                <p class="mb-1"><strong>Jadwal:</strong> {{ \Carbon\Carbon::parse($booking->booking_time)->format('d F Y, H:i') }} WIB</p>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Total Harga:</h5>
                    <h5 class="price mb-0 fw-bold">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</h5>
                </div>
                <hr>

                {{-- Payment Method Selection --}}
                <div class="mt-3">
                    <h5 class="mb-3">Pilih Metode Pembayaran</h5>
                    <div class="d-grid gap-3">
                        <button id="pay-button" class="btn btn-gold p-3 fw-bold">
                            <i class="bi bi-credit-card me-2"></i>Bayar Sekarang (Online)
                        </button>

                        <form action="{{ route('booking.pay-on-site', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100 p-3 fw-bold">
                            <i class="bi bi-shop me-2"></i>Bayar di Tempat (Pay on Site)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Midtrans Snap.js Script and payment trigger logic --}}
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function () {
        // FIX: Use snap_token from the booking object
        snap.pay('{{ $booking->snap_token }}', {
            onSuccess: function(result){
                alert("Pembayaran berhasil!");
                window.location.href = "{{ route('riwayat-booking') }}";
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda!");
                window.location.href = "{{ route('riwayat-booking') }}";
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            },
        });
    });
</script>
@endpush
