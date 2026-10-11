@extends('layouts.layoutmaster')
@section('title', 'Chi tiết sinh viên')

@section('content')
    <h1>Chi tiết sinh viên</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $sinhVien->ho_ten }}</h5>
            <p class="card-text">
                <strong>MSV:</strong> {{ $sinhVien->msv }}<br>
                <strong>Giới tính:</strong> {{ $sinhVien->gioi_tinh }}<br>
                <strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($sinhVien->ngay_sinh)->format('d/m/Y') }}<br>
                <strong>Lớp:</strong> {{ $sinhVien->lopHoc ? $sinhVien->lopHoc->ten_lop . ' (' . $sinhVien->lopHoc->ma_lop . ')' : '-' }}<br>
                <strong>Số điện thoại:</strong> {{ $sinhVien->so_dien_thoai }}
            </p>
            <a href="{{ route('sinhvien.edit', $sinhVien) }}" class="btn btn-primary">Sửa</a>
            <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
@endsection
