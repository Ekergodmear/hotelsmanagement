# Hệ thống Quản lý Khách sạn

Hệ thống Quản lý Khách sạn là một ứng dụng web được xây dựng bằng Laravel, cho phép quản lý khách sạn, phòng, đặt phòng và các dịch vụ đi kèm như đưa đón sân bay.

## Tính năng chính

- **Quản lý khách sạn**: Thêm, sửa, xóa thông tin khách sạn
- **Quản lý phòng**: Thêm, sửa, xóa thông tin phòng, loại phòng
- **Đặt phòng**: Cho phép người dùng đặt phòng, xem lịch sử đặt phòng
- **Dịch vụ đưa đón sân bay**: Đặt dịch vụ đưa đón sân bay kèm theo đặt phòng
- **Quản lý người dùng**: Phân quyền admin và người dùng thông thường
- **Tìm kiếm và lọc**: Tìm kiếm khách sạn, phòng theo nhiều tiêu chí

## Yêu cầu hệ thống

- PHP >= 8.0
- Composer
- MySQL hoặc MariaDB
- Node.js và NPM (để biên dịch assets)
- Git

## Cài đặt

### Bước 1: Clone dự án

```bash
git clone https://github.com/yourusername/hotelsmanagement.git
cd hotelsmanagement
```

### Bước 2: Cài đặt các gói phụ thuộc

```bash
composer install
npm install
npm run dev
```

### Bước 3: Cấu hình môi trường

Sao chép file `.env.example` thành `.env` và cấu hình kết nối cơ sở dữ liệu:

```bash
cp .env.example .env
php artisan key:generate
```

Mở file `.env` và cấu hình kết nối cơ sở dữ liệu:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotelsmanagement
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 4: Tạo cơ sở dữ liệu và chạy migration

```bash
php artisan migrate
```

### Bước 5: Tạo dữ liệu mẫu

```bash
php artisan db:seed
```

Để tạo dữ liệu mẫu cho dịch vụ đưa đón sân bay:

```bash
php artisan db:seed --class=AirportTransferSeeder
```

### Bước 6: Tạo symbolic link cho storage

```bash
php artisan storage:link
```

### Bước 7: Khởi chạy ứng dụng

```bash
php artisan serve
```

Truy cập ứng dụng tại địa chỉ: http://localhost:8000

## Tài khoản mặc định

- **Admin**:
  - Email: admin@example.com
  - Mật khẩu: password

- **Người dùng**:
  - Email: user@example.com
  - Mật khẩu: password

## Cấu trúc dự án

### Controllers

#### AirportTransferController

`app/Http/Controllers/AirportTransferController.php` - Quản lý dịch vụ đưa đón sân bay cho người dùng:

- `index()`: Hiển thị danh sách dịch vụ đưa đón sân bay
- `create()`: Hiển thị form đặt dịch vụ đưa đón sân bay
- `store()`: Lưu đặt dịch vụ đưa đón sân bay
- `show()`: Hiển thị chi tiết dịch vụ đưa đón
- `cancel()`: Hủy dịch vụ đưa đón sân bay

#### Admin/AirportTransferController

`app/Http/Controllers/Admin/AirportTransferController.php` - Quản lý dịch vụ đưa đón sân bay cho admin:

- `index()`: Hiển thị danh sách dịch vụ đưa đón sân bay
- `create()`: Hiển thị form tạo dịch vụ đưa đón sân bay mới
- `store()`: Lưu dịch vụ đưa đón sân bay mới
- `show()`: Hiển thị chi tiết dịch vụ đưa đón
- `edit()`: Hiển thị form chỉnh sửa dịch vụ đưa đón sân bay
- `update()`: Cập nhật dịch vụ đưa đón sân bay
- `destroy()`: Xóa dịch vụ đưa đón sân bay

### Models

#### AirportTransfer

`app/Models/AirportTransfer.php` - Model quản lý dịch vụ đưa đón sân bay:

- Thuộc tính: `name`, `vehicle_type`, `max_passengers`, `max_luggage`, `price`, `description`, `image_path`, `is_popular`, `is_active`
- Quan hệ: `bookings()` - Lấy tất cả các đặt phòng có sử dụng dịch vụ đưa đón này
- Accessor: `getImageUrlAttribute()` - Lấy URL hình ảnh

#### Booking

`app/Models/Booking.php` - Model quản lý đặt phòng:

- Thuộc tính bổ sung cho đưa đón sân bay: `has_airport_transfer`, `airport_name`, `transfer_datetime`, `transfer_type`, `transfer_passengers`, `transfer_price`, `transfer_notes`, `transfer_status`
- Quan hệ: `user()`, `room()`, `hotel()`
- Methods: `hasAirportTransfer()`, `calculateTotalPrice()`

### Migrations

#### add_airport_transfer_to_bookings_table

`database/migrations/2025_03_16_044748_add_airport_transfer_to_bookings_table.php` - Thêm các cột liên quan đến đưa đón sân bay vào bảng bookings:

- `has_airport_transfer`: boolean
- `airport_name`: string
- `transfer_datetime`: datetime
- `transfer_type`: string
- `transfer_passengers`: integer
- `transfer_price`: decimal
- `transfer_notes`: text
- `transfer_status`: string

#### create_airport_transfers_table

`database/migrations/2025_03_16_044836_create_airport_transfers_table.php` - Tạo bảng airport_transfers:

