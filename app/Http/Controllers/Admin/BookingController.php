<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use App\Models\Service;
use Illuminate\View\View;

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
    public function show(Booking $booking): View
    {
        $services = Service::where('is_active', true)->get();
        return view('admin.bookings.show', compact('booking', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        $rooms = Room::where('status', 'available')
            ->orWhere('id', $booking->room_id)
            ->get();
        return view('admin.bookings.edit', compact('booking', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
        ]);

        // Nếu phòng thay đổi
        if ($booking->room_id != $validated['room_id']) {
            // Cập nhật trạng thái phòng cũ
            $oldRoom = Room::findOrFail($booking->room_id);
            $oldRoom->update(['status' => 'available']);

            // Cập nhật trạng thái phòng mới
            $newRoom = Room::findOrFail($validated['room_id']);
            $newRoom->update(['status' => 'occupied']);
        }

        // Tính lại tổng tiền
        $room = Room::findOrFail($validated['room_id']);
        $checkIn = new \DateTime($validated['check_in']);
        $checkOut = new \DateTime($validated['check_out']);
        $days = $checkIn->diff($checkOut)->days;
        $totalPrice = $room->price * $days;

        if ($booking->has_airport_transfer) {
            $totalPrice += $booking->transfer_price;
        }

        // Thêm thông tin vào mảng validated
        $validated['total_price'] = $totalPrice;

        // Cập nhật đặt phòng
        $booking->update($validated);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Thông tin đặt phòng đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);

        // Cập nhật trạng thái phòng thành available
        $room = Room::findOrFail($booking->room_id);
        $room->update(['status' => 'available']);

        // Xóa đặt phòng
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Đặt phòng đã được xóa thành công.');
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
