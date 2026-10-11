@extends('layouts.layoutmaster')
@section('title', 'Sửa tài liệu')
@section('content')
    <h1>Sửa tài liệu: {{ $taiLieu->tieu_de }}</h1>
    <form action="{{ route('tailieu.update', $taiLieu) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')
        @include('tai_lieus._form', ['taiLieu' => $taiLieu])
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('tailieu.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
