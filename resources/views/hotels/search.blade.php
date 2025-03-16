@extends('layouts.app')

@section('styles')
<style>
    .search-filters {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .hotel-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .hotel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .hotel-image {
        height: 220px;
        object-fit: cover;
    }

    .amenity-badge {
        margin-right: 5px;
        margin-bottom: 5px;
        font-size: 0.8rem;
    }

    .price-tag {
        font-size: 1.5rem;
        font-weight: bold;
        color: #5392f9;
    }

    .rating-badge {
        background-color: #5392f9;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kết quả tìm kiếm</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Bộ lọc tìm kiếm -->
        <div class="col-md-3">
            <div class="search-filters sticky-top" style="top: 20px;">
                <h4 class="mb-3">Bộ lọc</h4>

                <form action="{{ route('hotels.search') }}" method="GET">
                    <div class="mb-3">
                        <label for="city" class="form-label">Điểm đến</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ $search['city'] ?? '' }}" placeholder="Thành phố, khách sạn...">
                    </div>

                    <div class="mb-3">
                        <label for="check_in" class="form-label">Ngày nhận phòng</label>
                        <input type="date" class="form-control" id="check_in" name="check_in" value="{{ $search['check_in'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="check_out" class="form-label">Ngày trả phòng</label>
                        <input type="date" class="form-control" id="check_out" name="check_out" value="{{ $search['check_out'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="guests" class="form-label">Số khách</label>
                        <select class="form-select" id="guests" name="guests">
                            <option value="1" {{ isset($search['guests']) && $search['guests'] == 1 ? 'selected' : '' }}>1 người lớn</option>
                            <option value="2" {{ isset($search['guests']) && $search['guests'] == 2 ? 'selected' : '' }}>2 người lớn</option>
                            <option value="3" {{ isset($search['guests']) && $search['guests'] == 3 ? 'selected' : '' }}>3 người lớn</option>
                            <option value="4" {{ isset($search['guests']) && $search['guests'] == 4 ? 'selected' : '' }}>4 người lớn</option>
                            <option value="5" {{ isset($search['guests']) && $search['guests'] == 5 ? 'selected' : '' }}>5 người lớn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Khoảng giá (VNĐ/đêm)</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" class="form-control" name="price_min" placeholder="Tối thiểu" value="{{ $search['price_min'] ?? '' }}">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" name="price_max" placeholder="Tối đa" value="{{ $search['price_max'] ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Đánh giá</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="rating5" name="star_rating[]" value="5" {{ isset($search['star_rating']) && in_array('5', (array)$search['star_rating']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating5">5 sao</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="rating4" name="star_rating[]" value="4" {{ isset($search['star_rating']) && in_array('4', (array)$search['star_rating']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating4">4 sao</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="rating3" name="star_rating[]" value="3" {{ isset($search['star_rating']) && in_array('3', (array)$search['star_rating']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating3">3 sao</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="rating2" name="star_rating[]" value="2" {{ isset($search['star_rating']) && in_array('2', (array)$search['star_rating']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating2">2 sao</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="rating1" name="star_rating[]" value="1" {{ isset($search['star_rating']) && in_array('1', (array)$search['star_rating']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="rating1">1 sao</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tiện nghi</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="wifi" name="amenities[]" value="wifi" {{ isset($search['amenities']) && in_array('wifi', (array)$search['amenities']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="wifi">Wifi</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="pool" name="amenities[]" value="pool" {{ isset($search['amenities']) && in_array('pool', (array)$search['amenities']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="pool">Hồ bơi</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="restaurant" name="amenities[]" value="restaurant" {{ isset($search['amenities']) && in_array('restaurant', (array)$search['amenities']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="restaurant">Nhà hàng</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="spa" name="amenities[]" value="spa" {{ isset($search['amenities']) && in_array('spa', (array)$search['amenities']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="spa">Spa</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="gym" name="amenities[]" value="gym" {{ isset($search['amenities']) && in_array('gym', (array)$search['amenities']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="gym">Phòng tập gym</label>
                            </div>
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" id="parking" name="amenities[]" value="parking" {{ isset($search['amenities']) && in_array('parking', (array)$search['amenities']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="parking">Bãi đậu xe</label>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="sort" value="{{ $search['sort'] ?? 'rating_desc' }}">

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Lọc kết quả
                    </button>
                </form>
            </div>
        </div>

        <!-- Kết quả tìm kiếm -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Kết quả tìm kiếm ({{ $hotels->total() }} khách sạn)</h4>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Sắp xếp theo:
                        @if(isset($search['sort']))
                            @if($search['sort'] == 'price_asc')
                                Giá thấp đến cao
                            @elseif($search['sort'] == 'price_desc')
                                Giá cao đến thấp
                            @elseif($search['sort'] == 'rating_desc')
                                Đánh giá cao nhất
                            @else
                                Đánh giá cao nhất
                            @endif
                        @else
                            Đánh giá cao nhất
                        @endif
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li>
                            <a class="dropdown-item {{ isset($search['sort']) && $search['sort'] == 'rating_desc' ? 'active' : '' }}"
                               href="{{ route('hotels.search', array_merge($search, ['sort' => 'rating_desc'])) }}">
                                Đánh giá cao nhất
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ isset($search['sort']) && $search['sort'] == 'price_asc' ? 'active' : '' }}"
                               href="{{ route('hotels.search', array_merge($search, ['sort' => 'price_asc'])) }}">
                                Giá thấp đến cao
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ isset($search['sort']) && $search['sort'] == 'price_desc' ? 'active' : '' }}"
                               href="{{ route('hotels.search', array_merge($search, ['sort' => 'price_desc'])) }}">
                                Giá cao đến thấp
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            @if(isset($search['check_in']) && isset($search['check_out']))
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                Hiển thị khách sạn có phòng trống từ <strong>{{ \Carbon\Carbon::parse($search['check_in'])->format('d/m/Y') }}</strong> đến <strong>{{ \Carbon\Carbon::parse($search['check_out'])->format('d/m/Y') }}</strong>
                @if(isset($search['guests']) && $search['guests'] > 0)
                    cho <strong>{{ $search['guests'] }} khách</strong>
                @endif
            </div>
            @endif

            @forelse($hotels as $hotel)
                <div class="card hotel-card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="position-relative h-100">
                                @if($hotel->images->count() > 0)
                                    <img src="{{ Storage::url($hotel->images->first()->image_path) }}" class="hotel-image w-100 h-100" alt="{{ $hotel->name }}">
                                @else
                                    <img src="https://source.unsplash.com/800x600/?hotel&sig={{ $hotel->id }}" class="hotel-image w-100 h-100" alt="{{ $hotel->name }}">
                                @endif
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success">Giảm giá</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title">{{ $hotel->name }}</h5>
                                    <span class="rating-badge">
                                        {{ number_format($hotel->rating, 1) }}
                                        <i class="fas fa-star ms-1"></i>
                                    </span>
                                </div>

                                <p class="card-text text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $hotel->address }}, {{ $hotel->province_city ?? $hotel->city }}
                                </p>

                                <div class="mb-2">
                                    @if(is_array($hotel->amenities) && count($hotel->amenities) > 0)
                                        @foreach($hotel->amenities as $amenity)
                                            <span class="badge bg-light text-dark amenity-badge">{{ $amenity }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-light text-dark amenity-badge">Wifi miễn phí</span>
                                        <span class="badge bg-light text-dark amenity-badge">Hồ bơi</span>
                                        <span class="badge bg-light text-dark amenity-badge">Bãi đậu xe</span>
                                        <span class="badge bg-light text-dark amenity-badge">Nhà hàng</span>
                                    @endif
                                </div>

                                <p class="card-text">{{ Str::limit($hotel->description, 150) }}</p>

                                @if(isset($search['check_in']) && isset($search['check_out']))
                                <div class="mb-2">
                                    <span class="badge bg-success">Còn phòng trống từ {{ \Carbon\Carbon::parse($search['check_in'])->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($search['check_out'])->format('d/m/Y') }}</span>
                                </div>
                                @endif

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Giá mỗi đêm từ</small>
                                        @php
                                            $minPrice = null;
                                            foreach ($hotel->rooms as $room) {
                                                if ($minPrice === null || $room->price < $minPrice) {
                                                    $minPrice = $room->price;
                                                }
                                            }
                                            // Nếu không có phòng hoặc không có giá, sử dụng giá khách sạn
                                            $minPrice = $minPrice ?: $hotel->price_per_night;
                                        @endphp
                                        <p class="price-tag mb-0">{{ number_format($minPrice) }}đ</p>
                                        <small class="text-muted d-block">(Giá phòng thấp nhất)</small>
                                    </div>
                                    <a href="{{ route('hotels.show', $hotel) }}{{ isset($search['check_in']) && isset($search['check_out']) ? '?check_in='.$search['check_in'].'&check_out='.$search['check_out'].'&guests='.($search['guests'] ?? 2) : '' }}" class="btn btn-primary">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Không tìm thấy khách sạn nào phù hợp với tiêu chí tìm kiếm của bạn.
                </div>
            @endforelse

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-4">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');

        // Đặt ngày tối thiểu cho check-in là ngày hôm nay
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const formattedToday = `${year}-${month}-${day}`;

        checkInInput.min = formattedToday;

        // Cập nhật ngày check-out tối thiểu khi ngày check-in thay đổi
        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);

            // Định dạng ngày thành YYYY-MM-DD
            const year = nextDay.getFullYear();
            const month = String(nextDay.getMonth() + 1).padStart(2, '0');
            const day = String(nextDay.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;

            checkOutInput.min = formattedDate;

            // Nếu ngày check-out hiện tại nhỏ hơn ngày check-in + 1, cập nhật nó
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = formattedDate;
            }
        });

        // Kiểm tra khi form được gửi
        const searchForm = document.querySelector('.search-filters form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(event) {
                if (checkInInput.value && checkOutInput.value) {
                    const checkIn = new Date(checkInInput.value);
                    const checkOut = new Date(checkOutInput.value);

                    // Kiểm tra nếu ngày check-out <= ngày check-in
                    if (checkOut <= checkIn) {
                        event.preventDefault();
                        alert('Ngày trả phòng phải sau ngày nhận phòng ít nhất 1 ngày');
                        return false;
                    }

                    // Kiểm tra nếu khoảng thời gian quá dài (ví dụ: > 30 ngày)
                    const diffTime = Math.abs(checkOut - checkIn);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    if (diffDays > 30) {
                        event.preventDefault();
                        alert('Thời gian đặt phòng không được vượt quá 30 ngày');
                        return false;
                    }
                }

                return true;
            });
        }
    });
</script>
@endsection
