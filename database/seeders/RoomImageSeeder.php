<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RoomImageSeeder extends Seeder
{
    public function run(): void
    {
        // Đảm bảo thư mục tồn tại
        $storageDir = storage_path('app/public/rooms');
        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $rooms = Room::all();
        $sourceImages = [
            storage_path('app/public/rooms/room_16_image_1.jpg'),
            storage_path('app/public/rooms/room_16_image_2.jpg'),
            storage_path('app/public/rooms/room_16_image_3.jpg'),
            storage_path('app/public/rooms/room_16_image_4.jpg'),
            storage_path('app/public/rooms/room_16_image_5.jpg'),
        ];

        foreach ($rooms as $room) {
            // Tạo 5 ảnh cho mỗi phòng
            for ($i = 1; $i <= 5; $i++) {
                // Bỏ qua nếu là room_18_image_2.jpg và room_22_image_2.jpg vì không tồn tại
                if (($room->id == 18 || $room->id == 22 || $room->id == 26 || $room->id == 30) && $i == 2) {
                    continue;
                }

                // Lấy ảnh mẫu từ thư mục nguồn
                $sourceIndex = ($i - 1) % count($sourceImages);
                $sourcePath = $sourceImages[$sourceIndex];
                $destinationPath = "rooms/room_{$room->id}_image_{$i}.jpg";

                // Copy ảnh từ nguồn vào storage
                if (File::exists($sourcePath)) {
                    File::copy($sourcePath, storage_path("app/public/{$destinationPath}"));
                }

                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $destinationPath,
                    'is_primary' => $i === 1,
                    'display_order' => $i
                ]);
            }
        }
    }
}
