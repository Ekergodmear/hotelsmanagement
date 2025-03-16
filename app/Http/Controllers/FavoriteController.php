<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Hiển thị danh sách phòng yêu thích của người dùng
     */
    public function index(): View
    {
        $favorites = Auth::user()->favorites()->with('room.hotel', 'room.roomType')->get();
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Thêm phòng vào danh sách yêu thích
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'room_id' => $validated['room_id'],
        ]);

        return back()->with('success', 'Đã thêm phòng vào danh sách yêu thích.');
    }

    /**
     * Xóa phòng khỏi danh sách yêu thích
     */
    public function destroy(Favorite $favorite): RedirectResponse
    {
        // Kiểm tra xem người dùng có quyền xóa mục yêu thích này không
        if ($favorite->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        $favorite->delete();
        return back()->with('success', 'Đã xóa phòng khỏi danh sách yêu thích.');
    }
}
