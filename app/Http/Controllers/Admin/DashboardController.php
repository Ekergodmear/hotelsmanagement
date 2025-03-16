<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard cho admin
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy số liệu thống kê
        $totalHotels = Hotel::count();
        $totalRooms = Room::count();
        $totalBookings = Booking::count();
        $totalUsers = User::where('role', 'user')->count();

        // Lấy các đặt phòng mới nhất
        $recentBookings = Booking::with(['user', 'room'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Lấy các người dùng mới nhất
        $newUsers = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Trả về view với dữ liệu
        return view('admin.dashboard', compact(
            'totalHotels',
            'totalRooms',
            'totalBookings',
            'totalUsers',
            'recentBookings',
            'newUsers'
        ));
    }
}
