# Hệ Thống Quản Lý Khách Sạn

Hệ thống quản lý khách sạn toàn diện được xây dựng bằng Laravel Framework, hỗ trợ đặt phòng trực tuyến và quản lý khách sạn. Dự án này bao gồm đầy đủ các chức năng từ quản lý phòng, đặt phòng, quản lý dịch vụ đưa đón sân bay đến báo cáo thống kê.

## Công Nghệ Sử Dụng

- **Framework:** Laravel 10.x
- **Frontend:** 
  - Bootstrap 5
  - jQuery
  - AJAX
  - Blade Template Engine
- **Database:** MySQL 5.7+
- **Công cụ phát triển:**
  - PHP 8.1+
  - Composer
  - Node.js và NPM
  - Git

## Yêu Cầu Hệ Thống

1. **Phần mềm cần thiết:**
   - PHP >= 8.1
   - MySQL >= 5.7 hoặc MariaDB >= 10.3
   - Composer (latest version)
   - Node.js >= 14.x
   - NPM >= 6.x
   - Git

2. **Extension PHP yêu cầu:**
   - BCMath PHP Extension
   - Ctype PHP Extension
   - JSON PHP Extension
   - Mbstring PHP Extension
   - OpenSSL PHP Extension
   - PDO PHP Extension
   - Tokenizer PHP Extension
   - XML PHP Extension
   - Fileinfo PHP Extension
   - GD PHP Extension

## Cài Đặt Chi Tiết

### 1. Chuẩn Bị Môi Trường
```bash
# Kiểm tra phiên bản PHP
php -v  # Phải >= 8.1

# Kiểm tra phiên bản Composer
composer -V  # Nên dùng bản mới nhất

# Kiểm tra Node và NPM
node -v  # >= 14.x
npm -v   # >= 6.x
```

### 2. Clone Dự Án
```bash
# Clone repository
git clone https://github.com/yourusername/hotelsmanagement.git

# Di chuyển vào thư mục dự án
cd hotelsmanagement
```

### 3. Cài Đặt Dependencies
```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node dependencies
npm install

# Build assets
npm run dev
```

### 4. Cấu Hình Môi Trường
```bash
# Tạo file .env
cp .env.example .env

# Tạo application key
php artisan key:generate
```

### 5. Cấu Hình Database
Mở file `.env` và cập nhật các thông tin sau:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotelsmanagement
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=your_mail_port
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Khởi Tạo Database
```bash
# Tạo các bảng trong database
php artisan migrate

# Thêm dữ liệu mẫu
6. Chạy migration và seeder:
```bash
php artisan migrate --seed
```

7. Tạo symbolic link cho storage:
```bash
php artisan storage:link
```

8. Biên dịch assets:
```bash
npm run dev
```

9. Khởi chạy server:
```bash
php artisan serve
```

## Tính Năng

### 1. Quản Lý Phòng
- Thêm, sửa, xóa thông tin phòng
- Quản lý loại phòng và giá phòng
- Upload và quản lý hình ảnh phòng
- Theo dõi trạng thái phòng (trống, đã đặt, đang bảo trì)

### 2. Đặt Phòng
- Đặt phòng trực tuyến
- Chọn ngày check-in, check-out
- Tính toán tự động tổng tiền
- Thêm yêu cầu đặc biệt
- Dịch vụ đưa đón sân bay (tùy chọn)

### 3. Quản Lý Đặt Phòng
- Xem danh sách đặt phòng
- Xác nhận/hủy đặt phòng
- Check-in/check-out khách hàng
- Quản lý thanh toán
- Gửi email xác nhận tự động

### 4. Dịch Vụ Đưa Đón Sân Bay
- Đặt dịch vụ đưa đón
- Chọn loại dịch vụ (một chiều/hai chiều)
- Nhập thông tin chuyến bay
- Tính phí tự động

### 5. Quản Lý Người Dùng
- Đăng ký/đăng nhập tài khoản
- Phân quyền người dùng (Admin/Nhân viên/Khách hàng)
- Quản lý thông tin cá nhân
- Xem lịch sử đặt phòng

### 6. Báo Cáo và Thống Kê
- Thống kê doanh thu
- Báo cáo công suất phòng
- Theo dõi tình trạng đặt phòng
- Phân tích xu hướng khách hàng

## Hướng Dẫn Sử Dụng

### Dành Cho Admin
1. Đăng nhập vào trang admin
2. Quản lý phòng:
   - Thêm/sửa/xóa phòng
   - Cập nhật trạng thái phòng
   - Quản lý hình ảnh
3. Quản lý đặt phòng:
   - Xem danh sách đặt phòng
   - Xác nhận đặt phòng
   - Thực hiện check-in/check-out
4. Quản lý người dùng:
   - Thêm/sửa/xóa tài khoản
   - Phân quyền người dùng

### Dành Cho Khách Hàng
1. Đăng ký/đăng nhập tài khoản
2. Tìm và đặt phòng:
   - Chọn ngày check-in/check-out
   - Chọn loại phòng
   - Thêm dịch vụ đưa đón sân bay (nếu cần)
3. Quản lý đặt phòng:
   - Xem thông tin đặt phòng
   - Hủy đặt phòng
   - Xem lịch sử đặt phòng

## Cấu Trúc Database

### Bảng chính:
- users: Thông tin người dùng
- rooms: Thông tin phòng
- room_types: Loại phòng
- bookings: Thông tin đặt phòng
- booking_services: Dịch vụ đi kèm
- room_images: Hình ảnh phòng

## Bảo Mật

- Xác thực người dùng
- Phân quyền truy cập
- Bảo vệ thông tin cá nhân
- Mã hóa dữ liệu nhạy cảm

## Hỗ Trợ

Nếu bạn gặp vấn đề hoặc cần hỗ trợ, vui lòng:
1. Tạo issue trên GitHub
2. Liên hệ qua email: [email của bạn]
3. Tham khảo documentation tại [link documentation]

## License

[Loại license của dự án]

