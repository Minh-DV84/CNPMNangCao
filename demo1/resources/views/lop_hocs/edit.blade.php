@extends('layouts.layoutmaster')
@section('title', 'Sửa lớp học')

@section('content')
    <h1>Sửa lớp học: {{ $lopHoc->ten_lop }}</h1>

    <form action="{{ route('lophoc.update', $lopHoc) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        @include('lop_hocs._form', ['lopHoc' => $lopHoc])
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('lophoc.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
