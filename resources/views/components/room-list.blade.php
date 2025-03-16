@if(isset($rooms) && $rooms->count() > 0)
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Danh sách phòng</h2>
        <a href="{{ route('rooms.index') }}" class="btn btn-link text-primary">
            Xem tất cả <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
    <div class="row">
        @foreach($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card hotel-card">
                    <div class="card-body">
                        <h5 class="card-title">Phòng {{ $room->room_number }}</h5>
                        @if($room->roomType)
                            <p class="card-text">
                                Loại phòng: {{ $room->roomType->name }}<br>
                                Giá: {{ number_format($room->roomType->base_price) }}đ/đêm
                            </p>
                        @endif
                        @if($room->hotel)
                            <p class="card-text">
                                <i class="fas fa-hotel"></i> {{ $room->hotel->name }}
                            </p>
                        @endif
                        <p class="card-text">
                            <span class="badge {{ $room->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                {{ $room->status === 'available' ? 'Còn trống' : 'Đã đặt' }}
                            </span>
                        </p>
                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-outline-primary">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
