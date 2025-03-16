@extends('layouts.app')

@section('title', $airportTransfer->name . ' - Dịch vụ đưa đón sân bay')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h1 class="h2 mb-3">{{ $airportTransfer->name }}</h1>

                    <div class="mb-4">
                        @if($airportTransfer->image_path)
                            <img src="{{ $airportTransfer->image_url }}" alt="{{ $airportTransfer->name }}" class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/airport-transfers/default-' . strtolower($airportTransfer->vehicle_type) . '.jpg') }}" alt="Default" class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                        @endif
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3 text-white">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Loại phương tiện</h6>
                                    <p class="mb-0">{{ $airportTransfer->vehicle_type }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3 text-white">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Số hành khách tối đa</h6>
                                    <p class="mb-0">{{ $airportTransfer->max_passengers }} người</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3 text-white">
                                    <i class="fas fa-suitcase"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Số hành lý tối đa</h6>
                                    <p class="mb-0">{{ $airportTransfer->max_luggage }} kiện</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3 text-white">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Giá dịch vụ</h6>
                                    <p class="mb-0 text-primary fw-bold">{{ number_format($airportTransfer->price) }}đ</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($airportTransfer->description)
                        <div class="mb-4">
                            <h4 class="h5 mb-3">Mô tả dịch vụ</h4>
                            <p>{{ $airportTransfer->description }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4 class="h5 mb-3">Thông tin dịch vụ</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Đón tại sân bay</span>
                                <span><i class="fas fa-check text-success"></i></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Theo dõi chuyến bay</span>
                                <span><i class="fas fa-check text-success"></i></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Hỗ trợ hành lý</span>
                                <span><i class="fas fa-check text-success"></i></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Đón muộn không phụ phí</span>
                                <span><i class="fas fa-check text-success"></i></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Ghế trẻ em (theo yêu cầu)</span>
                                <span><i class="fas fa-check text-success"></i></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Đặt dịch vụ đưa đón</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('airport-transfers.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="transfer_type" value="{{ $airportTransfer->vehicle_type }}">

                        <div class="mb-3">
                            <label for="airport_name" class="form-label">Sân bay <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('airport_name') is-invalid @enderror" id="airport_name" name="airport_name" value="{{ old('airport_name', request('airport')) }}" required>
                            @error('airport_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="transfer_datetime" class="form-label">Ngày và giờ đón <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('transfer_datetime') is-invalid @enderror" id="transfer_datetime" name="transfer_datetime" value="{{ old('transfer_datetime') }}" required>
                            @error('transfer_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="transfer_passengers" class="form-label">Số hành khách <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('transfer_passengers') is-invalid @enderror" id="transfer_passengers" name="transfer_passengers" value="{{ old('transfer_passengers', 1) }}" min="1" max="{{ $airportTransfer->max_passengers }}" required>
                            <small class="form-text text-muted">Tối đa {{ $airportTransfer->max_passengers }} người</small>
                            @error('transfer_passengers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="transfer_notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('transfer_notes') is-invalid @enderror" id="transfer_notes" name="transfer_notes" rows="3">{{ old('transfer_notes') }}</textarea>
                            @error('transfer_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">điều khoản dịch vụ</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if(request('room_id'))
                                <input type="hidden" name="room_id" value="{{ request('room_id') }}">
                                <button type="submit" class="btn btn-primary">Đặt dịch vụ cho phòng này</button>
                            @elseif(request('booking_id'))
                                <input type="hidden" name="booking_id" value="{{ request('booking_id') }}">
                                <button type="submit" class="btn btn-primary">Thêm dịch vụ vào đặt phòng</button>
                            @else
                                <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary">Chọn phòng trước</a>
                                <p class="text-center text-muted small mt-2">Bạn cần chọn phòng trước khi đặt dịch vụ đưa đón</p>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Giá dịch vụ:</span>
                        <span class="text-primary fw-bold">{{ number_format($airportTransfer->price) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Các dịch vụ khác -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="h4 mb-4">Các dịch vụ đưa đón khác</h3>
        </div>

        @php $otherTransfers = $transfers->where('id', '!=', $airportTransfer->id)->take(3); @endphp

        @if($otherTransfers->count() > 0)
            @foreach($otherTransfers as $transfer)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            @if($transfer->image_path)
                                <img src="{{ $transfer->image_url }}" alt="{{ $transfer->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/airport-transfers/default-' . strtolower($transfer->vehicle_type) . '.jpg') }}" alt="Default" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @endif
                            @if($transfer->is_popular)
                                <div class="position-absolute top-0 end-0 p-2">
                                    <span class="badge bg-success">Phổ biến</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $transfer->name }}</h5>
                            <p class="card-text text-muted small">{{ $transfer->vehicle_type }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <i class="fas fa-users"></i> {{ $transfer->max_passengers }} người
                                </div>
                                <div>
                                    <i class="fas fa-suitcase"></i> {{ $transfer->max_luggage }} hành lý
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h5 class="text-primary mb-0">{{ number_format($transfer->price) }}đ</h5>
                                <a href="{{ route('airport-transfers.show', $transfer) }}?{{ http_build_query(request()->query()) }}" class="btn btn-outline-primary btn-sm">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    Không có dịch vụ đưa đón khác.
                </div>
            </div>
        @endif
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
    });
</script>
@endpush
