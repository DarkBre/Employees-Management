# Employees Management

Ứng dụng quản lí nhân viên cơ bản viết bằng PHP MVC, dùng MySQL cho đăng nhập/đăng kí và file JSON cho dữ liệu nhân viên mẫu.

## Yêu cầu

- XAMPP hoặc PHP 8+
- MySQL/MariaDB

## Cài đặt nhanh

1. Clone dự án:

```bash
git clone https://github.com/DarkBre/Employees-Management.git
cd Employees-Management
```

2. Bật MySQL trong XAMPP.

3. Cấu hình database nếu cần tại `config/config.php`.

Mặc định:

```php
DB_HOST = 127.0.0.1
DB_PORT = 3306
DB_NAME = employee_manager
DB_USER = root
DB_PASS = ''
```

Ứng dụng sẽ tự tạo database `employee_manager` và bảng `users` khi MySQL đang chạy. Nếu muốn tạo thủ công, import file `database.sql` trong phpMyAdmin.

4. Chạy web:

```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8000 -t public public\index.php
```

Hoặc nếu đã có `php` trong PATH:

```bash
php -S 127.0.0.1:8000 -t public public/index.php
```

5. Mở trình duyệt:

```text
http://127.0.0.1:8000/login
```

Vào `/register` để tạo tài khoản đầu tiên, sau đó đăng nhập để quản lí nhân viên.

## Cấu trúc

```text
app/
  Controllers/
  Core/
  Models/
  Views/
config/
public/
  assets/
storage/
database.sql
```
