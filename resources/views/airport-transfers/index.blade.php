@extends('layouts.app')

@section('title', 'Dịch vụ đưa đón sân bay')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <h1 class="h2 mb-3">Dịch vụ đưa đón sân bay</h1>
                <p class="text-muted">Chúng tôi cung cấp dịch vụ đưa đón sân bay chất lượng cao, đảm bảo sự thoải mái và an toàn cho chuyến đi của bạn.</p>
            </div>
        </div>
    </div>

    <!-- Dịch vụ phổ biến -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="h4 mb-4">Dịch vụ phổ biến</h2>
        </div>

        @php $popularTransfers = $transfers->where('is_popular', true); @endphp

        @if($popularTransfers->count() > 0)
            @foreach($popularTransfers as $transfer)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            @if($transfer->image_path)
                                <img src="{{ $transfer->image_url }}" alt="{{ $transfer->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/airport-transfers/default-' . strtolower($transfer->vehicle_type) . '.jpg') }}" alt="Default" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-success">Phổ biến</span>
                            </div>
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
                            @if($transfer->description)
                                <p class="card-text small">{{ Str::limit($transfer->description, 100) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h5 class="text-primary mb-0">{{ number_format($transfer->price) }}đ</h5>
                                <a href="{{ route('airport-transfers.show', $transfer) }}" class="btn btn-outline-primary btn-sm">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    Hiện chưa có dịch vụ đưa đón phổ biến nào.
                </div>
            </div>
        @endif
    </div>

    <!-- Tất cả dịch vụ -->
    <div class="row">
        <div class="col-12">
            <h2 class="h4 mb-4">Tất cả dịch vụ đưa đón</h2>
        </div>

        @if($transfers->count() > 0)
            @foreach($transfers as $transfer)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            @if($transfer->image_path)
                                <img src="{{ $transfer->image_url }}" alt="{{ $transfer->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/airport-transfers/default-' . strtolower($transfer->vehicle_type) . '.jpg') }}" alt="Default" class="card-img-top" style="height: 200px; object-fit: cover;">
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
                            @if($transfer->description)
                                <p class="card-text small">{{ Str::limit($transfer->description, 100) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h5 class="text-primary mb-0">{{ number_format($transfer->price) }}đ</h5>
                                <a href="{{ route('airport-transfers.show', $transfer) }}" class="btn btn-outline-primary btn-sm">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    Hiện chưa có dịch vụ đưa đón sân bay nào.
                </div>
            </div>
        @endif
    </div>

    <!-- Thông tin thêm -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h3 class="h5 mb-3">Thông tin về dịch vụ đưa đón sân bay</h3>
                    <p>Dịch vụ đưa đón sân bay của chúng tôi cung cấp nhiều lựa chọn phương tiện phù hợp với nhu cầu của bạn, từ xe sedan sang trọng đến xe minivan rộng rãi cho nhóm đông người.</p>
                    <p>Đội ngũ tài xế chuyên nghiệp, thông thạo đường đi và có kinh nghiệm lái xe an toàn sẽ đảm bảo bạn đến điểm đến đúng giờ và thoải mái.</p>
                    <p>Để đặt dịch vụ đưa đón sân bay, vui lòng chọn loại phương tiện phù hợp và điền thông tin chi tiết về chuyến bay của bạn.</p>

                    <h4 class="h6 mt-4">Các lợi ích khi sử dụng dịch vụ:</h4>
                    <ul>
                        <li>Đón tiễn đúng giờ, không phải chờ đợi</li>
                        <li>Giá cả cạnh tranh và minh bạch</li>
                        <li>Đa dạng loại xe phù hợp với mọi nhu cầu</li>
                        <li>Tài xế chuyên nghiệp, thân thiện</li>
                        <li>Dịch vụ 24/7, hỗ trợ đặt xe khẩn cấp</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
