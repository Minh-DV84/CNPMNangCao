@extends('layouts.layoutmaster')
@section('title', 'Thêm menu')

@section('content')
    <h1>Thêm menu</h1>

    <form action="{{ route('menu.store') }}" method="POST" novalidate>
        @csrf
        @include('menus._form')
        <button type="submit" class="btn btn-primary">Thêm menu</button>
        <a href="{{ route('menu.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
@endsection
