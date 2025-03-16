@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết khách sạn</h1>
        <div>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách sạn</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h2>{{ $hotel->name }}</h2>
                        <div class="d-flex align-items-center mb-2">
                            <div class="mr-2">
                                <i class="fas fa-map-marker-alt text-danger"></i> {{ $hotel->province_city }}
                            </div>
                            <div class="ml-3">
                                <i class="fas fa-star text-warning"></i> {{ $hotel->rating ?? 'Chưa đánh giá' }}
                            </div>
                        </div>
                        <p><strong>Địa chỉ:</strong> {{ $hotel->address }}</p>
                        <p><strong>Phường/Xã:</strong> {{ $hotel->ward ?? 'Không có' }}</p>
                        <p><strong>Quận/Huyện:</strong> {{ $hotel->district }}</p>
                        <p><strong>Tỉnh/Thành phố:</strong> {{ $hotel->province_city }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="font-weight-bold">Mô tả</h5>
                        <p>{{ $hotel->description }}</p>
                    </div>

                    @if($hotel->amenities)
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Tiện nghi</h5>
                        <div class="row">
                            @foreach(is_array($hotel->amenities) ? $hotel->amenities : json_decode($hotel->amenities, true) ?? [])
                            <div class="col-md-4 mb-2">
                                <i class="fas fa-check text-success"></i>
                                @switch($amenity)
                                    @case('wifi')
                                        Wi-Fi miễn phí
                                        @break
                                    @case('pool')
                                        Hồ bơi
                                        @break
                                    @case('restaurant')
                                        Nhà hàng
                                        @break
                                    @case('spa')
                                        Spa
                                        @break
                                    @case('gym')
                                        Phòng tập gym
                                        @break
                                    @case('parking')
                                        Bãi đỗ xe
                                        @break
                                    @default
                                        {{ $amenity }}
                                @endswitch
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng</h6>
                    <a href="{{ route('admin.hotels.rooms', $hotel->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-bed"></i> Xem tất cả phòng
                    </a>
                </div>
                <div class="card-body">
                    @if($hotel->rooms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Số phòng</th>
                                    <th>Loại phòng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hotel->rooms->take(5) as $room)
                                <tr>
                                    <td>{{ $room->room_number }}</td>
                                    <td>{{ $room->roomType->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($room->status == 'available')
                                            <span class="badge bg-success text-white">Trống</span>
                                        @elseif($room->status == 'occupied')
                                            <span class="badge bg-danger text-white">Đã đặt</span>
                                        @else
                                            <span class="badge bg-warning text-white">Bảo trì</span>
                                        @endif
                                    </td>
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
                    <p class="text-center">Khách sạn này chưa có phòng nào.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hình ảnh khách sạn</h6>
                </div>
                <div class="card-body">
                    <div id="hotelCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if($hotel->images && $hotel->images->count() > 0)
                                @foreach($hotel->images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($image->image_path) }}" class="d-block w-100" alt="Hotel Image" style="height: 300px; object-fit: cover;">
                                </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <img src="https://via.placeholder.com/800x600?text=Không+có+ảnh" class="d-block w-100" alt="No Image" style="height: 300px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                        @if($hotel->images && $hotel->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next">
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
                    <p><strong>Ngày tạo:</strong> {{ $hotel->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Cập nhật lần cuối:</strong> {{ $hotel->updated_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Tổng số phòng:</strong> {{ $hotel->rooms->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
