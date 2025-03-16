@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Chi tiết khách hàng</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thông tin khách hàng #{{ $customer->id }}</h5>
                <div>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="font-medium mb-4">Thông tin cá nhân</h4>
                        <div class="mb-4">
                            <p><span class="font-medium">Họ tên:</span> {{ $customer->name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $customer->email }}</p>
                            <p><span class="font-medium">Điện thoại:</span> {{ $customer->phone ?? 'N/A' }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $customer->address ?? 'N/A' }}</p>
                            <p><span class="font-medium">Ngày tạo:</span> {{ $customer->created_at->format('d/m/Y H:i') }}</p>
                            <p><span class="font-medium">Cập nhật lần cuối:</span> {{ $customer->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="font-medium mb-4">Thống kê đặt phòng</h4>
                        <div class="mb-4">
                            <p><span class="font-medium">Tổng số lần đặt phòng:</span> {{ $bookings->count() }}</p>
                            <p><span class="font-medium">Đặt phòng đang chờ:</span> {{ $bookings->where('status', 'pending')->count() }}</p>
                            <p><span class="font-medium">Đặt phòng đã xác nhận:</span> {{ $bookings->where('status', 'confirmed')->count() }}</p>
                            <p><span class="font-medium">Đặt phòng đã check-in:</span> {{ $bookings->where('status', 'checked_in')->count() }}</p>
                            <p><span class="font-medium">Đặt phòng đã check-out:</span> {{ $bookings->where('status', 'checked_out')->count() }}</p>
                            <p><span class="font-medium">Đặt phòng đã hủy:</span> {{ $bookings->where('status', 'cancelled')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lịch sử đặt phòng</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Phòng</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Trạng thái</th>
                                <th>Tổng tiền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $booking->check_in ? $booking->check_in->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $booking->check_out ? $booking->check_out->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning">Chờ xác nhận</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-primary">Đã xác nhận</span>
                                    @elseif($booking->status == 'checked_in')
                                        <span class="badge bg-success">Đã nhận phòng</span>
                                    @elseif($booking->status == 'checked_out')
                                        <span class="badge bg-secondary">Đã trả phòng</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @endif
                                </td>
                                <td>{{ number_format($booking->total_price) }} VNĐ</td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info btn-sm">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có đặt phòng nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
