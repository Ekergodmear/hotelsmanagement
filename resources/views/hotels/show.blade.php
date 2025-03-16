@extends('layouts.app')

@section('styles')
<style>
    .hotel-thumbnail {
        cursor: pointer;
        transition: all 0.3s;
        opacity: 0.7;
        height: 80px;
        object-fit: cover;
    }
    .hotel-thumbnail:hover, .hotel-thumbnail.active {
        opacity: 1;
        border: 2px solid #0d6efd;
    }
    .main-image {
        height: 500px;
        object-fit: cover;
        width: 100%;
    }
    .thumbnail-container {
        overflow-x: auto;
        white-space: nowrap;
        padding: 10px 0;
    }
    .thumbnail-container img {
        display: inline-block;
        margin-right: 10px;
    }
    .similar-hotel-img {
        height: 100px;
        object-fit: cover;
    }
    .rating-stars {
        color: #ffc107;
    }
    .review-item {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .review-item:last-child {
        border-bottom: none;
    }
    .map-container {
        height: 250px;
        width: 100%;
        border-radius: 0.25rem;
    }
    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating-input input {
        display: none;
    }
    .rating-input label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        margin: 0 2px;
    }
    .rating-input label:hover,
    .rating-input label:hover ~ label,
    .rating-input input:checked ~ label {
        color: #ffc107;
    }
    /* Thêm CSS cho lịch phòng trống */
    .availability-calendar {
        overflow-x: auto;
    }
    .availability-calendar table {
        min-width: 100%;
    }
    .availability-calendar th {
        min-width: 80px;
        text-align: center;
        padding: 8px;
    }
    .availability-calendar td {
        text-align: center;
        padding: 8px;
    }
    .availability-day {
        position: relative;
        height: 60px;
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .availability-high {
        background-color: #d4edda;
        color: #155724;
    }
    .availability-medium {
        background-color: #fff3cd;
        color: #856404;
    }
    .availability-low {
        background-color: #f8d7da;
        color: #721c24;
    }
    .availability-none {
        background-color: #f5f5f5;
        color: #6c757d;
    }
    .date-number {
        font-size: 1.2rem;
        font-weight: bold;
    }
    .day-name {
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    .room-count {
        font-size: 0.9rem;
    }
    .search-form-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .transfer-service {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .transfer-service h3 {
        margin-bottom: 15px;
        color: #333;
    }

    .transfer-option {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .transfer-option:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    .transfer-option .price {
        font-weight: bold;
        color: #0d6efd;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}">Khách sạn</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết khách sạn</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-3">{{ $hotel->name }}</h1>
            <div class="d-flex align-items-center mb-3">
                <span class="rating me-2">{{ number_format($hotel->rating, 1) }}</span>
                <p class="mb-0 text-muted">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    {{ $hotel->address }}, {{ $hotel->ward ?? '' }} {{ !empty($hotel->ward) ? ',' : '' }} {{ $hotel->district ?? '' }} {{ !empty($hotel->district) ? ',' : '' }} {{ $hotel->province_city ?? '' }}
                </p>
            </div>

            <!-- Ảnh khách sạn -->
            <div class="hotel-gallery mb-4">
                <div class="main-image-container mb-2">
                    @if($hotel->images->count() > 0)
                        <img id="mainImage" src="{{ Storage::url($hotel->images->first()->image_path) }}" class="main-image rounded" alt="{{ $hotel->name }}">
                    @else
                        <img id="mainImage" src="https://source.unsplash.com/1200x800/?hotel,room&sig=1" class="main-image rounded" alt="{{ $hotel->name }}">
                    @endif
                </div>

                <div class="thumbnail-container">
                    @if($hotel->images->count() > 0)
                        @foreach($hotel->images as $index => $image)
                            <img src="{{ Storage::url($image->image_path) }}"
                                class="hotel-thumbnail rounded {{ $index === 0 ? 'active' : '' }}"
                                alt="Thumbnail {{ $index + 1 }}"
                                onclick="changeMainImage('{{ Storage::url($image->image_path) }}', this)">
                        @endforeach
                    @else
                        @for($i = 1; $i <= 5; $i++)
                            <img src="https://source.unsplash.com/300x200/?hotel,room&sig={{ $i }}"
                                class="hotel-thumbnail rounded {{ $i === 1 ? 'active' : '' }}"
                                alt="Thumbnail {{ $i }}"
                                onclick="changeMainImage('https://source.unsplash.com/1200x800/?hotel,room&sig={{ $i }}', this)">
                        @endfor
                    @endif
                </div>
            </div>

            <!-- Form tìm kiếm phòng trống -->
            <div class="search-form-container mb-4">
                <h4 class="mb-3">Tìm phòng trống</h4>
                <form action="{{ route('hotels.show', $hotel) }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="check_in" class="form-label">Ngày nhận phòng</label>
                        <input type="date" class="form-control" id="check_in" name="check_in"
                               value="{{ $checkInDate ?? '' }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="check_out" class="form-label">Ngày trả phòng</label>
                        <input type="date" class="form-control" id="check_out" name="check_out"
                               value="{{ $checkOutDate ?? '' }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="guests" class="form-label">Số khách</label>
                        <select class="form-select" id="guests" name="guests">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ ($guests ?? 2) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Tìm phòng</button>
                    </div>
                </form>
            </div>

            <!-- Hiển thị phòng trống nếu đã tìm kiếm -->
            @if(isset($availableRooms) && $checkInDate && $checkOutDate)
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Phòng trống từ {{ date('d/m/Y', strtotime($checkInDate)) }} đến {{ date('d/m/Y', strtotime($checkOutDate)) }}</h4>

                        @if(count($availableRooms) > 0)
                            <div class="row">
                                @foreach($availableRooms as $room)
                                    <div class="col-md-6 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="position-relative">
                                                @if($room->primaryImage)
                                                    <img src="{{ Storage::url($room->primaryImage->image_path) }}" class="card-img-top" alt="{{ $room->roomType->name }}" style="height: 200px; object-fit: cover;">
                                                @elseif($room->images->count() > 0)
                                                    <img src="{{ Storage::url($room->images->first()->image_path) }}" class="card-img-top" alt="{{ $room->roomType->name }}" style="height: 200px; object-fit: cover;">
                                                @else
                                                    <img src="https://source.unsplash.com/800x600/?hotel,room&sig={{ $room->id }}" class="card-img-top" alt="Room Image" style="height: 200px; object-fit: cover;">
                                                @endif
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-success">Còn trống</span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">Phòng {{ $room->room_number }}</h5>
                                                <p class="card-text">
                                                    <span class="badge bg-info">{{ $room->roomType->name ?? 'Phòng tiêu chuẩn' }}</span>
                                                    <span class="badge bg-secondary">{{ $room->roomType->capacity ?? '2' }} người</span>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="h5 text-primary mb-0">{{ number_format($room->price ?? $room->roomType->base_price ?? 0) }}đ</p>
                                                    <a href="{{ route('bookings.create', ['room_id' => $room->id, 'check_in' => $checkInDate, 'check_out' => $checkOutDate, 'guests' => $guests]) }}" class="btn btn-sm btn-primary">Đặt ngay</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Không có phòng trống phù hợp cho {{ $guests }} khách trong khoảng thời gian này.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Lịch phòng trống -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Lịch phòng trống 30 ngày tới</h4>
                    <p class="text-muted">Xem nhanh tình trạng phòng trống của khách sạn trong 30 ngày tới</p>

                    <div class="availability-calendar">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    @foreach($availabilityCalendar as $date => $info)
                                        @php
                                            $availabilityPercentage = ($info['available_rooms'] / $info['total_rooms']) * 100;
                                            if ($availabilityPercentage >= 70) {
                                                $availabilityClass = 'availability-high';
                                            } elseif ($availabilityPercentage >= 30) {
                                                $availabilityClass = 'availability-medium';
                                            } elseif ($availabilityPercentage > 0) {
                                                $availabilityClass = 'availability-low';
                                            } else {
                                                $availabilityClass = 'availability-none';
                                            }
                                        @endphp

                                        <td>
                                            <div class="availability-day {{ $availabilityClass }}">
                                                <div class="day-name">{{ $info['day_of_week'] }}</div>
                                                <div class="date-number">{{ explode('/', $info['date'])[0] }}</div>
                                                <div class="room-count">{{ $info['available_rooms'] }}/{{ $info['total_rooms'] }}</div>
                                            </div>
                                        </td>

                                        @if(($loop->index + 1) % 7 == 0 && !$loop->last)
                                            </tr><tr>
                                        @endif
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <div class="d-flex align-items-center me-3">
                            <div class="availability-day availability-high me-2" style="width: 20px; height: 20px;"></div>
                            <small>Nhiều phòng trống</small>
                        </div>
                        <div class="d-flex align-items-center me-3">
                            <div class="availability-day availability-medium me-2" style="width: 20px; height: 20px;"></div>
                            <small>Phòng trống vừa phải</small>
                        </div>
                        <div class="d-flex align-items-center me-3">
                            <div class="availability-day availability-low me-2" style="width: 20px; height: 20px;"></div>
                            <small>Ít phòng trống</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="availability-day availability-none me-2" style="width: 20px; height: 20px;"></div>
                            <small>Hết phòng</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Thông tin chi tiết</h3>
                    <p>{{ $hotel->description }}</p>

                    <h4 class="mt-4">Tiện nghi</h4>
                    <div class="row">
                        @if(is_array($hotel->amenities) && count($hotel->amenities) > 0)
                            @foreach($hotel->amenities as $amenity)
                                <div class="col-md-4 mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>{{ $amenity }}
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-wifi me-2"></i> Wifi miễn phí</li>
                                    <li><i class="fas fa-swimming-pool me-2"></i> Hồ bơi</li>
                                    <li><i class="fas fa-parking me-2"></i> Bãi đậu xe</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-utensils me-2"></i> Nhà hàng</li>
                                    <li><i class="fas fa-dumbbell me-2"></i> Phòng gym</li>
                                    <li><i class="fas fa-spa me-2"></i> Spa</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Danh sách phòng -->
            <h3>Danh sách phòng</h3>
            <div class="row">
                @foreach($hotel->rooms as $room)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="position-relative">
                                @if($room->primaryImage)
                                    <img src="{{ Storage::url($room->primaryImage->image_path) }}" class="card-img-top" alt="{{ $room->roomType->name }}" style="height: 200px; object-fit: cover;">
                                @elseif($room->images->count() > 0)
                                    <img src="{{ Storage::url($room->images->first()->image_path) }}" class="card-img-top" alt="{{ $room->roomType->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="https://source.unsplash.com/800x600/?hotel,room&sig={{ $room->id }}" class="card-img-top" alt="Room Image" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge {{ $room->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $room->status === 'available' ? 'Còn trống' : 'Đã đặt' }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Phòng {{ $room->room_number }}</h5>
                                <p class="card-text">
                                    <span class="badge bg-info">{{ $room->roomType->name ?? 'Phòng tiêu chuẩn' }}</span>
                                    <span class="badge bg-secondary">{{ $room->roomType->capacity ?? '2' }} người</span>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="h5 text-primary mb-0">{{ number_format($room->price ?? $room->roomType->base_price ?? 0) }}đ</p>
                                    <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Thêm phần dịch vụ đưa đón sau phần thông tin khách sạn -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Dịch vụ đưa đón sân bay</h3>
                    <p>Đặt dịch vụ đưa đón sân bay để di chuyển thuận tiện đến khách sạn {{ $hotel->name }}</p>

                    <div class="transfer-service">
                        <form action="{{ route('hotels.search') }}" method="GET">
                            <input type="hidden" name="type" value="airport-transfer">
                            <input type="hidden" name="destination" value="{{ $hotel->name }}, {{ $hotel->address }}, {{ $hotel->province_city }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-plane"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" name="airport" placeholder="Sân bay" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <input type="date" class="form-control border-start-0" name="transfer_date" required min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <input type="time" class="form-control border-start-0" name="transfer_time" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <select class="form-select border-start-0" name="passengers">
                                            <option value="1">1 người</option>
                                            <option value="2" selected>2 người</option>
                                            <option value="3">3 người</option>
                                            <option value="4">4 người</option>
                                            <option value="5+">5+ người</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i> Tìm dịch vụ đưa đón
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <h4>Các lựa chọn phổ biến</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="transfer-option">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Xe riêng 4 chỗ</h5>
                                    <span class="badge bg-success">Phổ biến nhất</span>
                                </div>
                                <p class="mb-2"><i class="fas fa-user me-2"></i> Tối đa 4 người</p>
                                <p class="mb-2"><i class="fas fa-suitcase me-2"></i> 4 hành lý</p>
                                <p class="mb-2"><i class="fas fa-clock me-2"></i> Thời gian di chuyển: khoảng 30 phút</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="price">350.000₫</span>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Đặt ngay</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="transfer-option">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Xe riêng 7 chỗ</h5>
                                    <span class="badge bg-info">Tiết kiệm</span>
                                </div>
                                <p class="mb-2"><i class="fas fa-user me-2"></i> Tối đa 7 người</p>
                                <p class="mb-2"><i class="fas fa-suitcase me-2"></i> 7 hành lý</p>
                                <p class="mb-2"><i class="fas fa-clock me-2"></i> Thời gian di chuyển: khoảng 30 phút</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="price">450.000₫</span>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Đặt ngay</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột bên phải: Đánh giá và Khách sạn tương tự -->
        <div class="col-md-4">
            <!-- Đánh giá -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">Đánh giá</h3>
                    <div class="d-flex align-items-center mb-3">
                        <h2 class="me-2 mb-0">{{ number_format($hotel->rating, 1) }}</h2>
                        <div>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $hotel->rating)
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $hotel->rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="text-muted">Dựa trên đánh giá của khách hàng</small>
                        </div>
                    </div>

                    <!-- Danh sách đánh giá mẫu -->
                    <div class="review-list">
                        <div class="review-item">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Nguyễn Văn A</h6>
                                <div class="rating-stars small">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <small class="text-muted">Đã đánh giá 2 tháng trước</small>
                            <p class="mt-2 mb-0">Khách sạn rất tuyệt vời, phòng sạch sẽ và nhân viên thân thiện.</p>
                        </div>
                        <div class="review-item">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Trần Thị B</h6>
                                <div class="rating-stars small">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </div>
                            <small class="text-muted">Đã đánh giá 1 tháng trước</small>
                            <p class="mt-2 mb-0">Vị trí thuận tiện, gần trung tâm và dễ dàng di chuyển.</p>
                        </div>
                        <div class="review-item">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Lê Văn C</h6>
                                <div class="rating-stars small">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                            <small class="text-muted">Đã đánh giá 3 tuần trước</small>
                            <p class="mt-2 mb-0">Bữa sáng ngon, nhiều lựa chọn. Phòng rộng rãi và thoáng mát.</p>
                        </div>
                    </div>

                    <!-- Form đánh giá -->
                    <div class="mt-4">
                        <h5>Viết đánh giá của bạn</h5>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                            <div class="mb-3">
                                <label class="form-label">Đánh giá của bạn</label>
                                <div class="rating-input mb-2">
                                    <input type="radio" id="star5" name="rating" value="5" required>
                                    <label for="star5"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1"><i class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">Nhận xét</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Google Maps -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="card-title">Vị trí trên bản đồ</h3>
                    <div id="map" class="map-container"></div>
                </div>
            </div>

            <!-- Khách sạn tương tự -->
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Khách sạn tương tự</h3>

                    @if($similarHotels->count() > 0)
                        @foreach($similarHotels as $similarHotel)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    @if($similarHotel->images->count() > 0)
                                        <img src="{{ Storage::url($similarHotel->images->first()->image_path) }}" class="similar-hotel-img rounded" width="100" alt="{{ $similarHotel->name }}">
                                    @else
                                        <img src="https://source.unsplash.com/100x100/?hotel&sig={{ $similarHotel->id }}" class="similar-hotel-img rounded" width="100" alt="{{ $similarHotel->name }}">
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('hotels.show', $similarHotel) }}" class="text-decoration-none">{{ $similarHotel->name }}</a>
                                    </h6>
                                    <div class="rating-stars small mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $similarHotel->rating)
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $similarHotel->rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted d-block">{{ $similarHotel->district ?? '' }} {{ !empty($similarHotel->district) ? ',' : '' }} {{ $similarHotel->province_city ?? '' }}</small>
                                    <span class="text-primary">{{ number_format($similarHotel->price_per_night) }}₫</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Không tìm thấy khách sạn tương tự trong khu vực này.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Airport Transfer Service Section -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-3">Dịch vụ đưa đón sân bay</h3>
                            <p>Chúng tôi cung cấp dịch vụ đưa đón sân bay chất lượng cao, đảm bảo sự thoải mái và an toàn cho chuyến đi của bạn.</p>

                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-car-side text-primary fa-2x me-3"></i>
                                        </div>
                                        <div>
                                            <h5>Đa dạng phương tiện</h5>
                                            <p class="text-muted">Từ xe sedan đến xe bus lớn, phù hợp với mọi nhu cầu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-clock text-primary fa-2x me-3"></i>
                                        </div>
                                        <div>
                                            <h5>Đúng giờ</h5>
                                            <p class="text-muted">Tài xế sẽ đón bạn đúng giờ, không để bạn phải chờ đợi</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-user-tie text-primary fa-2x me-3"></i>
                                        </div>
                                        <div>
                                            <h5>Tài xế chuyên nghiệp</h5>
                                            <p class="text-muted">Đội ngũ tài xế thân thiện, chuyên nghiệp và kinh nghiệm</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-shield-alt text-primary fa-2x me-3"></i>
                                        </div>
                                        <div>
                                            <h5>An toàn tối đa</h5>
                                            <p class="text-muted">Xe được bảo dưỡng thường xuyên, đảm bảo an toàn</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('airport-transfers.index') }}" class="btn btn-outline-primary me-2">Xem tất cả dịch vụ</a>
                                <a href="{{ route('airport-transfers.create', ['room_id' => isset($room) ? $room->id : null]) }}" class="btn btn-primary">Đặt dịch vụ ngay</a>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                            <img src="{{ asset('images/airport-transfers/default-sedan.jpg') }}" alt="Airport Transfer" class="img-fluid rounded" style="max-height: 250px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function changeMainImage(src, thumbnail) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.hotel-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }

    // Xử lý form tìm kiếm phòng
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');

        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            const year = nextDay.getFullYear();
            const month = String(nextDay.getMonth() + 1).padStart(2, '0');
            const day = String(nextDay.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            checkOutInput.min = formattedDate;
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = formattedDate;
            }
        });

        const searchForm = document.querySelector('.search-form-container form');
        searchForm.addEventListener('submit', function(event) {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            if (checkOut <= checkIn) {
                event.preventDefault();
                alert('Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 ngày');
                return false;
            }
            const diffTime = Math.abs(checkOut - checkIn);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays > 30) {
                event.preventDefault();
                alert('Thời gian đặt phòng không được vượt quá 30 ngày');
                return false;
            }
            return true;
        });
    });

    // Google Maps
    function initMap() {
        const hotelLocation = {
            lat: {{ $hotel->latitude ?? 10.762622 }},
            lng: {{ $hotel->longitude ?? 106.660172 }}
        };

        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: hotelLocation,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });

        const marker = new google.maps.Marker({
            position: hotelLocation,
            map: map,
            title: '{{ $hotel->name }}'
        });

        const infowindow = new google.maps.InfoWindow({
            content: '<div style="padding: 10px;"><strong>{{ $hotel->name }}</strong><br>{{ $hotel->address }}</div>'
        });

        marker.addListener('click', () => {
            infowindow.open(map, marker);
        });

        infowindow.open(map, marker);
    }
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap">
</script>
@endsection

