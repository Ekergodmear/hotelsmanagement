<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chi tiết loại phòng') }}
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
                <h3 class="text-lg font-semibold">Thông tin loại phòng: {{ $roomType->name }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('room-types.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Quay lại
                    </a>
                    <a href="{{ route('room-types.edit', $roomType) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                        Chỉnh sửa
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium mb-4">Thông tin cơ bản</h4>
                            <div class="mb-4">
                                <p><span class="font-medium">Tên loại phòng:</span> {{ $roomType->name }}</p>
                                <p><span class="font-medium">Giá cơ bản:</span> {{ number_format($roomType->base_price) }} VNĐ</p>
                                <p><span class="font-medium">Sức chứa:</span> {{ $roomType->capacity }} người</p>
                            </div>

                            <h4 class="font-medium mb-4">Mô tả</h4>
                            <div class="mb-4">
                                <p>{{ $roomType->description ?? 'Không có mô tả' }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium mb-4">Thông tin khác</h4>
                            <div class="mb-4">
                                <p><span class="font-medium">Số lượng phòng:</span> {{ $roomType->rooms->count() }}</p>
                                <p><span class="font-medium">Ngày tạo:</span> {{ $roomType->created_at->format('d/m/Y H:i') }}</p>
                                <p><span class="font-medium">Cập nhật lần cuối:</span> {{ $roomType->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="font-medium mb-4">Danh sách phòng thuộc loại này</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Số phòng</th>
                                    <th class="py-2 px-4 border-b text-left">Trạng thái</th>
                                    <th class="py-2 px-4 border-b text-left">Ghi chú</th>
                                    <th class="py-2 px-4 border-b text-left">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roomType->rooms as $room)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $room->room_number }}</td>
                                    <td class="py-2 px-4 border-b">
                                        @if($room->status == 'available')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Trống</span>
                                        @elseif($room->status == 'occupied')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Đã đặt</span>
                                        @elseif($room->status == 'maintenance')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Bảo trì</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b">{{ $room->notes ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('rooms.show', $room) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 px-4 text-center text-gray-500">Không có phòng nào thuộc loại này.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <form action="{{ route('room-types.destroy', $roomType) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại phòng này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Xóa loại phòng
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-hotel-layout>
