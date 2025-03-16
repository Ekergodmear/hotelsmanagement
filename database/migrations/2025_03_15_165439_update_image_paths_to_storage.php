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
        // Đảm bảo thư mục tồn tại
        if (!Storage::disk('public')->exists('hotels')) {
            Storage::disk('public')->makeDirectory('hotels');
        }

        if (!Storage::disk('public')->exists('rooms')) {
            Storage::disk('public')->makeDirectory('rooms');
        }

        // Cập nhật đường dẫn ảnh khách sạn
        $hotelImages = DB::table('hotel_images')->get();
        foreach ($hotelImages as $image) {
            $oldPath = $image->image_path;

            // Kiểm tra nếu đường dẫn đã ở định dạng storage
            if (strpos($oldPath, 'hotels/') === 0) {
                continue;
            }

            // Xử lý đường dẫn từ public
            $filename = basename($oldPath);
            $newPath = 'hotels/' . $filename;

            // Di chuyển file từ public sang storage nếu tồn tại
            $publicPath = public_path('images/hotel_images/' . $filename);
            if (File::exists($publicPath)) {
                $fileContent = File::get($publicPath);
                Storage::disk('public')->put($newPath, $fileContent);
            }

            // Cập nhật đường dẫn trong cơ sở dữ liệu
            DB::table('hotel_images')
                ->where('id', $image->id)
                ->update(['image_path' => $newPath]);
        }

        // Cập nhật đường dẫn ảnh phòng
        $roomImages = DB::table('room_images')->get();
        foreach ($roomImages as $image) {
            $oldPath = $image->image_path;

            // Kiểm tra nếu đường dẫn đã ở định dạng storage
            if (strpos($oldPath, 'rooms/') === 0) {
                continue;
            }

            // Xử lý đường dẫn từ public
            $filename = basename($oldPath);
            $newPath = 'rooms/' . $filename;

            // Di chuyển file từ public sang storage nếu tồn tại
            $publicPath = public_path('images/room_images/' . $filename);
            if (File::exists($publicPath)) {
                $fileContent = File::get($publicPath);
                Storage::disk('public')->put($newPath, $fileContent);
            }

            // Cập nhật đường dẫn trong cơ sở dữ liệu
            DB::table('room_images')
                ->where('id', $image->id)
                ->update(['image_path' => $newPath]);
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
