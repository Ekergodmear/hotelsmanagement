<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class DownloadHotelImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:download-hotel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tải ảnh khách sạn từ Unsplash về máy chủ và cập nhật đường dẫn trong cơ sở dữ liệu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu tải ảnh khách sạn...');

        // Lấy tất cả khách sạn
        $hotels = DB::table('hotels')->select('id')->get();

        foreach ($hotels as $hotel) {
            $hotelId = $hotel->id;

            // Tạo thư mục cho khách sạn nếu chưa tồn tại
            $hotelDir = public_path("images/hotel_images/{$hotelId}");
            if (!File::exists($hotelDir)) {
                File::makeDirectory($hotelDir, 0755, true);
            }

            // Lấy tất cả ảnh của khách sạn
            $images = DB::table('hotel_images')
                ->where('hotel_id', $hotelId)
                ->get();

            $this->info("Đang tải {$images->count()} ảnh cho khách sạn ID: {$hotelId}");

            foreach ($images as $index => $image) {
                $imageUrl = $image->image_path;
                $extension = 'jpg'; // Mặc định là jpg

                // Tạo tên file mới
                $newFileName = "hotel_{$hotelId}_image_" . ($index + 1) . ".{$extension}";
                $localPath = "images/hotel_images/{$hotelId}/{$newFileName}";
                $fullPath = public_path($localPath);

                try {
                    // Tải ảnh từ URL
                    $response = Http::get($imageUrl);

                    if ($response->successful()) {
                        // Lưu ảnh vào thư mục
                        File::put($fullPath, $response->body());

                        // Cập nhật đường dẫn trong cơ sở dữ liệu
                        DB::table('hotel_images')
                            ->where('id', $image->id)
                            ->update(['image_path' => "/{$localPath}"]);

                        $this->info("Đã tải ảnh: {$newFileName}");
                    } else {
                        $this->error("Không thể tải ảnh từ URL: {$imageUrl}");
                    }
                } catch (\Exception $e) {
                    $this->error("Lỗi khi tải ảnh: {$e->getMessage()}");
                }
            }
        }

        $this->info('Hoàn thành tải ảnh khách sạn!');
    }
}
