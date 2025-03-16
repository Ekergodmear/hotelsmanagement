<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HotelSearchController;
use App\Http\Controllers\AirportTransferController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;

// Routes công khai (không cần đăng nhập)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes cho xem và tìm kiếm khách sạn (công khai)
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/search', [App\Http\Controllers\HotelSearchController::class, 'search'])->name('hotels.search');
Route::get('/hotels/debug', [App\Http\Controllers\HotelSearchController::class, 'debug'])->name('hotels.debug');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

// Routes cho xem phòng (công khai)
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');
Route::get('/rooms-available', [RoomController::class, 'availableRooms'])->name('rooms.available');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// Routes cho xem loại phòng (công khai)
Route::get('/room-types', [RoomTypeController::class, 'index'])->name('room-types.index');
Route::get('/room-types/{roomType}', [RoomTypeController::class, 'show'])->name('room-types.show');

// Routes cho xem đặt phòng (công khai)
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

// Routes cho xem người dùng (công khai)
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Routes cho xem dịch vụ (công khai)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Route cho settings
Route::get('/settings', function() {
    return view('settings.index');
})->name('settings.index');

Route::put('/settings', function() {
    return redirect()->route('settings.index')->with('success', 'Cài đặt đã được cập nhật thành công');
})->name('settings.update');

Route::put('/settings/email', function() {
    return redirect()->route('settings.index')->with('success', 'Cài đặt email đã được cập nhật thành công');
})->name('settings.email');

Route::put('/settings/security', function() {
    return redirect()->route('settings.index')->with('success', 'Cài đặt bảo mật đã được cập nhật thành công');
})->name('settings.security');

// Route cho users
Route::get('/users/create', function() {
    return view('users.create');
})->name('users.create');

Route::post('/users', function() {
    return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công');
})->name('users.store');

Route::get('/users/{user}/edit', function($id) {
    $user = \App\Models\User::findOrFail($id);
    return view('users.edit', compact('user'));
})->name('users.edit');

Route::put('/users/{user}', function($id) {
    return redirect()->route('users.index')->with('success', 'Người dùng đã được cập nhật thành công');
})->name('users.update');

Route::delete('/users/{user}', function($id) {
    return redirect()->route('users.index')->with('success', 'Người dùng đã được xóa thành công');
})->name('users.destroy');

// Routes yêu cầu đăng nhập (người dùng đã xác thực)
Route::middleware(['auth'])->group(function () {
    // Quản lý dịch vụ đặt phòng
    Route::post('/booking-services', [BookingServiceController::class, 'store'])->name('booking-services.store');
    Route::delete('/booking-services/{bookingService}', [BookingServiceController::class, 'destroy'])->name('booking-services.destroy');

    // Dashboard - Chỉ dành cho admin
    Route::middleware(['admin'])->group(function () {
        // Đã xóa route /dashboard ở đây
    });

    // Quản lý profile
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Đánh giá khách sạn
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Quản lý khách sạn
    Route::get('/hotels/create', [HotelController::class, 'create'])->name('hotels.create');
    Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
    Route::get('/hotels/{hotel}/edit', [HotelController::class, 'edit'])->name('hotels.edit');
    Route::put('/hotels/{hotel}', [HotelController::class, 'update'])->name('hotels.update');
    Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy'])->name('hotels.destroy');

    // Đặt phòng
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{booking}/success', [BookingController::class, 'success'])->name('bookings.success');

    // Yêu thích phòng
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // User profile routes
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/profile/update', [UserController::class, 'update'])->name('user.update');
});

