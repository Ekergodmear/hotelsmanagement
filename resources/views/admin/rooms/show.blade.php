@extends('layouts.admin')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết phòng: {{ $room->room_number }}</h1>
        <div>
            <a href="{{ route('admin.hotels.rooms', $room->hotel_id) }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách phòng
            </a>
            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin phòng</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="font-weight-bold">Hình ảnh</h5>
                            <div class="row">
                                @if($room->images && $room->images->count() > 0)
                                    @foreach($room->images as $image)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                @if(Str::endsWith($image->image_path, '.txt'))
                                                    <div class="card h-100">
                                                        <div class="card-body d-flex align-items-center justify-content-center">
                                                            <p class="mb-0">{{ basename($image->image_path) }}</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <img src="{{ Storage::url($image->image_path) }}" class="card-img-top" alt="{{ $room->room_number }}" style="height: 200px; object-fit: cover;">
                                                @endif
                                                <div class="card-body p-2 text-center">
                                                    @if($image->is_primary)
                                                        <span class="badge badge-success">Ảnh chính</span>
                                                    @else
                                                        <span class="badge badge-secondary">Ảnh #{{ $image->display_order }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            Phòng này chưa có ảnh nào.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Thông tin cơ bản</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 40%">Số phòng</th>
                                    <td>{{ $room->room_number }}</td>
                                </tr>
                                <tr>
                                    <th>Khách sạn</th>
                                    <td>
                                        <a href="{{ route('admin.hotels.show', $room->hotel_id) }}">
                                            {{ $room->hotel->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Loại phòng</th>
                                    <td>
                                        <a href="{{ route('admin.room-types.show', $room->room_type_id) }}">
                                            {{ $room->roomType->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Giá</th>
                                    <td>{{ number_format($room->price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        @if($room->status == 'available')
                                            <span class="badge bg-success text-white">Trống</span>
                                        @elseif($room->status == 'occupied')
                                            <span class="badge bg-danger text-white">Đã đặt</span>
                                        @else
                                            <span class="badge bg-warning text-white">Bảo trì</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Thông tin khác</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 40%">Sức chứa</th>
                                    <td>{{ $room->roomType->capacity ?? 'N/A' }} người</td>
                                </tr>
                                <tr>
                                    <th>Diện tích</th>
                                    <td>{{ $room->roomType->size ?? 'N/A' }} m²</td>
                                </tr>
                                <tr>
                                    <th>Tiện nghi</th>
                                    <td>
                                        <div class="mb-4">
                                            <h5 class="font-weight-bold">Tiện nghi</h5>
                                            <div class="row">
                                                @if($room->roomType->amenities)
                                                    @php
                                                        $amenities = is_array($room->roomType->amenities)
                                                            ? $room->roomType->amenities
                                                            : json_decode($room->roomType->amenities, true) ?? [];
                                                    @endphp
                                                    @foreach($amenities as $amenity)
                                                        <div class="col-md-4 mb-2">
                                                            <i class="fas fa-check text-success"></i> {{ $amenity }}
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-12">
                                                        <p class="text-muted">Không có tiện nghi nào được chỉ định.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $room->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $room->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($room->description)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="font-weight-bold">Mô tả</h5>
                            <div class="card">
                                <div class="card-body">
                                    {{ $room->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử đặt phòng</h6>
                </div>
                <div class="card-body">
                    @if($room->bookings && $room->bookings->count() > 0)
                        <div class="list-group">
                            @foreach($room->bookings->sortByDesc('check_in_date') as $booking)
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Đặt phòng #{{ $booking->id }}</h5>
                                        <small>{{ $booking->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <strong>Khách hàng:</strong> {{ $booking->user->name }}<br>
                                        <strong>Check-in:</strong> {{ date('d/m/Y', strtotime($booking->check_in_date)) }}<br>
                                        <strong>Check-out:</strong> {{ date('d/m/Y', strtotime($booking->check_out_date)) }}
                                    </p>
                                    <small>
                                        <span class="badge
                                            @if($booking->status == 'confirmed') badge-success
                                            @elseif($booking->status == 'pending') badge-warning
                                            @elseif($booking->status == 'cancelled') badge-danger
                                            @else badge-secondary
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            Phòng này chưa có lịch sử đặt phòng nào.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
