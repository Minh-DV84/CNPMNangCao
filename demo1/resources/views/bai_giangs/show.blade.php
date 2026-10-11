@extends('layouts.layoutmaster')
@section('title', 'Chi tiết bài giảng')
@section('content')
    <h1>Chi tiết bài giảng</h1>
    <table class="table">
        <tr><th style="width: 220px">ID</th><td>{{ $baiGiang->id }}</td></tr>
        <tr><th>Tiêu đề</th><td>{{ $baiGiang->tieu_de }}</td></tr>
        <tr><th>Môn học</th><td>{{ $baiGiang->monHoc?->ma_mon }} - {{ $baiGiang->monHoc?->ten_mon }}</td></tr>
        <tr><th>Mô tả</th><td>{{ $baiGiang->mo_ta ?: '-' }}</td></tr>
        <tr><th>Ngày đăng</th><td>{{ $baiGiang->created_at?->format('d/m/Y H:i') }}</td></tr>
        <tr><th>Tệp đính kèm</th><td>@if ($baiGiang->file_path)<a href="{{ route('baigiang.download', $baiGiang) }}">{{ $baiGiang->file_name }}</a>@else - @endif</td></tr>
    </table>
    @if (auth()->user()->role !== 'sinh_vien')
        <a href="{{ route('baigiang.edit', $baiGiang) }}" class="btn btn-primary">Sửa</a>
    @endif
    <a href="{{ route('baigiang.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
