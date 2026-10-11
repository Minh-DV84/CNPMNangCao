@extends('layouts.layoutmaster')
@section('title', 'Thêm bài tập')
@section('content')
    <h1>Thêm bài tập</h1>
    <form action="{{ route('baitap.store') }}" method="POST" novalidate>
        @csrf
        @include('bai_taps._form')
        <button type="submit" class="btn btn-primary">Thêm bài tập</button>
        <a href="{{ route('baitap.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
