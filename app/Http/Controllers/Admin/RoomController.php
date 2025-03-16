<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with(['hotel', 'roomType', 'images', 'primaryImage', 'hotel.images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::all();
        $roomTypes = RoomType::all();
        return view('admin.rooms.create', compact('hotels', 'roomTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50',
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'status' => 'required|in:available,occupied,maintenance',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Kiểm tra xem phòng đã tồn tại trong khách sạn chưa
        $exists = Room::where('room_number', $validated['room_number'])
            ->where('hotel_id', $validated['hotel_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['room_number' => 'Số phòng này đã tồn tại trong khách sạn.'])
                ->withInput();
        }

        $room = Room::create($validated);

        // Xử lý tải lên nhiều ảnh
        if ($request->hasFile('images')) {
            $this->uploadRoomImages($request->file('images'), $room);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Phòng đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::with(['hotel', 'roomType', 'bookings', 'images'])->findOrFail($id);
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::with('images')->findOrFail($id);
        $hotels = Hotel::all();
        $roomTypes = RoomType::all();

        // Debug: Kiểm tra dữ liệu ảnh
        if ($room->images->count() > 0) {
            Log::info('Room #' . $id . ' has ' . $room->images->count() . ' images');
            foreach ($room->images as $image) {
                Log::info('Image path: ' . $image->image_path);
            }
        } else {
            Log::info('Room #' . $id . ' has no images');
        }

        return view('admin.rooms.edit', compact('room', 'hotels', 'roomTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'room_number' => 'required|string|max:10',
            'hotel_id' => 'required|exists:hotels,id',
            'room_type_id' => 'required|exists:room_types,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        try {
            $room = Room::findOrFail($id);

            // Cập nhật thông tin phòng
            $room->update([
                'room_number' => $request->room_number,
                'hotel_id' => $request->hotel_id,
                'room_type_id' => $request->room_type_id,
                'price' => $request->price,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Xử lý ảnh nếu có
            if ($request->hasFile('images')) {
                // Xóa ảnh cũ
                foreach ($room->images as $image) {
                    // Xóa file ảnh từ storage
                    Storage::disk('public')->delete($image->image_path);

                    // Xóa bản ghi ảnh
                    $image->delete();
                }

                // Tải lên ảnh mới
                $this->uploadRoomImages($request->file('images'), $room);
            }

            return redirect()->route('admin.rooms.show', $room->id)
                ->with('success', 'Phòng đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể cập nhật phòng: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $room = Room::findOrFail($id);

            // Xóa ảnh liên quan
            foreach ($room->images as $image) {
                // Xóa file ảnh từ storage
                Storage::disk('public')->delete($image->image_path);

                // Xóa bản ghi ảnh
                $image->delete();
            }

            // Xóa phòng
            $room->delete();

            return redirect()->route('admin.rooms.index')->with('success', 'Phòng đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.rooms.index')->with('error', 'Không thể xóa phòng: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị danh sách phòng theo khách sạn
     */
    public function byHotel(string $hotelId)
    {
        $hotel = Hotel::with(['images', 'primaryImage'])->findOrFail($hotelId);
        $rooms = Room::where('hotel_id', $hotelId)
            ->with(['roomType', 'images', 'primaryImage'])
            ->orderBy('room_number')
            ->paginate(10);

        return view('admin.rooms.by-hotel', compact('rooms', 'hotel'));
    }

    /**
     * Tải lên nhiều ảnh cho phòng
     */
    private function uploadRoomImages($images, $room)
    {
        $maxImages = 5;
        $count = 0;

        foreach ($images as $image) {
            if ($count >= $maxImages) {
                break;
            }

            // Lưu ảnh vào storage
            $imagePath = $image->store('rooms', 'public');

            RoomImage::create([
                'room_id' => $room->id,
                'image_path' => $imagePath,
                'is_primary' => $count === 0, // Ảnh đầu tiên là ảnh chính
                'display_order' => $count + 1,
            ]);

            $count++;
        }
    }
}
