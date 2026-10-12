@extends('layouts.mitra')

@section('title', 'Kelola Barbershop')

@section('content')
<div class="card text-white" style="background-color: #1f1f1f; border-color: #444;">
    <div class="card-header" style="border-bottom: 1px solid #444;">
        <h4 class="mb-0"><i class="bi bi-scissors me-2"></i>Tambah Barbershop Baru</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('mitra.barbershop.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Barbershop</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Kota</label>
                <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required>
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="open_time" class="form-label">Jam Buka</label>
                    <input type="time" id="open_time" name="open_time" class="form-control @error('open_time') is-invalid @enderror" value="{{ old('open_time') }}" required>
                    @error('open_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="close_time" class="form-label">Jam Tutup</label>
                    <input type="time" id="close_time" name="close_time" class="form-control @error('close_time') is-invalid @enderror" value="{{ old('close_time') }}" required>
                    @error('close_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Foto Barbershop</label>
                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" required>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Singkat</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Nomor WhatsApp (Contoh: 6281234567890)</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $barbershop->phone_number ?? '') }}">
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr style="border-color: #444;">
            <h5>Layanan</h5>
            <div id="services-container">
                {{-- JavaScript will add services here --}}
            </div>
            <button type="button" id="add-service-btn" class="btn btn-outline-light mt-2">Tambah Layanan</button>
            @error('service_name.*')
                <div class="text-danger mt-2"><small>{{ $message }}</small></div>
            @enderror
             @error('service_price.*')
                <div class="text-danger mt-2"><small>{{ $message }}</small></div>
            @enderror
            <hr style="border-color: #444;">

            <button type="submit" class="btn btn-gold w-100 mt-3">
            <i class="bi bi-check-circle-fill me-2"></i> Simpan Barbershop
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add-service-btn').addEventListener('click', function() {
        const container = document.getElementById('services-container');
        const serviceDiv = document.createElement('div');
        serviceDiv.classList.add('row', 'g-3', 'mb-2', 'align-items-center');
        serviceDiv.innerHTML = `
            <div class="col-md-5"><input type="text" name="service_name[]" class="form-control" placeholder="Nama Layanan" required></div>
            <div class="col-md-3"><input type="number" name="service_price[]" class="form-control" placeholder="Harga (Rp)" required></div>
            <div class="col-md-3"><input type="number" name="service_duration[]" class="form-control" placeholder="Durasi (menit)" required></div>
            <div class="col-md-1"><button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()">X</button></div>
        `;
        container.appendChild(serviceDiv);
    });
});
</script>
@push('styles')
    <style>
        /* This targets the placeholder text specifically */
        .form-control::placeholder {
            color: #6c757d; /* A muted, light gray color */
            opacity: 1;    /* Ensures the color is not faded */
        }
    </style>
@endpush
@endsection

