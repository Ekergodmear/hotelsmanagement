<?php

namespace App\Console\Commands;

use App\Models\Hotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SimplifyHotelAmenities extends Command
{
    /**
     * Tên lệnh
     *
     * @var string
     */
    protected $signature = 'hotels:simplify-amenities';

    /**
     * Mô tả lệnh
     *
     * @var string
     */
    protected $description = 'Đơn giản hóa dữ liệu tiện nghi của tất cả khách sạn';

    /**
     * Thực thi lệnh
     */
    public function handle()
    {
        $this->info('Bắt đầu đơn giản hóa dữ liệu tiện nghi...');

        $hotels = Hotel::all();
        $count = 0;

        foreach ($hotels as $hotel) {
            $originalAmenities = $hotel->amenities;
            $simplifiedAmenities = $this->simplifyAmenities($originalAmenities);

            // Cập nhật dữ liệu
            $hotel->amenities = $simplifiedAmenities;
            $hotel->save();

            $this->info("Đã cập nhật khách sạn #{$hotel->id}: {$hotel->name}");
            $count++;
        }

        $this->info("Hoàn thành! Đã cập nhật {$count} khách sạn.");
    }

    /**
     * Đơn giản hóa dữ liệu tiện nghi
     */
    private function simplifyAmenities($amenities)
    {
        $result = [];

        // Nếu là chuỗi JSON, chuyển thành mảng
        if (is_string($amenities)) {
            $amenities = json_decode($amenities, true);
        }

        // Nếu không phải mảng, trả về mảng rỗng
        if (!is_array($amenities)) {
            return ['wifi'];
        }

        // Kiểm tra các tiện nghi phổ biến
        if ($this->containsAny($amenities, ['wifi', 'Wifi', 'internet', 'Internet', 'Wifi miễn phí'])) {
            $result[] = 'wifi';
        }

        if ($this->containsAny($amenities, ['bơi', 'bể', 'hồ', 'pool', 'Bể bơi', 'Hồ bơi'])) {
            $result[] = 'pool';
        }

        if ($this->containsAny($amenities, ['nhà hàng', 'Nhà hàng', 'restaurant', 'Restaurant'])) {
            $result[] = 'restaurant';
        }

        if ($this->containsAny($amenities, ['spa', 'Spa', 'massage', 'Massage'])) {
            $result[] = 'spa';
        }

        if ($this->containsAny($amenities, ['gym', 'Gym', 'tập', 'phòng tập', 'Phòng tập'])) {
            $result[] = 'gym';
        }

        if ($this->containsAny($amenities, ['đậu xe', 'Đậu xe', 'bãi đỗ', 'Bãi đỗ', 'parking', 'Parking'])) {
            $result[] = 'parking';
        }

        // Đảm bảo có ít nhất một tiện nghi
        if (empty($result)) {
            $result[] = 'wifi';
        }

        return $result;
    }

    /**
     * Kiểm tra xem mảng có chứa bất kỳ từ khóa nào không
     */
    private function containsAny($array, $keywords)
    {
        foreach ($array as $item) {
            foreach ($keywords as $keyword) {
                if (stripos($item, $keyword) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
