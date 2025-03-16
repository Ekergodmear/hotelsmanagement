<?php
require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Khởi tạo Eloquent
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'hotel_management',
    'username'  => 'root',
    'password'  => 'TrongKhoi2401',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Lấy danh sách tất cả các ảnh phòng
$roomImages = Capsule::table('room_images')->get();

// Kiểm tra thư mục lưu trữ
$storageDir = 'storage/app/public/rooms';
$imageFiles = scandir($storageDir);
$jpgFiles = [];

// Lọc ra các tệp .jpg và tạo ánh xạ theo ID phòng
foreach ($imageFiles as $file) {
    if (strpos($file, '.jpg') !== false) {
        preg_match('/room_(\d+)_image_(\d+)\.jpg/', $file, $matches);
        if (isset($matches[1]) && isset($matches[2])) {
            $roomId = $matches[1];
            $jpgFiles[$roomId][] = $file;
        }
    }
}

// Đếm số lượng cập nhật
$updatedCount = 0;
$errorCount = 0;

// Cập nhật đường dẫn hình ảnh
foreach ($roomImages as $image) {
    // Lấy ID phòng từ đường dẫn hiện tại
    preg_match('/room_(\d+)_\d+_\d+\.txt/', $image->image_path, $matches);

    if (isset($matches[1])) {
        $roomId = $matches[1];

        // Kiểm tra xem có hình ảnh .jpg cho phòng này không
        if (isset($jpgFiles[$roomId]) && !empty($jpgFiles[$roomId])) {
            // Lấy chỉ số của hình ảnh hiện tại
            preg_match('/room_\d+_(\d+)_\d+\.txt/', $image->image_path, $indexMatches);
            $index = isset($indexMatches[1]) ? (int)$indexMatches[1] : 0;

            // Đảm bảo chỉ số nằm trong phạm vi của mảng hình ảnh có sẵn
            $availableIndex = min($index, count($jpgFiles[$roomId]) - 1);

            // Cập nhật đường dẫn hình ảnh
            $newPath = 'rooms/' . $jpgFiles[$roomId][$availableIndex];

            try {
                Capsule::table('room_images')
                    ->where('id', $image->id)
                    ->update(['image_path' => $newPath]);

                echo "Đã cập nhật ảnh ID {$image->id} cho phòng {$roomId}: {$image->image_path} -> {$newPath}\n";
                $updatedCount++;
            } catch (Exception $e) {
                echo "Lỗi khi cập nhật ảnh ID {$image->id}: " . $e->getMessage() . "\n";
                $errorCount++;
            }
        } else {
            echo "Không tìm thấy hình ảnh .jpg cho phòng {$roomId}\n";
            $errorCount++;
        }
    } else {
        echo "Không thể phân tích đường dẫn: {$image->image_path}\n";
        $errorCount++;
    }
}

echo "\nTổng kết:\n";
echo "- Số lượng ảnh đã cập nhật: {$updatedCount}\n";
echo "- Số lượng lỗi: {$errorCount}\n";
