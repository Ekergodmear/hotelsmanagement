@extends('layouts.app')

@section('styles')
<style>
    .login-container {
        padding: 80px 0;
        background-color: #f8f9fa;
    }

    .login-card {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #fff;
    }

    .login-image {
        background-image: url("{{ Storage::url('destinations/login.jpg') }}");
        background-size: cover;
        background-position: center;
        height: 100%;
        min-height: 500px;
    }

    .login-form {
        padding: 40px;
    }

    .login-form h2 {
        font-weight: 700;
        margin-bottom: 30px;
        color: #2c3e50;
        position: relative;
    }

    .login-form h2::after {
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

    .btn-login {
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

    .btn-login:hover {
        background-color: #3a7bd5;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(83, 146, 249, 0.4);
    }

    .forgot-password {
        color: #5392f9;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }

    .forgot-password:hover {
        color: #3a7bd5;
        text-decoration: underline;
    }

    .register-link {
        margin-top: 20px;
        text-align: center;
        font-size: 14px;
        color: #666;
    }

    .register-link a {
        color: #5392f9;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .register-link a:hover {
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
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="login-card">
                    <div class="row g-0">
                        <div class="col-md-6 d-none d-md-block">
                            <div class="login-image"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="login-form">
                                <h2>Đăng nhập</h2>

                                @if (session('status'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input id="email" type="email" class="form-control left-border-none @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Nhập email của bạn">
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
                                            <input id="password" type="password" class="form-control left-border-none @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Nhập mật khẩu của bạn">
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                        <label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        @if (Route::has('password.request'))
                                            <a class="forgot-password" href="{{ route('password.request') }}">
                                                Quên mật khẩu?
                                            </a>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-login">
                                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                    </button>

                                    <div class="register-link">
                                        Bạn chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
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
