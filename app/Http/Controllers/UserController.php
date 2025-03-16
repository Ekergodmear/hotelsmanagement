<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Hiển thị thông tin người dùng đã đăng nhập
     */
    public function profile()
    {
        // Lấy thông tin người dùng đã đăng nhập
        $user = Auth::user();

        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem thông tin cá nhân.');
        }

        return view('user.profile', compact('user'));
    }

    /**
     * Hiển thị form cập nhật thông tin người dùng
     */
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để cập nhật thông tin cá nhân.');
        }

        return view('user.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để cập nhật thông tin cá nhân.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Sử dụng DB::table thay vì phương thức update trên model
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

        return redirect()->route('user.profile')->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }
}
