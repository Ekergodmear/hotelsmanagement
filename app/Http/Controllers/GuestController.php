<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuestController extends Controller
{
    /**
     * Hiển thị danh sách khách hàng.
     */
    public function index(): View
    {
        $guests = Guest::orderBy('created_at', 'desc')->get();
        return view('guests.index', compact('guests'));
    }

    /**
     * Hiển thị form tạo khách hàng mới.
     */
    public function create(): View
    {
        return view('guests.create');
    }

    /**
     * Lưu khách hàng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guests',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'id_card_type' => 'nullable|string|max:50',
            'id_card_number' => 'nullable|string|max:50',
        ]);

        $guest = Guest::create($validated);

        return redirect()->route('guests.show', $guest)
            ->with('success', 'Khách hàng đã được tạo thành công.');
    }

    /**
     * Hiển thị thông tin chi tiết của khách hàng.
     */
    public function show(Guest $guest): View
    {
        $guest->load('bookings.room');
        return view('guests.show', compact('guest'));
    }

    /**
     * Hiển thị form chỉnh sửa khách hàng.
     */
    public function edit(Guest $guest): View
    {
        return view('guests.edit', compact('guest'));
    }

    /**
     * Cập nhật thông tin khách hàng trong cơ sở dữ liệu.
     */
    public function update(Request $request, Guest $guest): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guests,email,' . $guest->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'id_card_type' => 'nullable|string|max:50',
            'id_card_number' => 'nullable|string|max:50',
        ]);

        $guest->update($validated);

        return redirect()->route('guests.show', $guest)
            ->with('success', 'Thông tin khách hàng đã được cập nhật thành công.');
    }

    /**
     * Xóa khách hàng khỏi cơ sở dữ liệu.
     */
    public function destroy(Guest $guest): RedirectResponse
    {
        // Kiểm tra xem khách hàng có đặt phòng đang hoạt động không
        $hasActiveBookings = $guest->bookings()->whereIn('status', ['pending', 'confirmed', 'checked_in'])->exists();

        if ($hasActiveBookings) {
            return back()->with('error', 'Không thể xóa khách hàng vì có đặt phòng đang hoạt động.');
        }

        $guest->delete();

        return redirect()->route('guests.index')
            ->with('success', 'Khách hàng đã được xóa thành công.');
    }
}
