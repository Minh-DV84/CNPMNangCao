@extends('layouts.layoutmaster')
@section('title', 'Sửa sinh viên')

@section('content')
    <h1>Sửa sinh viên: {{ $sinhVien->ho_ten }}</h1>

    <form action="{{ route('sinhvien.update', $sinhVien) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        @include('sinhvien._form', ['sinhVien' => $sinhVien])
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('sinhvien.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
