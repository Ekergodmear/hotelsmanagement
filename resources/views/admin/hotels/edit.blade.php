@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa khách sạn</h1>
        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin khách sạn</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.hotels.update', $hotel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Tên khách sạn <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $hotel->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rating">Đánh giá (sao)</label>
                            <input type="number" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" value="{{ old('rating', $hotel->rating) }}" min="0" max="10" step="0.1">
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address">Địa chỉ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $hotel->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="province_city">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <select class="form-control @error('province_city') is-invalid @enderror" id="province_city" name="province_city" required>
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ $selectedProvinceId == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="district">Quận/Huyện <span class="text-danger">*</span></label>
                            <select class="form-control @error('district') is-invalid @enderror" id="district" name="district" required>
                                <option value="">-- Chọn Quận/Huyện --</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ $selectedDistrictId == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ward">Phường/Xã</label>
                            <select class="form-control @error('ward') is-invalid @enderror" id="ward" name="ward">
                                <option value="">-- Chọn Phường/Xã --</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}" {{ $selectedWardId == $ward->id ? 'selected' : '' }}>
                                        {{ $ward->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ward')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="images">Hình ảnh (tối đa 5 ảnh)</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*" onchange="previewImages(this)">
                            <small class="form-text text-muted">Chọn tối đa 5 ảnh. Nếu chọn ảnh mới, ảnh cũ sẽ bị xóa.</small>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="mt-3">
                                <div class="row" id="image-preview-container">
                                    @if($hotel->images && $hotel->images->count() > 0)
                                        @foreach($hotel->images as $image)
                                            <div class="col-md-2 mb-2 existing-image">
                                                <img src="{{ Storage::url($image->image_path) }}" alt="Hotel Image" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $hotel->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Tiện nghi</label>
                            <div class="row mt-2">
                                @php
                                    $amenities = is_array($hotel->amenities) ? $hotel->amenities : (json_decode($hotel->amenities, true) ?? []);
                                @endphp
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="wifi" {{ in_array('wifi', $amenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="wifi">Wi-Fi miễn phí</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="pool" id="pool" {{ in_array('pool', $amenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pool">Hồ bơi</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="restaurant" id="restaurant" {{ in_array('restaurant', $amenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="restaurant">Nhà hàng</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="spa" id="spa" {{ in_array('spa', $amenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="spa">Spa</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="gym" id="gym" {{ in_array('gym', $amenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gym">Phòng tập gym</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="parking" id="parking" {{ in_array('parking', $amenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="parking">Bãi đỗ xe</label>
                                    </div>
                                </div>
                            </div>
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

@section('scripts')
<script>
    $(document).ready(function() {
        // Xử lý khi thay đổi tỉnh/thành phố
        $('#province_city').change(function() {
            var provinceId = $(this).val();
            if (provinceId) {
                // Lấy danh sách quận/huyện
                $.ajax({
                    url: "{{ route('admin.get-districts') }}",
                    type: "GET",
                    data: { province_id: provinceId },
                    success: function(data) {
                        $('#district').empty();
                        $('#district').append('<option value="">-- Chọn Quận/Huyện --</option>');
                        $.each(data, function(key, value) {
                            $('#district').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        // Xóa danh sách phường/xã
                        $('#ward').empty();
                        $('#ward').append('<option value="">-- Chọn Phường/Xã --</option>');
                    }
                });
            } else {
                $('#district').empty();
                $('#district').append('<option value="">-- Chọn Quận/Huyện --</option>');
                $('#ward').empty();
                $('#ward').append('<option value="">-- Chọn Phường/Xã --</option>');
            }
        });

        // Xử lý khi thay đổi quận/huyện
        $('#district').change(function() {
            var districtId = $(this).val();
            if (districtId) {
                // Lấy danh sách phường/xã
                $.ajax({
                    url: "{{ route('admin.get-wards') }}",
                    type: "GET",
                    data: { district_id: districtId },
                    success: function(data) {
                        $('#ward').empty();
                        $('#ward').append('<option value="">-- Chọn Phường/Xã --</option>');
                        $.each(data, function(key, value) {
                            $('#ward').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#ward').empty();
                $('#ward').append('<option value="">-- Chọn Phường/Xã --</option>');
            }
        });
    });

    // Xem trước ảnh
    function previewImages(input) {
        // Xóa ảnh cũ
        $('.existing-image').hide();

        var preview = document.getElementById('image-preview-container');
        preview.innerHTML = '';

        if (input.files) {
            // Giới hạn số lượng ảnh
            var maxFiles = 5;
            var filesToPreview = Array.from(input.files).slice(0, maxFiles);

            if (input.files.length > maxFiles) {
                alert('Bạn chỉ có thể tải lên tối đa ' + maxFiles + ' ảnh.');
            }

            filesToPreview.forEach(function(file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = 'col-md-2 mb-2';

                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style = 'height: 100px; object-fit: cover;';

                    div.appendChild(img);
                    preview.appendChild(div);
                }

                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection
