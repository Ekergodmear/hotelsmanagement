<?php

require __DIR__ . '/vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Kết nối database
$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$database = env('DB_DATABASE', 'forge');
$username = env('DB_USERNAME', 'forge');
$password = env('DB_PASSWORD', '');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cập nhật đường dẫn ảnh khách sạn
    $stmt = $pdo->query("SELECT id, image_path FROM hotel_images");
    $hotelImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($hotelImages as $image) {
        $oldPath = $image['image_path'];

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

            // Cập nhật đường dẫn trong database
            $updateStmt = $pdo->prepare("UPDATE hotel_images SET image_path = ? WHERE id = ?");
            $updateStmt->execute([$newPath, $image['id']]);

            echo "Updated hotel image: {$image['id']} from $oldPath to $newPath\n";
        }
    }

    // Cập nhật đường dẫn ảnh phòng
    $stmt = $pdo->query("SELECT id, image_path FROM room_images");
    $roomImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($roomImages as $image) {
        $oldPath = $image['image_path'];

        // Bỏ qua nếu đường dẫn đã ở định dạng storage
        if (strpos($oldPath, 'rooms/') === 0) {
            continue;
        }

        // Lấy tên file từ đường dẫn cũ
        $filename = basename($oldPath);
        $newPath = 'rooms/' . $filename;

        // Cập nhật đường dẫn trong database
        $updateStmt = $pdo->prepare("UPDATE room_images SET image_path = ? WHERE id = ?");
        $updateStmt->execute([$newPath, $image['id']]);

        echo "Updated room image: {$image['id']} from $oldPath to $newPath\n";
    }

    echo "Done!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Helper function to get environment variables
function env($key, $default = null) {
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    return $value;
}
