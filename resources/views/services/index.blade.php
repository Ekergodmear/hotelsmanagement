<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý dịch vụ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Danh sách dịch vụ</h3>
                <a href="{{ route('services.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Thêm dịch vụ mới
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">ID</th>
                                    <th class="py-2 px-4 border-b text-left">Tên dịch vụ</th>
                                    <th class="py-2 px-4 border-b text-left">Loại</th>
                                    <th class="py-2 px-4 border-b text-left">Giá</th>
                                    <th class="py-2 px-4 border-b text-left">Trạng thái</th>
                                    <th class="py-2 px-4 border-b text-left">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $service->id }}</td>
                                    <td class="py-2 px-4 border-b">{{ $service->name }}</td>
                                    <td class="py-2 px-4 border-b">
                                        @if($service->type == 'room_service')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Dịch vụ phòng</span>
                                        @elseif($service->type == 'food')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Đồ ăn</span>
                                        @elseif($service->type == 'laundry')
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Giặt là</span>
                                        @elseif($service->type == 'transport')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Vận chuyển</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Khác</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b">{{ number_format($service->price) }} VNĐ</td>
                                    <td class="py-2 px-4 border-b">
                                        @if($service->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Hoạt động</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('services.show', $service) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                                Chi tiết
                                            </a>
                                            <a href="{{ route('services.edit', $service) }}" class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200">
                                                Sửa
                                            </a>
                                            <form action="{{ route('services.destroy', $service) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?');">
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
                                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">Không có dịch vụ nào.</td>
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
