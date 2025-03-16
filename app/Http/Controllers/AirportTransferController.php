<?php

namespace App\Http\Controllers;

use App\Models\AirportTransfer;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AirportTransferController extends Controller
{
    /**
     * Hiển thị danh sách dịch vụ đưa đón sân bay
     */
    public function index()
    {
        $transfers = AirportTransfer::where('is_active', true)
            ->orderBy('is_popular', 'desc')
            ->orderBy('price', 'asc')
            ->get();

        return view('airport-transfers.index', compact('transfers'));
    }

    /**
     * Hiển thị form đặt dịch vụ đưa đón sân bay
     */
    public function create(Request $request)
    {
        $roomId = $request->input('room_id');
        $bookingId = $request->input('booking_id');
        $airport = $request->input('airport');

        $room = null;
        $booking = null;

        if ($roomId) {
            $room = Room::with('hotel')->findOrFail($roomId);
        }

        if ($bookingId) {
            $booking = Booking::findOrFail($bookingId);
            $room = $booking->room;
        }

        $transfers = AirportTransfer::where('is_active', true)
            ->orderBy('is_popular', 'desc')
            ->orderBy('price', 'asc')
            ->get();

        return view('airport-transfers.create', compact('transfers', 'room', 'booking', 'airport'));
    }

    /**
     * Lưu đặt dịch vụ đưa đón sân bay
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'room_id' => 'required_without:booking_id|exists:rooms,id',
            'airport_name' => 'required|string|max:255',
            'transfer_datetime' => 'required|date',
            'transfer_type' => 'required|exists:airport_transfers,vehicle_type',
            'transfer_passengers' => 'required|integer|min:1',
            'transfer_notes' => 'nullable|string',
        ]);

        // Lấy thông tin dịch vụ đưa đón
        $transfer = AirportTransfer::where('vehicle_type', $validated['transfer_type'])->firstOrFail();

        // Kiểm tra số lượng hành khách
        if ($validated['transfer_passengers'] > $transfer->max_passengers) {
            return back()->with('error', 'Số lượng hành khách vượt quá sức chứa của phương tiện.');
        }

        if (isset($validated['booking_id'])) {
            // Cập nhật đặt phòng hiện có
            $booking = Booking::findOrFail($validated['booking_id']);

            $booking->update([
                'has_airport_transfer' => true,
                'airport_name' => $validated['airport_name'],
                'transfer_datetime' => $validated['transfer_datetime'],
                'transfer_type' => $validated['transfer_type'],
                'transfer_passengers' => $validated['transfer_passengers'],
                'transfer_price' => $transfer->price,
                'transfer_notes' => $validated['transfer_notes'],
                'transfer_status' => 'confirmed'
            ]);

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Dịch vụ đưa đón sân bay đã được thêm vào đặt phòng của bạn.');
        } else {
            // Tạo đặt phòng mới với dịch vụ đưa đón
            $room = Room::findOrFail($validated['room_id']);

            // Kiểm tra xem phòng có trống không
            if ($room->status !== 'available') {
                return back()->with('error', 'Phòng này hiện không khả dụng.');
            }

            // Tạo đặt phòng mới
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'check_in' => now()->format('Y-m-d'),
                'check_out' => now()->addDays(1)->format('Y-m-d'),
                'guests' => $validated['transfer_passengers'],
                'total_price' => $room->price,
                'status' => 'pending',
                'has_airport_transfer' => true,
                'airport_name' => $validated['airport_name'],
                'transfer_datetime' => $validated['transfer_datetime'],
                'transfer_type' => $validated['transfer_type'],
                'transfer_passengers' => $validated['transfer_passengers'],
                'transfer_price' => $transfer->price,
                'transfer_notes' => $validated['transfer_notes'],
                'transfer_status' => 'confirmed'
            ]);

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Đặt phòng với dịch vụ đưa đón sân bay đã được tạo thành công.');
        }
    }

    /**
     * Hiển thị chi tiết dịch vụ đưa đón
     */
    public function show(AirportTransfer $airportTransfer)
    {
        $transfers = AirportTransfer::where('is_active', true)
            ->where('id', '!=', $airportTransfer->id)
            ->orderBy('is_popular', 'desc')
            ->orderBy('price', 'asc')
            ->take(3)
            ->get();

        return view('airport-transfers.show', compact('airportTransfer', 'transfers'));
    }

    /**
     * Hủy dịch vụ đưa đón sân bay
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Kiểm tra quyền sở hữu
        if (Auth::id() !== $booking->user_id) {
            return redirect()->route('bookings.index')
                ->with('error', 'Bạn không có quyền hủy dịch vụ đưa đón này.');
        }

        // Kiểm tra xem đặt phòng có dịch vụ đưa đón không
        if (!$booking->has_airport_transfer) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Đặt phòng này không có dịch vụ đưa đón sân bay.');
        }

        // Cập nhật trạng thái
        $booking->update([
            'has_airport_transfer' => false,
            'transfer_status' => 'cancelled'
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Dịch vụ đưa đón sân bay đã được hủy thành công.');
    }
}
