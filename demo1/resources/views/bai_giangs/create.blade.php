@extends('layouts.layoutmaster')
@section('title', 'Thêm bài giảng')
@section('content')
    <h1>Thêm bài giảng</h1>
    <form action="{{ route('baigiang.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @include('bai_giangs._form')
        <button type="submit" class="btn btn-primary">Thêm bài giảng</button>
        <a href="{{ route('baigiang.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
