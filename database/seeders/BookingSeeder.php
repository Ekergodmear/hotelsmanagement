<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo khách hàng mẫu
        $guest = Guest::create([
            'first_name' => 'Nguyễn',
            'last_name' => 'Văn A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0123456789',
            'address' => '123 Đường ABC',
            'city' => 'Hà Nội',
            'country' => 'Việt Nam',
            'id_card_type' => 'CMND',
            'id_card_number' => '123456789',
        ]);

        // Lấy phòng đầu tiên
        $room = Room::first();

        if ($room) {
            // Tạo đặt phòng mẫu
            Booking::create([
                'guest_id' => $guest->id,
                'room_id' => $room->id,
                'check_in' => now(),
                'check_out' => now()->addDays(2),
                'adults' => 2,
                'children' => 0,
                'total_price' => $room->roomType->base_price * 2, // 2 ngày
                'status' => 'confirmed',
                'special_requests' => 'Yêu cầu phòng yên tĩnh',
            ]);

            // Cập nhật trạng thái phòng
            $room->update(['status' => 'occupied']);
        }
    }
}
