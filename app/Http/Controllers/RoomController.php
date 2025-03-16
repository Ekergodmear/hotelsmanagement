<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    /**
     * Hiển thị danh sách phòng.
     */
    public function index(): View
    {
        $rooms = Room::with(['roomType', 'hotel'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('rooms.index', [
            'rooms' => $rooms,
            'search' => []
        ]);
    }

    /**
     * Hiển thị form tạo phòng mới.
     */
    public function create(): View
    {
        $roomTypes = RoomType::all();
        return view('rooms.create', compact('roomTypes'));
    }

    /**
     * Lưu phòng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'room_type_id' => 'required|exists:room_types,id',
            'status' => 'required|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Phòng đã được tạo thành công.');
    }

    /**
     * Hiển thị thông tin chi tiết của phòng.
     */
    public function show(Room $room): View
    {
        $room->load(['roomType', 'hotel', 'bookings.guest']);
        return view('rooms.show', compact('room'));
    }

    /**
     * Hiển thị form chỉnh sửa phòng.
     */
    public function edit(Room $room): View
    {
        $roomTypes = RoomType::all();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    /**
     * Cập nhật thông tin phòng trong cơ sở dữ liệu.
     */
    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id,
            'room_type_id' => 'required|exists:room_types,id',
            'status' => 'required|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Thông tin phòng đã được cập nhật thành công.');
    }

    /**
     * Xóa phòng khỏi cơ sở dữ liệu.
     */
    public function destroy(Room $room): RedirectResponse
    {
        // Kiểm tra xem phòng có đang được đặt không
        $hasBookings = $room->bookings()->whereIn('status', ['pending', 'confirmed', 'checked_in'])->exists();

        if ($hasBookings) {
            return back()->with('error', 'Không thể xóa phòng vì có đặt phòng đang hoạt động.');
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Phòng đã được xóa thành công.');
    }

    /**
     * Hiển thị danh sách phòng trống trong khoảng thời gian.
     */
    public function availableRooms(Request $request): View
    {
        $validated = $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $checkIn = $validated['check_in'];
        $checkOut = $validated['check_out'];

        // Lấy ID của các phòng đã được đặt trong khoảng thời gian
        $bookedRoomIds = Booking::where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function($query) use ($checkIn, $checkOut) {
                        $query->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->pluck('room_id');

        // Lấy các phòng không nằm trong danh sách đã đặt và có trạng thái là available
        $availableRooms = Room::with('roomType')
            ->whereNotIn('id', $bookedRoomIds)
            ->where('status', 'available')
            ->get();

        return view('rooms.available', compact('availableRooms', 'checkIn', 'checkOut'));
    }

    /**
     * Tìm kiếm phòng theo các tiêu chí
     */
    public function search(Request $request): View
    {
        $query = Room::with(['roomType', 'hotel']);

        // Tìm theo tên khách sạn
        if ($request->filled('hotel_name')) {
            $query->whereHas('hotel', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->hotel_name . '%');
            });
        }

        // Tìm theo thành phố
        if ($request->filled('city')) {
            $query->whereHas('hotel', function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->city . '%');
            });
        }

        // Tìm theo quận/huyện (địa chỉ)
        if ($request->filled('district')) {
            $query->whereHas('hotel', function($q) use ($request) {
                $q->where('address', 'like', '%' . $request->district . '%');
            });
        }

        // Tìm theo loại phòng
        if ($request->filled('room_type')) {
            $query->whereHas('roomType', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->room_type . '%');
            });
        }

        // Tìm theo giá
        if ($request->filled('min_price')) {
            $query->whereHas('roomType', function($q) use ($request) {
                $q->where('base_price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('roomType', function($q) use ($request) {
                $q->where('base_price', '<=', $request->max_price);
            });
        }

        // Tìm theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sắp xếp kết quả
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy === 'price') {
            $query->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                  ->orderBy('room_types.base_price', $sortOrder)
                  ->select('rooms.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $rooms = $query->get();

        return view('rooms.index', [
            'rooms' => $rooms,
            'search' => $request->all()
        ]);
    }
}
