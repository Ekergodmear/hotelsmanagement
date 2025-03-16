<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Khởi tạo controller với middleware auth
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Lưu đánh giá mới vào cơ sở dữ liệu
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        // Thêm user_id vào dữ liệu
        $validated['user_id'] = Auth::id();

        // Tạo đánh giá mới
        Review::create($validated);

        // Cập nhật rating trung bình của khách sạn
        $hotel = Hotel::find($request->hotel_id);
        $avgRating = $hotel->reviews()->avg('rating');
        $hotel->update(['rating' => $avgRating]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
