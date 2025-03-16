<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Hiển thị danh sách đặt phòng.
     */
    public function index(): View
    {
        $bookings = Booking::with(['user', 'room'])->orderBy('created_at', 'desc')->get();
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Hiển thị form tạo đặt phòng mới.
     */
    public function create(Request $request): View
    {
        // Lấy thông tin từ request
        $roomId = $request->input('room_id');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $guests = $request->input('guests', 2);

        // Kiểm tra và lấy thông tin phòng
        $room = Room::with(['roomType', 'primaryImage'])->findOrFail($roomId);
        $rooms = collect([$room]);

        return view('bookings.create', compact(
            'room',
            'rooms',
            'roomId',
            'checkIn',
            'checkOut',
            'guests'
        ));
    }

    /**
     * Lưu đặt phòng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:5',
            'special_requests' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'in:airport_pickup,airport_dropoff,breakfast,dinner'
        ]);

        // Tính tổng tiền phòng
        $room = Room::findOrFail($validated['room_id']);
        $checkIn = new \DateTime($validated['check_in']);
        $checkOut = new \DateTime($validated['check_out']);
        $nights = $checkIn->diff($checkOut)->days;
        $totalPrice = $room->price * $nights;

        // Tính tiền dịch vụ bổ sung
        $services = $request->input('services', []);
        $servicePrices = [
            'airport_pickup' => 300000,
            'airport_dropoff' => 300000,
            'breakfast' => 150000,
            'dinner' => 250000
        ];

        foreach ($services as $service) {
            if (in_array($service, ['airport_pickup', 'airport_dropoff'])) {
                $totalPrice += $servicePrices[$service];
            } else if (in_array($service, ['breakfast', 'dinner'])) {
                $totalPrice += $servicePrices[$service] * ($validated['adults'] + ($validated['children'] ?? 0)) * $nights;
            }
        }

        // Tạo đặt phòng
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $validated['room_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'adults' => $validated['adults'],
            'children' => $validated['children'],
            'special_requests' => $validated['special_requests'],
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'guest_name' => $validated['name'],
            'guest_email' => $validated['email'],
            'guest_phone' => $validated['phone']
        ]);

        // Lưu các dịch vụ đã chọn
        if (!empty($services)) {
            foreach ($services as $service) {
                $booking->bookingServices()->create([
                    'service_name' => $service,
                    'price' => $servicePrices[$service]
                ]);
            }
        }

        // Cập nhật trạng thái phòng
        $room->update(['status' => 'occupied']);

        // Gửi email xác nhận đặt phòng
        // TODO: Implement email notification

        return redirect()->route('bookings.success', $booking)
            ->with('success', 'Đặt phòng thành công! Chúng tôi sẽ gửi email xác nhận cho bạn trong giây lát.');
    }

    /**
     * Hiển thị trang xác nhận đặt phòng thành công.
     */
    public function success(Booking $booking): View
    {
        $booking->load(['room.roomType', 'bookingServices']);
        return view('bookings.success', compact('booking'));
    }

    /**
     * Hiển thị thông tin chi tiết của đặt phòng.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'room.roomType', 'services.service']);
        $availableServices = Service::where('is_active', true)->get();

        return view('bookings.show', compact('booking', 'availableServices'));
    }

    /**
     * Hiển thị form chỉnh sửa đặt phòng.
     */
    public function edit(Booking $booking): View
    {
        $users = User::where('role', 'user')->get();
        $rooms = Room::where('status', 'available')
            ->orWhere('id', $booking->room_id)
            ->get();

        return view('bookings.edit', compact('booking', 'users', 'rooms'));
    }

    /**
     * Cập nhật thông tin đặt phòng trong cơ sở dữ liệu.
     */
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
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
        $totalPrice = $room->roomType->base_price * $days;

        // Thêm thông tin vào mảng validated
        $validated['total_price'] = $totalPrice;

        // Cập nhật đặt phòng
        $booking->update($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Thông tin đặt phòng đã được cập nhật thành công.');
    }

    /**
     * Xóa đặt phòng khỏi cơ sở dữ liệu.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        // Cập nhật trạng thái phòng
        $room = Room::findOrFail($booking->room_id);
        $room->update(['status' => 'available']);

        // Xóa đặt phòng
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Đặt phòng đã được xóa thành công.');
    }

    /**
     * Check-in khách hàng.
     */
    public function checkIn(Booking $booking): RedirectResponse
    {
        if ($booking->status != 'confirmed') {
            return back()->with('error', 'Chỉ có thể check-in cho đặt phòng đã xác nhận.');
        }

        $booking->update(['status' => 'checked_in']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Khách hàng đã check-in thành công.');
    }

    /**
     * Check-out khách hàng.
     */
    public function checkOut(Booking $booking): RedirectResponse
    {
        if ($booking->status != 'checked_in') {
            return back()->with('error', 'Chỉ có thể check-out cho khách hàng đã check-in.');
        }

        $booking->update(['status' => 'checked_out']);

        // Cập nhật trạng thái phòng
        $room = Room::findOrFail($booking->room_id);
        $room->update(['status' => 'available']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Khách hàng đã check-out thành công.');
    }
}
