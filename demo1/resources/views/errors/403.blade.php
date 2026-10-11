@extends('layouts.layoutmaster')
@section('title', 'Không có quyền truy cập')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <span style="font-size: 80px; color: #dc3545;">403</span>
                        </div>
                        <h2 class="card-title mb-3">Không có quyền truy cập</h2>
                        <p class="card-text text-muted mb-4">
                            Bạn không có quyền truy cập trang này. Vui lòng liên hệ quản trị viên nếu bạn nghĩ đây là lỗi.
                        </p>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            ← Quay lại
                        </a>
                        <a href="{{ route('tongquan') }}" class="btn btn-primary ms-2">
                            Về trang Tổng quan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
