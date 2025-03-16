@extends('layouts.app')

@section('styles')
<style>
    .hero-section {
        background-image: url('https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2787&q=80');
        background-size: cover;
        background-position: center;
        height: 450px;
        position: relative;
        color: white;
        margin-bottom: 50px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
    }

    .hero-content {
        position: relative;
        z-index: 1;
        padding-top: 80px;
    }

    .search-container {
        position: relative;
        margin-top: -100px;
        margin-bottom: 30px;
        z-index: 20;
    }

    .search-box {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 10;
    }

    .search-tabs {
        margin-bottom: 20px;
    }

    .search-tab {
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        color: #333;
        text-decoration: none;
        opacity: 0.7;
        transition: all 0.3s;
    }

    .search-tab.active {
        opacity: 1;
        background-color: #5392f9;
        color: white !important;
    }

    .search-tab i {
        margin-right: 8px;
    }

    .search-button {
        background-color: #5392f9;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
    }

    .destination-section {
        margin-top: 0;
        padding-top: 0;
    }

    .spacer {
        height: 20px;
        clear: both;
    }

    .destination-card {
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        height: 180px;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .destination-card:hover {
        transform: translateY(-5px);
    }

    .destination-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .destination-card .overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        color: white;
    }

    .destination-card .overlay h4 {
        margin: 0;
        font-size: 18px;
    }

    .destination-card .overlay p {
        margin: 5px 0 0;
        font-size: 14px;
    }

    .section-title {
        margin: 30px 0 20px;
        font-weight: bold;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: #5392f9;
    }

    .hotel-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 8px;
        overflow: hidden;
    }

    .hotel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Custom pagination styles */
    .pagination-custom {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 30px;
        overflow: hidden;
    }

    .pagination-custom .page-item .page-link {
        border: none;
        color: #5392f9;
        font-weight: 500;
        padding: 10px 15px;
        transition: all 0.3s;
    }

    .pagination-custom .page-item.active .page-link {
        background-color: #5392f9;
        color: white;
        box-shadow: 0 2px 5px rgba(83, 146, 249, 0.4);
    }

    .pagination-custom .page-item .page-link:hover:not(.active) {
        background-color: #f0f7ff;
        color: #3a7bd5;
    }

    .pagination-custom .page-item .page-link:focus {
        box-shadow: none;
    }

    .pagination-custom .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
    }

    /* Thêm CSS cho các form tìm kiếm */
    .search-form {
        display: none;
    }

    .search-form.active {
        display: block;
    }
</style>
@endsection

@section('content')
<!-- Hero Section with Search Box -->
<div class="hero-section">
    <div class="container hero-content">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1 class="display-4 fw-bold">KHÁM PHÁ ĐIỂM ĐẾN, TẬN HƯỞNG TRỌN VẸN</h1>
                <p class="lead">Mỗi chuyến đi là một câu chuyện, hãy để chúng tôi viết nên những trang đẹp nhất</p>
            </div>
        </div>
            </div>
        </div>

