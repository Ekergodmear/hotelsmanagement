<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">HotelManagement</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('hotels.index') }}">Khách sạn</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Căn hộ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Biệt thự</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn btn-light">Đăng ký</a>
            </div>
        </div>
    </div>
</nav>
