<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý loại phòng') }}
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
                <h3 class="text-lg font-semibold">Danh sách loại phòng</h3>
                <a href="{{ route('room-types.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Thêm loại phòng mới
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">ID</th>
                                    <th class="py-2 px-4 border-b text-left">Tên loại phòng</th>
                                    <th class="py-2 px-4 border-b text-left">Giá cơ bản</th>
                                    <th class="py-2 px-4 border-b text-left">Sức chứa</th>
                                    <th class="py-2 px-4 border-b text-left">Số phòng</th>
                                    <th class="py-2 px-4 border-b text-left">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roomTypes as $roomType)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $roomType->id }}</td>
                                    <td class="py-2 px-4 border-b">{{ $roomType->name }}</td>
                                    <td class="py-2 px-4 border-b">{{ number_format($roomType->base_price) }} VNĐ</td>
                                    <td class="py-2 px-4 border-b">{{ $roomType->capacity }} người</td>
                                    <td class="py-2 px-4 border-b">{{ $roomType->rooms->count() }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('room-types.show', $roomType) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                                                Chi tiết
                                            </a>
                                            <a href="{{ route('room-types.edit', $roomType) }}" class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200">
                                                Sửa
                                            </a>
                                            <form action="{{ route('room-types.destroy', $roomType) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại phòng này?');">
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
                                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">Không có loại phòng nào.</td>
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
