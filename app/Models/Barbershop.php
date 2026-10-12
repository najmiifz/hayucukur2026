<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbershop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Foreign key untuk user yang memiliki barbershop
        'name', // Nama barbershop
        'address', // Alamat barbershop
        'location', // Lokasi barbershop
        'description', // Deskripsi barbershop, optional
        'open_time', // Jam buka barbershop
        'close_time', // Jam tutup barbershop
        'image', // URL gambar barbershop, optional
        'services', // Layanan yang ditawarkan, disimpan sebagai JSON
        'average_rating', // Rata-rata rating barbershop
        'phone_number', // Nomor telepon barbershop
    ];
    protected $casts = [
        'services' => 'array', // merubah json dari Database ke array PHP
    ];

    public function user() {
        return $this->belongsTo(User::class); // Relasi ke model User
    }
    public function ratings(){
        return $this->hasMany(Rating::class); // Relasi ke model Rating
    }
    public function averageRating()
{
    return $this->ratings()->avg('rating'); // Menghitung rata-rata rating dari barbershop
}

}
