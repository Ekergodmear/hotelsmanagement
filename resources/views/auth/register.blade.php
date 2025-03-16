@extends('layouts.app')

@section('styles')
<style>
    .register-container {
        padding: 80px 0;
        background-color: #f8f9fa;
    }

    .register-card {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #fff;
    }

    .register-image {
        background-image: url("{{ Storage::url('destinations/register.jpg') }}");
        background-size: cover;
        background-position: center;
        height: 100%;
        min-height: 600px;
    }

    .register-form {
        padding: 40px;
    }

    .register-form h2 {
        font-weight: 700;
        margin-bottom: 30px;
        color: #2c3e50;
        position: relative;
    }

    .register-form h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: #5392f9;
    }

    .form-control {
        height: 50px;
        border-radius: 5px;
        box-shadow: none;
        border: 1px solid #e0e0e0;
        padding-left: 15px;
        font-size: 16px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #5392f9;
        box-shadow: 0 0 0 0.2rem rgba(83, 146, 249, 0.25);
    }

    .btn-register {
        background-color: #5392f9;
        border: none;
        color: white;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s;
        width: 100%;
        margin-top: 20px;
    }

    .btn-register:hover {
        background-color: #3a7bd5;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(83, 146, 249, 0.4);
    }

    .login-link {
        margin-top: 20px;
        text-align: center;
        font-size: 14px;
        color: #666;
    }

    .login-link a {
        color: #5392f9;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .login-link a:hover {
        color: #3a7bd5;
        text-decoration: underline;
    }

    .input-group-text {
        background-color: transparent;
        border-right: none;
    }

    .form-control.left-border-none {
        border-left: none;
    }
</style>
@endsection

@section('content')
<div class="register-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="register-card">
                    <div class="row g-0">
                        <div class="col-md-6 d-none d-md-block">
                            <div class="register-image"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="register-form">
                                <h2>Đăng ký tài khoản</h2>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Họ và tên</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input id="name" type="text" class="form-control left-border-none @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nhập họ và tên của bạn">
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input id="email" type="email" class="form-control left-border-none @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Nhập email của bạn">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input id="password" type="password" class="form-control left-border-none @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Nhập mật khẩu của bạn">
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password-confirm" class="form-label">Xác nhận mật khẩu</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input id="password-confirm" type="password" class="form-control left-border-none" name="password_confirmation" required autocomplete="new-password" placeholder="Nhập lại mật khẩu của bạn">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-register">
                                        <i class="fas fa-user-plus me-2"></i>Đăng ký
                                    </button>

                                    <div class="login-link">
                                        Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
