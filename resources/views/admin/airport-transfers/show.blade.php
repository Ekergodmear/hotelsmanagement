@extends('layouts.admin')

@section('title', 'Chi tiết dịch vụ đưa đón sân bay')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin dịch vụ</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.airport-transfers.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($airportTransfer->image_path)
                            <img src="{{ $airportTransfer->image_url }}" alt="{{ $airportTransfer->name }}" class="img-fluid rounded" style="max-height: 200px">
                        @else
                            <img src="{{ asset('images/airport-transfers/default-' . strtolower($airportTransfer->vehicle_type) . '.jpg') }}" alt="Default" class="img-fluid rounded" style="max-height: 200px">
                        @endif
                    </div>

                    <h4 class="text-center">{{ $airportTransfer->name }}</h4>

                    <div class="d-flex justify-content-center mb-3">
                        @if($airportTransfer->is_popular)
                            <span class="badge badge-success mx-1">Phổ biến</span>
                        @endif

                        @if($airportTransfer->is_active)
                            <span class="badge badge-primary mx-1">Hoạt động</span>
                        @else
                            <span class="badge badge-danger mx-1">Không hoạt động</span>
                        @endif
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{ $airportTransfer->id }}</td>
                        </tr>
                        <tr>
                            <th>Loại phương tiện</th>
                            <td>{{ $airportTransfer->vehicle_type }}</td>
                        </tr>
                        <tr>
                            <th>Số hành khách tối đa</th>
                            <td>{{ $airportTransfer->max_passengers }} người</td>
                        </tr>
                        <tr>
                            <th>Số hành lý tối đa</th>
                            <td>{{ $airportTransfer->max_luggage }} kiện</td>
                        </tr>
                        <tr>
                            <th>Giá dịch vụ</th>
                            <td>{{ number_format($airportTransfer->price) }}đ</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $airportTransfer->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Cập nhật lần cuối</th>
                            <td>{{ $airportTransfer->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    @if($airportTransfer->description)
                        <div class="mt-3">
                            <h5>Mô tả dịch vụ:</h5>
                            <p>{{ $airportTransfer->description }}</p>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.airport-transfers.edit', $airportTransfer) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </div>

                    <!-- Modal xóa -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc chắn muốn xóa dịch vụ đưa đón "<strong>{{ $airportTransfer->name }}</strong>" không?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                    <form action="{{ route('admin.airport-transfers.destroy', $airportTransfer) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách đặt phòng sử dụng dịch vụ này</h3>
                </div>
                <div class="card-body">
                    @if(count($bookings) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Khách hàng</th>
                                        <th>Phòng</th>
                                        <th>Sân bay</th>
                                        <th>Thời gian đón</th>
                                        <th>Số hành khách</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->id }}</td>
                                            <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($booking->room)
                                                    {{ $booking->room->room_number }} - {{ $booking->room->hotel->name ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $booking->airport_name }}</td>
                                            <td>{{ $booking->transfer_datetime->format('d/m/Y H:i') }}</td>
                                            <td>{{ $booking->transfer_passengers }}</td>
                                            <td>
                                                @if($booking->transfer_status == 'confirmed')
                                                    <span class="badge badge-success">Đã xác nhận</span>
                                                @elseif($booking->transfer_status == 'pending')
                                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                                @elseif($booking->transfer_status == 'cancelled')
                                                    <span class="badge badge-danger">Đã hủy</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $booking->transfer_status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            Chưa có đặt phòng nào sử dụng dịch vụ đưa đón này.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo DataTable
        $('.table').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "search": "Tìm kiếm:",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                "infoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                "infoFiltered": "(lọc từ _MAX_ mục)"
            }
        });
    });
</script>
@endpush
