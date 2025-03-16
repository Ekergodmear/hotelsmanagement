@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm dịch vụ mới</h1>
        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Tên dịch vụ <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Mô tả <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Giá (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number"
                           class="form-control @error('price') is-invalid @enderror"
                           id="price"
                           name="price"
                           value="{{ old('price') }}"
                           min="0"
                           required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">Loại dịch vụ <span class="text-danger">*</span></label>
                    <select class="form-control @error('type') is-invalid @enderror"
                            id="type"
                            name="type"
                            required>
                        <option value="">Chọn loại dịch vụ</option>
                        <option value="room_service" {{ old('type') == 'room_service' ? 'selected' : '' }}>
                            Dịch vụ phòng
                        </option>
                        <option value="food_beverage" {{ old('type') == 'food_beverage' ? 'selected' : '' }}>
                            Đồ ăn & Thức uống
                        </option>
                        <option value="spa" {{ old('type') == 'spa' ? 'selected' : '' }}>
                            Spa & Massage
                        </option>
                        <option value="laundry" {{ old('type') == 'laundry' ? 'selected' : '' }}>
                            Giặt ủi
                        </option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>
                            Khác
                        </option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox"
                               class="custom-control-input"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Kích hoạt dịch vụ</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu dịch vụ
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
