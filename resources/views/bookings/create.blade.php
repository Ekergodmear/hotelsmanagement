@extends('layouts.app')

@section('content')
    <div class="py-10 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Trang chủ
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500">Đặt phòng</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-8">
                    <!-- Tiêu đề -->
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Đặt phòng</h1>
                        <p class="mt-2 text-gray-600">Vui lòng điền thông tin để hoàn tất đặt phòng của bạn</p>
                    </div>

                    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-8" data-room-price="{{ $rooms->firstWhere('id', $roomId)->price }}">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $roomId }}">

                        <!-- Thông tin phòng -->
                        <div class="bg-blue-50 rounded-lg p-6 mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                                <i class="fas fa-bed text-blue-600 mr-2"></i>
                                Thông tin phòng đã chọn
                            </h2>
                            @foreach($rooms as $room)
                                @if($room->id == $roomId)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        @if($room->primaryImage)
                                            <img src="{{ Storage::url($room->primaryImage->image_path) }}"
                                                alt="{{ $room->room_number }}"
                                                class="w-full h-48 object-cover rounded-lg">
                                        @endif
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <span class="text-gray-600">Số phòng:</span>
                                            <span class="font-semibold">{{ $room->room_number }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Loại phòng:</span>
                                            <span class="font-semibold">{{ $room->roomType->name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Giá:</span>
                                            <span class="font-semibold text-blue-600">{{ number_format($room->price, 0, ',', '.') }} VNĐ/đêm</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Sức chứa:</span>
                                            <span class="font-semibold">{{ $room->roomType->capacity }} người</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Thông tin đặt phòng -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Thông tin người đặt -->
                            <div class="bg-white rounded-lg border p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-user text-blue-600 mr-2"></i>
                                    Thông tin người đặt
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                                        <input type="text" id="name" name="name"
                                            value="{{ auth()->user()->name ?? old('name') }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            required>
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" id="email" name="email"
                                            value="{{ auth()->user()->email ?? old('email') }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            required>
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                                        <input type="tel" id="phone" name="phone"
                                            value="{{ auth()->user()->phone ?? old('phone') }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            required>
                                        @error('phone')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Thời gian -->
                            <div class="bg-white rounded-lg border p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                    Thời gian
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1">Ngày nhận phòng</label>
                                        <input type="date" id="check_in" name="check_in"
                                            value="{{ $checkIn ?? old('check_in') ?? date('Y-m-d') }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            required>
                                        @error('check_in')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1">Ngày trả phòng</label>
                                        <input type="date" id="check_out" name="check_out"
                                            value="{{ $checkOut ?? old('check_out') ?? date('Y-m-d', strtotime('+1 day')) }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                            required>
                                        @error('check_out')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Số lượng khách -->
                            <div class="bg-white rounded-lg border p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-users text-blue-600 mr-2"></i>
                                    Số lượng khách
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="adults" class="block text-sm font-medium text-gray-700 mb-1">Người lớn</label>
                                        <div class="flex items-center">
                                            <button type="button" onclick="decrementAdults()" class="px-3 py-1 border rounded-l bg-gray-100">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="adults" name="adults" min="1" max="10"
                                                value="{{ old('adults') ?? 2 }}"
                                                class="w-20 text-center border-t border-b"
                                                readonly>
                                            <button type="button" onclick="incrementAdults()" class="px-3 py-1 border rounded-r bg-gray-100">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        @error('adults')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="children" class="block text-sm font-medium text-gray-700 mb-1">Trẻ em (dưới 12 tuổi)</label>
                                        <div class="flex items-center">
                                            <button type="button" onclick="decrementChildren()" class="px-3 py-1 border rounded-l bg-gray-100">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="children" name="children" min="0" max="5"
                                                value="{{ old('children') ?? 0 }}"
                                                class="w-20 text-center border-t border-b"
                                                readonly>
                                            <button type="button" onclick="incrementChildren()" class="px-3 py-1 border rounded-r bg-gray-100">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        @error('children')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dịch vụ đưa đón sân bay -->
                        <div class="bg-white rounded-lg border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-plane text-blue-600 mr-2"></i>
                                Đưa đón sân bay
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               name="has_airport_transfer"
                                               value="1"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                               {{ old('has_airport_transfer') ? 'checked' : '' }}
                                               onchange="toggleAirportFields(this.checked)">
                                        <span class="ml-2">Tôi cần dịch vụ đưa đón sân bay</span>
                                    </label>
                                </div>

                                <div id="airport_fields" class="space-y-4" style="display: none;">
                                    <div>
                                        <label for="airport_name" class="block text-sm font-medium text-gray-700 mb-1">Tên sân bay</label>
                                        <input type="text"
                                               id="airport_name"
                                               name="airport_name"
                                               value="{{ old('airport_name') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    </div>

                                    <div>
                                        <label for="transfer_datetime" class="block text-sm font-medium text-gray-700 mb-1">Thời gian đón/trả</label>
                                        <input type="datetime-local"
                                               id="transfer_datetime"
                                               name="transfer_datetime"
                                               value="{{ old('transfer_datetime') }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    </div>

                                    <div>
                                        <label for="transfer_type" class="block text-sm font-medium text-gray-700 mb-1">Loại dịch vụ</label>
                                        <select id="transfer_type"
                                                name="transfer_type"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                            <option value="pickup" {{ old('transfer_type') == 'pickup' ? 'selected' : '' }}>Đón từ sân bay về khách sạn</option>
                                            <option value="dropoff" {{ old('transfer_type') == 'dropoff' ? 'selected' : '' }}>Đưa từ khách sạn ra sân bay</option>
                                            <option value="both" {{ old('transfer_type') == 'both' ? 'selected' : '' }}>Cả đưa và đón</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="transfer_passengers" class="block text-sm font-medium text-gray-700 mb-1">Số hành khách</label>
                                        <input type="number"
                                               id="transfer_passengers"
                                               name="transfer_passengers"
                                               min="1"
                                               value="{{ old('transfer_passengers', 1) }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    </div>

                                    <div>
                                        <label for="transfer_notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                                        <textarea id="transfer_notes"
                                                  name="transfer_notes"
                                                  rows="2"
                                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                                  placeholder="Số chuyến bay, yêu cầu đặc biệt...">{{ old('transfer_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tổng tiền -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Tổng tiền</h3>
                                    <p class="text-sm text-gray-600 mt-1">Đã bao gồm thuế và phí</p>
                                </div>
                                <div class="text-right">
                                    <span id="total-price" class="text-2xl font-bold text-blue-600"></span>
                                    <p class="text-sm text-gray-600 mt-1">VNĐ</p>
                                </div>
                            </div>
                        </div>

                        <!-- Yêu cầu đặc biệt -->
                        <div class="bg-white rounded-lg border p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-comment-alt text-blue-600 mr-2"></i>
                                Yêu cầu đặc biệt
                            </h3>
                            <textarea name="special_requests" rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Nhập yêu cầu đặc biệt của bạn (nếu có)">{{ old('special_requests') }}</textarea>
                        </div>

                        <!-- Nút đặt phòng -->
                        <div class="text-center">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-check mr-2"></i>
                                Xác nhận đặt phòng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Xử lý tăng giảm số lượng người
        function incrementAdults() {
            const input = document.getElementById('adults');
            const currentValue = parseInt(input.value);
            if (currentValue < 10) {
                input.value = currentValue + 1;
                calculateTotal();
            }
        }

        function decrementAdults() {
            const input = document.getElementById('adults');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                calculateTotal();
            }
        }

        function incrementChildren() {
            const input = document.getElementById('children');
            const currentValue = parseInt(input.value);
            if (currentValue < 5) {
                input.value = currentValue + 1;
                calculateTotal();
            }
        }

        function decrementChildren() {
            const input = document.getElementById('children');
            const currentValue = parseInt(input.value);
            if (currentValue > 0) {
                input.value = currentValue - 1;
                calculateTotal();
            }
        }

        // Xử lý hiển thị form đưa đón sân bay
        function toggleAirportFields(show) {
            const fields = document.getElementById('airport_fields');
            fields.style.display = show ? 'block' : 'none';
            calculateTotal();
        }

        // Tính tổng tiền
        function calculateTotal() {
            const checkIn = new Date(document.getElementById('check_in').value);
            const checkOut = new Date(document.getElementById('check_out').value);
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const roomPrice = parseFloat(document.querySelector('form').dataset.roomPrice);
            let totalPrice = roomPrice * nights;

            // Tính thêm phí đưa đón sân bay
            const hasTransfer = document.querySelector('input[name="has_airport_transfer"]').checked;
            const transferType = document.getElementById('transfer_type').value;

            if (hasTransfer) {
                if (transferType === 'both') {
                    totalPrice += 600000; // Phí đưa đón 2 chiều
                } else {
                    totalPrice += 300000; // Phí đưa đón 1 chiều
                }
            }

            document.getElementById('total-price').textContent = new Intl.NumberFormat('vi-VN').format(totalPrice);
        }

        // Tính tổng tiền khi trang load và khi thay đổi
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
            toggleAirportFields(document.querySelector('input[name="has_airport_transfer"]').checked);
        });
        document.getElementById('check_in').addEventListener('change', calculateTotal);
        document.getElementById('check_out').addEventListener('change', calculateTotal);
        document.getElementById('transfer_type').addEventListener('change', calculateTotal);
    </script>
    @endpush
@endsection
