<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room', 'room.roomType']);

        // Tìm kiếm theo tên khách hàng
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'room', 'room.roomType', 'services', 'services.service'])
            ->findOrFail($id);

        $availableServices = \App\Models\Service::where('status', 'active')->get();

        return view('admin.bookings.show', compact('booking', 'availableServices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Đánh dấu đặt phòng là đã check-in.
     */
    public function checkIn(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => 'checked_in']);

        // Cập nhật trạng thái phòng thành 'occupied'
        $room = Room::findOrFail($booking->room_id);
        $room->update(['status' => 'occupied']);

        return redirect()->back()->with('success', 'Khách hàng đã check-in thành công.');
    }

    /**
     * Đánh dấu đặt phòng là đã check-out.
     */
    public function checkOut(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => 'checked_out']);

        // Cập nhật trạng thái phòng thành 'available'
        $room = Room::findOrFail($booking->room_id);
        $room->update(['status' => 'available']);

        return redirect()->back()->with('success', 'Khách hàng đã check-out thành công.');
    }
}
