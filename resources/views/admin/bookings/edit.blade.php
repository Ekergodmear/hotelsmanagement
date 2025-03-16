@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Chỉnh sửa đặt phòng #{{ $booking->id }}</h1>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Thông tin phòng -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Thông tin phòng</h4>

                        <div class="mb-3">
                            <label for="room_id" class="form-label">Phòng <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                                <option value="">Chọn phòng</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_number }} - {{ $room->roomType->name }} ({{ number_format($room->price) }} VNĐ/đêm)
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_in" class="form-label">Ngày nhận phòng <span class="text-danger">*</span></label>
                                    <input type="date" name="check_in" id="check_in"
                                           class="form-control @error('check_in') is-invalid @enderror"
                                           value="{{ old('check_in', $booking->check_in->format('Y-m-d')) }}" required>
                                    @error('check_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_out" class="form-label">Ngày trả phòng <span class="text-danger">*</span></label>
                                    <input type="date" name="check_out" id="check_out"
                                           class="form-control @error('check_out') is-invalid @enderror"
                                           value="{{ old('check_out', $booking->check_out->format('Y-m-d')) }}" required>
                                    @error('check_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adults" class="form-label">Số người lớn <span class="text-danger">*</span></label>
                                    <input type="number" name="adults" id="adults"
                                           class="form-control @error('adults') is-invalid @enderror"
                                           value="{{ old('adults', $booking->adults) }}" min="1" required>
                                    @error('adults')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="children" class="form-label">Số trẻ em</label>
                                    <input type="number" name="children" id="children"
                                           class="form-control @error('children') is-invalid @enderror"
                                           value="{{ old('children', $booking->children) }}" min="0">
                                    @error('children')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin khách hàng -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Thông tin khách hàng</h4>

                        <div class="mb-3">
                            <label for="guest_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="guest_name" id="guest_name"
                                   class="form-control @error('guest_name') is-invalid @enderror"
                                   value="{{ old('guest_name', $booking->guest_name) }}" required>
                            @error('guest_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="guest_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="guest_email" id="guest_email"
                                   class="form-control @error('guest_email') is-invalid @enderror"
                                   value="{{ old('guest_email', $booking->guest_email) }}" required>
                            @error('guest_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="guest_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="guest_phone" id="guest_phone"
                                   class="form-control @error('guest_phone') is-invalid @enderror"
                                   value="{{ old('guest_phone', $booking->guest_phone) }}" required>
                            @error('guest_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="special_requests" class="form-label">Yêu cầu đặc biệt</label>
                            <textarea name="special_requests" id="special_requests" rows="3"
                                      class="form-control @error('special_requests') is-invalid @enderror">{{ old('special_requests', $booking->special_requests) }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="checked_in" {{ old('status', $booking->status) == 'checked_in' ? 'selected' : '' }}>Đã nhận phòng</option>
                                <option value="checked_out" {{ old('status', $booking->status) == 'checked_out' ? 'selected' : '' }}>Đã trả phòng</option>
                                <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary me-2">Hủy</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tính tổng tiền khi thay đổi phòng hoặc ngày
    function calculateTotal() {
        const roomSelect = document.getElementById('room_id');
        const checkIn = new Date(document.getElementById('check_in').value);
        const checkOut = new Date(document.getElementById('check_out').value);

        if (roomSelect.value && checkIn && checkOut) {
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            const roomPrice = parseFloat(selectedOption.textContent.match(/(\d+(?:,\d+)*)/)[0].replace(/,/g, ''));
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

            if (nights > 0) {
                const total = roomPrice * nights;
                document.getElementById('total-price').textContent = new Intl.NumberFormat('vi-VN').format(total);
            }
        }
    }

    document.getElementById('room_id').addEventListener('change', calculateTotal);
    document.getElementById('check_in').addEventListener('change', calculateTotal);
    document.getElementById('check_out').addEventListener('change', calculateTotal);
});
</script>
@endpush
@endsection
