@extends('layouts.admin')

@section('title', 'Quản lý dịch vụ đưa đón sân bay')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách dịch vụ đưa đón sân bay</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.airport-transfers.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Thêm dịch vụ mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px">ID</th>
                                    <th style="width: 80px">Hình ảnh</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Loại phương tiện</th>
                                    <th>Số hành khách</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 150px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $transfer)
                                    <tr>
                                        <td>{{ $transfer->id }}</td>
                                        <td>
                                            @if($transfer->image_path)
                                                <img src="{{ $transfer->image_url }}" alt="{{ $transfer->name }}" class="img-thumbnail" style="max-height: 50px">
                                            @else
                                                <img src="{{ asset('images/airport-transfers/default-' . strtolower($transfer->vehicle_type) . '.jpg') }}" alt="Default" class="img-thumbnail" style="max-height: 50px">
                                            @endif
                                        </td>
                                        <td>
                                            {{ $transfer->name }}
                                            @if($transfer->is_popular)
                                                <span class="badge badge-success">Phổ biến</span>
                                            @endif
                                        </td>
                                        <td>{{ $transfer->vehicle_type }}</td>
                                        <td>{{ $transfer->max_passengers }} người / {{ $transfer->max_luggage }} hành lý</td>
                                        <td>{{ number_format($transfer->price) }}đ</td>
                                        <td>
                                            @if($transfer->is_active)
                                                <span class="badge badge-success">Hoạt động</span>
                                            @else
                                                <span class="badge badge-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.airport-transfers.show', $transfer) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.airport-transfers.edit', $transfer) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $transfer->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <!-- Modal xóa -->
                                            <div class="modal fade" id="deleteModal{{ $transfer->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $transfer->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $transfer->id }}">Xác nhận xóa</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Bạn có chắc chắn muốn xóa dịch vụ đưa đón "<strong>{{ $transfer->name }}</strong>" không?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                            <form action="{{ route('admin.airport-transfers.destroy', $transfer) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dịch vụ đưa đón sân bay nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị trang _PAGE_ / _PAGES_",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ dòng)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Sau",
                    "previous": "Trước"
                }
            },
            "responsive": true,
            "autoWidth": false
        });
    });
</script>
@endpush
