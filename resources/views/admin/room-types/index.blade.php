@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý loại phòng</h1>
        <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm loại phòng mới
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách loại phòng</h6>
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
                            <th>Tên loại phòng</th>
                            <th>Giá cơ bản</th>
                            <th>Sức chứa</th>
                            <th>Số phòng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomTypes as $roomType)
                        <tr>
                            <td>{{ $roomType->id }}</td>
                            <td>
                                @php
                                    $firstRoom = $roomType->rooms()->with('images')->first();
                                @endphp

                                @if($firstRoom && $firstRoom->primaryImage)
                                    <img src="{{ Storage::url($firstRoom->primaryImage->image_path) }}" alt="{{ $roomType->name }}" width="50" height="50" class="img-thumbnail">
                                @elseif($firstRoom && $firstRoom->images && $firstRoom->images->count() > 0)
                                    <img src="{{ Storage::url($firstRoom->images->first()->image_path) }}" alt="{{ $roomType->name }}" width="50" height="50" class="img-thumbnail">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-image fa-2x text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $roomType->name }}</td>
                            <td>{{ number_format($roomType->base_price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $roomType->capacity }} người</td>
                            <td>{{ $roomType->rooms_count }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.room-types.show', $roomType->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.room-types.edit', $roomType->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.room-types.destroy', $roomType->id) }}" method="POST" class="d-inline">
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
                            <td colspan="7" class="text-center">Không có loại phòng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $roomTypes->links() }}
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






