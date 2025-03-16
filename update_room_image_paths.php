<?php

// Đường dẫn đến file autoload.php của Laravel
require __DIR__ . '/vendor/autoload.php';

// Tạo ứng dụng Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Sử dụng DB facade của Laravel
use Illuminate\Support\Facades\DB;

// Cập nhật đường dẫn ảnh phòng
$roomImages = DB::table('room_images')->get();
foreach ($roomImages as $image) {
    $oldPath = $image->image_path;

    // Bỏ qua nếu đường dẫn đã ở định dạng storage
    if (strpos($oldPath, 'rooms/') === 0) {
        continue;
    }

    // Xử lý đường dẫn cũ
    $parts = explode('/', $oldPath);
    if (count($parts) >= 2) {
        $roomId = $parts[0]; // Thư mục con (ID phòng)
        $filename = $parts[1]; // Tên file

        // Đường dẫn mới trong storage
        $newPath = 'rooms/' . $filename;

        // Cập nhật đường dẫn trong database
        DB::table('room_images')
            ->where('id', $image->id)
            ->update(['image_path' => $newPath]);

        echo "Updated room image: {$image->id} from $oldPath to $newPath\n";
    } else {
        // Nếu đường dẫn không có thư mục con
        $filename = basename($oldPath);
        $newPath = 'rooms/' . $filename;

        // Cập nhật đường dẫn trong database
        DB::table('room_images')
            ->where('id', $image->id)
            ->update(['image_path' => $newPath]);

        echo "Updated room image: {$image->id} from $oldPath to $newPath\n";
    }
}

echo "Done!\n";

