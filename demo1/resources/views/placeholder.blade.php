@extends('layouts.layoutmaster')
@section('title', $title)

@section('content')
    <div class="alert alert-info">
        <h4>{{ $title }}</h4>
        <p>Chức năng này đang được phát triển. Vui lòng quay lại sau.</p>
        <a href="{{ route('tongquan') }}" class="btn btn-primary mt-2">Về trang Tổng quan</a>
    </div>
@endsection
