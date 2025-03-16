<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomTypeController extends Controller
{
    /**
     * Hiển thị danh sách loại phòng.
     */
    public function index(): View
    {
        $roomTypes = RoomType::all();
        return view('room-types.index', compact('roomTypes'));
    }

    /**
     * Hiển thị form tạo loại phòng mới.
     */
    public function create(): View
    {
        return view('room-types.create');
    }

    /**
     * Lưu loại phòng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        RoomType::create($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Loại phòng đã được tạo thành công.');
    }

    /**
     * Hiển thị thông tin chi tiết của loại phòng.
     */
    public function show(RoomType $roomType): View
    {
        $roomType->load('rooms');
        return view('room-types.show', compact('roomType'));
    }

    /**
     * Hiển thị form chỉnh sửa loại phòng.
     */
    public function edit(RoomType $roomType): View
    {
        return view('room-types.edit', compact('roomType'));
    }

    /**
     * Cập nhật thông tin loại phòng trong cơ sở dữ liệu.
     */
    public function update(Request $request, RoomType $roomType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        $roomType->update($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Thông tin loại phòng đã được cập nhật thành công.');
    }

    /**
     * Xóa loại phòng khỏi cơ sở dữ liệu.
     */
    public function destroy(RoomType $roomType): RedirectResponse
    {
        // Kiểm tra xem loại phòng có phòng nào không
        if ($roomType->rooms()->exists()) {
            return back()->with('error', 'Không thể xóa loại phòng vì có phòng thuộc loại này.');
        }

        $roomType->delete();

        return redirect()->route('room-types.index')
            ->with('success', 'Loại phòng đã được xóa thành công.');
    }
}
