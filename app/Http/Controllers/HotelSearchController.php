<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HotelSearchController extends Controller
{
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
                $q->where('province_city', 'like', '%' . $searchTerm . '%')
                  ->orWhere('district', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ward', 'like', '%' . $searchTerm . '%')
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
        if ($request->filled('star_rating')) {
            $ratings = $request->star_rating;
            if (is_array($ratings)) {
                $query->where(function($q) use ($ratings) {
                    foreach ($ratings as $rating) {
                        $q->orWhere(function($q2) use ($rating) {
                            $q2->where('rating', '>=', $rating)
                               ->where('rating', '<', $rating + 1);
                        });
                    }
                });
            } else {
                $query->where('rating', '>=', $ratings);
            }
        }

        // Lọc theo tiện nghi
        if ($request->filled('amenities')) {
            $amenities = $request->amenities;

            // Chuyển đổi thành mảng nếu không phải mảng
            if (!is_array($amenities)) {
                $amenities = [$amenities];
            }

            // Log thông tin tiện nghi đang tìm kiếm
            Log::info('Searching for amenities:', $amenities);

            // Tìm kiếm tiện nghi trong dữ liệu đã đơn giản hóa
            $query->where(function($q) use ($amenities) {
                foreach ($amenities as $amenity) {
                    // Tìm kiếm chính xác tiện nghi
                    $q->orWhereJsonContains('amenities', $amenity);
                }
            });

            // Ghi log truy vấn SQL
            DB::enableQueryLog();
        }

        // Lọc theo khoảng giá
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $minPrice = $request->price_min;
            $maxPrice = $request->price_max;

            $query->whereHas('rooms', function($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price', [$minPrice, $maxPrice])
                  ->orWhereHas('roomType', function($q2) use ($minPrice, $maxPrice) {
                      $q2->whereBetween('base_price', [$minPrice, $maxPrice]);
                  });
            });
        } elseif ($request->filled('price_min')) {
            $minPrice = $request->price_min;

            $query->whereHas('rooms', function($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice)
                  ->orWhereHas('roomType', function($q2) use ($minPrice) {
                      $q2->where('base_price', '>=', $minPrice);
                  });
            });
        } elseif ($request->filled('price_max')) {
            $maxPrice = $request->price_max;

            $query->whereHas('rooms', function($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice)
                  ->orWhereHas('roomType', function($q2) use ($maxPrice) {
                      $q2->where('base_price', '<=', $maxPrice);
                  });
            });
        }

        // Sắp xếp kết quả
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price_per_night', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price_per_night', 'desc');
                    break;
                case 'rating_desc':
                    $query->orderBy('rating', 'desc');
                    break;
                default:
                    $query->orderBy('rating', 'desc');
                    break;
            }
        } else {
            $query->orderBy('rating', 'desc');
        }

        // Debug SQL query
        $hotels = $query->paginate(10)->withQueryString();
        $queries = DB::getQueryLog();

        // Lưu query vào log file
        Log::info('Hotel Search Query:', $queries);

        return view('hotels.search', [
            'hotels' => $hotels,
            'search' => $request->all()
        ]);
    }

    /**
     * Debug dữ liệu khách sạn
     */
    public function debug()
    {
        $hotels = Hotel::with(['rooms.roomType', 'images'])->limit(10)->get();

        // Lấy thông tin chi tiết về cấu trúc dữ liệu tiện nghi
        $amenitiesInfo = [];
        foreach ($hotels as $hotel) {
            $amenitiesInfo[] = [
                'id' => $hotel->id,
                'name' => $hotel->name,
                'amenities_raw' => $hotel->getRawOriginal('amenities'),
                'amenities_cast' => $hotel->amenities,
                'amenities_type' => gettype($hotel->amenities),
                'amenities_json' => json_encode($hotel->amenities)
            ];
        }

        // Ghi log thông tin tiện nghi
        Log::info('Hotel amenities debug info:', $amenitiesInfo);

        return view('hotels.debug', [
            'hotels' => $hotels,
            'amenitiesInfo' => $amenitiesInfo
        ]);
    }
}
