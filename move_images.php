<?php

// Đường dẫn đến thư mục gốc của dự án
$basePath = __DIR__;

// Đường dẫn đến thư mục public và storage
$publicPath = $basePath . '/public';
$storagePath = $basePath . '/storage/app/public';

// Tạo thư mục hotels và rooms trong storage nếu chưa tồn tại
if (!file_exists($storagePath . '/hotels')) {
    mkdir($storagePath . '/hotels', 0755, true);
}

if (!file_exists($storagePath . '/rooms')) {
    mkdir($storagePath . '/rooms', 0755, true);
}

// Chuyển ảnh khách sạn
$hotelImagesPath = $publicPath . '/images/hotel_images';
if (file_exists($hotelImagesPath) && is_dir($hotelImagesPath)) {
    $hotelDirs = scandir($hotelImagesPath);
    foreach ($hotelDirs as $hotelDir) {
        if ($hotelDir === '.' || $hotelDir === '..') {
            continue;
        }

        $hotelDirPath = $hotelImagesPath . '/' . $hotelDir;
        if (is_dir($hotelDirPath)) {
            $hotelImages = scandir($hotelDirPath);
            foreach ($hotelImages as $image) {
                if ($image === '.' || $image === '..') {
                    continue;
                }

                $sourcePath = $hotelDirPath . '/' . $image;
                $destPath = $storagePath . '/hotels/' . $image;

                if (file_exists($sourcePath) && is_file($sourcePath)) {
                    copy($sourcePath, $destPath);
                    echo "Copied: $sourcePath -> $destPath\n";
                }
            }
        }
    }
}

// Chuyển ảnh phòng
$roomImagesPath = $publicPath . '/images/room_images';
if (file_exists($roomImagesPath) && is_dir($roomImagesPath)) {
    $roomImages = scandir($roomImagesPath);
    foreach ($roomImages as $image) {
        if ($image === '.' || $image === '..') {
            continue;
        }

        $sourcePath = $roomImagesPath . '/' . $image;
        $destPath = $storagePath . '/rooms/' . $image;

        if (file_exists($sourcePath) && is_file($sourcePath)) {
            copy($sourcePath, $destPath);
            echo "Copied: $sourcePath -> $destPath\n";
        }
    }
}

echo "Done!\n";
