<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    /**
     * Hiển thị danh sách tất cả khách sạn
     */
    public function index(): View
    {
        $hotels = Hotel::orderBy('rating', 'desc')->paginate(9);
        return view('hotels.index', compact('hotels'));
    }

    /**
     * Hiển thị form tạo khách sạn mới.
     */
    public function create(): View
    {
        return view('hotels.create');
    }

    /**
     * Lưu khách sạn mới vào cơ sở dữ liệu.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|in:wifi,pool,restaurant,spa,gym,parking',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hotels', 'public');
            $validated['image'] = $imagePath;
        }

        $hotel = Hotel::create($validated);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Khách sạn đã được thêm thành công.');
    }

    /**
     * Hiển thị thông tin chi tiết của một khách sạn
     */
    public function show(Hotel $hotel, Request $request): View
    {
        $hotel->load('rooms.roomType', 'rooms.bookings');
        $rooms = $hotel->rooms;

        // Lấy các khách sạn tương tự (cùng tỉnh/thành phố)
        $similarHotels = Hotel::where('id', '!=', $hotel->id)
            ->where(function($query) use ($hotel) {
                $query->where('province_city', 'like', '%' . $hotel->province_city . '%')
                    ->orWhere('district', 'like', '%' . $hotel->district . '%');
            })
            ->orderBy('rating', 'desc')
            ->limit(5)
            ->get();

        // Tạo lịch phòng trống cho 30 ngày tới
        $availabilityCalendar = [];
        $startDate = now();
        $endDate = now()->addDays(30);

        // Tạo mảng các ngày cần kiểm tra
        $datesToCheck = [];
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $datesToCheck[] = $dateString;
            $availabilityCalendar[$dateString] = [
                'date' => $currentDate->format('d/m/Y'),
                'day_of_week' => $currentDate->format('D'),
                'available_rooms' => 0,
                'total_rooms' => $rooms->count()
            ];
            $currentDate->addDay();
        }

        // Kiểm tra từng phòng xem có trống trong các ngày không
        foreach ($rooms as $room) {
            foreach ($datesToCheck as $date) {
                $isAvailable = true;
                foreach ($room->bookings as $booking) {
                    if ($booking->status === 'cancelled') {
                        continue;
                    }

                    $checkIn = \Carbon\Carbon::parse($booking->check_in);
                    $checkOut = \Carbon\Carbon::parse($booking->check_out);
                    $checkDate = \Carbon\Carbon::parse($date);

                    if ($checkDate >= $checkIn && $checkDate < $checkOut) {
                        $isAvailable = false;
                        break;
                    }
                }

                if ($isAvailable) {
                    $availabilityCalendar[$date]['available_rooms']++;
                }
            }
        }

        // Kiểm tra nếu có ngày check-in và check-out từ request
        $checkInDate = $request->filled('check_in') ? $request->check_in : null;
        $checkOutDate = $request->filled('check_out') ? $request->check_out : null;
        $guests = $request->filled('guests') ? $request->guests : 2;

        // Lọc phòng trống theo ngày check-in và check-out
        $availableRooms = null;
        if ($checkInDate && $checkOutDate) {
            $availableRooms = $this->getAvailableRooms($hotel, $checkInDate, $checkOutDate, $guests);
        }

        return view('hotels.show', compact(
            'hotel',
            'rooms',
            'similarHotels',
            'availabilityCalendar',
            'checkInDate',
            'checkOutDate',
            'guests',
            'availableRooms'
        ));
    }

    /**
     * Lấy danh sách phòng trống theo ngày check-in và check-out
     */
    private function getAvailableRooms(Hotel $hotel, $checkInDate, $checkOutDate, $guests = 1)
    {
        // Tạo mảng các ngày cần kiểm tra
        $datesToCheck = [];
        $currentDate = new \DateTime($checkInDate);
        $endDate = new \DateTime($checkOutDate);

        while ($currentDate < $endDate) {
            $datesToCheck[] = $currentDate->format('Y-m-d');
            $currentDate->modify('+1 day');
        }

        $availableRooms = [];

        foreach ($hotel->rooms as $room) {
            // Kiểm tra số lượng khách
            if ($room->roomType->capacity < $guests) {
                continue;
            }

            $isAvailable = true;

            // Kiểm tra từng ngày
            foreach ($datesToCheck as $date) {
                foreach ($room->bookings as $booking) {
                    if ($booking->status === 'cancelled') {
                        continue;
                    }

                    $bookingCheckIn = \Carbon\Carbon::parse($booking->check_in);
                    $bookingCheckOut = \Carbon\Carbon::parse($booking->check_out);
                    $checkDate = \Carbon\Carbon::parse($date);

                    if ($checkDate >= $bookingCheckIn && $checkDate < $bookingCheckOut) {
                        $isAvailable = false;
                        break 2; // Thoát cả 2 vòng lặp
                    }
                }
            }

            if ($isAvailable) {
                $availableRooms[] = $room;
            }
        }

        return $availableRooms;
    }

    /**
     * Hiển thị form chỉnh sửa khách sạn.
     */
    public function edit(Hotel $hotel): View
    {
        return view('hotels.edit', compact('hotel'));
    }

    /**
     * Cập nhật thông tin khách sạn trong cơ sở dữ liệu.
     */
    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:10',
            'price_per_night' => 'required|numeric|min:0',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|in:wifi,pool,restaurant,spa,gym,parking',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hotels', 'public');
            $validated['image'] = $imagePath;
        }

        $hotel->update($validated);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Thông tin khách sạn đã được cập nhật thành công.');
    }

    /**
     * Xóa khách sạn khỏi cơ sở dữ liệu.
     */
    public function destroy(Hotel $hotel): RedirectResponse
    {
        $hotel->delete();
        return redirect()->route('hotels.index')
            ->with('success', 'Khách sạn đã được xóa thành công.');
    }

    /**
     * Tìm kiếm khách sạn theo các tiêu chí
     */
    public function search(Request $request): View
    {
        $query = Hotel::with(['rooms.roomType', 'images']);

        // Tìm theo thành phố hoặc tên khách sạn
        if ($request->filled('city')) {
            $searchTerm = $request->city;
            $query->where(function($q) use ($searchTerm) {
                $q->where('city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lọc theo số lượng khách
        if ($request->filled('guests')) {
            $guests = $request->guests;
            $query->whereHas('rooms.roomType', function($q) use ($guests) {
                $q->where('capacity', '>=', $guests);
            });
        }

        // Lọc theo ngày check-in và check-out
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            $query->whereHas('rooms', function($q) use ($checkIn, $checkOut) {
                $q->whereDoesntHave('bookings', function($q2) use ($checkIn, $checkOut) {
                    $q2->where(function($q3) use ($checkIn, $checkOut) {
                        $q3->whereBetween('check_in', [$checkIn, $checkOut])
                           ->orWhereBetween('check_out', [$checkIn, $checkOut])
                           ->orWhere(function($q4) use ($checkIn, $checkOut) {
                               $q4->where('check_in', '<=', $checkIn)
                                  ->where('check_out', '>=', $checkOut);
                           });
                    })
                    ->whereIn('status', ['pending', 'confirmed', 'checked_in']);
                });
            });
        }

        // Lọc theo xếp hạng sao
        if ($request->has('star_rating')) {
            $ratings = $request->star_rating;
            $query->where(function($q) use ($ratings) {
                foreach ($ratings as $rating) {
                    $q->orWhere(function($q2) use ($rating) {
                        $q2->where('rating', '>=', $rating)
                           ->where('rating', '<', $rating + 1);
                    });
                }
            });
        }

        // Lọc theo tiện nghi
        if ($request->filled('amenities')) {
            $amenities = $request->amenities;
            if (is_array($amenities)) {
                $query->where(function($q) use ($amenities) {
                    foreach ($amenities as $amenity) {
                        // Sử dụng LIKE để tìm kiếm tiện nghi một cách linh hoạt hơn
                        $q->where('amenities', 'like', '%' . $amenity . '%');
                    }
                });
            } else {
                $query->where('amenities', 'like', '%' . $amenities . '%');
            }
        }

        $hotels = $query->orderBy('rating', 'desc')->get();

        return view('hotels.search', [
            'hotels' => $hotels,
            'search' => $request->all()
        ]);
    }
}
