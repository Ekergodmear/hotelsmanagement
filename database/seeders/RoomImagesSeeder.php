<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RoomImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả ảnh phòng hiện tại
        DB::table('room_images')->truncate();

        // Đảm bảo thư mục tồn tại
        $storageDir = storage_path('app/public/rooms');
        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        // Lấy tất cả phòng
        $rooms = Room::all();
        $count = 0;

        foreach ($rooms as $room) {
            // Tạo từ 1-3 ảnh cho mỗi phòng
            $numImages = rand(1, 3);

            for ($i = 0; $i < $numImages; $i++) {
                // Tạo tên file mới
                $newFileName = "room_{$room->id}_{$i}_" . time() . ".txt";
                $destinationPath = "rooms/{$newFileName}";

                // Tạo nội dung file text đơn giản
                $content = "Room {$room->room_number} Image " . ($i+1);

                // Lưu file vào storage
                Storage::disk('public')->put($destinationPath, $content);

                // Tạo bản ghi trong cơ sở dữ liệu
                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $destinationPath,
                    'is_primary' => ($i === 0), // Ảnh đầu tiên là ảnh chính
                    'display_order' => $i + 1,
                ]);

                $count++;
            }
        }

        $this->command->info("Đã tạo {$count} ảnh giả cho {$rooms->count()} phòng.");
        $this->command->info("Lưu ý: Đây chỉ là file text, không phải ảnh thật. Bạn cần tải lên ảnh thật từ giao diện.");
    }
}
