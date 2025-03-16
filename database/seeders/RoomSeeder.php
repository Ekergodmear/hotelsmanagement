<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo đã có dữ liệu loại phòng
        $roomTypes = RoomType::all();
        $hotel = Hotel::first(); // Hoặc chọn hotel cụ thể

        if ($roomTypes->isEmpty()) {
            $this->call(RoomTypeSeeder::class);
            $roomTypes = RoomType::all();
        }

        // Tạo phòng tiêu chuẩn
        $standardRoomType = $roomTypes->where('name', 'Phòng Tiêu Chuẩn')->first();
        for ($i = 1; $i <= 5; $i++) {
            Room::create([
                'room_number' => '10' . $i,
                'room_type_id' => $standardRoomType->id,
                'hotel_id' => $hotel->id,
                'status' => 'available',
                'notes' => 'Phòng tiêu chuẩn tầng 1',
            ]);
        }

        // Tạo phòng deluxe
        $deluxeRoomType = $roomTypes->where('name', 'Phòng Deluxe')->first();
        for ($i = 1; $i <= 5; $i++) {
            Room::create([
                'room_number' => '20' . $i,
                'room_type_id' => $deluxeRoomType->id,
                'hotel_id' => $hotel->id,
                'status' => 'available',
                'notes' => 'Phòng deluxe tầng 2',
            ]);
        }

        // Tạo phòng suite
        $suiteRoomType = $roomTypes->where('name', 'Phòng Suite')->first();
        for ($i = 1; $i <= 3; $i++) {
            Room::create([
                'room_number' => '30' . $i,
                'room_type_id' => $suiteRoomType->id,
                'hotel_id' => $hotel->id,
                'status' => 'available',
                'notes' => 'Phòng suite tầng 3',
            ]);
        }

        // Tạo phòng gia đình
        $familyRoomType = $roomTypes->where('name', 'Phòng Gia Đình')->first();
        for ($i = 1; $i <= 3; $i++) {
            Room::create([
                'room_number' => '40' . $i,
                'room_type_id' => $familyRoomType->id,
                'hotel_id' => $hotel->id,
                'status' => 'available',
                'notes' => 'Phòng gia đình tầng 4',
            ]);
        }
    }
}
