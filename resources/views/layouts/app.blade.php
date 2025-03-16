<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

        <!-- Styles -->
        <style>
            .footer {
                background-color: #2c3e50;
                padding: 50px 0;
                color: #ecf0f1;
                border-top: 3px solid #3498db;
            }

            .footer h5 {
                font-weight: bold;
                margin-bottom: 20px;
                color: #ffffff;
                position: relative;
                padding-bottom: 10px;
            }

            .footer h5::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 40px;
                height: 2px;
                background-color: #3498db;
            }

            .footer ul {
                list-style: none;
                padding-left: 0;
            }

            .footer ul li {
                margin-bottom: 12px;
            }

            .footer ul li a {
                color: #bdc3c7;
                text-decoration: none;
                transition: color 0.3s, transform 0.3s;
                display: inline-block;
            }

            .footer ul li a:hover {
                color: #3498db;
                transform: translateX(5px);
            }

            .footer-bottom {
                background-color: #1a252f;
                padding: 20px 0;
                color: #95a5a6;
                font-size: 14px;
            }

            .social-icons a {
                display: inline-block;
                width: 40px;
                height: 40px;
                background-color: #3498db;
                color: white;
                border-radius: 50%;
                text-align: center;
                line-height: 40px;
                margin-right: 10px;
                transition: all 0.3s;
            }

            .social-icons a:hover {
                background-color: #2980b9;
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            }

            .footer p {
                color: #bdc3c7;
            }

            .footer .btn-dark {
                background-color: #34495e;
                border-color: #34495e;
                transition: all 0.3s;
            }

            .footer .btn-dark:hover {
                background-color: #3498db;
                border-color: #3498db;
                transform: translateY(-2px);
            }
        </style>
        @yield('styles')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5>Về chúng tôi</h5>
                        <ul>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Giới thiệu</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Cách chúng tôi hoạt động</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Bảo mật & Quyền riêng tư</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Trách nhiệm xã hội</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Quan hệ đối tác</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5>Hỗ trợ</h5>
                        <ul>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Trung tâm trợ giúp</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Câu hỏi thường gặp</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Chính sách hủy đặt phòng</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Chính sách hoàn tiền</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Báo cáo vấn đề</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5>Đối tác</h5>
                        <ul>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Đăng ký khách sạn</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Đăng ký đối tác</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Chương trình liên kết</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Quảng cáo trên trang web</a></li>
                            <li><a href="#"><i class="fas fa-angle-right me-2"></i>Giải pháp doanh nghiệp</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5>Tải ứng dụng</h5>
                        <p>Đặt phòng dễ dàng và nhận ưu đãi độc quyền</p>
                        <div class="mb-3">
                            <a href="#" class="btn btn-dark btn-sm mb-2 me-2"><i class="fab fa-apple me-2"></i> App Store</a>
                            <a href="#" class="btn btn-dark btn-sm mb-2"><i class="fab fa-google-play me-2"></i> Google Play</a>
                        </div>
                        <h5 class="mt-4">Kết nối với chúng tôi</h5>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-md-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Tất cả các quyền được bảo lưu.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-muted me-3">Điều khoản sử dụng</a>
                        <a href="#" class="text-muted">Chính sách bảo mật</a>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
    </body>
</html>
