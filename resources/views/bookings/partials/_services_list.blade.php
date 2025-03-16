<!-- Danh sách dịch vụ đã đặt -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Dịch vụ đã đặt</h6>
        <button type="button"
                class="btn btn-primary btn-sm"
                data-toggle="modal"
                data-target="#addServiceModal">
            <i class="fas fa-plus"></i> Thêm dịch vụ
        </button>
    </div>
    <div class="card-body">
        @if($booking->bookingServices->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Dịch vụ</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                            <th>Ngày sử dụng</th>
                            <th>Ghi chú</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->bookingServices as $bookingService)
                            <tr>
                                <td>{{ $bookingService->service->name }}</td>
                                <td>{{ $bookingService->quantity }}</td>
                                <td>{{ number_format($bookingService->price) }} VNĐ</td>
                                <td>{{ number_format($bookingService->price * $bookingService->quantity) }} VNĐ</td>
                                <td>{{ $bookingService->service_date->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $bookingService->notes ?: 'Không có' }}</td>
                                <td>
                                    <form action="{{ route('admin.booking-services.destroy', $bookingService) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Tổng cộng:</th>
                            <th colspan="4">
                                {{ number_format($booking->bookingServices->sum(function($service) {
                                    return $service->price * $service->quantity;
                                })) }} VNĐ
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <p class="text-center mb-0">Chưa có dịch vụ nào được đặt</p>
        @endif
    </div>
</div>

<!-- Modal thêm dịch vụ -->
<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServiceModalLabel">Thêm dịch vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('bookings.partials._add_service_form')
            </div>
        </div>
    </div>
</div>
