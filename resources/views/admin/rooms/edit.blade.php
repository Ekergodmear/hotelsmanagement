@extends('layouts.admin')

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa phòng</h1>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin phòng</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="room_number">Số phòng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Trống</option>
                                <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Đã đặt</option>
                                <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hotel_id">Khách sạn <span class="text-danger">*</span></label>
                            <select class="form-control @error('hotel_id') is-invalid @enderror" id="hotel_id" name="hotel_id" required>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" {{ $room->hotel_id == $hotel->id ? 'selected' : '' }}>
                                        {{ $hotel->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="room_type_id">Loại phòng <span class="text-danger">*</span></label>
                            <select class="form-control @error('room_type_id') is-invalid @enderror" id="room_type_id" name="room_type_id" required>
                                @foreach($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}" {{ $room->room_type_id == $roomType->id ? 'selected' : '' }}>
                                        {{ $roomType->name }} ({{ number_format($roomType->base_price, 0, ',', '.') }} VNĐ)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $room->price) }}" min="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="images">Hình ảnh (tối đa 5 ảnh)</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                            <small class="form-text text-muted">Tải lên ảnh mới sẽ xóa tất cả ảnh cũ. Chỉ chọn ảnh nếu bạn muốn thay đổi.</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Ảnh hiện tại</label>
                        <div class="d-flex flex-wrap">
                            @if($room->images && $room->images->count() > 0)
                                @foreach($room->images as $image)
                                    <div class="mr-2 mb-2">
                                        @if(Str::endsWith($image->image_path, '.txt'))
                                            <div class="text-center">
                                                <small class="d-block">{{ basename($image->image_path) }}</small>
                                            </div>
                                        @else
                                            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $room->room_number }}" class="img-thumbnail" style="height: 100px; width: 100px; object-fit: cover;">
                                        @endif
                                        @if($image->is_primary)
                                            <span class="badge badge-success">Ảnh chính</span>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Chưa có ảnh nào</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">Ghi chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $room->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
