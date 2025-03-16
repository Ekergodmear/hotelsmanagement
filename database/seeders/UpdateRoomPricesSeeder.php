<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateRoomPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();
        $count = 0;

        foreach ($rooms as $room) {
            // Lấy giá cơ bản của loại phòng
            $basePrice = $room->roomType->base_price;

            // Tạo giá ngẫu nhiên cho phòng, dao động từ 80% đến 120% giá cơ bản
            $minPrice = $basePrice * 0.8;
            $maxPrice = $basePrice * 1.2;

            // Làm tròn giá đến 10,000 VNĐ
            $randomPrice = round(rand($minPrice, $maxPrice) / 10000) * 10000;

            $room->price = $randomPrice;
            $room->save();

            $count++;
        }

        $this->command->info("Đã cập nhật giá cho {$count} phòng.");
    }
}
