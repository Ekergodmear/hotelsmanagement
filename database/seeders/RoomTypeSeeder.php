<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Phòng Đơn',
                'description' => 'Phòng 1 giường đơn, phù hợp cho 1 người',
                'base_price' => 500000,
                'capacity' => 1,
            ],
            [
                'name' => 'Phòng Đôi',
                'description' => 'Phòng 1 giường đôi, phù hợp cho 2 người',
                'base_price' => 800000,
                'capacity' => 2,
            ],
            [
                'name' => 'Phòng Gia Đình',
                'description' => 'Phòng 2 giường đôi, phù hợp cho gia đình',
                'base_price' => 1200000,
                'capacity' => 4,
            ],
            [
                'name' => 'Phòng Suite',
                'description' => 'Phòng cao cấp với không gian rộng rãi',
                'base_price' => 2000000,
                'capacity' => 2,
            ],
        ];

        foreach ($roomTypes as $type) {
            RoomType::create($type);
        }
    }
}