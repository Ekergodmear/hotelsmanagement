@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Phòng yêu thích của tôi</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($favorites->count() > 0)
        <div class="row">
            @foreach($favorites as $favorite)
                <div class="col-md-4 mb-4">
                    <div class="card hotel-card">
                        @if($favorite->room->hotel->image)
                            <img src="{{ Storage::url($favorite->room->hotel->image) }}" class="card-img-top" alt="{{ $favorite->room->hotel->name }}">
                        @else
                            <img src="https://via.placeholder.com/800x600?text=Không+có+hình+ảnh" class="card-img-top" alt="Không có hình ảnh">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $favorite->room->hotel->name }} - Phòng {{ $favorite->room->room_number }}</h5>
                                <form action="{{ route('favorites.destroy', $favorite) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-heart-broken"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $favorite->room->hotel->city }}
                            </p>
                            <p class="card-text">
                                <strong>Loại phòng:</strong> {{ $favorite->room->roomType->name }}<br>
                                <strong>Giá:</strong> {{ number_format($favorite->room->roomType->base_price) }}đ/đêm
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('rooms.show', $favorite->room) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                                <a href="{{ route('hotels.show', $favorite->room->hotel) }}" class="btn btn-link">Xem khách sạn</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <p>Bạn chưa có phòng yêu thích nào. Hãy khám phá các <a href="{{ route('hotels.index') }}">khách sạn</a> của chúng tôi.</p>
        </div>
    @endif
</div>
@endsection
