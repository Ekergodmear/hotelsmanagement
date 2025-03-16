<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chuyển ảnh khách sạn từ public/images/hotel_images sang storage/app/public/hotels
        $hotelImages = DB::table('hotel_images')->get();
        foreach ($hotelImages as $image) {
            $oldPath = $image->image_path;

            // Bỏ qua nếu đường dẫn đã ở định dạng storage
            if (strpos($oldPath, 'hotels/') === 0) {
                continue;
            }

            // Xử lý đường dẫn cũ
            $parts = explode('/', $oldPath);
            if (count($parts) >= 2) {
                $hotelId = $parts[0]; // Thư mục con (ID khách sạn)
                $filename = $parts[1]; // Tên file

                // Đường dẫn mới trong storage
                $newPath = 'hotels/' . $filename;

                // Đường dẫn đầy đủ đến file trong public
                $publicPath = public_path('images/hotel_images/' . $hotelId . '/' . $filename);

                // Kiểm tra file tồn tại
                if (File::exists($publicPath)) {
                    // Đọc nội dung file
                    $fileContent = File::get($publicPath);

                    // Lưu vào storage
                    Storage::disk('public')->put($newPath, $fileContent);

                    // Cập nhật đường dẫn trong database
                    DB::table('hotel_images')
                        ->where('id', $image->id)
                        ->update(['image_path' => $newPath]);
                }
            }
        }

        // Chuyển ảnh phòng từ public/images/room_images sang storage/app/public/rooms
        $roomImages = DB::table('room_images')->get();
        foreach ($roomImages as $image) {
            $oldPath = $image->image_path;

            // Bỏ qua nếu đường dẫn đã ở định dạng storage
            if (strpos($oldPath, 'rooms/') === 0) {
                continue;
            }

            // Lấy tên file từ đường dẫn cũ
            $filename = basename($oldPath);
            $newPath = 'rooms/' . $filename;

            // Đường dẫn đầy đủ đến file trong public
            $publicPath = public_path('images/room_images/' . $filename);

            // Kiểm tra file tồn tại
            if (File::exists($publicPath)) {
                // Đọc nội dung file
                $fileContent = File::get($publicPath);

                // Lưu vào storage
                Storage::disk('public')->put($newPath, $fileContent);

                // Cập nhật đường dẫn trong database
                DB::table('room_images')
                    ->where('id', $image->id)
                    ->update(['image_path' => $newPath]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần thực hiện gì trong down vì chúng ta không muốn mất dữ liệu
    }
};
