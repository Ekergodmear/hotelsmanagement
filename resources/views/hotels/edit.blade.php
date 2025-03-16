@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Chỉnh sửa Khách sạn</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên khách sạn</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $hotel->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                   id="address" name="address" value="{{ old('address', $hotel->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">Thành phố</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city" value="{{ old('city', $hotel->city) }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="4" required>{{ old('description', $hotel->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Đánh giá (0-10)</label>
                            <input type="number" class="form-control @error('rating') is-invalid @enderror"
                                   id="rating" name="rating" value="{{ old('rating', $hotel->rating) }}" step="0.1" min="0" max="10">
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price_per_night" class="form-label">Giá mỗi đêm (VNĐ)</label>
                            <input type="number" class="form-control @error('price_per_night') is-invalid @enderror"
                                   id="price_per_night" name="price_per_night" value="{{ old('price_per_night', $hotel->price_per_night) }}" required>
                            @error('price_per_night')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh</label>
                            @if($hotel->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($hotel->image) }}" alt="Current Image"
                                         class="img-thumbnail" style="max-height: 200px">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiện nghi</label>
                            <div class="row">
                                @php
                                    $amenities = [
                                        'wifi' => 'Wifi miễn phí',
                                        'pool' => 'Hồ bơi',
                                        'parking' => 'Bãi đậu xe',
                                        'restaurant' => 'Nhà hàng',
                                        'gym' => 'Phòng gym',
                                        'spa' => 'Spa'
                                    ];
                                @endphp
                                @foreach($amenities as $key => $amenity)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="amenities[]" value="{{ $key }}"
                                                   id="amenity_{{ $key }}"
                                                   {{ in_array($key, old('amenities', $hotel->amenities ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="amenity_{{ $key }}">
                                                {{ $amenity }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('amenities')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hotels.index') }}" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-primary">Cập nhật khách sạn</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
