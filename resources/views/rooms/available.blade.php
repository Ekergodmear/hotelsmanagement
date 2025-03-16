<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kiểm tra phòng trống') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Tìm kiếm phòng trống</h3>
                    <form action="{{ route('rooms.available') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Ngày nhận phòng</label>
                            <input type="date" name="check_in" id="check_in" value="{{ request('check_in', date('Y-m-d')) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Ngày trả phòng</label>
                            <input type="date" name="check_out" id="check_out" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($availableRooms))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Phòng trống từ {{ date('d/m/Y', strtotime($checkIn)) }} đến {{ date('d/m/Y', strtotime($checkOut)) }}</h3>

                    @if($availableRooms->isEmpty())
                        <p class="text-gray-500">Không có phòng trống trong khoảng thời gian này.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($availableRooms as $room)
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-100 p-4">
                                    <h4 class="font-semibold">{{ $room->room_number }} - {{ $room->roomType->name }}</h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Loại phòng:</span> {{ $room->roomType->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Sức chứa:</span> {{ $room->roomType->capacity }} người
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="font-semibold">Giá:</span> {{ number_format($room->roomType->base_price) }} VNĐ/đêm
                                    </p>
                                    <p class="text-sm text-gray-600 mb-4">
                                        <span class="font-semibold">Mô tả:</span> {{ $room->roomType->description }}
                                    </p>

                                    <a href="{{ route('bookings.create', ['room_id' => $room->id, 'check_in' => $checkIn, 'check_out' => $checkOut]) }}"
                                        class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Đặt phòng
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</x-hotel-layout>
