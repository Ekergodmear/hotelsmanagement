@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý khách sạn</h1>
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm khách sạn mới
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách khách sạn</h6>
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên khách sạn</th>
                            <th>Địa chỉ</th>
                            <th>Tỉnh/Thành phố</th>
                            <th>Quận/Huyện</th>
                            <th>Đánh giá</th>
                            <th>Phòng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hotels as $hotel)
                        <tr>
                            <td>{{ $hotel->id }}</td>
                            <td>
                                @if($hotel->primaryImage)
                                    <img src="{{ Storage::url($hotel->primaryImage->image_path) }}" alt="{{ $hotel->name }}" width="50" height="50" class="img-thumbnail">
                                @elseif($hotel->images->count() > 0)
                                    <img src="{{ Storage::url($hotel->images->first()->image_path) }}" alt="{{ $hotel->name }}" width="50" height="50" class="img-thumbnail">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-image fa-2x text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $hotel->name }}</td>
                            <td>{{ $hotel->address }}</td>
                            <td>{{ $hotel->province_city }}</td>
                            <td>
                                @if(!empty($hotel->district))
                                    {{ $hotel->district }}
                                @else
                                    <span class="text-muted">Chưa cập nhật</span>
                                @endif
                            </td>
                            <td>{{ $hotel->rating ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.hotels.rooms', $hotel->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-bed"></i> Xem phòng
                                </a>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.hotels.show', $hotel->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Không có khách sạn nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $hotels->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Tìm kiếm
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#dataTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection
