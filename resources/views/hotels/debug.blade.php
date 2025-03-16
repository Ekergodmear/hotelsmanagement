@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Debug Dữ Liệu Khách Sạn</h1>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Thông tin chi tiết về dữ liệu tiện nghi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khách sạn</th>
                                    <th>Dữ liệu gốc (Raw)</th>
                                    <th>Dữ liệu đã cast</th>
                                    <th>Kiểu dữ liệu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($amenitiesInfo as $info)
                                <tr>
                                    <td>{{ $info['id'] }}</td>
                                    <td>{{ $info['name'] }}</td>
                                    <td><pre>{{ $info['amenities_raw'] }}</pre></td>
                                    <td><pre>{{ print_r($info['amenities_cast'], true) }}</pre></td>
                                    <td>{{ $info['amenities_type'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($hotels as $hotel)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $hotel->name }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $hotel->id }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $hotel->address }}</p>
                    <p><strong>Xếp hạng:</strong> {{ $hotel->rating }} sao</p>
                    <p><strong>Giá mỗi đêm:</strong> {{ number_format($hotel->price_per_night, 0, ',', '.') }} VNĐ</p>

                    <h6>Tiện nghi:</h6>
                    <p><strong>Raw data:</strong> {{ is_array($hotel->amenities) ? json_encode($hotel->amenities) : $hotel->amenities }}</p>

                    @if(is_array($hotel->amenities))
                    <ul>
                        @foreach($hotel->amenities as $amenity)
                        <li>{{ $amenity }}</li>
                        @endforeach
                    </ul>
                    @else
                    <p>Không phải mảng: {{ gettype($hotel->amenities) }}</p>

                    @if(is_string($hotel->amenities))
                    <p>Thử phân tích JSON:</p>
                    @php
                    $parsedAmenities = json_decode($hotel->amenities, true);
                    @endphp

                    @if(is_array($parsedAmenities))
                    <ul>
                        @foreach($parsedAmenities as $amenity)
                        <li>{{ $amenity }}</li>
                        @endforeach
                    </ul>
                    @else
                    <p>Không thể phân tích JSON</p>
                    @endif
                    @endif
                    @endif

                    <h6>Phòng:</h6>
                    <ul>
                        @foreach($hotel->rooms as $room)
                        <li>{{ $room->roomType->name }} - {{ number_format($room->price, 0, ',', '.') }} VNĐ</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
