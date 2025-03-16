<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tạo đặt phòng mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Thông tin đặt phòng</h3>
                <a href="{{ route('bookings.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Quay lại
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium mb-4">Thông tin khách hàng</h4>

                                <div class="mb-4">
                                    <label for="guest_id" class="block text-sm font-medium text-gray-700 mb-1">Khách hàng</label>
                                    <select id="guest_id" name="guest_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn khách hàng</option>
                                        @foreach($guests as $guest)
                                            <option value="{{ $guest->id }}">{{ $guest->full_name }} ({{ $guest->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('guest_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="flex justify-between items-center">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Không tìm thấy khách hàng?</label>
                                        <a href="{{ route('guests.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                            Tạo khách hàng mới
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium mb-4">Thông tin phòng</h4>

                                <div class="mb-4">
                                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Phòng</label>
                                    <select id="room_id" name="room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn phòng</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }}>
                                                {{ $room->room_number }} - {{ $room->roomType->name }} ({{ number_format($room->roomType->base_price) }} VNĐ/đêm)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="flex justify-between items-center">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Không tìm thấy phòng trống?</label>
                                        <a href="{{ route('rooms.available') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                            Kiểm tra phòng trống
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <h4 class="font-medium mb-4">Thời gian đặt phòng</h4>

                                <div class="mb-4">
                                    <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Ngày check-in</label>
                                    <input type="date" id="check_in" name="check_in" value="{{ $checkIn ?? old('check_in') ?? date('Y-m-d') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @error('check_in')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Ngày check-out</label>
                                    <input type="date" id="check_out" name="check_out" value="{{ $checkOut ?? old('check_out') ?? date('Y-m-d', strtotime('+1 day')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @error('check_out')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium mb-4">Thông tin khách</h4>

                                <div class="mb-4">
                                    <label for="adults" class="block text-sm font-medium text-gray-700 mb-1">Số người lớn</label>
                                    <input type="number" id="adults" name="adults" min="1" value="{{ old('adults') ?? 1 }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @error('adults')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="children" class="block text-sm font-medium text-gray-700 mb-1">Số trẻ em</label>
                                    <input type="number" id="children" name="children" min="0" value="{{ old('children') ?? 0 }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('children')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-medium mb-4">Yêu cầu đặc biệt</h4>

                            <div class="mb-4">
                                <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-1">Yêu cầu đặc biệt</label>
                                <textarea id="special_requests" name="special_requests" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Tạo đặt phòng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-hotel-layout>
