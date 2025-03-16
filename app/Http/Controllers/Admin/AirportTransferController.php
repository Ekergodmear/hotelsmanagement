<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AirportTransfer;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AirportTransferController extends Controller
{
    /**
     * Hiển thị danh sách dịch vụ đưa đón sân bay
     */
    public function index()
    {
        $transfers = AirportTransfer::orderBy('created_at', 'desc')->get();

        return view('admin.airport-transfers.index', compact('transfers'));
    }

    /**
     * Hiển thị form tạo dịch vụ đưa đón mới
     */
    public function create()
    {
        return view('admin.airport-transfers.create');
    }

    /**
     * Lưu dịch vụ đưa đón mới vào database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:100|unique:airport_transfers',
            'max_passengers' => 'required|integer|min:1',
            'max_luggage' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('transfers', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');

        AirportTransfer::create($validated);

        return redirect()->route('admin.airport-transfers.index')
            ->with('success', 'Dịch vụ đưa đón sân bay đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết dịch vụ đưa đón
     */
    public function show(AirportTransfer $airportTransfer)
    {
        $bookings = Booking::where('transfer_type', $airportTransfer->vehicle_type)
            ->where('has_airport_transfer', true)
            ->orderBy('transfer_datetime', 'desc')
            ->paginate(10);

        return view('admin.airport-transfers.show', compact('airportTransfer', 'bookings'));
    }

    /**
     * Hiển thị form chỉnh sửa dịch vụ đưa đón
     */
    public function edit(AirportTransfer $airportTransfer)
    {
        return view('admin.airport-transfers.edit', compact('airportTransfer'));
    }

    /**
     * Cập nhật dịch vụ đưa đón trong database
     */
    public function update(Request $request, AirportTransfer $airportTransfer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_type' => [
                'required',
                'string',
                'max:100',
                Rule::unique('airport_transfers')->ignore($airportTransfer->id)
            ],
            'max_passengers' => 'required|integer|min:1',
            'max_luggage' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($airportTransfer->image_path) {
                Storage::disk('public')->delete($airportTransfer->image_path);
            }

            $path = $request->file('image')->store('transfers', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');

        $airportTransfer->update($validated);

        return redirect()->route('admin.airport-transfers.index')
            ->with('success', 'Dịch vụ đưa đón sân bay đã được cập nhật thành công.');
    }

    /**
     * Xóa dịch vụ đưa đón khỏi database
     */
    public function destroy(AirportTransfer $airportTransfer)
    {
        // Kiểm tra xem có đặt phòng nào đang sử dụng dịch vụ này không
        $bookingsCount = Booking::where('transfer_type', $airportTransfer->vehicle_type)
            ->where('has_airport_transfer', true)
            ->count();

        if ($bookingsCount > 0) {
            return redirect()->route('admin.airport-transfers.index')
                ->with('error', 'Không thể xóa dịch vụ đưa đón này vì đang được sử dụng trong ' . $bookingsCount . ' đặt phòng.');
        }

        // Xóa ảnh nếu có
        if ($airportTransfer->image_path) {
            Storage::disk('public')->delete($airportTransfer->image_path);
        }

        $airportTransfer->delete();

        return redirect()->route('admin.airport-transfers.index')
            ->with('success', 'Dịch vụ đưa đón sân bay đã được xóa thành công.');
    }
}
