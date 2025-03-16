<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index(): View
    {
        // Lấy danh sách khách sạn được sắp xếp theo đánh giá
        $hotels = Hotel::with(['rooms', 'images'])
            ->orderBy('rating', 'desc')
            ->get();

        // Lấy các phòng nổi bật (phòng có giá cao nhất)
        $rooms = Room::with(['hotel', 'roomType'])
            ->whereHas('roomType', function($query) {
                $query->orderBy('base_price', 'desc');
            })
            ->take(6)
            ->get();

        return view('welcome', compact('hotels', 'rooms'));
    }
}
