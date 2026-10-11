@extends('layouts.layoutmaster')
@section('title', 'Sửa menu')

@section('content')
    <h1>Sửa menu: {{ $menu->ten }}</h1>

    <form action="{{ route('menu.update', $menu) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        @include('menus._form', ['menu' => $menu])
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('menu.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
