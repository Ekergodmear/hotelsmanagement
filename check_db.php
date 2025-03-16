<?php

// Đường dẫn đến file autoload.php của Laravel
require __DIR__ . '/vendor/autoload.php';

// Tạo ứng dụng Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Sử dụng DB facade của Laravel
use Illuminate\Support\Facades\DB;

// Kiểm tra dữ liệu trong bảng hotel_images
$hotelImages = DB::table('hotel_images')->select('id', 'hotel_id', 'image_path')->limit(5)->get();
echo "Hotel Images:\n";
foreach ($hotelImages as $image) {
    echo "ID: {$image->id}, Hotel ID: {$image->hotel_id}, Image Path: {$image->image_path}\n";
}

// Kiểm tra dữ liệu trong bảng room_images
$roomImages = DB::table('room_images')->select('id', 'room_id', 'image_path')->limit(10)->get();
echo "\nRoom Images:\n";
foreach ($roomImages as $image) {
    echo "ID: {$image->id}, Room ID: {$image->room_id}, Image Path: {$image->image_path}\n";
}

// Kiểm tra số lượng ảnh phòng có đường dẫn bắt đầu bằng 'rooms/'
$roomImagesCount = DB::table('room_images')->where('image_path', 'like', 'rooms/%')->count();
echo "\nSố lượng ảnh phòng có đường dẫn bắt đầu bằng 'rooms/': $roomImagesCount\n";

// Kiểm tra số lượng ảnh phòng có đường dẫn không bắt đầu bằng 'rooms/'
$roomImagesOtherCount = DB::table('room_images')->where('image_path', 'not like', 'rooms/%')->count();
echo "Số lượng ảnh phòng có đường dẫn không bắt đầu bằng 'rooms/': $roomImagesOtherCount\n";

// Hiển thị một số ảnh phòng có đường dẫn không bắt đầu bằng 'rooms/'
$roomImagesOther = DB::table('room_images')->where('image_path', 'not like', 'rooms/%')->limit(5)->get();
echo "\nMột số ảnh phòng có đường dẫn không bắt đầu bằng 'rooms/':\n";
foreach ($roomImagesOther as $image) {
    echo "ID: {$image->id}, Room ID: {$image->room_id}, Image Path: {$image->image_path}\n";
}
