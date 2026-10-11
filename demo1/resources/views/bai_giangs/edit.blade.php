@extends('layouts.layoutmaster')
@section('title', 'Sửa bài giảng')
@section('content')
    <h1>Sửa bài giảng: {{ $baiGiang->tieu_de }}</h1>
    <form action="{{ route('baigiang.update', $baiGiang) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')
        @include('bai_giangs._form', ['baiGiang' => $baiGiang])
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('baigiang.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
