@extends('layouts.admin')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý phòng</h1>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm phòng mới
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách phòng</h6>
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
                            <th>Số phòng</th>
                            <th>Khách sạn</th>
                            <th>Loại phòng</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td>{{ $room->id }}</td>
                            <td>
                                @if($room->primaryImage)
                                    @if(Str::endsWith($room->primaryImage->image_path, '.txt'))
                                        <div class="text-center">
                                            <i class="fas fa-image fa-2x text-secondary"></i>
                                        </div>
                                    @else
                                        <img src="{{ Storage::url($room->primaryImage->image_path) }}" alt="{{ $room->room_number }}" width="50" height="50" class="img-thumbnail">
                                    @endif
                                @elseif($room->images && $room->images->count() > 0)
                                    @if(Str::endsWith($room->images->first()->image_path, '.txt'))
                                        <div class="text-center">
                                            <i class="fas fa-image fa-2x text-secondary"></i>
                                        </div>
                                    @else
                                        <img src="{{ Storage::url($room->images->first()->image_path) }}" alt="{{ $room->room_number }}" width="50" height="50" class="img-thumbnail">
                                    @endif
                                @elseif($room->hotel->images && $room->hotel->images->count() > 0)
                                    @if(Str::endsWith($room->hotel->images->first()->image_path, '.txt'))
                                        <div class="text-center" style="width: 50px; height: 50px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 0.25rem;">
                                            <i class="fas fa-image text-secondary"></i>
                                        </div>
                                    @else
                                        <img src="{{ Storage::url($room->hotel->images->first()->image_path) }}" alt="{{ $room->hotel->name }}" width="50" height="50" class="img-thumbnail">
                                    @endif
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-image fa-2x text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $room->room_number }}</td>
                            <td>
                                <a href="{{ route('admin.hotels.show', $room->hotel_id) }}">
                                    {{ $room->hotel->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.room-types.show', $room->room_type_id) }}">
                                    {{ $room->roomType->name }}
                                </a>
                            </td>
                            <td>{{ number_format($room->price, 0, ',', '.') }} VNĐ</td>
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
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
                            <td colspan="8" class="text-center">Không có phòng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $rooms->links() }}
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
