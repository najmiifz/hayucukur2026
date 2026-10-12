@extends('layouts.mitra')

@section('title', 'Edit Detail Barbershop')

@section('content')
<div class="card text-white" style="background-color: #1f1f1f; border-color: #444;">
    <div class="card-header" style="border-bottom: 1px solid #444;">
        <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Detail Barbershop</h4>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('mitra.barbershop.update', $barbershop->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="text-center my-3">
                <p class="mb-2 text-white-50">Foto Saat Ini:</p>
                <img src="{{ asset('storage/' . $barbershop->image) }}" alt="Foto Barbershop" class="img-fluid rounded shadow-sm" style="max-width: 350px; border: 3px solid #444;">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Ubah Foto Barbershop (Opsional)</label>
                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Barbershop</label>
                <input type="text" id="nama" name="name" class="form-control" value="{{ $barbershop->name }}" required>
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
                @foreach($barbershop->services as $service)
                <div class="row g-3 mb-2 align-items-center">
                    <div class="col-md-5"><input type="text" name="service_name[]" class="form-control" value="{{ $service['name'] }}" required></div>
                    <div class="col-md-3"><input type="number" name="service_price[]" class="form-control" value="{{ $service['price'] }}" required></div>
                    <div class="col-md-3"><input type="number" name="service_duration[]" class="form-control" value="{{ $service['duration'] }}" required></div>
                    <div class="col-md-1"><button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()">X</button></div>
                </div>
                @endforeach
            </div>
            <button type="button" id="add-service-btn" class="btn btn-outline-light mt-2">Tambah Layanan</button>
            <hr style="border-color: #444;">

            <button type="submit" class="btn btn-gold w-100 mt-3">
                <i class="bi bi-save me-2"></i> Simpan Perubahan
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
@endsection
