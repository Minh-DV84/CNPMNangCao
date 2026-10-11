@extends('layouts.layoutmaster')
@section('title', 'Thêm sinh viên')
@push('styles')
    <style>
        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            margin-top: 10px;
        }

        input[type="text"],
        input[type="number"] {
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
@endpush
@section('content')
    <form method="POST" action="{{ route('sinhvien.store')}}">
        @csrf
        <label for="ten">Tên sinh viên</label>
        <input type="text" name="ten" id="ten">
        <label for="tuoi">Tuổi</label>
        <input type="number" name="tuoi" id="tuoi">
        <label for="diachi">Địa chỉ</label>
        <input type="text" name="diachi" id="diachi">
        <button type="submit" style="margin-top: 20px; padding: 10px; background-color: var(--c-accent); color: #fff; border: none; border-radius: 4px;">Thêm sinh viên</button>
    </form>
@endsection