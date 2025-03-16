@extends('layouts.admin')

@section('title', 'Thêm khách sạn mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm khách sạn mới</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tên khách sạn <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Địa chỉ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="province_id">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <select class="form-control @error('province_id') is-invalid @enderror" id="province_id" name="province_id" required>
                                        <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="district_id">Quận/Huyện <span class="text-danger">*</span></label>
                                    <select class="form-control @error('district_id') is-invalid @enderror" id="district_id" name="district_id" required>
                                        <option value="">-- Chọn Quận/Huyện --</option>
                                    </select>
                                    @error('district_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="ward_id">Phường/Xã <span class="text-danger">*</span></label>
                                    <select class="form-control @error('ward_id') is-invalid @enderror" id="ward_id" name="ward_id" required>
                                        <option value="">-- Chọn Phường/Xã --</option>
                                    </select>
                                    @error('ward_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tiện ích</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="wifi" name="amenities[]" value="wifi" {{ in_array('wifi', (array)old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="wifi">Wifi miễn phí</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="pool" name="amenities[]" value="pool" {{ in_array('pool', (array)old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="pool">Hồ bơi</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="restaurant" name="amenities[]" value="restaurant" {{ in_array('restaurant', (array)old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="restaurant">Nhà hàng</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="spa" name="amenities[]" value="spa" {{ in_array('spa', (array)old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="spa">Spa</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="gym" name="amenities[]" value="gym" {{ in_array('gym', (array)old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="gym">Phòng tập gym</label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="parking" name="amenities[]" value="parking" {{ in_array('parking', (array)old('amenities', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="parking">Bãi đậu xe</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="star_rating">Xếp hạng sao</label>
                                    <select class="form-control @error('star_rating') is-invalid @enderror" id="star_rating" name="star_rating">
                                        <option value="">Chọn xếp hạng</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('star_rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                        @endfor
                                    </select>
                                    @error('star_rating')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">Hình ảnh chính</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                                        <label class="custom-file-label" for="image">Chọn file</label>
                                        @error('image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="gallery">Bộ sưu tập hình ảnh</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('gallery') is-invalid @enderror" id="gallery" name="gallery[]" multiple>
                                        <label class="custom-file-label" for="gallery">Chọn nhiều file</label>
                                        @error('gallery')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Có thể chọn nhiều hình ảnh cùng lúc</small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_featured">Hiển thị nổi bật</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Kích hoạt</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Lưu khách sạn</button>
                            <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('Script starting...');
    console.log('jQuery version:', typeof $ !== 'undefined' ? $.fn.jquery : 'jQuery not loaded');

    // Set CSRF token cho tất cả các Ajax request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        console.log('Document ready...');
        // Xử lý dropdown tỉnh/thành phố
        $('#province_id').change(function() {
            var provinceId = $(this).val();
            console.log('Province ID selected:', provinceId);

            if (provinceId) {
                $.ajax({
                    url: "{{ route('admin.hotels.getDistricts') }}",
                    type: 'GET',
                    data: { province_id: provinceId },
                    beforeSend: function() {
                        console.log('Sending request for districts...');
                    },
                    success: function(data) {
                        console.log('Districts received:', data);
                        $('#district_id').empty();
                        $('#district_id').append('<option value="">-- Chọn Quận/Huyện --</option>');
                        $('#ward_id').empty();
                        $('#ward_id').append('<option value="">-- Chọn Phường/Xã --</option>');

                        $.each(data, function(key, value) {
                            $('#district_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('XHR:', xhr);
                        console.log('Status:', status);
                        console.log('Error:', error);
                        alert('Có lỗi khi tải danh sách quận/huyện. Vui lòng thử lại.');
                    }
                });
            } else {
                $('#district_id').empty();
                $('#district_id').append('<option value="">-- Chọn Quận/Huyện --</option>');
                $('#ward_id').empty();
                $('#ward_id').append('<option value="">-- Chọn Phường/Xã --</option>');
            }
        });

        // Xử lý dropdown quận/huyện
        $('#district_id').change(function() {
            var districtId = $(this).val();
            console.log('District ID selected:', districtId);

            if (districtId) {
                $.ajax({
                    url: "{{ route('admin.hotels.getWards') }}",
                    type: 'GET',
                    data: { district_id: districtId },
                    beforeSend: function() {
                        console.log('Sending request for wards...');
                    },
                    success: function(data) {
                        console.log('Wards received:', data);
                        $('#ward_id').empty();
                        $('#ward_id').append('<option value="">-- Chọn Phường/Xã --</option>');

                        $.each(data, function(key, value) {
                            $('#ward_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('XHR:', xhr);
                        console.log('Status:', status);
                        console.log('Error:', error);
                        alert('Có lỗi khi tải danh sách phường/xã. Vui lòng thử lại.');
                    }
                });
            } else {
                $('#ward_id').empty();
                $('#ward_id').append('<option value="">-- Chọn Phường/Xã --</option>');
            }
        });

        // Hiển thị tên file khi chọn
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Sử dụng trình soạn thảo cho mô tả
        if ($("#description").length) {
            $("#description").summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    });
</script>
@endpush
