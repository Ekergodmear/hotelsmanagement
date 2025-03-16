<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class UpdateHotelDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách các quận/huyện mẫu theo tỉnh/thành phố
        $districtsByCity = [
            'Hà Nội' => ['Ba Đình', 'Hoàn Kiếm', 'Hai Bà Trưng', 'Đống Đa', 'Cầu Giấy', 'Thanh Xuân', 'Tây Hồ'],
            'Hồ Chí Minh' => ['Quận 1', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 7', 'Phú Nhuận', 'Bình Thạnh', 'Tân Bình'],
            'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu'],
            'Nha Trang' => ['Vĩnh Hải', 'Vĩnh Phước', 'Vĩnh Thọ', 'Lộc Thọ', 'Phước Hải'],
            'Đà Lạt' => ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5'],
            'Hội An' => ['Minh An', 'Cẩm Phô', 'Sơn Phong', 'Cẩm Châu', 'Thanh Hà'],
            'Huế' => ['Phường Đông Ba', 'Phường Gia Hội', 'Phường Phú Hậu', 'Phường Phú Nhuận', 'Phường Vĩnh Ninh'],
            'Phú Quốc' => ['Dương Đông', 'An Thới', 'Cửa Dương', 'Hàm Ninh', 'Bãi Thơm'],
            'Hạ Long' => ['Hồng Gai', 'Bãi Cháy', 'Tuần Châu', 'Hà Khẩu', 'Hồng Hải'],
            'Sapa' => ['Thị trấn Sa Pa', 'Lao Chải', 'Tả Van', 'Bản Hồ', 'Thanh Bình']
        ];

        // Lấy tất cả khách sạn
        $hotels = Hotel::all();
        $count = 0;

        foreach ($hotels as $hotel) {
            // Nếu đã có quận/huyện thì bỏ qua
            if (!empty($hotel->district) && $hotel->district != 'Chưa cập nhật') {
                continue;
            }

            // Lấy tỉnh/thành phố của khách sạn
            $city = $hotel->province_city;

            // Nếu có danh sách quận/huyện cho tỉnh/thành phố này
            if (isset($districtsByCity[$city])) {
                // Chọn ngẫu nhiên một quận/huyện
                $randomDistrict = $districtsByCity[$city][array_rand($districtsByCity[$city])];

                // Cập nhật quận/huyện cho khách sạn
                $hotel->district = $randomDistrict;
                $hotel->save();

                $count++;
            } else {
                // Nếu không có danh sách quận/huyện cho tỉnh/thành phố này
                $hotel->district = 'Trung tâm';
                $hotel->save();

                $count++;
            }
        }

        $this->command->info("Đã cập nhật quận/huyện cho $count khách sạn.");
    }
}
