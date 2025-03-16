<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý đặt phòng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Danh sách đặt phòng</h3>
                <a href="{{ route('bookings.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Tạo đặt phòng mới
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">ID</th>
                                    <th class="py-2 px-4 border-b text-left">Khách hàng</th>
                                    <th class="py-2 px-4 border-b text-left">Phòng</th>
                                    <th class="py-2 px-4 border-b text-left">Check-in</th>
                                    <th class="py-2 px-4 border-b text-left">Check-out</th>
                                    <th class="py-2 px-4 border-b text-left">Trạng thái</th>
                                    <th class="py-2 px-4 border-b text-left">Tổng tiền</th>
                                    <th class="py-2 px-4 border-b text-left">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $booking->id }}</td>
                                    <td class="py-2 px-4 border-b">{{ $booking->user->name ?? 'N/A' }}</td>
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
                                        <div class="flex space-x-2">
                                            <a href="{{ route('bookings.show', $booking) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                                Chi tiết
                                            </a>
                                            <a href="{{ route('bookings.edit', $booking) }}" class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200">
                                                Sửa
                                            </a>
                                            @if($booking->status == 'confirmed')
                                                <form action="{{ route('admin.bookings.check-in', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200">
                                                        Check-in
                                                    </button>
                                                </form>
                                            @endif
                                            @if($booking->status == 'checked_in')
                                                <form action="{{ route('admin.bookings.check-out', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2 py-1 bg-purple-100 text-purple-700 rounded-md hover:bg-purple-200">
                                                        Check-out
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đặt phòng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="py-4 px-4 text-center text-gray-500">Không có đặt phòng nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-hotel-layout>
