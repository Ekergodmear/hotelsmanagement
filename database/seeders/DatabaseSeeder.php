<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\HotelImage;
use App\Models\RoomImage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            ImageSeeder::class,
            RoomTypeSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Tạo các loại phòng
        $roomTypes = [
            [
                'name' => 'Phòng Tiêu Chuẩn',
                'description' => 'Phòng tiêu chuẩn thoải mái với đầy đủ tiện nghi cơ bản.',
                'base_price' => 800000,
                'capacity' => 2,
            ],
            [
                'name' => 'Phòng Deluxe',
                'description' => 'Phòng rộng rãi với tầm nhìn đẹp và tiện nghi cao cấp.',
                'base_price' => 1200000,
                'capacity' => 2,
            ],
            [
                'name' => 'Phòng Suite',
                'description' => 'Phòng suite sang trọng với phòng khách riêng biệt và không gian rộng rãi.',
                'base_price' => 2000000,
                'capacity' => 3,
            ],
            [
                'name' => 'Phòng Gia Đình',
                'description' => 'Phòng rộng rãi phù hợp cho gia đình với nhiều giường và tiện nghi đầy đủ.',
                'base_price' => 1500000,
                'capacity' => 4,
            ],
        ];

        foreach ($roomTypes as $type) {
            RoomType::create($type);
        }

        // Danh sách các thành phố nổi tiếng ở Việt Nam
        $cities = ['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Nha Trang', 'Phú Quốc'];

        // Danh sách các khách sạn theo thành phố
        $hotels = [
            // Hà Nội
            [
                'name' => 'Hanoi Elegance Diamond Hotel',
                'address' => '32 Lò Sũ, Hoàn Kiếm',
                'city' => 'Hà Nội',
                'description' => 'Khách sạn sang trọng nằm ở trung tâm Hà Nội, gần Hồ Hoàn Kiếm và phố cổ. Cung cấp dịch vụ 5 sao với nhà hàng, spa và phòng nghỉ sang trọng.',
                'rating' => 4.8,
                'price_per_night' => 1500000,
                'amenities' => json_encode(['Wifi miễn phí', 'Bể bơi', 'Nhà hàng', 'Spa', 'Phòng tập gym', 'Dịch vụ phòng 24/7']),
            ],
            [
                'name' => 'Sofitel Legend Metropole Hanoi',
                'address' => '15 Ngô Quyền, Hoàn Kiếm',
                'city' => 'Hà Nội',
                'description' => 'Khách sạn lịch sử sang trọng với kiến trúc Pháp cổ điển, cung cấp dịch vụ đẳng cấp thế giới và ẩm thực tinh tế.',
                'rating' => 4.9,
                'price_per_night' => 3500000,
                'amenities' => json_encode(['Wifi miễn phí', 'Bể bơi', 'Nhà hàng 5 sao', 'Spa cao cấp', 'Phòng tập gym', 'Dịch vụ đưa đón sân bay']),
            ],

            // Hồ Chí Minh
            [
                'name' => 'Park Hyatt Saigon',
                'address' => '2 Công Trường Lam Sơn, Quận 1',
                'city' => 'Hồ Chí Minh',
                'description' => 'Khách sạn 5 sao sang trọng tại trung tâm Sài Gòn, gần Nhà hát Thành phố và các điểm mua sắm cao cấp.',
                'rating' => 4.8,
                'price_per_night' => 3200000,
                'amenities' => json_encode(['Wifi miễn phí', 'Bể bơi ngoài trời', 'Nhà hàng Pháp', 'Spa', 'Phòng tập gym', 'Dịch vụ concierge']),
            ],

            // Đà Nẵng
            [
                'name' => 'InterContinental Danang Sun Peninsula Resort',
                'address' => 'Bãi Bắc, Sơn Trà',
                'city' => 'Đà Nẵng',
                'description' => 'Khu nghỉ dưỡng sang trọng nằm trên bán đảo Sơn Trà với tầm nhìn tuyệt đẹp ra biển và thiết kế độc đáo của kiến trúc sư Bill Bensley.',
                'rating' => 4.9,
                'price_per_night' => 5000000,
                'amenities' => json_encode(['Wifi miễn phí', 'Bể bơi vô cực', 'Nhà hàng đạt sao Michelin', 'Spa cao cấp', 'Bãi biển riêng', 'Dịch vụ đưa đón sân bay']),
            ],

            // Nha Trang
            [
                'name' => 'Vinpearl Resort & Spa Nha Trang Bay',
                'address' => 'Đảo Hòn Tre, Vịnh Nha Trang',
                'city' => 'Nha Trang',
                'description' => 'Khu nghỉ dưỡng sang trọng trên đảo riêng với bãi biển cát trắng, nhiều nhà hàng và công viên giải trí Vinpearl Land.',
                'rating' => 4.7,
                'price_per_night' => 2800000,
                'amenities' => json_encode(['Wifi miễn phí', 'Bể bơi vô cực', 'Nhiều nhà hàng', 'Spa', 'Bãi biển riêng', 'Cáp treo qua biển']),
            ],

            // Phú Quốc
            [
                'name' => 'JW Marriott Phu Quoc Emerald Bay Resort & Spa',
                'address' => 'Bãi Khem, An Thới',
                'city' => 'Phú Quốc',
                'description' => 'Khu nghỉ dưỡng 5 sao+ với thiết kế độc đáo lấy cảm hứng từ trường đại học hư cấu Lamarck, cung cấp trải nghiệm sang trọng bậc nhất.',
                'rating' => 4.9,
                'price_per_night' => 4500000,
                'amenities' => json_encode(['Wifi miễn phí', 'Bể bơi vô cực', 'Nhà hàng đẳng cấp', 'Spa cao cấp', 'Bãi biển riêng', 'Dịch vụ quản gia']),
            ],
        ];

        // Tạo các khách sạn và phòng
        foreach ($hotels as $hotelData) {
            $hotel = Hotel::create($hotelData);

            // Tạo 5 ảnh cho mỗi khách sạn
            $this->createHotelImages($hotel->id, $hotel->city);

            // Tạo 4 phòng cho mỗi khách sạn (1 phòng mỗi loại)
            $this->createRooms($hotel);
        }

        // Chạy RoomImageSeeder sau khi đã tạo xong phòng
        $this->call([
            RoomImageSeeder::class,
        ]);
    }

    /**
     * Tạo ảnh cho khách sạn
     */
    private function createHotelImages($hotelId, $city)
    {
        $cityMapping = [
            'Hà Nội' => 'hanoi',
            'Hồ Chí Minh' => 'hochiminh',
            'Đà Nẵng' => 'danang',
            'Nha Trang' => 'nhatrang',
            'Phú Quốc' => 'phuquoc'
        ];

        $citySlug = $cityMapping[$city] ?? strtolower(str_replace([' ', 'á', 'à', 'ã', 'ạ', 'ả', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'đ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ'], ['', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y'], $city));

        $imageCount = 5;
        for ($i = 1; $i <= $imageCount; $i++) {
            HotelImage::create([
                'hotel_id' => $hotelId,
                'image_path' => 'hotels/' . $citySlug . $i . '.jpg',
                'is_primary' => $i === 1,
                'display_order' => $i
            ]);
        }
    }

    /**
     * Tạo phòng cho khách sạn
     */
    private function createRooms($hotel)
    {
        $roomTypes = RoomType::all();

        foreach ($roomTypes as $index => $roomType) {
            Room::create([
                'room_number' => $hotel->id . '0' . ($index + 1),
                'room_type_id' => $roomType->id,
                'hotel_id' => $hotel->id,
                'status' => 'available',
                'notes' => 'Phòng mới, view đẹp',
            ]);
        }
    }
}
