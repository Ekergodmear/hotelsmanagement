<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = [
            [
                'name' => 'Vinpearl Resort & Spa Đà Nẵng',
                'address' => '23 Trường Sa, Hoà Hải',
                'city' => 'Đà Nẵng',
                'description' => 'Vinpearl Resort & Spa Da Nang là khu nghỉ dưỡng 5 sao với kiến trúc hiện đại, tọa lạc bên bờ biển Non Nước xinh đẹp.',
                'rating' => 9.2,
                'price_per_night' => 2500000,
                'amenities' => ['wifi', 'pool', 'restaurant', 'spa', 'gym', 'parking'],
            ],
            [
                'name' => 'Rex Hotel Saigon',
                'address' => '141 Nguyễn Huệ, Bến Nghé',
                'city' => 'Hồ Chí Minh',
                'description' => 'Rex Hotel Saigon là khách sạn 5 sao nằm ở trung tâm Thành phố Hồ Chí Minh, gần Nhà hát Thành phố và Nhà thờ Đức Bà.',
                'rating' => 8.8,
                'price_per_night' => 1800000,
                'amenities' => ['wifi', 'pool', 'restaurant', 'gym', 'parking'],
            ],
            [
                'name' => 'Metropole Hanoi',
                'address' => '15 Ngô Quyền, Hoàn Kiếm',
                'city' => 'Hà Nội',
                'description' => 'Sofitel Legend Metropole Hanoi là khách sạn 5 sao mang đậm phong cách Pháp, được xây dựng từ năm 1901.',
                'rating' => 9.5,
                'price_per_night' => 3500000,
                'amenities' => ['wifi', 'pool', 'restaurant', 'spa', 'gym', 'parking'],
            ],
            [
                'name' => 'Mường Thanh Luxury Nha Trang',
                'address' => '60 Trần Phú, Lộc Thọ',
                'city' => 'Nha Trang',
                'description' => 'Mường Thanh Luxury Nha Trang là khách sạn 5 sao với tầm nhìn tuyệt đẹp ra biển Nha Trang.',
                'rating' => 8.5,
                'price_per_night' => 1500000,
                'amenities' => ['wifi', 'pool', 'restaurant', 'gym'],
            ],
            [
                'name' => 'Ana Mandara Dalat',
                'address' => '1 Lê Lai, Phường 3',
                'city' => 'Đà Lạt',
                'description' => 'Ana Mandara Villas Dalat Resort & Spa là khu nghỉ dưỡng độc đáo với 17 biệt thự kiểu Pháp được xây dựng từ những năm 1920.',
                'rating' => 9.0,
                'price_per_night' => 2200000,
                'amenities' => ['wifi', 'restaurant', 'spa', 'parking'],
            ],
        ];

        foreach ($hotels as $hotel) {
            Hotel::create($hotel);
        }
    }
}
