@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}">Khách sạn</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hotels.show', $room->hotel) }}">{{ $room->hotel->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Phòng {{ $room->room_number }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Phòng {{ $room->room_number }} - {{ $room->roomType->name }}</h1>
                @auth
                    @if(Auth::user()->favorites()->where('room_id', $room->id)->exists())
                        <form action="{{ route('favorites.destroy', Auth::user()->favorites()->where('room_id', $room->id)->first()) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-heart"></i> Đã yêu thích
                            </button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="far fa-heart"></i> Yêu thích
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            <!-- Ảnh phòng -->
            <div id="roomCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @if($room->images->count() > 0)
                        @foreach($room->images as $index => $image)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ Storage::url($image->image_path) }}" class="d-block w-100" alt="Room Image" style="height: 500px; object-fit: cover;">
                            </div>
                        @endforeach
                    @else
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="carousel-item {{ $i == 1 ? 'active' : '' }}">
                                <img src="https://source.unsplash.com/1200x800/?hotel,room&sig={{ $room->id }}-{{ $i }}" class="d-block w-100" alt="Room Image {{ $i }}" style="height: 500px; object-fit: cover;">
                            </div>
                        @endfor
                    @endif
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Thông tin chi tiết phòng -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Thông tin chi tiết</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Loại phòng:</strong> {{ $room->roomType->name }}</p>
                            <p><strong>Diện tích:</strong> {{ $room->roomType->size ?? '30' }} m²</p>
                            <p><strong>Sức chứa:</strong> {{ $room->roomType->capacity ?? '2' }} người</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Giá:</strong> {{ number_format($room->price) }}đ/đêm</p>
                            <p><strong>Trạng thái:</strong>
                                <span class="badge {{ $room->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $room->status === 'available' ? 'Còn trống' : 'Đã đặt' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Lịch đặt phòng -->
                    <h4 class="mt-4">Tình trạng phòng trong 30 ngày tới</h4>
                    <div class="availability-calendar mb-4">
                        <div class="row">
                            @php
                                $today = \Carbon\Carbon::today();
                                $bookings = $room->bookings;
                                $bookedDates = [];

                                // Lấy tất cả các ngày đã đặt
                                foreach ($bookings as $booking) {
                                    $checkIn = \Carbon\Carbon::parse($booking->check_in);
                                    $checkOut = \Carbon\Carbon::parse($booking->check_out);
                                    $period = \Carbon\CarbonPeriod::create($checkIn, $checkOut->subDay());

                                    foreach ($period as $date) {
                                        $bookedDates[] = $date->format('Y-m-d');
                                    }
                                }
                            @endphp

                            @for ($i = 0; $i < 30; $i++)
                                @php
                                    $date = $today->copy()->addDays($i);
                                    $isBooked = in_array($date->format('Y-m-d'), $bookedDates);
                                @endphp
                                <div class="col-md-2 col-4 mb-2">
                                    <div class="card {{ $isBooked ? 'bg-danger text-white' : 'bg-success text-white' }}">
                                        <div class="card-body p-2 text-center">
                                            <small>{{ $date->format('d/m') }}</small>
                                            <div>
                                                <i class="fas {{ $isBooked ? 'fa-times' : 'fa-check' }}"></i>
                                                <small>{{ $isBooked ? 'Đã đặt' : 'Trống' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <h4 class="mt-4">Tiện nghi phòng</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-wifi me-2"></i> Wifi miễn phí</li>
                                <li><i class="fas fa-tv me-2"></i> TV màn hình phẳng</li>
                                <li><i class="fas fa-snowflake me-2"></i> Điều hòa</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-bath me-2"></i> Phòng tắm riêng</li>
                                <li><i class="fas fa-coffee me-2"></i> Minibar</li>
                                <li><i class="fas fa-concierge-bell me-2"></i> Dịch vụ phòng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin khách sạn -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Về khách sạn</h3>
                    <p><strong>Tên khách sạn:</strong> {{ $room->hotel->name }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $room->hotel->address }}, {{ $room->hotel->province_city }}</p>
                    <p><strong>Đánh giá:</strong> {{ number_format($room->hotel->rating, 1) }}/10</p>
                    <a href="{{ route('hotels.show', $room->hotel) }}" class="btn btn-outline-primary">Xem chi tiết khách sạn</a>
                </div>
            </div>
        </div>

        <!-- Đặt phòng -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h3 class="card-title">Đặt phòng</h3>
                    <p class="h3 text-primary mb-3">{{ number_format($room->price) }}₫ <small class="text-muted">/đêm</small></p>

                    @auth
                    <form action="{{ route('bookings.store') }}" method="POST" id="booking-form">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <div class="mb-3">
                            <label class="form-label">Ngày nhận phòng</label>
                            <input type="date" name="check_in" id="check_in" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngày trả phòng</label>
                            <input type="date" name="check_out" id="check_out" class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số khách</label>
                            <select name="guests" class="form-control">
                                @for($i = 1; $i <= ($room->roomType->capacity ?? 4); $i++)
                                    <option value="{{ $i }}">{{ $i }} người</option>
                                @endfor
                            </select>
                        </div>

                        <div id="booking-summary" class="mb-3 d-none">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Tóm tắt đặt phòng</h5>
                                    <div class="d-flex justify-content-between">
                                        <span>Giá phòng:</span>
                                        <span>{{ number_format($room->price) }}₫/đêm</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Số đêm:</span>
                                        <span id="num-nights">0</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Tổng cộng:</span>
                                        <span id="total-price">0₫</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($room->status === 'available')
                            <button type="submit" class="btn btn-primary w-100">Đặt ngay</button>
                        @else
                            <button type="button" class="btn btn-secondary w-100" disabled>Phòng đã được đặt</button>
                        @endif
                    </form>
                    @else
                    <div class="alert alert-info">
                        Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đặt phòng.
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const bookingSummary = document.getElementById('booking-summary');
        const numNightsElement = document.getElementById('num-nights');
        const totalPriceElement = document.getElementById('total-price');
        const roomPrice = parseInt('{{ $room->price ?? 0 }}');

        // Mảng các ngày đã đặt
        const bookedDates = [];
        @if(isset($bookedDates) && is_array($bookedDates))
            @foreach($bookedDates as $date)
                bookedDates.push("{{ $date }}");
            @endforeach
        @endif

        function updateBookingSummary() {
            if (checkInInput.value && checkOutInput.value) {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);

                // Tính số đêm
                const diffTime = Math.abs(checkOut - checkIn);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                // Cập nhật tóm tắt đặt phòng
                numNightsElement.textContent = diffDays;
                totalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(diffDays * roomPrice) + '₫';

                // Hiển thị tóm tắt
                bookingSummary.classList.remove('d-none');
            }
        }

        // Kiểm tra xem ngày đã chọn có bị đặt chưa
        function checkDateAvailability() {
            if (checkInInput.value && checkOutInput.value) {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);

                let currentDate = new Date(checkIn);
                while (currentDate < checkOut) {
                    const dateString = currentDate.toISOString().split('T')[0];
                    if (bookedDates.includes(dateString)) {
                        alert('Phòng đã được đặt trong khoảng thời gian bạn chọn. Vui lòng chọn ngày khác.');
                        checkInInput.value = '';
                        checkOutInput.value = '';
                        bookingSummary.classList.add('d-none');
                        return false;
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            }
            return true;
        }

        // Sự kiện khi thay đổi ngày
        checkInInput.addEventListener('change', function() {
            // Cập nhật ngày trả phòng tối thiểu
            if (checkInInput.value) {
                const nextDay = new Date(checkInInput.value);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.min = nextDay.toISOString().split('T')[0];

                // Nếu ngày trả phòng nhỏ hơn ngày nhận phòng + 1, cập nhật ngày trả phòng
                if (checkOutInput.value && new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
            }

            checkDateAvailability();
            updateBookingSummary();
        });

        checkOutInput.addEventListener('change', function() {
            checkDateAvailability();
            updateBookingSummary();
        });
    });
</script>
@endpush
@endsection