<!-- Search Box (Moved outside hero section) -->
<div class="container search-container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="search-box">
                <div class="search-tabs">
                    <a href="#" class="search-tab active" data-form="hotel-form">
                        <i class="fas fa-hotel"></i> Khách sạn
                    </a>
                    <a href="#" class="search-tab" data-form="apartment-form">
                        <i class="fas fa-home"></i> Nhà và Căn hộ
                    </a>
                    <a href="#" class="search-tab" data-form="flight-hotel-form">
                        <i class="fas fa-plane"></i> Máy bay + Khách sạn
                    </a>
                    <a href="#" class="search-tab" data-form="flight-form">
                        <i class="fas fa-plane-departure"></i> Vé máy bay
                    </a>
                    <a href="#" class="search-tab" data-form="activity-form">
                        <i class="fas fa-calendar-alt"></i> Hoạt động
                    </a>
                    <a href="#" class="search-tab" data-form="airport-transfer-form">
                        <i class="fas fa-car"></i> Đưa đón sân bay
                    </a>
                </div>

                <!-- Form tìm kiếm khách sạn -->
                <form action="{{ route('hotels.search') }}" method="GET" id="hotel-form" class="search-form active">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="city" placeholder="Nhập điểm đến, khách sạn, hoặc địa điểm">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="check_in" required min="{{ date('Y-m-d') }}">
                            </div>
                </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="check_out" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <select class="form-select border-start-0" name="guests">
                                    <option value="1">1 người lớn</option>
                                    <option value="2" selected>2 người lớn</option>
                                    <option value="3">3 người lớn</option>
                                    <option value="4">4 người lớn</option>
                                    <option value="5">5 người lớn</option>
                                </select>
            </div>
        </div>

                        <div class="col-md-12">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search me-2"></i> TÌM KIẾM
                            </button>
            </div>
                    </div>
                </form>

                <!-- Form tìm kiếm nhà và căn hộ -->
                <form action="{{ route('hotels.search') }}" method="GET" id="apartment-form" class="search-form">
                    <input type="hidden" name="type" value="apartment">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="city" placeholder="Nhập địa điểm, khu vực hoặc tên căn hộ">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="check_in" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="check_out" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            </div>
        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <select class="form-select border-start-0" name="guests">
                                    <option value="1">1 người lớn</option>
                                    <option value="2" selected>2 người lớn</option>
                                    <option value="3">3 người lớn</option>
                                    <option value="4">4 người lớn</option>
                                    <option value="5">5 người lớn</option>
                                </select>
                            </div>
        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-home"></i>
                                </span>
                                <select class="form-select border-start-0" name="rooms">
                                    <option value="1">1 phòng ngủ</option>
                                    <option value="2">2 phòng ngủ</option>
                                    <option value="3">3 phòng ngủ</option>
                                    <option value="4+">4+ phòng ngủ</option>
                                </select>
        </div>
    </div>

                        <div class="col-md-12">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search me-2"></i> TÌM KIẾM NHÀ & CĂN HỘ
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Form tìm kiếm máy bay + khách sạn -->
                <form action="{{ route('hotels.search') }}" method="GET" id="flight-hotel-form" class="search-form">
                    <input type="hidden" name="type" value="flight-hotel">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-plane-departure"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="departure" placeholder="Điểm khởi hành">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-plane-arrival"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="destination" placeholder="Điểm đến">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="departure_date" required min="{{ date('Y-m-d') }}" placeholder="Ngày đi">
                    </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="return_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" placeholder="Ngày về">
                        </div>
                    </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <select class="form-select border-start-0" name="passengers">
                                    <option value="1">1 người lớn</option>
                                    <option value="2" selected>2 người lớn</option>
                                    <option value="3">3 người lớn</option>
                                    <option value="4">4 người lớn</option>
                            </select>
                        </div>
                    </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-ticket-alt"></i>
                                </span>
                                <select class="form-select border-start-0" name="class">
                                    <option value="economy">Phổ thông</option>
                                    <option value="premium">Phổ thông đặc biệt</option>
                                    <option value="business">Thương gia</option>
                                    <option value="first">Hạng nhất</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search me-2"></i> TÌM KIẾM COMBO
                        </button>
                        </div>
                    </div>
                </form>

                <!-- Form tìm kiếm vé máy bay -->
                <form action="{{ route('hotels.search') }}" method="GET" id="flight-form" class="search-form">
                    <input type="hidden" name="type" value="flight">
                    <div class="row g-3">
                        <div class="col-md-12 mb-2">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="trip_type" id="one_way" value="one_way" autocomplete="off">
                                <label class="btn btn-outline-primary" for="one_way">Một chiều</label>

                                <input type="radio" class="btn-check" name="trip_type" id="round_trip" value="round_trip" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="round_trip">Khứ hồi</label>

                                <input type="radio" class="btn-check" name="trip_type" id="multi_city" value="multi_city" autocomplete="off">
                                <label class="btn btn-outline-primary" for="multi_city">Nhiều thành phố</label>
        </div>
    </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-plane-departure"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="departure" placeholder="Điểm khởi hành">
                            </div>
            </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-plane-arrival"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="destination" placeholder="Điểm đến">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="departure_date" required min="{{ date('Y-m-d') }}" placeholder="Ngày đi">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="return_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" placeholder="Ngày về">
                            </div>
                                </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <select class="form-select border-start-0" name="passengers">
                                    <option value="1">1 người lớn</option>
                                    <option value="2" selected>2 người lớn</option>
                                    <option value="3">3 người lớn</option>
                                    <option value="4">4 người lớn</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-ticket-alt"></i>
                                </span>
                                <select class="form-select border-start-0" name="class">
                                    <option value="economy">Phổ thông</option>
                                    <option value="premium">Phổ thông đặc biệt</option>
                                    <option value="business">Thương gia</option>
                                    <option value="first">Hạng nhất</option>
                                </select>
                        </div>
                    </div>

                        <div class="col-md-12">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search me-2"></i> TÌM KIẾM CHUYẾN BAY
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Form tìm kiếm hoạt động -->
                <form action="{{ route('hotels.search') }}" method="GET" id="activity-form" class="search-form">
                    <input type="hidden" name="type" value="activity">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="location" placeholder="Bạn muốn đi đâu?">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="activity_date" required min="{{ date('Y-m-d') }}">
                            </div>
                                </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <select class="form-select border-start-0" name="participants">
                                    <option value="1">1 người</option>
                                    <option value="2" selected>2 người</option>
                                    <option value="3">3 người</option>
                                    <option value="4">4 người</option>
                                    <option value="5+">5+ người</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <select class="form-select border-start-0" name="activity_type">
                                    <option value="">Loại hoạt động</option>
                                    <option value="tour">Tour tham quan</option>
                                    <option value="adventure">Phiêu lưu & ngoài trời</option>
                                    <option value="food">Ẩm thực & ăn uống</option>
                                    <option value="culture">Văn hóa & nghệ thuật</option>
                                    <option value="relax">Thư giãn & spa</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search me-2"></i> TÌM KIẾM HOẠT ĐỘNG
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Form tìm kiếm đưa đón sân bay -->
                <form action="{{ route('hotels.search') }}" method="GET" id="airport-transfer-form" class="search-form">
                    <input type="hidden" name="type" value="airport-transfer">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-plane"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="airport" placeholder="Sân bay">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-hotel"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" name="destination" placeholder="Điểm đến (khách sạn, địa chỉ)">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" class="form-control border-start-0" name="transfer_date" required min="{{ date('Y-m-d') }}">
                            </div>
                                </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <input type="time" class="form-control border-start-0" name="transfer_time" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <select class="form-select border-start-0" name="passengers">
                                    <option value="1">1 người</option>
                                    <option value="2" selected>2 người</option>
                                    <option value="3">3 người</option>
                                    <option value="4">4 người</option>
                                    <option value="5+">5+ người</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search me-2"></i> TÌM KIẾM DỊCH VỤ ĐƯA ĐÓN
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
            </div>

