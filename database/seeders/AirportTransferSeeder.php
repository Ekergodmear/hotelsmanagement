<?php

namespace Database\Seeders;

use App\Models\AirportTransfer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transfers = [
            [
                'name' => 'Xe Sedan tiêu chuẩn',
                'vehicle_type' => 'Sedan',
                'max_passengers' => 3,
                'max_luggage' => 2,
                'price' => 250000,
                'description' => 'Xe sedan 4 chỗ tiêu chuẩn, phù hợp cho 1-3 người với hành lý vừa phải.',
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Xe SUV cao cấp',
                'vehicle_type' => 'SUV',
                'max_passengers' => 4,
                'max_luggage' => 4,
                'price' => 350000,
                'description' => 'Xe SUV rộng rãi, phù hợp cho gia đình hoặc nhóm nhỏ với nhiều hành lý.',
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Xe Minivan 7 chỗ',
                'vehicle_type' => 'Minivan',
                'max_passengers' => 7,
                'max_luggage' => 7,
                'price' => 450000,
                'description' => 'Xe Minivan rộng rãi, phù hợp cho nhóm lớn hoặc gia đình đông người.',
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Xe Limousine VIP',
                'vehicle_type' => 'Limousine',
                'max_passengers' => 3,
                'max_luggage' => 3,
                'price' => 650000,
                'description' => 'Xe Limousine sang trọng với dịch vụ VIP, đồ uống miễn phí và WiFi.',
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Xe Bus 16 chỗ',
                'vehicle_type' => 'Bus',
                'max_passengers' => 16,
                'max_luggage' => 16,
                'price' => 850000,
                'description' => 'Xe Bus lớn phù hợp cho đoàn khách hoặc nhóm lớn với nhiều hành lý.',
                'is_popular' => false,
                'is_active' => true,
            ],
        ];

        foreach ($transfers as $transfer) {
            AirportTransfer::create($transfer);
        }
    }
}
