@extends('layouts.layoutmaster')
@section('title', 'Hướng dẫn sử dụng')

@push('styles')
    <style>
        .guide-intro {
            background: linear-gradient(135deg, var(--c-header), var(--c-sidebar));
            color: #fff;
            border-radius: 6px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .guide-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .guide-step {
            position: relative;
            border: 1px solid #e1e8f0;
            border-left: 4px solid var(--c-accent);
            border-radius: 6px;
            padding: 20px 20px 20px 60px;
            background: #fafcff;
            min-height: 130px;
        }

        .guide-number {
            position: absolute;
            left: 18px;
            top: 20px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--c-accent);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
        }

        .guide-step h3 {
            color: var(--c-header);
            font-size: 17px;
            margin-bottom: 8px;
        }

        .guide-step p { margin: 0; color: #5a6b80; }
        .guide-section { margin-top: 28px; }
        .guide-section h2 { margin-bottom: 14px; }
    </style>
@endpush

@section('content')
    @php($role = auth()->user()->role)

    <div class="guide-intro">
        <h1 class="text-white mb-2">Hướng dẫn sử dụng HUCE.Web</h1>
        <p class="mb-0">Các bước thao tác theo vai trò của bạn trong hệ thống quản lý đào tạo.</p>
    </div>

    <section class="guide-section">
        <h2>{{ auth()->user()->role_label }}</h2>
        <div class="guide-grid">
            @if ($role === 'admin')
                <article class="guide-step"><span class="guide-number">1</span><h3>Quản lý sinh viên</h3><p>Mở mục Sinh viên để thêm, sửa, xóa và tra cứu thông tin sinh viên.</p></article>
                <article class="guide-step"><span class="guide-number">2</span><h3>Quản lý lớp học</h3><p>Quản lý lớp, sĩ số, trạng thái và phân công giảng viên phụ trách.</p></article>
                <article class="guide-step"><span class="guide-number">3</span><h3>Quản lý menu</h3><p>Thêm, sửa, sắp xếp và kiểm soát quyền hiển thị các mục menu.</p></article>
                <article class="guide-step"><span class="guide-number">4</span><h3>Tạo tài khoản</h3><p>Tài khoản được tạo theo dữ liệu người dùng và có thể gán vai trò phù hợp.</p></article>
                <article class="guide-step"><span class="guide-number">5</span><h3>Cấp quyền</h3><p>Phân quyền admin, giảng viên hoặc sinh viên để giới hạn chức năng và dữ liệu.</p></article>
            @elseif ($role === 'giang_vien')
                <article class="guide-step"><span class="guide-number">1</span><h3>Xem sinh viên</h3><p>Mở mục Sinh viên để xem danh sách sinh viên thuộc các lớp mình phụ trách.</p></article>
                <article class="guide-step"><span class="guide-number">2</span><h3>Đăng bài giảng và tài liệu</h3><p>Chọn môn mình dạy, nhập nội dung và tải file lên tại Bài giảng hoặc Tài liệu.</p></article>
                <article class="guide-step"><span class="guide-number">3</span><h3>Giao bài tập</h3><p>Tạo bài tập, chọn môn, nhập nội dung và đặt hạn nộp.</p></article>
                <article class="guide-step"><span class="guide-number">4</span><h3>Chấm bài</h3><p>Mở bài tập để xem bài nộp, tải file, nhập điểm và nhận xét cho sinh viên.</p></article>
                <article class="guide-step"><span class="guide-number">5</span><h3>Nhập điểm</h3><p>Chọn môn và lớp trong Điểm số, nhập các cột điểm rồi bấm Lưu tất cả.</p></article>
            @else
                <article class="guide-step"><span class="guide-number">1</span><h3>Xem bài giảng</h3><p>Mở Bài giảng để xem và tải nội dung thuộc các môn của lớp mình.</p></article>
                <article class="guide-step"><span class="guide-number">2</span><h3>Xem tài liệu</h3><p>Mở Tài liệu để xem hoặc tải tài liệu học tập được cung cấp.</p></article>
                <article class="guide-step"><span class="guide-number">3</span><h3>Nộp bài tập</h3><p>Mở bài tập còn hạn, chọn file hợp lệ và bấm Nộp bài. Bạn có thể nộp lại trước hạn.</p></article>
                <article class="guide-step"><span class="guide-number">4</span><h3>Xem điểm</h3><p>Mở Điểm số để xem điểm thành phần, tổng kết và xếp loại của mình.</p></article>
            @endif
        </div>
    </section>

    <section class="guide-section">
        <h2>Thao tác chung</h2>
        <div class="guide-grid">
            <article class="guide-step"><span class="guide-number">1</span><h3>Đăng nhập</h3><p>Dùng email đã đăng ký hoặc mã sinh viên 7 chữ số cùng mật khẩu để đăng nhập.</p></article>
            <article class="guide-step"><span class="guide-number">2</span><h3>Đăng xuất</h3><p>Mở menu tài khoản ở phần đầu trang và chọn Đăng xuất sau khi hoàn tất.</p></article>
        </div>
    </section>
@endsection
