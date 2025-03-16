@extends('layouts.app')

@section('styles')
<style>
    .edit-profile-container {
        padding: 80px 0;
        background-color: #f8f9fa;
    }

    .edit-profile-card {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #fff;
        padding: 30px;
    }

    .edit-profile-header {
        margin-bottom: 30px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .edit-profile-header h2 {
        font-weight: 700;
        color: #2c3e50;
        position: relative;
    }

    .edit-profile-header h2::after {
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

    .input-group-text {
        background-color: transparent;
        border-right: none;
    }

    .form-control.left-border-none {
        border-left: none;
    }

    .btn-update-profile {
        background-color: #5392f9;
        border: none;
        color: white;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .btn-update-profile:hover {
        background-color: #3a7bd5;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(83, 146, 249, 0.4);
    }

    .btn-cancel {
        background-color: #6c757d;
        border: none;
        color: white;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
    }
</style>
@endsection

@section('content')
<div class="edit-profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="edit-profile-card">
                    <div class="edit-profile-header">
                        <h2>Cập nhật thông tin cá nhân</h2>
                    </div>

                    <form method="POST" action="{{ route('user.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input id="name" type="text" class="form-control left-border-none @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control left-border-none" value="{{ $user->email }}" disabled readonly>
                            </div>
                            <small class="text-muted">Email không thể thay đổi.</small>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input id="phone" type="text" class="form-control left-border-none @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input id="address" type="text" class="form-control left-border-none @error('address') is-invalid @enderror" name="address" value="{{ old('address', $user->address) }}" autocomplete="address">
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('user.profile') }}" class="btn btn-cancel">
                                <i class="fas fa-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-update-profile">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
