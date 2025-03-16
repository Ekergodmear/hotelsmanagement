<?php

namespace App\Http\Controllers;

use App\Models\BookingService;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingServiceController extends Controller
{
    /**
     * Lưu dịch vụ đặt phòng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1',
            'service_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Lấy thông tin dịch vụ
        $service = Service::findOrFail($validated['service_id']);

        // Thêm giá vào mảng validated
        $validated['price'] = $service->price;

        // Tạo dịch vụ đặt phòng
        BookingService::create($validated);

        return redirect()->route('bookings.show', $validated['booking_id'])
            ->with('success', 'Dịch vụ đã được thêm thành công.');
    }

    /**
     * Xóa dịch vụ đặt phòng khỏi cơ sở dữ liệu.
     */
    public function destroy(BookingService $bookingService): RedirectResponse
    {
        $bookingId = $bookingService->booking_id;
        $bookingService->delete();

        return redirect()->route('bookings.show', $bookingId)
            ->with('success', 'Dịch vụ đã được xóa thành công.');
    }
}
