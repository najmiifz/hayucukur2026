<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barbershop>
 */
class BarbershopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $servicesList = [
            ['name' => 'Cukur Dewasa', 'price' => 25000, 'duration' => 30],
            ['name' => 'Cukur Anak', 'price' => 18000, 'duration' => 25],
            ['name' => 'Creambath', 'price' => 50000, 'duration' => 60],
            ['name' => 'Shaving', 'price' => 18000, 'duration' => 15],
            ['name' => 'Hair Coloring', 'price' => 30000, 'duration' => 12],
        ];
        return [
            'name' => fake()->company() . ' Barbershop',
            'address' => fake()->streetaddress(),
            'location' => fake()->city(),
            'description' => fake()->paragraph(2),
            'phone_number' => fake()->e164PhoneNumber(),
            'open_time' => fake()->time(),
            'close_time' => fake()->time(),
            'image' => 'barbershop_image/default.jpg',
            'services' => fake()->randomElements($servicesList, rand(2,4)),
        ];
    }
}
