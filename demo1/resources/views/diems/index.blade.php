@extends('layouts.layoutmaster')
@section('title', 'Điểm số')

@push('styles')
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: middle; }
        th { background-color: #f2f2f2; white-space: nowrap; }
        .filter-box { background: #fafcff; border: 1px solid #e1e8f0; border-radius: 6px; padding: 16px; margin-bottom: 16px; }
        .score-input { width: 90px; }
    </style>
@endpush

@section('content')
    <h1>Điểm số</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('diemso') }}" method="GET" class="filter-box">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="mon_hoc_id" class="form-label">Môn học</label>
                <select id="mon_hoc_id" name="mon_hoc_id" class="form-select">
                    <option value="">-- Chọn môn học --</option>
                    @foreach ($monHocs as $monHoc)
                        <option value="{{ $monHoc->id }}" @selected($selectedMonHocId === $monHoc->id)>{{ $monHoc->ma_mon }} - {{ $monHoc->ten_mon }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="lop_hoc_id" class="form-label">Lớp</label>
                <select id="lop_hoc_id" name="lop_hoc_id" class="form-select">
                    <option value="">-- Chọn lớp --</option>
                    @foreach ($lopHocs as $lopHoc)
                        <option value="{{ $lopHoc->id }}" @selected($selectedLopHocId === $lopHoc->id)>{{ $lopHoc->ma_lop }} - {{ $lopHoc->ten_lop }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="keyword" class="form-label">MSV hoặc họ tên</label>
                <input type="text" id="keyword" name="keyword" class="form-control" value="{{ $filters['keyword'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Xem</button>
                <a href="{{ route('diemso') }}" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </div>
    </form>

    @if ($students)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0">Tìm thấy <strong>{{ $students->total() }}</strong> sinh viên.</p>
            <a href="{{ route('diemso.export', request()->query()) }}" class="btn btn-outline-success">Xuất CSV</a>
        </div>
        <form action="{{ route('diemso.save') }}" method="POST">
            @csrf
            <input type="hidden" name="mon_hoc_id" value="{{ $selectedMonHocId }}">
            <input type="hidden" name="lop_hoc_id" value="{{ $selectedLopHocId }}">
            <input type="hidden" name="keyword" value="{{ $filters['keyword'] ?? '' }}">
            <table>
                <tr>
                    <th>MSV</th><th>Họ tên</th><th>Chuyên cần</th><th>Giữa kỳ</th><th>Cuối kỳ</th><th>Tổng kết</th><th>Xếp loại</th>
                </tr>
                @forelse ($students as $student)
                    @php($diem = $student->diems->first())
                    <tr>
                        <td>{{ $student->msv }}<input type="hidden" name="diems[{{ $student->id }}][sinh_vien_id]" value="{{ $student->id }}"></td>
                        <td>{{ $student->ho_ten }}</td>
                        <td><input type="number" name="diems[{{ $student->id }}][diem_chuyen_can]" class="form-control score-input" min="0" max="10" step="0.1" value="{{ $diem?->diem_chuyen_can }}"></td>
                        <td><input type="number" name="diems[{{ $student->id }}][diem_giua_ky]" class="form-control score-input" min="0" max="10" step="0.1" value="{{ $diem?->diem_giua_ky }}"></td>
                        <td><input type="number" name="diems[{{ $student->id }}][diem_cuoi_ky]" class="form-control score-input" min="0" max="10" step="0.1" value="{{ $diem?->diem_cuoi_ky }}"></td>
                        <td>{{ $diem?->diem_tong_ket ?? '-' }}</td>
                        <td>{{ $diem?->xep_loai ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">Không có sinh viên phù hợp.</td></tr>
                @endforelse
            </table>
            @if ($students->count())
                <button type="submit" class="btn btn-primary mt-3">Lưu tất cả</button>
            @endif
        </form>
        <div class="mt-3">{{ $students->links() }}</div>
    @else
        <div class="alert alert-info">Vui lòng chọn môn học và lớp để xem bảng điểm.</div>
    @endif
@endsection
