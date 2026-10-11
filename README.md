# HUCE.Web

Ứng dụng quản lý đào tạo xây dựng bằng Laravel 13, MySQL và giao diện HUCE.Web.
Mã nguồn Laravel nằm trong thư mục `demo1`.

## Tài khoản mẫu

Các tài khoản được tạo trong `demo1/database/seeders/UserSeeder.php`.

| Role | Tài khoản đăng nhập | Mật khẩu |
|---|---|---|
| Admin | `admin@huce.edu.vn` | `Admin@123` |
| Giảng viên | `gv1@huce.edu.vn` | `GiangVien@123` |
| Giảng viên | `gv2@huce.edu.vn` | `GiangVien@123` |
| Giảng viên | `gv3@huce.edu.vn` | `GiangVien@123` |
| Sinh viên | `1011068` | `SinhVien@123` |
| Sinh viên | `0110168` | `SinhVien@123` |

Sinh viên đăng nhập bằng mã sinh viên 7 chữ số thay vì email. Các tài khoản mẫu chỉ dùng cho môi trường phát triển; cần đổi hoặc vô hiệu hóa trước khi triển khai thật.

## Quyền theo role

### Admin

- Quản lý sinh viên, lớp học và menu.
- Quản lý toàn bộ môn học, bài giảng, tài liệu và bài tập.
- Nhập/sửa điểm của mọi lớp và môn.
- Xuất bảng điểm CSV.

### Giảng viên

- Xem sinh viên thuộc lớp mình phụ trách.
- Quản lý bài giảng, tài liệu và bài tập của môn mình dạy.
- Xem bài nộp, chấm điểm và nhập điểm cho môn/lớp được phân công.
- Không truy cập được dữ liệu của môn hoặc lớp khác.

### Sinh viên

- Xem bài giảng, tài liệu và bài tập thuộc lớp của mình.
- Nộp hoặc nộp lại bài trước hạn.
- Xem điểm và nhận xét của chính mình.
- Không có quyền tạo, sửa, xóa hoặc nhập điểm.

## Chức năng chính

- Đăng nhập bằng email hoặc mã sinh viên.
- Phân quyền `admin`, `giang_vien`, `sinh_vien`.
- Quản lý sinh viên, lớp học và menu.
- Quản lý môn học, bài giảng và tài liệu.
- Quản lý bài tập, nộp bài, tải bài nộp và chấm điểm.
- Quản lý điểm chuyên cần, giữa kỳ, cuối kỳ và xuất bảng điểm CSV.
- Hướng dẫn sử dụng và câu hỏi thường gặp.

## Cài đặt

Yêu cầu đã cài PHP, Composer, Node.js, npm và MySQL.

```bash
cd demo1
composer install
npm install
```

Tạo file môi trường:

```bash
cp .env.example .env
php artisan key:generate
```

Mở file `.env` và cấu hình MySQL:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=68pm12
DB_USERNAME=root
DB_PASSWORD=
```

Nếu MySQL trên Ubuntu không cho tài khoản `root` đăng nhập qua TCP, hãy tạo user riêng cho ứng dụng và cập nhật lại `DB_USERNAME`, `DB_PASSWORD`.

## Khởi tạo database

```bash
php artisan migrate
php artisan storage:link
php artisan db:seed
```

Seeder sẽ tạo dữ liệu mẫu gồm lớp học, giảng viên, sinh viên, môn học, bài giảng, tài liệu, bài tập và điểm số.

Để xóa và tạo lại toàn bộ database trong môi trường phát triển:

```bash
php artisan migrate:fresh --seed
```

## Chạy ứng dụng

Terminal 1:

```bash
cd demo1
php artisan serve
```

Terminal 2, dùng khi phát triển frontend:

```bash
cd demo1
npm run dev
```

Mở trình duyệt tại http://127.0.0.1:8000.


## Các URL chính

| URL | Chức năng |
|---|---|
| `/login` | Đăng nhập |
| `/tongquan` | Trang tổng quan |
| `/sinhvien` | Danh sách sinh viên |
| `/lophoc` | Quản lý lớp học |
| `/menu` | Quản lý menu |
| `/baigiang` | Bài giảng |
| `/tailieu` | Tài liệu |
| `/baitap` | Bài tập và bài nộp |
| `/diemso` | Bảng điểm |
| `/huongdan` | Hướng dẫn sử dụng |
| `/cauhoi` | Câu hỏi thường gặp |

## Kiểm tra nhanh

```bash
cd demo1
php artisan route:list
php artisan view:cache
php artisan test
```

## Lưu ý file upload

File bài giảng và tài liệu được lưu trong `storage/app/public/baigiang` và `storage/app/public/tailieu`.
File bài nộp được lưu trong `storage/app/public/baitap`.

Sau khi clone hoặc cài đặt mới, luôn chạy:

```bash
php artisan storage:link
```
