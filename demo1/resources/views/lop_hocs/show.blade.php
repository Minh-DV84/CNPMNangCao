@extends('layouts.layoutmaster')
@section('title', 'Chi tiết lớp học')

@section('content')
    <h1>Chi tiết lớp học</h1>

    <table class="table">
        <tr><th style="width: 220px">ID</th><td>{{ $lopHoc->id }}</td></tr>
        <tr><th>Tên lớp</th><td>{{ $lopHoc->ten_lop }}</td></tr>
        <tr><th>Mã lớp</th><td>{{ $lopHoc->ma_lop }}</td></tr>
        <tr><th>Giáo viên</th><td>{{ $lopHoc->giao_vien }}</td></tr>
        <tr><th>Số điện thoại GVCN</th><td>{{ $lopHoc->so_dien_thoai_gvcn }}</td></tr>
        <tr><th>Ghi chú</th><td>{{ $lopHoc->ghi_chu }}</td></tr>
        <tr><th>Sĩ số</th><td>{{ $lopHoc->si_so }}</td></tr>
        <tr><th>Trạng thái</th><td>{{ $lopHoc->trang_thai ? 'Hoạt động' : 'Không hoạt động' }}</td></tr>
    </table>

    <a href="{{ route('lophoc.edit', $lopHoc) }}" class="btn btn-primary">Sửa</a>
    <a href="{{ route('lophoc.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
