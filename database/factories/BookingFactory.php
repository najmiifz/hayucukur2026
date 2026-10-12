<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['Selesai', 'Selesai', 'Selesai', 'Dibatalkan', 'Dibatalkan' ]);
        $paymentMethod = fake()->randomElement(['Online', 'Bayar di Tempat']);
        $customer = User::where('role', 'pelanggan')->inRandomOrder()->first();

        return [
            'booking_time' => fake()->dateTimeBetween('-1 year', 'now'),
            'total_price' => fake()->numberBetween(25000, 150000),
            'status' => $status,
            'payment_status' => $status == 'Selesai' ? 'Success' : 'Pending',
            'payment_method' => $paymentMethod,
            'name' => $customer ? $customer->name : fake()->name(),
        ];
    }
}
