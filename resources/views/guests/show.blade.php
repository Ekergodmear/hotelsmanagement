<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết khách hàng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Thông tin khách hàng: {{ $guest->full_name }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.guests.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Quay lại
                    </a>
                    <a href="{{ route('admin.guests.edit', $guest) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        Chỉnh sửa
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-4">Thông tin cá nhân</h4>
                            <div class="mb-4">
                                <p><span class="font-medium">Họ tên:</span> {{ $guest->full_name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $guest->email }}</p>
                                <p><span class="font-medium">Điện thoại:</span> {{ $guest->phone ?? 'N/A' }}</p>
                            </div>

                            <h4 class="font-medium mb-4">Địa chỉ</h4>
                            <div class="mb-4">
                                <p><span class="font-medium">Địa chỉ:</span> {{ $guest->address ?? 'N/A' }}</p>
                                <p><span class="font-medium">Thành phố:</span> {{ $guest->city ?? 'N/A' }}</p>
                                <p><span class="font-medium">Quốc gia:</span> {{ $guest->country ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium mb-4">Thông tin giấy tờ</h4>
                            <div class="mb-4">
                                <p><span class="font-medium">Loại giấy tờ:</span> {{ $guest->id_card_type ?? 'N/A' }}</p>
                                <p><span class="font-medium">Số giấy tờ:</span> {{ $guest->id_card_number ?? 'N/A' }}</p>
                            </div>

                            <h4 class="font-medium mb-4">Thông tin khác</h4>
                            <div class="mb-4">
                                <p><span class="font-medium">Ngày tạo:</span> {{ $guest->created_at->format('d/m/Y H:i') }}</p>
                                <p><span class="font-medium">Cập nhật lần cuối:</span> {{ $guest->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                                @forelse($guest->bookings as $booking)
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
                                    <td colspan="7" class="py-4 px-4 text-center text-gray-500">Khách hàng chưa có đặt phòng nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <form action="{{ route('admin.guests.destroy', $guest) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Xóa khách hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-hotel-layout>
