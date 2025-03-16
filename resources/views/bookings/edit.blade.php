<x-hotel-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa đặt phòng') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Chỉnh sửa đặt phòng #{{ $booking->id }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('bookings.show', $booking) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Quay lại
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium mb-4">Thông tin khách hàng</h4>

                                <div class="mb-4">
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Khách hàng</label>
                                    <select id="user_id" name="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn khách hàng</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium mb-4">Thông tin phòng</h4>

                                <div class="mb-4">
                                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Phòng</label>
                                    <select id="room_id" name="room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Chọn phòng</option>
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                                                {{ $room->room_number }} - {{ $room->roomType->name }} ({{ number_format($room->roomType->base_price) }} VNĐ/đêm)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('room_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <h4 class="font-medium mb-4">Thời gian đặt phòng</h4>

                                <div class="mb-4">
                                    <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Ngày check-in</label>
                                    <input type="date" id="check_in" name="check_in" value="{{ old('check_in') ?? $booking->check_in->format('Y-m-d') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @error('check_in')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Ngày check-out</label>
                                    <input type="date" id="check_out" name="check_out" value="{{ old('check_out') ?? $booking->check_out->format('Y-m-d') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @error('check_out')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <h4 class="font-medium mb-4">Thông tin khách</h4>

                                <div class="mb-4">
                                    <label for="adults" class="block text-sm font-medium text-gray-700 mb-1">Số người lớn</label>
                                    <input type="number" id="adults" name="adults" min="1" value="{{ old('adults') ?? $booking->adults }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    @error('adults')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="children" class="block text-sm font-medium text-gray-700 mb-1">Số trẻ em</label>
                                    <input type="number" id="children" name="children" min="0" value="{{ old('children') ?? $booking->children }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('children')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-medium mb-4">Trạng thái đặt phòng</h4>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                                <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="checked_in" {{ $booking->status == 'checked_in' ? 'selected' : '' }}>Đã nhận phòng</option>
                                    <option value="checked_out" {{ $booking->status == 'checked_out' ? 'selected' : '' }}>Đã trả phòng</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-medium mb-4">Yêu cầu đặc biệt</h4>

                            <div class="mb-4">
                                <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-1">Yêu cầu đặc biệt</label>
                                <textarea id="special_requests" name="special_requests" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('special_requests') ?? $booking->special_requests }}</textarea>
                                @error('special_requests')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Cập nhật đặt phòng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-hotel-layout>