<!-- Popular Destinations -->
<div class="container">
    <h2 class="section-title">Điểm đến nổi tiếng tại Việt Nam</h2>

    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('hotels.search', ['city' => 'Hà Nội']) }}" class="text-decoration-none">
                <div class="destination-card">
                    <img src="{{ Storage::url('destinations/hanoi.jpg') }}" alt="Hà Nội" onerror="this.src='https://images.unsplash.com/photo-1509030450996-04d5c6bfb848?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2835&q=80'">
                    <div class="overlay">
                        <h4>Hà Nội</h4>
                        <p>1,234 chỗ nghỉ</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('hotels.search', ['city' => 'Hồ Chí Minh']) }}" class="text-decoration-none">
                <div class="destination-card">
                    <img src="{{ Storage::url('destinations/hochiminh.jpg') }}" alt="Hồ Chí Minh" onerror="this.src='https://images.unsplash.com/photo-1583417319070-4a69db38a482?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2940&q=80'">
                    <div class="overlay">
                        <h4>Hồ Chí Minh</h4>
                        <p>2,345 chỗ nghỉ</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('hotels.search', ['city' => 'Đà Nẵng']) }}" class="text-decoration-none">
                <div class="destination-card">
                    <img src="{{ Storage::url('destinations/danang.jpg') }}" alt="Đà Nẵng" onerror="this.src='https://images.unsplash.com/photo-1564596823821-78ec3e8cb765?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2874&q=80'">
                    <div class="overlay">
                        <h4>Đà Nẵng</h4>
                        <p>1,567 chỗ nghỉ</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('hotels.search', ['city' => 'Nha Trang']) }}" class="text-decoration-none">
                <div class="destination-card">
                    <img src="{{ Storage::url('destinations/nhatrang.jpg') }}" alt="Nha Trang" onerror="this.src='https://images.unsplash.com/photo-1540611025311-01df3cef54b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2940&q=80'">
                    <div class="overlay">
                        <h4>Nha Trang</h4>
                        <p>987 chỗ nghỉ</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('hotels.search', ['city' => 'Phú Quốc']) }}" class="text-decoration-none">
                <div class="destination-card">
                    <img src="{{ Storage::url('destinations/phuquoc.png') }}" alt="Phú Quốc" onerror="this.src='https://images.unsplash.com/photo-1596422846543-75c6fc197f11?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2874&q=80'">
                    <div class="overlay">
                        <h4>Phú Quốc</h4>
                        <p>765 chỗ nghỉ</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('hotels.search', ['city' => 'Đà Lạt']) }}" class="text-decoration-none">
                <div class="destination-card">
                    <img src="{{ Storage::url('destinations/dalat.jpg') }}" alt="Đà Lạt" onerror="this.src='https://images.unsplash.com/photo-1558424087-896a99907152?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2940&q=80'">
                    <div class="overlay">
                        <h4>Đà Lạt</h4>
                        <p>1,123 chỗ nghỉ</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Featured Hotels -->
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="section-title">Chỗ nghỉ được yêu thích</h2>
        <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary">Xem tất cả</a>
                            </div>

    <div class="row">
        @if(isset($hotels) && $hotels->count() > 0)
            @foreach($hotels->take(6) as $hotel)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('hotels.show', $hotel) }}" class="text-decoration-none">
                        <div class="card hotel-card h-100">
                            <div class="position-relative">
                                @if($hotel->primaryImage)
                                    <img src="{{ Storage::url($hotel->primaryImage->image_path) }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 180px; object-fit: cover;">
                                @elseif($hotel->images->count() > 0)
                                    <img src="{{ Storage::url($hotel->images->first()->image_path) }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 180px; object-fit: cover;">
                                @else
                                    <img src="https://source.unsplash.com/800x600/?hotel&sig={{ $hotel->id }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 180px; object-fit: cover;">
                                @endif
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-danger">Ưu đãi hot</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-dark">{{ $hotel->name }}</h5>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        <span>{{ number_format($hotel->rating, 1) }}</span>
                            </div>
                                </div>

                                <p class="card-text text-muted mb-1">
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $hotel->city }}
                                </p>

                                <p class="card-text small text-muted mt-2">
                                    {{ Str::limit($hotel->description, 80) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <small class="text-muted">Giá mỗi đêm từ</small>
                                        <p class="h5 text-primary mb-0">{{ number_format($hotel->min_room_price) }}đ</p>
                            </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Không có khách sạn nào để hiển thị.
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-4 mb-5">
        <nav aria-label="Phân trang chỗ nghỉ">
            <ul class="pagination pagination-custom">
                <li class="page-item disabled">
                    <a class="page-link rounded-start" href="#" tabindex="-1" aria-disabled="true">
                        <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">1</a>
                        </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                        </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                        </li>
                <li class="page-item">
                    <a class="page-link rounded-end" href="#">
                        <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
        </nav>
                </div>
            </div>
@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý chuyển đổi giữa các tab tìm kiếm
        const searchTabs = document.querySelectorAll('.search-tab');
        const searchForms = document.querySelectorAll('.search-form');

        searchTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                // Xóa class active từ tất cả các tab và form
                searchTabs.forEach(t => t.classList.remove('active'));
                searchForms.forEach(f => f.classList.remove('active'));

                // Thêm class active cho tab được chọn
                this.classList.add('active');

                // Hiển thị form tương ứng
                const formId = this.getAttribute('data-form');
                document.getElementById(formId).classList.add('active');
            });
        });

        // Xử lý form vé máy bay
        const oneWayRadio = document.getElementById('one_way');
        const roundTripRadio = document.getElementById('round_trip');
        const returnDateInput = document.querySelector('#flight-form input[name="return_date"]');

        if (oneWayRadio && roundTripRadio && returnDateInput) {
            oneWayRadio.addEventListener('change', function() {
                if (this.checked) {
                    returnDateInput.disabled = true;
                    returnDateInput.required = false;
                }
            });

            roundTripRadio.addEventListener('change', function() {
                if (this.checked) {
                    returnDateInput.disabled = false;
                    returnDateInput.required = true;
                }
            });
        }
        });
    </script>
@endsection
