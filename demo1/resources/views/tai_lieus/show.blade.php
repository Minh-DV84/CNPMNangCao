@extends('layouts.layoutmaster')
@section('title', 'Chi tiết tài liệu')
@section('content')
    <h1>Chi tiết tài liệu</h1>
    <table class="table">
        <tr><th style="width: 220px">ID</th><td>{{ $taiLieu->id }}</td></tr>
        <tr><th>Tiêu đề</th><td>{{ $taiLieu->tieu_de }}</td></tr>
        <tr><th>Môn học</th><td>{{ $taiLieu->monHoc?->ma_mon }} - {{ $taiLieu->monHoc?->ten_mon }}</td></tr>
        <tr><th>Mô tả</th><td>{{ $taiLieu->mo_ta ?: '-' }}</td></tr>
        <tr><th>Ngày đăng</th><td>{{ $taiLieu->created_at?->format('d/m/Y H:i') }}</td></tr>
        <tr><th>Tệp đính kèm</th><td>@if ($taiLieu->file_path)<a href="{{ route('tailieu.download', $taiLieu) }}">{{ $taiLieu->file_name }}</a>@else - @endif</td></tr>
    </table>
    @if (auth()->user()->role !== 'sinh_vien')
        <a href="{{ route('tailieu.edit', $taiLieu) }}" class="btn btn-primary">Sửa</a>
    @endif
    <a href="{{ route('tailieu.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
