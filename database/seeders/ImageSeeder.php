<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ImageSeeder extends Seeder
{
    private $unsplashImages = [
        'hotels' => [
            'hanoi' => [
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1562778612-e1e0cda9915c?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1600&auto=format&fit=crop',
            ],
            'hochiminh' => [
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=1600&auto=format&fit=crop',
            ],
            'danang' => [
                'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1600&auto=format&fit=crop',
            ],
            'nhatrang' => [
                'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600&auto=format&fit=crop',
            ],
            'phuquoc' => [
                'https://images.unsplash.com/photo-1615880484746-a134be9a6ecf?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?q=80&w=1600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1600&auto=format&fit=crop',
            ],
        ],
    ];

    public function run(): void
    {
        // Tạo thư mục storage nếu chưa có
        if (!File::exists(storage_path('app/public'))) {
            File::makeDirectory(storage_path('app/public'));
        }

        // Tạo thư mục hotels nếu chưa có
        if (!File::exists(storage_path('app/public/hotels'))) {
            File::makeDirectory(storage_path('app/public/hotels'));
        }

        // Tạo thư mục rooms nếu chưa có
        if (!File::exists(storage_path('app/public/rooms'))) {
            File::makeDirectory(storage_path('app/public/rooms'));
        }

        // Copy ảnh khách sạn từ resources vào storage
        $cities = ['hanoi', 'hochiminh', 'danang', 'nhatrang', 'phuquoc'];
        foreach ($cities as $city) {
            for ($i = 1; $i <= 5; $i++) {
                $sourcePath = resource_path("images/hotels/{$city}{$i}.jpg");
                $destinationPath = storage_path("app/public/hotels/{$city}{$i}.jpg");
                if (File::exists($sourcePath)) {
                    File::copy($sourcePath, $destinationPath);
                }
            }
        }

        // Tạo ảnh mẫu cho các loại phòng
        $roomTypes = [
            'phong-tieu-chuan' => 'standard',
            'phong-deluxe' => 'deluxe',
            'phong-suite' => 'suite',
            'phong-gia-dinh' => 'family'
        ];

        foreach ($roomTypes as $type => $folder) {
            for ($i = 1; $i <= 3; $i++) {
                $sourcePath = resource_path("images/rooms/{$folder}{$i}.jpg");
                $destinationPath = storage_path("app/public/rooms/{$type}{$i}.jpg");
                if (File::exists($sourcePath)) {
                    File::copy($sourcePath, $destinationPath);
                } else {
                    // Nếu không có ảnh mẫu, copy ảnh từ thư mục hotels
                    $sourcePath = resource_path("images/hotels/hanoi1.jpg");
                    if (File::exists($sourcePath)) {
                        File::copy($sourcePath, $destinationPath);
                    }
                }
            }
        }

        echo "Đã copy và tải hình ảnh vào storage thành công!\n";
    }
}
