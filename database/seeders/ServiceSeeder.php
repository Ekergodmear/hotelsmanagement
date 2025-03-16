<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Dịch vụ phòng - Dọn phòng',
                'description' => 'Dịch vụ dọn phòng theo yêu cầu ngoài lịch dọn phòng hàng ngày.',
                'price' => 100000,
                'type' => 'room_service',
                'is_active' => true,
            ],
            [
                'name' => 'Dịch vụ phòng - Đồ ăn nhẹ',
                'description' => 'Đồ ăn nhẹ được phục vụ tại phòng.',
                'price' => 150000,
                'type' => 'food',
                'is_active' => true,
            ],
            [
                'name' => 'Dịch vụ phòng - Bữa sáng',
                'description' => 'Bữa sáng được phục vụ tại phòng.',
                'price' => 200000,
                'type' => 'food',
                'is_active' => true,
            ],
            [
                'name' => 'Dịch vụ giặt là',
                'description' => 'Dịch vụ giặt là quần áo.',
                'price' => 50000,
                'type' => 'laundry',
                'is_active' => true,
            ],
            [
                'name' => 'Dịch vụ đưa đón sân bay',
                'description' => 'Dịch vụ đưa đón sân bay bằng xe riêng.',
                'price' => 300000,
                'type' => 'transport',
                'is_active' => true,
            ],
            [
                'name' => 'Dịch vụ spa',
                'description' => 'Dịch vụ spa và massage tại khách sạn.',
                'price' => 500000,
                'type' => 'other',
                'is_active' => true,
            ],
            [
                'name' => 'Thuê xe đạp',
                'description' => 'Dịch vụ cho thuê xe đạp để khám phá thành phố.',
                'price' => 100000,
                'type' => 'transport',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
