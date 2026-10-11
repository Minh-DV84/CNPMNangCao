@extends('layouts.layoutmaster')
@section('title', 'Thêm tài liệu')
@section('content')
    <h1>Thêm tài liệu</h1>
    <form action="{{ route('tailieu.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @include('tai_lieus._form')
        <button type="submit" class="btn btn-primary">Thêm tài liệu</button>
        <a href="{{ route('tailieu.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
