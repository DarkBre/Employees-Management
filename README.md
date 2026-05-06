# Employees Management

Ứng dụng quản lí nhân viên cơ bản viết bằng PHP MVC. Dự án hỗ trợ đăng kí, đăng nhập, đăng xuất và các chức năng thêm, sửa, xoá nhân viên. Tài khoản và nhân viên đều được lưu trong MySQL, có thể dùng MySQL local trong XAMPP hoặc database online.

## Chức năng chính

- Đăng kí tài khoản.
- Đăng nhập và đăng xuất.
- Thêm nhân viên.
- Sửa thông tin nhân viên.
- Xoá nhân viên.
- Lưu dữ liệu nhân viên trong bảng `employees`.
- Lưu tài khoản trong bảng `users`.

## Giao diện

- Giao diện đã được Việt hoá bằng tiếng Việt có dấu.
- Thanh navbar giữ nút chuyển giữa đăng nhập và đăng kí khi người dùng chưa đăng nhập.
- Sau khi đăng nhập, navbar hiển thị lời chào và nút đăng xuất.
- Trang quản lí nhân viên dùng layout quản trị đơn giản, không còn sidebar.
- Trường `Tuổi` là ô nhập text thường, không có nút tăng/giảm.
- Trường `Ngày bắt đầu` là ô nhập tay theo định dạng `dd/mm/yyyy`, không dùng bảng lịch popup.

Ví dụ ngày hợp lệ:

```text
07/05/2026
```

Khi lưu vào database, ứng dụng tự chuyển ngày từ `dd/mm/yyyy` sang định dạng MySQL `yyyy-mm-dd`.

## Yêu cầu

- PHP 8+
- MySQL/MariaDB local hoặc database MySQL online
- XAMPP nếu chạy bằng PHP/MySQL local

## Cài đặt nhanh

1. Clone dự án:

```bash
git clone https://github.com/DarkBre/Employees-Management.git
cd Employees-Management
```

2. Cấu hình database tại `config/config.php` hoặc bằng biến môi trường.

Mặc định cho XAMPP:

```text
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=employee_manager
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

Với database online, đặt các biến môi trường tương ứng theo thông tin nhà cung cấp:

```text
DB_HOST=your-online-db-host
DB_PORT=3306
DB_NAME=your-database-name
DB_USER=your-database-user
DB_PASS=your-database-password
DB_CHARSET=utf8mb4
```

Nếu nhà cung cấp yêu cầu SSL CA, có thể đặt thêm:

```text
DB_SSL_CA=/path/to/ca.pem
```

3. Tạo database.

Tạo database tên `employee_manager` nếu chạy local bằng XAMPP. Ứng dụng sẽ tự tạo bảng `users` và `employees` khi kết nối được database.

Nếu muốn tạo thủ công, import file `database.sql` trong phpMyAdmin hoặc công cụ quản trị database online.

4. Chạy web:

Nếu dùng XAMPP trên Windows:

```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8000 -t public public\index.php
```

Nếu máy đã có `php` trong PATH:

```bash
php -S 127.0.0.1:8000 -t public public/index.php
```

5. Mở trình duyệt:

```text
http://127.0.0.1:8000/login
```

Vào `/register` để tạo tài khoản đầu tiên, sau đó đăng nhập để quản lí nhân viên.

## Cấu trúc thư mục

```text
app/
  Controllers/
    AuthController.php
    EmployeeController.php
  Core/
    Controller.php
    Database.php
    Router.php
  Models/
    Employee.php
    User.php
  Views/
    auth/
    employees/
    layouts/
config/
  config.php
public/
  assets/
  index.php
database.sql
```

## Ghi chú chạy ở máy khác

- Không cần dùng đường dẫn cố định của máy cũ.
- Nếu PHP không nằm trong `C:\xampp\php\php.exe`, hãy thay bằng đường dẫn PHP trên máy đó.
- Nếu đã thêm PHP vào PATH, chỉ cần dùng lệnh `php -S ...`.
- Cấu hình database có thể đổi bằng biến môi trường để không phải sửa trực tiếp code.
