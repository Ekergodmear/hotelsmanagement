<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Chỗ nghỉ nổi bật</h2>
        <a href="{{ route('hotels.index') }}" class="btn btn-link text-primary">
            Xem tất cả <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
    <div class="row">
        @foreach($hotels->take(3) as $hotel)
            <div class="col-md-4">
                <a href="{{ route('hotels.show', $hotel) }}" class="hotel-link">
                    <div class="hotel-card">
                        @if($hotel->image)
                            <img src="{{ Storage::url($hotel->image) }}" class="card-img-top" alt="{{ $hotel->name }}">
                        @else
                            <img src="https://via.placeholder.com/800x600?text=Không+có+hình+ảnh" class="card-img-top" alt="Không có hình ảnh">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $hotel->name }}</h5>
                                <span class="rating">{{ number_format($hotel->rating, 1) }}</span>
                            </div>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $hotel->city }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Giá mỗi đêm từ</small>
                                    <p class="h5 text-primary mb-0">{{ number_format($hotel->price_per_night) }}đ</p>
                                </div>
                                <button class="btn btn-outline-primary">Đặt ngay</button>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
