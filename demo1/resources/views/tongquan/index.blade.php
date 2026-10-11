@extends('layouts.layoutmaster')
@section('title', 'Tổng quan')

@section('content')
    <h1>Tổng quan</h1>

    @if ($user->role === 'admin')
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-primary h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fs-4 text-white">Tổng số sinh viên</div>
                        <div class="display-3 fw-bold text-white">{{ $data['total_sinh_vien'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-success h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fs-4 text-white">Tổng số lớp học</div>
                        <div class="display-3 fw-bold text-white">{{ $data['total_lop_hoc'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-secondary h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fs-4 text-white">Tổng số giảng viên</div>
                        <div class="display-3 fw-bold text-white">{{ $data['total_giang_vien'] }}</div>
                    </div>
                </div>
            </div>
        </div>

    @elseif ($user->role === 'giang_vien')
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-primary h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fs-4 text-white">Số lớp phụ trách</div>
                        <div class="display-3 fw-bold text-white">{{ $data['total_lop'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-success h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="fs-4 text-white">Tổng số sinh viên</div>
                        <div class="display-3 fw-bold text-white">{{ $data['total_sinh_vien'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <h3>Các lớp phụ trách</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã lớp</th>
                        <th>Tên lớp</th>
                        <th>Sĩ số</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['lop_hocs'] as $lop)
                        <tr>
                            <td>{{ $lop->ma_lop }}</td>
                            <td>{{ $lop->ten_lop }}</td>
                            <td>{{ $lop->sinh_viens_count }}</td>
                            <td>{{ $lop->trang_thai ? 'Hoạt động' : 'Không hoạt động' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif ($user->role === 'sinh_vien')
        @if ($data['sinh_vien'])
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thông tin cá nhân</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã sinh viên:</strong> {{ $data['sinh_vien']->msv }}</p>
                            <p><strong>Họ và tên:</strong> {{ $data['sinh_vien']->ho_ten }}</p>
                            <p><strong>Giới tính:</strong> {{ $data['sinh_vien']->gioi_tinh }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($data['sinh_vien']->ngay_sinh)->format('d/m/Y') }}</p>
                            <p><strong>Lớp:</strong> {{ $data['sinh_vien']->lopHoc ? $data['sinh_vien']->lopHoc->ten_lop : '-' }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $data['sinh_vien']->so_dien_thoai }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Không tìm thấy thông tin sinh viên.
            </div>
        @endif
    @endif
@endsection