// Routes dành cho admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Quản lý khách sạn
    Route::get('hotels/get-districts', [AdminHotelController::class, 'getDistricts'])->name('admin.hotels.getDistricts');
    Route::get('hotels/get-wards', [AdminHotelController::class, 'getWards'])->name('admin.hotels.getWards');
    Route::resource('hotels', AdminHotelController::class)->names([
        'index' => 'admin.hotels.index',
        'create' => 'admin.hotels.create',
        'store' => 'admin.hotels.store',
        'show' => 'admin.hotels.show',
        'edit' => 'admin.hotels.edit',
        'update' => 'admin.hotels.update',
        'destroy' => 'admin.hotels.destroy',
    ]);

    // Quản lý phòng
    Route::resource('rooms', AdminRoomController::class)->names([
        'index' => 'admin.rooms.index',
        'create' => 'admin.rooms.create',
        'store' => 'admin.rooms.store',
        'show' => 'admin.rooms.show',
        'edit' => 'admin.rooms.edit',
        'update' => 'admin.rooms.update',
        'destroy' => 'admin.rooms.destroy',
    ]);
    Route::get('hotels/{hotel}/rooms', [AdminRoomController::class, 'byHotel'])->name('admin.hotels.rooms');

    // Quản lý loại phòng
    Route::resource('room-types', App\Http\Controllers\Admin\RoomTypeController::class)->names([
        'index' => 'admin.room-types.index',
        'create' => 'admin.room-types.create',
        'store' => 'admin.room-types.store',
        'show' => 'admin.room-types.show',
        'edit' => 'admin.room-types.edit',
        'update' => 'admin.room-types.update',
        'destroy' => 'admin.room-types.destroy',
    ]);

    // Quản lý đặt phòng
    Route::resource('bookings', AdminBookingController::class)->names([
        'index' => 'admin.bookings.index',
        'create' => 'admin.bookings.create',
        'store' => 'admin.bookings.store',
        'show' => 'admin.bookings.show',
        'edit' => 'admin.bookings.edit',
        'update' => 'admin.bookings.update',
        'destroy' => 'admin.bookings.destroy',
    ]);
    Route::patch('bookings/{booking}/check-in', [AdminBookingController::class, 'checkIn'])->name('admin.bookings.check-in');
    Route::patch('bookings/{booking}/check-out', [AdminBookingController::class, 'checkOut'])->name('admin.bookings.check-out');

    // Quản lý dịch vụ
    Route::resource('services', AdminServiceController::class);

    // Quản lý người dùng
    Route::resource('users', AdminUserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Quản lý khách hàng
    Route::resource('customers', CustomerController::class)->names([
        'index' => 'admin.customers.index',
        'show' => 'admin.customers.show',
        'edit' => 'admin.customers.edit',
        'update' => 'admin.customers.update',
    ])->except(['create', 'store', 'destroy']);

    // Admin Airport Transfer Routes
    Route::resource('airport-transfers', App\Http\Controllers\Admin\AirportTransferController::class);
});

// Debug routes
Route::get('/debug/hotels', [HotelSearchController::class, 'debug'])->name('debug.hotels');
Route::get('/debug/amenities', function() {
    $hotels = \App\Models\Hotel::limit(5)->get();
    $result = [];

    foreach ($hotels as $hotel) {
        $result[] = [
            'id' => $hotel->id,
            'name' => $hotel->name,
            'amenities_raw' => $hotel->getRawOriginal('amenities'),
            'amenities_cast' => $hotel->amenities,
            'amenities_type' => gettype($hotel->amenities)
        ];
    }

    return response()->json($result);
});

// Airport Transfer Routes
Route::get('/airport-transfers', [App\Http\Controllers\AirportTransferController::class, 'index'])->name('airport-transfers.index');
Route::get('/airport-transfers/create', [App\Http\Controllers\AirportTransferController::class, 'create'])->name('airport-transfers.create');
Route::post('/airport-transfers', [App\Http\Controllers\AirportTransferController::class, 'store'])->name('airport-transfers.store');
Route::get('/airport-transfers/{airportTransfer}', [App\Http\Controllers\AirportTransferController::class, 'show'])->name('airport-transfers.show');
Route::post('/airport-transfers/cancel/{booking}', [App\Http\Controllers\AirportTransferController::class, 'cancel'])->name('airport-transfers.cancel')->middleware('auth');

require __DIR__.'/auth.php';
