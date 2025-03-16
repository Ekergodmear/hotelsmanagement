@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Cài đặt hệ thống</h1>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="card-title">
                    <i class="fas fa-cog"></i>
                    <span>Cài đặt chung</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="site_name" class="form-label">Tên trang web</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" value="{{ config('app.name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Email quản trị viên</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" value="admin@example.com">
                        </div>

                        <div class="mb-3">
                            <label for="currency" class="form-label">Đơn vị tiền tệ</label>
                            <select class="form-select" id="currency" name="currency">
                                <option value="VND" selected>VND (₫)</option>
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="timezone" class="form-label">Múi giờ</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="Asia/Ho_Chi_Minh" selected>Asia/Ho_Chi_Minh (GMT+7)</option>
                                <option value="UTC">UTC</option>
                                <option value="Asia/Bangkok">Asia/Bangkok (GMT+7)</option>
                                <option value="Asia/Singapore">Asia/Singapore (GMT+8)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu cài đặt</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="dashboard-card">
                <div class="card-title">
                    <i class="fas fa-envelope"></i>
                    <span>Cài đặt email</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.email') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="mail_driver" class="form-label">Driver</label>
                            <select class="form-select" id="mail_driver" name="mail_driver">
                                <option value="smtp" selected>SMTP</option>
                                <option value="sendmail">Sendmail</option>
                                <option value="mailgun">Mailgun</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mail_host" class="form-label">Host</label>
                            <input type="text" class="form-control" id="mail_host" name="mail_host" value="smtp.gmail.com">
                        </div>

                        <div class="mb-3">
                            <label for="mail_port" class="form-label">Port</label>
                            <input type="text" class="form-control" id="mail_port" name="mail_port" value="587">
                        </div>

                        <div class="mb-3">
                            <label for="mail_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="mail_username" name="mail_username" value="">
                        </div>

                        <div class="mb-3">
                            <label for="mail_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="mail_password" name="mail_password" value="">
                        </div>

                        <div class="mb-3">
                            <label for="mail_encryption" class="form-label">Encryption</label>
                            <select class="form-select" id="mail_encryption" name="mail_encryption">
                                <option value="tls" selected>TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="null">None</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu cài đặt email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="dashboard-card">
                <div class="card-title">
                    <i class="fas fa-shield-alt"></i>
                    <span>Bảo mật</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.security') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_2fa" name="enable_2fa" checked>
                                <label class="form-check-label" for="enable_2fa">Bật xác thực hai yếu tố</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_recaptcha" name="enable_recaptcha" checked>
                                <label class="form-check-label" for="enable_recaptcha">Bật Google reCAPTCHA</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="session_lifetime" class="form-label">Thời gian hết hạn phiên (phút)</label>
                            <input type="number" class="form-control" id="session_lifetime" name="session_lifetime" value="120">
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu cài đặt bảo mật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
