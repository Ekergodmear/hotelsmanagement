@extends('layouts.app')

@section('styles')
<style>
    .profile-container {
        padding: 80px 0;
        background-color: #f8f9fa;
    }

    .profile-card {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: #fff;
        padding: 30px;
    }

    .profile-header {
        margin-bottom: 30px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .profile-header h2 {
        font-weight: 700;
        color: #2c3e50;
        position: relative;
    }

    .profile-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: #5392f9;
    }

    .profile-info {
        margin-bottom: 20px;
    }

    .profile-info-item {
        margin-bottom: 15px;
    }

    .profile-info-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    .profile-info-value {
        color: #333;
        font-size: 16px;
    }

    .profile-actions {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn-edit-profile {
        background-color: #5392f9;
        border: none;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .btn-edit-profile:hover {
        background-color: #3a7bd5;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(83, 146, 249, 0.4);
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="profile-card">
                    <div class="profile-header">
                        <h2>Thông tin cá nhân</h2>
                    </div>

                    <div class="profile-info">
                        <div class="row profile-info-item">
                            <div class="col-md-4">
                                <div class="profile-info-label">Họ và tên</div>
                            </div>
                            <div class="col-md-8">
                                <div class="profile-info-value">{{ $user->name }}</div>
                            </div>
                        </div>

                        <div class="row profile-info-item">
                            <div class="col-md-4">
                                <div class="profile-info-label">Email</div>
                            </div>
                            <div class="col-md-8">
                                <div class="profile-info-value">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="row profile-info-item">
                            <div class="col-md-4">
                                <div class="profile-info-label">Số điện thoại</div>
                            </div>
                            <div class="col-md-8">
                                <div class="profile-info-value">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                            </div>
                        </div>

                        <div class="row profile-info-item">
                            <div class="col-md-4">
                                <div class="profile-info-label">Địa chỉ</div>
                            </div>
                            <div class="col-md-8">
                                <div class="profile-info-value">{{ $user->address ?? 'Chưa cập nhật' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <a href="{{ route('user.edit') }}" class="btn btn-edit-profile">
                            <i class="fas fa-edit me-2"></i>Cập nhật thông tin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