- `id`: bigint
- `name`: string
- `vehicle_type`: string
- `max_passengers`: integer
- `max_luggage`: integer
- `price`: decimal
- `description`: text
- `image_path`: string
- `is_popular`: boolean
- `is_active`: boolean
- `timestamps`

### Views

#### Admin Views

- `resources/views/admin/airport-transfers/index.blade.php`: Hiển thị danh sách dịch vụ đưa đón sân bay
- `resources/views/admin/airport-transfers/create.blade.php`: Form tạo dịch vụ đưa đón sân bay mới
- `resources/views/admin/airport-transfers/edit.blade.php`: Form chỉnh sửa dịch vụ đưa đón sân bay
- `resources/views/admin/airport-transfers/show.blade.php`: Hiển thị chi tiết dịch vụ đưa đón sân bay

#### User Views

- `resources/views/airport-transfers/index.blade.php`: Hiển thị danh sách dịch vụ đưa đón sân bay
- `resources/views/airport-transfers/create.blade.php`: Form đặt dịch vụ đưa đón sân bay
- `resources/views/airport-transfers/show.blade.php`: Hiển thị chi tiết dịch vụ đưa đón sân bay

### Routes

`routes/web.php` - Định nghĩa các routes cho dịch vụ đưa đón sân bay:

```php
// Airport Transfer Routes
Route::get('/airport-transfers', [App\Http\Controllers\AirportTransferController::class, 'index'])->name('airport-transfers.index');
Route::get('/airport-transfers/create', [App\Http\Controllers\AirportTransferController::class, 'create'])->name('airport-transfers.create');
Route::post('/airport-transfers', [App\Http\Controllers\AirportTransferController::class, 'store'])->name('airport-transfers.store');
Route::get('/airport-transfers/{airportTransfer}', [App\Http\Controllers\AirportTransferController::class, 'show'])->name('airport-transfers.show');
Route::post('/airport-transfers/cancel/{booking}', [App\Http\Controllers\AirportTransferController::class, 'cancel'])->name('airport-transfers.cancel')->middleware('auth');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin Airport Transfer Routes
    Route::resource('airport-transfers', App\Http\Controllers\Admin\AirportTransferController::class);
});
```

## Hướng dẫn sử dụng

### Dành cho người dùng

1. **Đăng ký/Đăng nhập**: Người dùng cần đăng ký tài khoản hoặc đăng nhập để sử dụng các tính năng đặt phòng và dịch vụ.

2. **Tìm kiếm khách sạn**: Người dùng có thể tìm kiếm khách sạn theo địa điểm, ngày check-in/check-out, số lượng khách.

3. **Đặt phòng**: Chọn phòng phù hợp và tiến hành đặt phòng.

4. **Đặt dịch vụ đưa đón sân bay**:
   - Từ trang chi tiết khách sạn: Nhấn vào nút "Đặt dịch vụ ngay" trong phần dịch vụ đưa đón sân bay.
   - Từ trang chi tiết đặt phòng: Nhấn vào nút "Thêm dịch vụ đưa đón" trong phần dịch vụ đưa đón sân bay.
   - Từ menu chính: Chọn "Đưa đón sân bay" để xem danh sách dịch vụ, sau đó chọn dịch vụ phù hợp.

5. **Quản lý đặt phòng**: Người dùng có thể xem lịch sử đặt phòng, hủy đặt phòng hoặc hủy dịch vụ đưa đón sân bay.

### Dành cho admin

1. **Đăng nhập**: Admin đăng nhập với tài khoản có quyền admin.

2. **Quản lý khách sạn**: Thêm, sửa, xóa thông tin khách sạn.

3. **Quản lý phòng**: Thêm, sửa, xóa thông tin phòng, loại phòng.

4. **Quản lý dịch vụ đưa đón sân bay**:
   - Xem danh sách dịch vụ: Truy cập menu "Dịch vụ đưa đón sân bay" trong trang quản trị.
   - Thêm dịch vụ mới: Nhấn nút "Thêm dịch vụ mới" và điền thông tin.
   - Chỉnh sửa dịch vụ: Nhấn vào biểu tượng chỉnh sửa bên cạnh dịch vụ cần sửa.
   - Xóa dịch vụ: Nhấn vào biểu tượng xóa bên cạnh dịch vụ cần xóa.

5. **Quản lý đặt phòng**: Xem danh sách đặt phòng, chi tiết đặt phòng, xác nhận hoặc hủy đặt phòng.

## Tùy chỉnh và mở rộng

### Thêm loại phương tiện mới

1. Thêm dữ liệu mới vào bảng `airport_transfers` với thông tin phương tiện mới.
2. Tải lên hình ảnh phương tiện mới vào thư mục `public/images/airport-transfers/`.

### Tùy chỉnh giao diện

Các file view được lưu trong thư mục `resources/views/`. Bạn có thể chỉnh sửa các file này để thay đổi giao diện.

### Thêm tính năng mới

Dự án được xây dựng trên Laravel, bạn có thể dễ dàng thêm các tính năng mới bằng cách tạo thêm controllers, models, migrations và views.

## Giải quyết sự cố

### Lỗi "View [admin.hotels.create] not found"

Đảm bảo đã tạo file view `resources/views/admin/hotels/create.blade.php`.

### Lỗi "Undefined variable $transfers"

Đảm bảo đã truyền biến `$transfers` vào view trong phương thức `show()` của `AirportTransferController`.

### Lỗi "Route [dashboard] not defined"

Kiểm tra file `routes/web.php` và đảm bảo đã định nghĩa route có tên `dashboard`.

