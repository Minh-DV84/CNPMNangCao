@extends('layouts.layoutmaster')
@section('title', 'Thêm lớp học')

@section('content')
    <h1>Thêm lớp học</h1>

    {{-- novalidate: tắt kiểm tra của trình duyệt để thấy rõ lỗi do server (Form Request) trả về --}}
    <form action="{{ route('lophoc.store') }}" method="POST" novalidate>
        @csrf
        @include('lop_hocs._form')
        <button type="submit" class="btn btn-primary">Thêm lớp học</button>
        <a href="{{ route('lophoc.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
