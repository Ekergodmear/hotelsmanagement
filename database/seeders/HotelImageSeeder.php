<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Database\Seeder;

class HotelImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách ảnh mẫu từ Unsplash
        $sampleImages = [
            'https://source.unsplash.com/1200x800/?hotel,luxury&sig=1',
            'https://source.unsplash.com/1200x800/?hotel,room&sig=2',
            'https://source.unsplash.com/1200x800/?hotel,lobby&sig=3',
            'https://source.unsplash.com/1200x800/?hotel,pool&sig=4',
            'https://source.unsplash.com/1200x800/?hotel,restaurant&sig=5',
            'https://source.unsplash.com/1200x800/?hotel,bedroom&sig=6',
            'https://source.unsplash.com/1200x800/?hotel,bathroom&sig=7',
            'https://source.unsplash.com/1200x800/?hotel,view&sig=8',
            'https://source.unsplash.com/1200x800/?hotel,suite&sig=9',
            'https://source.unsplash.com/1200x800/?hotel,spa&sig=10',
        ];

        // Lấy tất cả khách sạn
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            // Số lượng ảnh ngẫu nhiên cho mỗi khách sạn (3-6 ảnh)
            $numImages = rand(3, 6);

            // Chọn ngẫu nhiên các ảnh từ danh sách mẫu
            $selectedImages = array_rand(array_flip($sampleImages), $numImages);

            // Thêm ảnh cho khách sạn
            foreach ($selectedImages as $index => $imageUrl) {
                HotelImage::create([
                    'hotel_id' => $hotel->id,
                    'image_path' => $imageUrl,
                    'is_primary' => $index === 0, // Ảnh đầu tiên là ảnh chính
                    'display_order' => $index
                ]);
            }
        }
    }
}
