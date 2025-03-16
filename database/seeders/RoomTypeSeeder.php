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
                'name' => 'Phòng Tiêu Chuẩn',
                'description' => 'Phòng tiêu chuẩn thoải mái với đầy đủ tiện nghi cơ bản.',
                'base_price' => 800000,
                'capacity' => 2,
            ],
            [
                'name' => 'Phòng Deluxe',
                'description' => 'Phòng rộng rãi với tầm nhìn đẹp và tiện nghi cao cấp.',
                'base_price' => 1200000,
                'capacity' => 2,
            ],
            [
                'name' => 'Phòng Suite',
                'description' => 'Phòng suite sang trọng với phòng khách riêng biệt và không gian rộng rãi.',
                'base_price' => 2000000,
                'capacity' => 3,
            ],
            [
                'name' => 'Phòng Gia Đình',
                'description' => 'Phòng rộng rãi phù hợp cho gia đình với nhiều giường và tiện nghi đầy đủ.',
                'base_price' => 1500000,
                'capacity' => 4,
            ],
        ];

        foreach ($roomTypes as $type) {
            RoomType::create($type);
        }
    }
}
