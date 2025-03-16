@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Danh sách phòng</h1>

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

    <!-- Form tìm kiếm -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Tìm kiếm phòng</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('rooms.search') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="hotel_name" class="form-label">Tên khách sạn</label>
                    <input type="text" class="form-control" id="hotel_name" name="hotel_name" value="{{ $search['hotel_name'] ?? '' }}" placeholder="Nhập tên khách sạn">
                </div>
                <div class="col-md-3">
                    <label for="city" class="form-label">Thành phố</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ $search['city'] ?? '' }}" placeholder="Nhập thành phố">
                </div>
                <div class="col-md-3">
                    <label for="district" class="form-label">Quận/Huyện</label>
                    <input type="text" class="form-control" id="district" name="district" value="{{ $search['district'] ?? '' }}" placeholder="Nhập quận/huyện">
                </div>
                <div class="col-md-3">
                    <label for="room_type" class="form-label">Loại phòng</label>
                    <input type="text" class="form-control" id="room_type" name="room_type" value="{{ $search['room_type'] ?? '' }}" placeholder="Nhập loại phòng">
                </div>
                <div class="col-md-3">
                    <label for="min_price" class="form-label">Giá tối thiểu</label>
                    <input type="number" class="form-control" id="min_price" name="min_price" value="{{ $search['min_price'] ?? '' }}" placeholder="VNĐ">
                </div>
                <div class="col-md-3">
                    <label for="max_price" class="form-label">Giá tối đa</label>
                    <input type="number" class="form-control" id="max_price" name="max_price" value="{{ $search['max_price'] ?? '' }}" placeholder="VNĐ">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả</option>
                        <option value="available" {{ isset($search['status']) && $search['status'] == 'available' ? 'selected' : '' }}>Còn trống</option>
                        <option value="occupied" {{ isset($search['status']) && $search['status'] == 'occupied' ? 'selected' : '' }}>Đã đặt</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort_by" class="form-label">Sắp xếp theo</label>
                    <div class="input-group">
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="created_at" {{ isset($search['sort_by']) && $search['sort_by'] == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price" {{ isset($search['sort_by']) && $search['sort_by'] == 'price' ? 'selected' : '' }}>Giá</option>
                        </select>
                        <select class="form-select" id="sort_order" name="sort_order">
                            <option value="desc" {{ isset($search['sort_order']) && $search['sort_order'] == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                            <option value="asc" {{ isset($search['sort_order']) && $search['sort_order'] == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-2"></i>Đặt lại
                </a>
            </div>
            </form>
        </div>
    </div>

    <!-- Kết quả tìm kiếm -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Kết quả ({{ $rooms->count() }} phòng)</h4>
    </div>

    <div class="row">
        @forelse($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card hotel-card h-100">
                    <div class="position-relative">
                        @if($room->image)
                            <img src="{{ Storage::url($room->image) }}" class="card-img-top" alt="{{ $room->room_number }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ Storage::url($room->hotel->images->first()->image_path) }}" class="card-img-top" alt="{{ $room->hotel->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge {{ $room->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                {{ $room->status === 'available' ? 'Còn trống' : 'Đã đặt' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title">Phòng {{ $room->room_number }}</h5>
                            @if($room->hotel)
                                <span class="badge bg-info">{{ $room->hotel->name }}</span>
                            @endif
                        </div>

                        @if($room->hotel)
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $room->hotel->city }}
                            </p>
                        @endif

                        @if($room->roomType)
                            <p class="card-text">
                                <strong>Loại phòng:</strong> {{ $room->roomType->name }}<br>
                                <strong>Sức chứa:</strong> {{ $room->roomType->capacity ?? '2' }} người<br>
                                <strong>Diện tích:</strong> {{ $room->roomType->size ?? '30' }} m²
                            </p>
                                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">Giá mỗi đêm</small>
                                <p class="h5 text-primary mb-0">{{ number_format($room->roomType->base_price ?? 0) }}đ</p>
                            </div>
                            <a href="{{ route('rooms.show', $room) }}" class="btn btn-outline-primary">
                                Xem chi tiết
                            </a>
                                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Không tìm thấy phòng nào phù hợp với tiêu chí tìm kiếm.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
