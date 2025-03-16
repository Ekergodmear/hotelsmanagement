@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Thêm Khách Sạn Mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Khách Sạn</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">Thành Phố</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Đánh Giá</label>
                            <input type="number" class="form-control @error('rating') is-invalid @enderror"
                                id="rating" name="rating" value="{{ old('rating') }}" step="0.1" min="0" max="10">
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price_per_night" class="form-label">Giá Mỗi Đêm (VNĐ)</label>
                            <input type="number" class="form-control @error('price_per_night') is-invalid @enderror"
                                id="price_per_night" name="price_per_night" value="{{ old('price_per_night') }}" required>
                            @error('price_per_night')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Tiện Nghi</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                    id="wifi" value="wifi" {{ in_array('wifi', old('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="wifi">WiFi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                    id="pool" value="pool" {{ in_array('pool', old('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="pool">Hồ Bơi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                    id="restaurant" value="restaurant" {{ in_array('restaurant', old('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="restaurant">Nhà Hàng</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                    id="spa" value="spa" {{ in_array('spa', old('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="spa">Spa</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                    id="gym" value="gym" {{ in_array('gym', old('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="gym">Phòng Gym</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="amenities[]"
                                    id="parking" value="parking" {{ in_array('parking', old('amenities', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="parking">Bãi Đậu Xe</label>
                            </div>
                            @error('amenities')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hotels.index') }}" class="btn btn-secondary">Quay Lại</a>
                            <button type="submit" class="btn btn-primary">Thêm Khách Sạn</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
