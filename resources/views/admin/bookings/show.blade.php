@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Chi tiết đặt phòng</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Đặt phòng #{{ $booking->id }}</h5>
                <div>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Thông tin đặt phòng</h3>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h4 class="text-lg font-medium mb-4">Thông tin đặt phòng</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h5 class="font-medium mb-2">Trạng thái</h5>
                                <div class="mb-4">
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
                                </div>

                                <h5 class="font-medium mb-2">Thời gian</h5>
                                <div class="mb-4">
                                    <p><span class="font-medium">Check-in:</span> {{ $booking->check_in->format('d/m/Y H:i') }}</p>
                                    <p><span class="font-medium">Check-out:</span> {{ $booking->check_out->format('d/m/Y H:i') }}</p>
                                    <p><span class="font-medium">Số đêm:</span> {{ $booking->check_in->diffInDays($booking->check_out) }}</p>
                                </div>

                                <h5 class="font-medium mb-2">Số lượng khách</h5>
                                <div class="mb-4">
                                    <p><span class="font-medium">Người lớn:</span> {{ $booking->adults }}</p>
                                    <p><span class="font-medium">Trẻ em:</span> {{ $booking->children }}</p>
                                </div>

                                <h5 class="font-medium mb-2">Yêu cầu đặc biệt</h5>
                                <div class="mb-4">
                                    <p>{{ $booking->special_requests ?? 'Không có yêu cầu đặc biệt' }}</p>
                                </div>
                            </div>

                            <div>
                                <h5 class="font-medium mb-2">Thông tin khách hàng</h5>
                                <div class="mb-4">
                                    <p><span class="font-medium">Họ tên:</span> {{ $booking->user->name }}</p>
                                    <p><span class="font-medium">Email:</span> {{ $booking->user->email }}</p>
                                    <p><span class="font-medium">Điện thoại:</span> {{ $booking->user->phone ?? 'N/A' }}</p>
                                    <p><span class="font-medium">Địa chỉ:</span> {{ $booking->user->address ?? 'N/A' }}</p>
                                    <p><span class="font-medium">Thành phố:</span> {{ $booking->user->city ?? 'N/A' }}</p>
                                    <p><span class="font-medium">Quốc gia:</span> {{ $booking->user->country ?? 'N/A' }}</p>
                                    <p><span class="font-medium">Giấy tờ:</span> {{ $booking->user->id_card_type ?? 'N/A' }} - {{ $booking->user->id_card_number ?? 'N/A' }}</p>
                                </div>

                                <h5 class="font-medium mb-2">Thông tin phòng</h5>
                                <div class="mb-4">
                                    <p><span class="font-medium">Số phòng:</span> {{ $booking->room->room_number }}</p>
                                    <p><span class="font-medium">Loại phòng:</span> {{ $booking->room->roomType->name }}</p>
                                    <p><span class="font-medium">Sức chứa:</span> {{ $booking->room->roomType->capacity }} người</p>
                                    <p><span class="font-medium">Giá phòng:</span> {{ number_format($booking->room->roomType->base_price) }} VNĐ/đêm</p>
                                </div>

                                <h5 class="font-medium mb-2">Thông tin thanh toán</h5>
                                <div class="mb-4">
                                    <p><span class="font-medium">Tổng tiền phòng:</span> {{ number_format($booking->total_price) }} VNĐ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium">Dịch vụ đã đặt</h4>
                            @if($booking->status == 'checked_in')
                                <button id="addServiceBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Thêm dịch vụ
                                </button>
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">ID</th>
                                        <th class="py-2 px-4 border-b text-left">Dịch vụ</th>
                                        <th class="py-2 px-4 border-b text-left">Số lượng</th>
                                        <th class="py-2 px-4 border-b text-left">Đơn giá</th>
                                        <th class="py-2 px-4 border-b text-left">Thành tiền</th>
                                        <th class="py-2 px-4 border-b text-left">Ngày sử dụng</th>
                                        <th class="py-2 px-4 border-b text-left">Ghi chú</th>
                                        <th class="py-2 px-4 border-b text-left">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($booking->services as $bookingService)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $bookingService->id }}</td>
                                        <td class="py-2 px-4 border-b">{{ $bookingService->service->name }}</td>
                                        <td class="py-2 px-4 border-b">{{ $bookingService->quantity }}</td>
                                        <td class="py-2 px-4 border-b">{{ number_format($bookingService->price) }} VNĐ</td>
                                        <td class="py-2 px-4 border-b">{{ number_format($bookingService->price * $bookingService->quantity) }} VNĐ</td>
                                        <td class="py-2 px-4 border-b">{{ $bookingService->service_date->format('d/m/Y H:i') }}</td>
                                        <td class="py-2 px-4 border-b">{{ $bookingService->notes ?? 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b">
                                            @if($booking->status == 'checked_in')
                                                <form action="{{ route('admin.booking-services.destroy', $bookingService) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                                        Xóa
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-4 text-center text-gray-500">Không có dịch vụ nào.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between">
                    <div>
                        @if($booking->status == 'confirmed')
                            <form action="{{ route('admin.bookings.check-in', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    Check-in
                                </button>
                            </form>
                        @endif

                        @if($booking->status == 'checked_in')
                            <form action="{{ route('admin.bookings.check-out', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                    Check-out
                                </button>
                            </form>
                        @endif
                    </div>

                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đặt phòng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Xóa đặt phòng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($booking->status == 'checked_in')
        <!-- Modal thêm dịch vụ -->
        <div id="addServiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Thêm dịch vụ</h3>
                    <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.booking-services.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                    <div class="mb-4">
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Dịch vụ</label>
                        <select id="service_id" name="service_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="">Chọn dịch vụ</option>
                            @foreach($availableServices as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}">{{ $service->name }} ({{ number_format($service->price) }} VNĐ)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4">
                        <label for="service_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày sử dụng</label>
                        <input type="datetime-local" id="service_date" name="service_date" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Thêm dịch vụ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Xử lý hiển thị modal
            const addServiceBtn = document.getElementById('addServiceBtn');
            const addServiceModal = document.getElementById('addServiceModal');
            const closeModalBtn = document.getElementById('closeModalBtn');

            addServiceBtn.addEventListener('click', function() {
                addServiceModal.classList.remove('hidden');
            });

            closeModalBtn.addEventListener('click', function() {
                addServiceModal.classList.add('hidden');
            });

            // Đóng modal khi click bên ngoài
            window.addEventListener('click', function(event) {
                if (event.target === addServiceModal) {
                    addServiceModal.classList.add('hidden');
                }
            });
        </script>
    @endif
@endsection
