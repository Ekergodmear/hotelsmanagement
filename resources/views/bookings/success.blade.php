@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Card chính -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white mb-6">
                    <i class="fas fa-check-circle text-4xl text-blue-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Đặt phòng thành công!</h1>
                <p class="text-blue-100">Cảm ơn bạn đã tin tưởng lựa chọn khách sạn của chúng tôi</p>
            </div>

            <!-- Thông tin đặt phòng -->
            <div class="px-6 py-8">
                <!-- Mã đặt phòng -->
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg mb-8">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Mã đặt phòng</p>
                        <p class="text-2xl font-bold text-blue-900">#{{ $booking->id }}</p>
                    </div>
                </div>

                <!-- Grid thông tin -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Thông tin phòng -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-bed text-blue-600 mr-2"></i>
                                Thông tin phòng
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-lg font-medium text-gray-900">{{ $booking->room->room_number }}</p>
                                <p class="text-gray-600">{{ $booking->room->roomType->name }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                                Thời gian
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nhận phòng:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Trả phòng:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t">
                                    <span class="text-gray-600">Số đêm:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays($booking->check_out) }} đêm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin khách và dịch vụ -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-user text-blue-600 mr-2"></i>
                                Thông tin liên hệ
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <p class="font-medium text-gray-900">{{ $booking->guest_name }}</p>
                                <p class="text-gray-600">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    {{ $booking->guest_email }}
                                </p>
                                <p class="text-gray-600">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    {{ $booking->guest_phone }}
                                </p>
                            </div>
                        </div>

                        @if($booking->has_airport_transfer)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-plane text-blue-600 mr-2"></i>
                                Đưa đón sân bay
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sân bay:</span>
                                    <span class="font-medium">{{ $booking->airport_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Thời gian:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($booking->transfer_datetime)->format('H:i d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Loại dịch vụ:</span>
                                    <span class="font-medium">
                                        @if($booking->transfer_type == 'pickup')
                                            Đón từ sân bay
                                        @elseif($booking->transfer_type == 'dropoff')
                                            Đưa ra sân bay
                                        @else
                                            Đưa đón 2 chiều
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Số hành khách:</span>
                                    <span class="font-medium">{{ $booking->transfer_passengers }} người</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Tổng tiền -->
                <div class="mt-8 border-t pt-8">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Tổng tiền:</span>
                            <span class="text-2xl font-bold text-blue-600">{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @if($booking->has_airport_transfer)
                        <div class="mt-4 pt-4 border-t border-blue-100">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Phí đưa đón sân bay:</span>
                                <span class="font-medium">{{ number_format($booking->transfer_price, 0, ',', '.') }} VNĐ</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Yêu cầu đặc biệt -->
                @if($booking->special_requests)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-comment-alt text-blue-600 mr-2"></i>
                        Yêu cầu đặc biệt
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-600">{{ $booking->special_requests }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-sm text-gray-600">
                        <p>Chúng tôi đã gửi email xác nhận đến {{ $booking->guest_email }}</p>
                        <p class="mt-1">Cần hỗ trợ? Gọi <a href="tel:1900xxxx" class="text-blue-600 hover:text-blue-500 font-medium">1900 xxxx</a></p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-home mr-2"></i>
                            Về trang chủ
                        </a>
                        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-print mr-2"></i>
                            In xác nhận
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .max-w-4xl, .max-w-4xl * {
            visibility: visible;
        }
        .max-w-4xl {
            position: absolute;
            left: 0;
            top: 0;
        }
        .no-print {
            display: none;
        }
    }
</style>
@endpush
@endsection
