@extends('layouts.admin')

@section('title', 'Bảng điều khiển quản trị')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Bảng điều khiển</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Bảng điều khiển</li>
    </ol>

    <!-- Thống kê -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Khách sạn</h5>
                            <h2>{{ $totalHotels }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hotel fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.hotels.index') }}">Xem chi tiết</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Phòng</h5>
                            <h2>{{ $totalRooms }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-door-open fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.rooms.index') }}">Xem chi tiết</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Đặt phòng</h5>
                            <h2>{{ $totalBookings }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.bookings.index') }}">Xem chi tiết</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Người dùng</h5>
                            <h2>{{ $totalUsers }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('admin.users.index') }}">Xem chi tiết</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Khách sạn nổi bật -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-hotel me-1"></i>
                    Khách sạn nổi bật
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($hotels = \App\Models\Hotel::with('images')->orderBy('rating', 'desc')->take(4)->get() as $hotel)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div style="height: 150px; overflow: hidden;">
                                    @if($hotel->primaryImage)
                                        <img src="{{ Storage::url($hotel->primaryImage->image_path) }}" class="card-img-top" alt="{{ $hotel->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif($hotel->images->count() > 0)
                                        <img src="{{ Storage::url($hotel->images->first()->image_path) }}" class="card-img-top" alt="{{ $hotel->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="https://source.unsplash.com/800x600/?hotel&sig={{ $hotel->id }}" class="card-img-top" alt="{{ $hotel->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $hotel->name }}</h5>
                                    <p class="card-text">
                                        <i class="fas fa-star text-warning"></i> {{ $hotel->rating }}
                                        <span class="ms-2"><i class="fas fa-map-marker-alt text-danger"></i> {{ $hotel->city }}</span>
                                    </p>
                                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="btn btn-sm btn-primary">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-door-open me-1"></i>
                    Phòng nổi bật
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($rooms = \App\Models\Room::with(['images', 'hotel', 'roomType'])->inRandomOrder()->take(4)->get() as $room)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div style="height: 150px; overflow: hidden;">
                                    @if($room->primaryImage)
                                        <img src="{{ Storage::url($room->primaryImage->image_path) }}" class="card-img-top" alt="{{ $room->room_number }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif($room->images->count() > 0)
                                        <img src="{{ Storage::url($room->images->first()->image_path) }}" class="card-img-top" alt="{{ $room->room_number }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif($room->hotel->images->count() > 0)
                                        <img src="{{ Storage::url($room->hotel->images->first()->image_path) }}" class="card-img-top" alt="{{ $room->hotel->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="https://source.unsplash.com/800x600/?hotel,room&sig={{ $room->id }}" class="card-img-top" alt="{{ $room->room_number }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $room->room_number }}</h5>
                                    <p class="card-text">
                                        {{ $room->roomType->name ?? 'Không có loại phòng' }}
                                        <span class="d-block"><i class="fas fa-hotel text-primary"></i> {{ $room->hotel->name }}</span>
                                    </p>
                                    <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-sm btn-primary">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đặt phòng gần đây -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Đặt phòng gần đây
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Phòng</th>
                            <th>Ngày nhận phòng</th>
                            <th>Ngày trả phòng</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->name ?? 'N/A' }}</td>
                            <td>{{ $booking->room->room_number ?? 'N/A' }} ({{ $booking->room->hotel->name ?? 'N/A' }})</td>
                            <td>{{ $booking->check_in ? $booking->check_in->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $booking->check_out ? $booking->check_out->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="badge bg-info">Đã xác nhận</span>
                                @elseif($booking->status == 'checked_in')
                                    <span class="badge bg-primary">Đã nhận phòng</span>
                                @elseif($booking->status == 'checked_out')
                                    <span class="badge bg-success">Đã trả phòng</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">{{ $booking->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có đặt phòng nào gần đây</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Người dùng mới -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Người dùng mới đăng ký
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Ngày đăng ký</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Không có người dùng mới</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
