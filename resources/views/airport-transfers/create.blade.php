@extends('layouts.app')

@section('title', 'Đặt dịch vụ đưa đón sân bay')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h2">Đặt dịch vụ đưa đón sân bay</h1>
            <p class="text-muted">Vui lòng chọn dịch vụ đưa đón phù hợp với nhu cầu của bạn</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($room)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin phòng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if($room->image_path)
                                    <img src="{{ asset('storage/' . $room->image_path) }}" alt="{{ $room->room_number }}" class="img-fluid rounded">
                                @else
                                    <img src="{{ asset('images/default-room.jpg') }}" alt="Default" class="img-fluid rounded">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h5>{{ $room->roomType->name ?? 'Phòng' }} {{ $room->room_number }}</h5>
                                <p class="mb-1"><strong>Khách sạn:</strong> {{ $room->hotel->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Địa chỉ:</strong> {{ $room->hotel->address ?? 'N/A' }}, {{ $room->hotel->city ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Giá phòng:</strong> {{ number_format($room->price) }}đ / đêm</p>

                                @if($booking)
                                    <div class="mt-3 p-3 bg-light rounded">
                                        <h6>Thông tin đặt phòng</h6>
                                        <p class="mb-1"><strong>Mã đặt phòng:</strong> #{{ $booking->id }}</p>
                                        <p class="mb-1"><strong>Ngày nhận phòng:</strong> {{ $booking->check_in->format('d/m/Y') }}</p>
                                        <p class="mb-1"><strong>Ngày trả phòng:</strong> {{ $booking->check_out->format('d/m/Y') }}</p>
                                        <p class="mb-0"><strong>Số khách:</strong> {{ $booking->guests }} người</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Chọn dịch vụ đưa đón</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('airport-transfers.store') }}" method="POST">
                        @csrf

                        @if($booking)
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        @elseif($room)
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                        @endif

                        <div class="mb-4">
                            <label for="airport_name" class="form-label">Sân bay <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('airport_name') is-invalid @enderror" id="airport_name" name="airport_name" value="{{ old('airport_name', $airport) }}" required>
                            <small class="form-text text-muted">Ví dụ: Sân bay Tân Sơn Nhất, Sân bay Nội Bài</small>
                            @error('airport_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="transfer_datetime" class="form-label">Ngày và giờ đón <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('transfer_datetime') is-invalid @enderror" id="transfer_datetime" name="transfer_datetime" value="{{ old('transfer_datetime') }}" required>
                            @error('transfer_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Chọn loại phương tiện <span class="text-danger">*</span></label>

                            <div class="row">
                                @foreach($transfers as $transfer)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 @if(old('transfer_type') == $transfer->vehicle_type) border-primary @endif">
                                            <div class="card-header bg-light">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="transfer_type" id="transfer_{{ $transfer->id }}" value="{{ $transfer->vehicle_type }}" @if(old('transfer_type') == $transfer->vehicle_type) checked @endif required>
                                                    <label class="form-check-label fw-bold" for="transfer_{{ $transfer->id }}">
                                                        {{ $transfer->name }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex mb-2">
                                                    <div class="flex-shrink-0">
                                                        @if($transfer->image_path)
                                                            <img src="{{ $transfer->image_url }}" alt="{{ $transfer->name }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                                        @else
                                                            <img src="{{ asset('images/airport-transfers/default-' . strtolower($transfer->vehicle_type) . '.jpg') }}" alt="Default" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <p class="card-text text-muted small mb-1">{{ $transfer->vehicle_type }}</p>
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <small><i class="fas fa-users"></i> {{ $transfer->max_passengers }} người</small>
                                                            <small><i class="fas fa-suitcase"></i> {{ $transfer->max_luggage }} hành lý</small>
                                                        </div>
                                                        <h5 class="text-primary mb-0">{{ number_format($transfer->price) }}đ</h5>
                                                    </div>
                                                </div>
                                                @if($transfer->description)
                                                    <p class="card-text small">{{ Str::limit($transfer->description, 100) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('transfer_type')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="transfer_passengers" class="form-label">Số hành khách <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('transfer_passengers') is-invalid @enderror" id="transfer_passengers" name="transfer_passengers" value="{{ old('transfer_passengers', $booking ? $booking->guests : 1) }}" min="1" required>
                            <small class="form-text text-muted">Vui lòng chọn số lượng hành khách phù hợp với loại phương tiện</small>
                            @error('transfer_passengers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="transfer_notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('transfer_notes') is-invalid @enderror" id="transfer_notes" name="transfer_notes" rows="3">{{ old('transfer_notes') }}</textarea>
                            <small class="form-text text-muted">Thông tin thêm về chuyến bay, yêu cầu đặc biệt, v.v.</small>
                            @error('transfer_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">điều khoản dịch vụ</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if($booking)
                                <button type="submit" class="btn btn-primary">Thêm dịch vụ vào đặt phòng</button>
                            @elseif($room)
                                <button type="submit" class="btn btn-primary">Đặt dịch vụ cho phòng này</button>
                            @else
                                <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary">Chọn phòng trước</a>
                                <p class="text-center text-muted small mt-2">Bạn cần chọn phòng trước khi đặt dịch vụ đưa đón</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin dịch vụ</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">Dịch vụ đưa đón sân bay bao gồm:</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex">
                            <i class="fas fa-check text-success me-3 mt-1"></i>
                            <div>
                                <strong>Đón tại sân bay</strong>
                                <p class="mb-0 small text-muted">Tài xế sẽ đón bạn tại khu vực đón khách của sân bay</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <i class="fas fa-check text-success me-3 mt-1"></i>
                            <div>
                                <strong>Theo dõi chuyến bay</strong>
                                <p class="mb-0 small text-muted">Chúng tôi theo dõi lịch trình chuyến bay của bạn</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <i class="fas fa-check text-success me-3 mt-1"></i>
                            <div>
                                <strong>Hỗ trợ hành lý</strong>
                                <p class="mb-0 small text-muted">Tài xế sẽ giúp bạn mang hành lý</p>
                            </div>
                        </li>
                        <li class="list-group-item d-flex">
                            <i class="fas fa-check text-success me-3 mt-1"></i>
                            <div>
                                <strong>Đón muộn không phụ phí</strong>
                                <p class="mb-0 small text-muted">Không tính phí nếu chuyến bay bị trễ</p>
                            </div>
                        </li>
                    </ul>

                    <h5 class="mb-3">Lưu ý:</h5>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><i class="fas fa-info-circle me-2"></i> Vui lòng cung cấp thông tin chính xác về chuyến bay và thời gian đến.</li>
                        <li class="mb-2"><i class="fas fa-info-circle me-2"></i> Tài xế sẽ đợi tối đa 60 phút sau khi chuyến bay hạ cánh.</li>
                        <li><i class="fas fa-info-circle me-2"></i> Bạn có thể hủy dịch vụ miễn phí trước 24 giờ so với thời gian đón.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Điều khoản -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Điều khoản dịch vụ đưa đón sân bay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Đặt dịch vụ</h5>
                <p>Khi đặt dịch vụ đưa đón sân bay, bạn cần cung cấp thông tin chính xác về chuyến bay, thời gian và số lượng hành khách.</p>

                <h5>2. Hủy dịch vụ</h5>
                <p>Bạn có thể hủy dịch vụ đưa đón miễn phí trước 24 giờ so với thời gian đón. Nếu hủy trong vòng 24 giờ, phí hủy sẽ là 50% giá dịch vụ.</p>

                <h5>3. Thay đổi lịch trình</h5>
                <p>Thay đổi lịch trình có thể được thực hiện miễn phí trước 12 giờ so với thời gian đón ban đầu, tùy thuộc vào tình trạng sẵn có.</p>

                <h5>4. Đón muộn</h5>
                <p>Chúng tôi theo dõi chuyến bay của bạn và điều chỉnh thời gian đón nếu chuyến bay bị trễ mà không tính thêm phí.</p>

                <h5>5. Hành lý</h5>
                <p>Mỗi phương tiện có giới hạn hành lý khác nhau. Vui lòng đảm bảo bạn chọn phương tiện phù hợp với số lượng hành lý của mình.</p>

                <h5>6. Thanh toán</h5>
                <p>Thanh toán được thực hiện khi đặt phòng hoặc trực tiếp với tài xế. Chúng tôi chấp nhận tiền mặt và các hình thức thanh toán điện tử.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đồng ý</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Thiết lập giá trị mặc định cho ngày giờ đón
        if (!$('#transfer_datetime').val()) {
            var now = new Date();
            now.setDate(now.getDate() + 1);
            now.setHours(12, 0, 0, 0);

            var dateString = now.toISOString().slice(0, 16);
            $('#transfer_datetime').val(dateString);
        }

        // Highlight thẻ khi chọn radio
        $('input[name="transfer_type"]').change(function() {
            $('.card').removeClass('border-primary');
            $(this).closest('.card').addClass('border-primary');
        });
    });
</script>
@endpush
