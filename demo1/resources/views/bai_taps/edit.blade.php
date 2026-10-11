@extends('layouts.layoutmaster')
@section('title', 'Sửa bài tập')
@section('content')
    <h1>Sửa bài tập: {{ $baiTap->tieu_de }}</h1>
    <form action="{{ route('baitap.update', $baiTap) }}" method="POST" novalidate>
        @csrf @method('PUT')
        @include('bai_taps._form', ['baiTap' => $baiTap])
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('baitap.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
