@extends('layouts.admin')

@section('title', 'Thêm dịch vụ đưa đón sân bay mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm dịch vụ đưa đón sân bay mới</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.airport-transfers.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.airport-transfers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tên dịch vụ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="vehicle_type">Loại phương tiện <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}" required>
                                    <small class="form-text text-muted">Ví dụ: Sedan, SUV, Minivan, Bus</small>
                                    @error('vehicle_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="max_passengers">Số hành khách tối đa <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_passengers') is-invalid @enderror" id="max_passengers" name="max_passengers" value="{{ old('max_passengers') }}" min="1" required>
                                    @error('max_passengers')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="max_luggage">Số hành lý tối đa <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_luggage') is-invalid @enderror" id="max_luggage" name="max_luggage" value="{{ old('max_luggage') }}" min="0" required>
                                    @error('max_luggage')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Giá dịch vụ <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                    </div>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả dịch vụ</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">Hình ảnh phương tiện</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                                        <label class="custom-file-label" for="image">Chọn file</label>
                                        @error('image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Hình ảnh phương tiện sẽ được hiển thị cho khách hàng</small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_popular" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_popular">Đánh dấu là dịch vụ phổ biến</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Kích hoạt dịch vụ</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Lưu dịch vụ</button>
                            <a href="{{ route('admin.airport-transfers.index') }}" class="btn btn-secondary">Hủy</a>
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
    $(document).ready(function() {
        // Hiển thị tên file khi chọn
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Định dạng số tiền
        $('#price').on('input', function() {
            var value = $(this).val();
            if (value !== '') {
                value = parseInt(value.replace(/,/g, ''));
                if (!isNaN(value)) {
                    $(this).val(value);
                }
            }
        });
    });
</script>
@endpush
