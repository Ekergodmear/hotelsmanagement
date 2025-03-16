@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Chi tiết người dùng</h1>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thông tin người dùng #{{ $user->id }}</h5>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6">
                    <h4 class="font-medium mb-4">Thông tin cá nhân</h4>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3498db&color=fff&size=128"
                                 alt="{{ $user->name }}" class="rounded-circle me-3" style="width: 128px; height: 128px;">
                            <div>
                                <h3>{{ $user->name }}</h3>
                                <p class="text-muted">{{ $user->role == 'admin' ? 'Quản trị viên' : 'Người dùng' }}</p>
                            </div>
                        </div>
                        <p><span class="font-medium">Họ tên:</span> {{ $user->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
                        <p><span class="font-medium">Điện thoại:</span> {{ $user->phone ?? 'N/A' }}</p>
                        <p><span class="font-medium">Địa chỉ:</span> {{ $user->address ?? 'N/A' }}</p>
                    </div>

                    <h4 class="font-medium mb-4">Thông tin khác</h4>
                    <div class="mb-4">
                        <p><span class="font-medium">Vai trò:</span> {{ $user->role == 'admin' ? 'Quản trị viên' : 'Người dùng' }}</p>
                        <p><span class="font-medium">Ngày tạo:</span> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                        <p><span class="font-medium">Cập nhật lần cuối:</span> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h4 class="font-medium mb-4">Lịch sử đặt phòng</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">ID</th>
                                        <th class="py-2 px-4 border-b text-left">Phòng</th>
                                        <th class="py-2 px-4 border-b text-left">Check-in</th>
                                        <th class="py-2 px-4 border-b text-left">Check-out</th>
                                        <th class="py-2 px-4 border-b text-left">Trạng thái</th>
                                        <th class="py-2 px-4 border-b text-left">Tổng tiền</th>
                                        <th class="py-2 px-4 border-b text-left">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->bookings as $booking)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $booking->id }}</td>
                                        <td class="py-2 px-4 border-b">{{ $booking->room->room_number ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $booking->check_in ? $booking->check_in->format('d/m/Y') : 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $booking->check_out ? $booking->check_out->format('d/m/Y') : 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">
                                            @if($booking->status == 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Chờ xác nhận</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Đã xác nhận</span>
                                            @elseif($booking->status == 'checked_in')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Đã nhận phòng</span>
                                            @elseif($booking->status == 'checked_out')
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Đã trả phòng</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ number_format($booking->total_price) }} VNĐ</td>
                                        <td class="py-2 px-4 border-b">
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                                Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-4 px-4 text-center text-gray-500">Người dùng chưa có đặt phòng nào.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Xóa người dùng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
