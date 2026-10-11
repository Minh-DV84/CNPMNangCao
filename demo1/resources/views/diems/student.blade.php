@extends('layouts.layoutmaster')
@section('title', 'Điểm số của tôi')

@push('styles')
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; white-space: nowrap; }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Điểm số của tôi</h1>
        <a href="{{ route('diemso.export') }}" class="btn btn-outline-success">Xuất CSV</a>
    </div>
    <table>
        <tr><th>Mã môn</th><th>Tên môn</th><th>Chuyên cần</th><th>Giữa kỳ</th><th>Cuối kỳ</th><th>Tổng kết</th><th>Xếp loại</th></tr>
        @forelse ($monHocs as $monHoc)
            @php($diem = $monHoc->diems->first())
            <tr>
                <td>{{ $monHoc->ma_mon }}</td>
                <td>{{ $monHoc->ten_mon }}</td>
                <td>{{ $diem?->diem_chuyen_can ?? '-' }}</td>
                <td>{{ $diem?->diem_giua_ky ?? '-' }}</td>
                <td>{{ $diem?->diem_cuoi_ky ?? '-' }}</td>
                <td>{{ $diem?->diem_tong_ket ?? '-' }}</td>
                <td>{{ $diem?->xep_loai ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Chưa có môn học nào.</td></tr>
        @endforelse
    </table>
    <p class="mt-3"><strong>Điểm trung bình chung:</strong> {{ $average ?? '-' }}</p>
@endsection
