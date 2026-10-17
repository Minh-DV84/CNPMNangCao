@extends('layouts.layoutmaster')
@section('title', 'Chi tiết người dùng')

@section('content')
    <h1>Chi tiết người dùng</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Quay lại</a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text">
                <strong>ID:</strong> {{ $user->id }}<br>
                <strong>Email:</strong> {{ $user->email ?? '-' }}<br>
                <strong>Vai trò:</strong> {{ $user->role_label }}<br>
                <strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}<br>
                <strong>Cập nhật lần cuối:</strong> {{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') }}
            </p>

            @if ($user->sinhVien)
                <hr>
                <h6>Thông tin sinh viên liên kết:</h6>
                <p class="card-text">
                    <strong>MSV:</strong> {{ $user->sinhVien->msv }}<br>
                    <strong>Họ tên:</strong> {{ $user->sinhVien->ho_ten }}<br>
                    <strong>Giới tính:</strong> {{ $user->sinhVien->gioi_tinh }}<br>
                    <strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($user->sinhVien->ngay_sinh)->format('d/m/Y') }}<br>
                    <strong>Lớp:</strong> {{ $user->sinhVien->lopHoc?->ten_lop ?? '-' }}
                </p>
            @endif

            @if ($user->giangVien)
                <hr>
                <h6>Thông tin giảng viên liên kết:</h6>
                <p class="card-text">
                    <strong>Mã GV:</strong> {{ $user->giangVien->ma_gv }}<br>
                    <strong>Họ tên:</strong> {{ $user->giangVien->ho_ten }}<br>
                    <strong>Khoa:</strong> {{ $user->giangVien->khoa }}<br>
                    <strong>SĐT:</strong> {{ $user->giangVien->so_dien_thoai }}
                </p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Sửa</a>
    </div>
@endsection
