@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết loại phòng</h1>
        <div>
            <a href="{{ route('admin.room-types.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.room-types.edit', $roomType->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin loại phòng</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h2>{{ $roomType->name }}</h2>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-3">
                                <i class="fas fa-users text-primary"></i> Sức chứa: {{ $roomType->capacity }} người
                            </div>
                            <div class="ml-3">
                                <i class="fas fa-money-bill-wave text-success"></i> Giá cơ bản: {{ number_format($roomType->base_price, 0, ',', '.') }} VNĐ
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Mô tả</h5>
                        <p>{{ $roomType->description }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng thuộc loại này</h6>
                </div>
                <div class="card-body">
                    @if($roomType->rooms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Số phòng</th>
                                    <th>Khách sạn</th>
                                    <th>Trạng thái</th>
                                    <th>Giá</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roomType->rooms as $room)
                                <tr>
                                    <td>{{ $room->room_number }}</td>
                                    <td>
                                        <a href="{{ route('admin.hotels.show', $room->hotel_id) }}">
                                            {{ $room->hotel->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($room->status == 'available')
                                            <span class="badge bg-success text-white">Trống</span>
                                        @elseif($room->status == 'occupied')
                                            <span class="badge bg-danger text-white">Đã đặt</span>
                                        @else
                                            <span class="badge bg-warning text-white">Bảo trì</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($room->price, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center">Chưa có phòng nào thuộc loại này.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hình ảnh</h6>
                </div>
                <div class="card-body">
                    @php
                        $firstRoom = $roomType->rooms()->with('images')->first();
                    @endphp

                    <div id="roomTypeCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if($firstRoom && $firstRoom->images && $firstRoom->images->count() > 0)
                                @foreach($firstRoom->images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($image->image_path) }}" class="d-block w-100" alt="Room Type Image" style="height: 300px; object-fit: cover;">
                                </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <img src="https://via.placeholder.com/800x600?text=Không+có+ảnh" class="d-block w-100" alt="No Image" style="height: 300px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                        @if($firstRoom && $firstRoom->images && $firstRoom->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#roomTypeCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#roomTypeCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin thêm</h6>
                </div>
                <div class="card-body">
                    <p><strong>Tổng số phòng:</strong> {{ $roomType->rooms->count() }}</p>
                    <p><strong>Ngày tạo:</strong> {{ $roomType->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Cập nhật lần cuối:</strong> {{ $roomType->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
