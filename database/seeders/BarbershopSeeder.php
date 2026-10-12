<?php

namespace Database\Seeders;

use App\Models\Barbershop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BarbershopSeeder extends Seeder
{
    public function run(): void
    {
        Barbershop::whereIn('name', [
            'Hayu Cukur Bandung',
            'Hayu Cukur Cimahi',
            'Hayu Cukur Jakarta',
        ])->delete();

        $imagePath = 'barbershop_images/tukangcukur.png';
        $sourceImage = public_path('images/tukangcukur.png');

        if (is_file($sourceImage) && ! Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->put($imagePath, file_get_contents($sourceImage));
        }

        $publicImage = public_path('storage/' . $imagePath);
        if (is_file($sourceImage) && ! is_file($publicImage)) {
            if (! is_dir(dirname($publicImage))) {
                mkdir(dirname($publicImage), 0755, true);
            }
            copy($sourceImage, $publicImage);
        }

        $barbershops = [
            [
                'owner' => ['name' => 'Mitra Hayu Cukur Bandung', 'email' => 'mitra.bandung@example.com'],
                'name' => 'Rusdi Barber',
                'address' => 'Jl. Buah Batu No. 18',
                'location' => 'Bandung',
                'description' => 'Barbershop nyaman dengan kapster berpengalaman untuk gaya rambut modern.',
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
                'services' => [
                    ['name' => 'Haircut', 'price' => 35000, 'duration' => 30],
                    ['name' => 'Haircut + Wash', 'price' => 50000, 'duration' => 45],
                ],
            ],
            [
                'owner' => ['name' => 'Mitra Hayu Cukur Cimahi', 'email' => 'mitra.cimahi@example.com'],
                'name' => 'Andre',
                'address' => 'Jl. Amir Machmud No. 72',
                'location' => 'Cimahi',
                'description' => 'Tempat cukur praktis untuk kebutuhan harian dan acara spesial.',
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
                'services' => [
                    ['name' => 'Classic Cut', 'price' => 30000, 'duration' => 30],
                    ['name' => 'Beard Trim', 'price' => 25000, 'duration' => 20],
                ],
            ],
            [
                'owner' => ['name' => 'Mitra Hayu Cukur Jakarta', 'email' => 'mitra.jakarta@example.com'],
                'name' => 'Asgar Cukur',
                'address' => 'Jl. Tebet Raya No. 41',
                'location' => 'Jakarta',
                'description' => 'Barbershop bergaya modern dengan layanan lengkap dan booking mudah.',
                'open_time' => '09:00:00',
                'close_time' => '22:00:00',
                'services' => [
                    ['name' => 'Premium Cut', 'price' => 75000, 'duration' => 45],
                    ['name' => 'Haircut + Beard', 'price' => 95000, 'duration' => 60],
                ],
            ],
        ];

        foreach ($barbershops as $data) {
            $owner = User::updateOrCreate(
                ['email' => $data['owner']['email']],
                [
                    'name' => $data['owner']['name'],
                    'password' => Hash::make('password'),
                    'role' => 'mitra',
                ]
            );

            Barbershop::updateOrCreate(
                ['user_id' => $owner->id, 'name' => $data['name']],
                [
                    'address' => $data['address'],
                    'location' => $data['location'],
                    'description' => $data['description'],
                    'open_time' => $data['open_time'],
                    'close_time' => $data['close_time'],
                    'image' => $imagePath,
                    'services' => $data['services'],
                    'average_rating' => 0,
                ]
            );
        }
    }
}
