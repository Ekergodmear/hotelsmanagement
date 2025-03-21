<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Hotel;
use App\Models\HotelImage;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::with(['images', 'primaryImage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Đảm bảo hiển thị thông tin quận/huyện
        foreach ($hotels as $hotel) {
            if (empty($hotel->district)) {
                // Nếu district trống, thử lấy từ bảng districts nếu có id
                if (is_numeric($hotel->district)) {
                    $district = \App\Models\District::find($hotel->district);
                    if ($district) {
                        $hotel->district = $district->name;
                    }
                }
            }
        }

        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('admin.hotels.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id' => 'required|exists:wards,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|in:wifi,pool,restaurant,spa,gym,parking',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Lấy tên tỉnh/thành phố, quận/huyện, phường/xã
        $province = Province::find($validated['province_id']);
        $district = District::find($validated['district_id']);
        $ward = Ward::find($validated['ward_id']);

        // Tạo khách sạn với dữ liệu cơ bản
        $hotel = new Hotel();
        $hotel->name = $validated['name'];
        $hotel->address = $validated['address'];
        $hotel->province_city = $province->name;
        $hotel->district = $district->name;
        $hotel->ward = $ward->name;
        $hotel->country = 'Việt Nam'; // Mặc định là Việt Nam
        $hotel->phone = $validated['phone'] ?? null;
        $hotel->email = $validated['email'] ?? null;
        $hotel->description = $validated['description'] ?? null;
        $hotel->star_rating = $validated['star_rating'] ?? null;
        $hotel->amenities = isset($validated['amenities']) ? json_encode($validated['amenities']) : null;
        $hotel->is_featured = $validated['is_featured'] ?? false;
        $hotel->is_active = $validated['is_active'] ?? true;
        $hotel->save();

        // Xử lý ảnh chính
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('hotels', 'public');

            HotelImage::create([
                'hotel_id' => $hotel->id,
                'image_path' => $path,
                'is_primary' => true
            ]);
        }

        // Xử lý bộ sưu tập ảnh
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('hotels', 'public');

                HotelImage::create([
                    'hotel_id' => $hotel->id,
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Khách sạn đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hotel = Hotel::with(['rooms', 'images'])->findOrFail($id);
        return view('admin.hotels.show', compact('hotel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hotel = Hotel::with('images')->findOrFail($id);
        $provinces = Province::orderBy('name')->get();

        // Tìm province_id dựa trên tên tỉnh/thành phố
        $selectedProvince = Province::where('name', $hotel->province_city)->first();
        $selectedProvinceId = $selectedProvince ? $selectedProvince->id : null;

        // Lấy danh sách quận/huyện của tỉnh/thành phố đã chọn
        $districts = [];
        if ($selectedProvinceId) {
            $districts = District::where('province_id', $selectedProvinceId)->orderBy('name')->get();
        }

        // Tìm district_id dựa trên tên quận/huyện
        $selectedDistrict = District::where('name', $hotel->district)->first();
        $selectedDistrictId = $selectedDistrict ? $selectedDistrict->id : null;

        // Lấy danh sách phường/xã của quận/huyện đã chọn
        $wards = [];
        if ($selectedDistrictId) {
            $wards = Ward::where('district_id', $selectedDistrictId)->orderBy('name')->get();
        }

        // Tìm ward_id dựa trên tên phường/xã
        $selectedWardId = null;
        if ($hotel->ward) {
            $selectedWard = Ward::where('name', $hotel->ward)->first();
            $selectedWardId = $selectedWard ? $selectedWard->id : null;
        }

        return view('admin.hotels.edit', compact('hotel', 'provinces', 'districts', 'wards', 'selectedProvinceId', 'selectedDistrictId', 'selectedWardId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'province_city' => 'required|exists:provinces,id',
            'district' => 'required|exists:districts,id',
            'ward' => 'nullable|exists:wards,id',
            'description' => 'required|string',
            'rating' => 'nullable|numeric|min:0|max:10',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|in:wifi,pool,restaurant,spa,gym,parking',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if (isset($validated['amenities'])) {
            $validated['amenities'] = json_encode($validated['amenities']);
        }

        // Lấy tên tỉnh/thành phố, quận/huyện, phường/xã
        $province = Province::find($validated['province_city']);
        $district = District::find($validated['district']);
        $ward = null;
        if (!empty($validated['ward'])) {
            $ward = Ward::find($validated['ward']);
        }

        // Gán giá trị tên thay vì ID
        $validated['province_city'] = $province->name;
        $validated['district'] = $district->name;
        $validated['ward'] = $ward ? $ward->name : null;

        $hotel->update($validated);

        // Xử lý tải lên nhiều ảnh
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ
            foreach ($hotel->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // Tải lên ảnh mới
            $this->uploadHotelImages($request->file('images'), $hotel);
        }

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Thông tin khách sạn đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hotel = Hotel::findOrFail($id);

        // Kiểm tra xem khách sạn có phòng nào không
        if ($hotel->rooms()->exists()) {
            return back()->with('error', 'Không thể xóa khách sạn vì có phòng thuộc khách sạn này.');
        }

        // Xóa ảnh nếu có
        foreach ($hotel->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $hotel->delete();

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Khách sạn đã được xóa thành công.');
    }

    /**
     * Lấy danh sách quận/huyện theo tỉnh/thành phố
     */
    public function getDistricts(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id'
        ]);

        $districts = District::where('province_id', $request->province_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id'
        ]);

        $wards = Ward::where('district_id', $request->district_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($wards);
    }

    /**
     * Tải lên nhiều ảnh cho khách sạn
     */
    private function uploadHotelImages($images, $hotel)
    {
        $maxImages = 5;
        $count = 0;

        foreach ($images as $image) {
            if ($count >= $maxImages) {
                break;
            }

            // Lưu ảnh vào storage
            $imagePath = $image->store('hotels', 'public');

            HotelImage::create([
                'hotel_id' => $hotel->id,
                'image_path' => $imagePath,
                'is_primary' => $count === 0, // Ảnh đầu tiên là ảnh chính
                'display_order' => $count + 1,
            ]);

            $count++;
        }
    }
}
