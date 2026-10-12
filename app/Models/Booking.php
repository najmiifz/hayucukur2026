<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'barbershop_id',
        'services',
        'name',
        'booking_time',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'order_id',
        'snap_token',
        'completed_at'
    ];

    protected $casts = [
        'booking_time' => 'datetime',
        'services' => 'array',
        'total_price' => 'decimal:2', // Asumsi total_price adalah nilai desimal
        'status' => 'string', // Asumsi status adalah string
    ];

    public function user()
    {
        return $this->belongsTo(User::class); //relasi ke model User
    }

    public function barbershop()
    {
        return $this->belongsTo(Barbershop::class); //relasi ke model Barbershop
    }
    public function rating()
    {
        return $this->hasOne(Rating::class); //relasi ke model Rating
    }
}
