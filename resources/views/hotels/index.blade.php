@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Danh sách Khách sạn</h1>
    </div>

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

    <div class="row">
        @foreach($hotels as $hotel)
            <div class="col-md-4 mb-4">
                <a href="{{ route('hotels.show', $hotel) }}" class="text-decoration-none">
                    <div class="card hotel-card h-100">
                        @if($hotel->primaryImage)
                            <img src="{{ Storage::url($hotel->primaryImage->image_path) }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 200px; object-fit: cover;">
                        @elseif($hotel->images->count() > 0)
                            <img src="{{ Storage::url($hotel->images->first()->image_path) }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://source.unsplash.com/800x600/?hotel&sig={{ $hotel->id }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $hotel->name }}</h5>
                                <span class="rating">{{ number_format($hotel->rating, 1) }}</span>
                            </div>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $hotel->city }}
                            </p>
                            <p class="card-text">{{ Str::limit($hotel->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Giá mỗi đêm từ</small>
                                    <p class="h5 text-primary mb-0">{{ number_format($hotel->min_room_price) }}₫</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
