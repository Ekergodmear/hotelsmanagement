<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class DownloadRoomImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:download-room';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tải ảnh phòng từ Unsplash về máy chủ và tạo bản ghi trong cơ sở dữ liệu';

    /**
     * Danh sách URL ảnh phòng theo loại phòng
     */
    protected $roomTypeImages = [
        // Phòng Tiêu Chuẩn
        5 => [
            'https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1600&auto=format&fit=crop',
        ],
        // Phòng Deluxe
        6 => [
            'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1445991842772-097fea258e7b?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?q=80&w=1600&auto=format&fit=crop',
        ],
        // Phòng Suite
        7 => [
            'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1631049552057-ce19745ff4af?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1631049035182-249067d7618e?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1629140727571-9b5c6f6267b4?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1591088398332-8a7791972843?q=80&w=1600&auto=format&fit=crop',
        ],
        // Phòng Gia Đình
        8 => [
            'https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1598928636135-d146006ff4be?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?q=80&w=1600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=1600&auto=format&fit=crop',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu tải ảnh phòng...');

        // Lấy tất cả phòng
        $rooms = DB::table('rooms')
            ->select('id', 'hotel_id', 'room_type_id')
            ->get();

        foreach ($rooms as $room) {
            $roomId = $room->id;
            $hotelId = $room->hotel_id;
            $roomTypeId = $room->room_type_id;

            // Tạo thư mục cho phòng nếu chưa tồn tại
            $roomDir = public_path("images/room_images/{$roomId}");
            if (!File::exists($roomDir)) {
                File::makeDirectory($roomDir, 0755, true);
            }

            // Lấy danh sách ảnh theo loại phòng
            $imageUrls = $this->roomTypeImages[$roomTypeId] ?? [];

            if (empty($imageUrls)) {
                $this->warn("Không có ảnh cho loại phòng ID: {$roomTypeId}");
                continue;
            }

            $this->info("Đang tải " . count($imageUrls) . " ảnh cho phòng ID: {$roomId} (Khách sạn ID: {$hotelId}, Loại phòng ID: {$roomTypeId})");

            foreach ($imageUrls as $index => $imageUrl) {
                $extension = 'jpg'; // Mặc định là jpg

                // Tạo tên file mới
                $newFileName = "room_{$roomId}_image_" . ($index + 1) . ".{$extension}";
                $localPath = "images/room_images/{$roomId}/{$newFileName}";
                $fullPath = public_path($localPath);

                try {
                    // Tải ảnh từ URL
                    $response = Http::get($imageUrl);

                    if ($response->successful()) {
                        // Lưu ảnh vào thư mục
                        File::put($fullPath, $response->body());

                        // Tạo bản ghi trong bảng room_images (nếu bảng này tồn tại)
                        // Nếu bảng chưa tồn tại, bạn cần tạo migration và model trước
                        try {
                            DB::table('room_images')->insert([
                                'room_id' => $roomId,
                                'image_path' => "/{$localPath}",
                                'is_primary' => $index === 0,
                                'display_order' => $index + 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } catch (\Exception $e) {
                            $this->warn("Không thể tạo bản ghi trong cơ sở dữ liệu: {$e->getMessage()}");
                            $this->warn("Ảnh đã được tải về nhưng chưa được lưu vào cơ sở dữ liệu.");
                        }

                        $this->info("Đã tải ảnh: {$newFileName}");
                    } else {
                        $this->error("Không thể tải ảnh từ URL: {$imageUrl}");
                    }
                } catch (\Exception $e) {
                    $this->error("Lỗi khi tải ảnh: {$e->getMessage()}");
                }
            }
        }

        $this->info('Hoàn thành tải ảnh phòng!');
    }
}
