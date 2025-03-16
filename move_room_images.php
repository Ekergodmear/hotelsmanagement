<?php

// Đường dẫn đến thư mục gốc của dự án
$basePath = __DIR__;

// Đường dẫn đến thư mục public và storage
$publicPath = $basePath . '/public';
$storagePath = $basePath . '/storage/app/public';

// Tạo thư mục rooms trong storage nếu chưa tồn tại
if (!file_exists($storagePath . '/rooms')) {
    mkdir($storagePath . '/rooms', 0755, true);
}

// Chuyển ảnh phòng
$roomImagesPath = $publicPath . '/images/room_images';
if (file_exists($roomImagesPath) && is_dir($roomImagesPath)) {
    $roomDirs = scandir($roomImagesPath);
    foreach ($roomDirs as $roomDir) {
        if ($roomDir === '.' || $roomDir === '..') {
            continue;
        }

        $roomDirPath = $roomImagesPath . '/' . $roomDir;
        if (is_dir($roomDirPath)) {
            $roomImages = scandir($roomDirPath);
            foreach ($roomImages as $image) {
                if ($image === '.' || $image === '..') {
                    continue;
                }

                $sourcePath = $roomDirPath . '/' . $image;
                $destPath = $storagePath . '/rooms/' . $image;

                if (file_exists($sourcePath) && is_file($sourcePath)) {
                    copy($sourcePath, $destPath);
                    echo "Copied: $sourcePath -> $destPath\n";
                }
            }
        }
    }
}

echo "Done!\n";
