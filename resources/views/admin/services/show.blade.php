@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết dịch vụ</h1>
        <div>
            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width: 200px">ID:</th>
                            <td>{{ $service->id }}</td>
                        </tr>
                        <tr>
                            <th>Tên dịch vụ:</th>
                            <td>{{ $service->name }}</td>
                        </tr>
                        <tr>
                            <th>Mô tả:</th>
                            <td>{{ $service->description }}</td>
                        </tr>
                        <tr>
                            <th>Giá:</th>
                            <td>{{ number_format($service->price) }} VNĐ</td>
                        </tr>
                        <tr>
                            <th>Loại dịch vụ:</th>
                            <td>
                                @switch($service->type)
                                    @case('room_service')
                                        Dịch vụ phòng
                                        @break
                                    @case('food_beverage')
                                        Đồ ăn & Thức uống
                                        @break
                                    @case('spa')
                                        Spa & Massage
                                        @break
                                    @case('laundry')
                                        Giặt ủi
                                        @break
                                    @default
                                        Khác
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge badge-{{ $service->is_active ? 'success' : 'danger' }}">
                                    {{ $service->is_active ? 'Đang hoạt động' : 'Ngừng hoạt động' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày tạo:</th>
                            <td>{{ $service->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật lần cuối:</th>
                            <td>{{ $service->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Thống kê sử dụng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center mb-4">
                                        <h6>Số lần đặt</h6>
                                        <h2>{{ $service->bookingServices()->count() }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center mb-4">
                                        <h6>Doanh thu</h6>
                                        <h2>{{ number_format($service->bookingServices()->sum('price')) }} VNĐ</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($service->bookingServices()->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Lịch sử sử dụng dịch vụ</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Đặt phòng</th>
                            <th>Khách hàng</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Ngày sử dụng</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($service->bookingServices()->latest()->get() as $bookingService)
                        <tr>
                            <td>
                                <a href="{{ route('admin.bookings.show', $bookingService->booking_id) }}">
                                    #{{ $bookingService->booking_id }}
                                </a>
                            </td>
                            <td>{{ $bookingService->booking->guest_name }}</td>
                            <td>{{ $bookingService->quantity }}</td>
                            <td>{{ number_format($bookingService->price) }} VNĐ</td>
                            <td>{{ $bookingService->service_date->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $bookingService->notes ?: 'Không có' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
