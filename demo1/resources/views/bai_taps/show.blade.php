@extends('layouts.layoutmaster')
@section('title', 'Chi tiết bài tập')

@push('styles')
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; vertical-align: top; }
        th { background-color: #f2f2f2; white-space: nowrap; }
        .grade-form { min-width: 260px; }
    </style>
@endpush

@section('content')
    <h1>{{ $baiTap->tieu_de }}</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <table class="table">
        <tr><th style="width: 220px">Môn học</th><td>{{ $baiTap->monHoc?->ma_mon }} - {{ $baiTap->monHoc?->ten_mon }}</td></tr>
        <tr><th>Nội dung</th><td>{!! nl2br(e($baiTap->noi_dung)) !!}</td></tr>
        <tr><th>Hạn nộp</th><td>{{ $baiTap->han_nop->format('d/m/Y H:i') }} ({{ $baiTap->han_nop->isPast() ? 'Hết hạn' : 'Còn hạn' }})</td></tr>
        <tr><th>Điểm tối đa</th><td>{{ $baiTap->diem_toi_da }}</td></tr>
    </table>

    @if ($isStudent)
        <h2>Bài nộp của tôi</h2>
        @if ($mySubmission)
            <p>
                <strong>Trạng thái:</strong> Đã nộp<br>
                <strong>Nộp lúc:</strong> {{ $mySubmission->nop_luc->format('d/m/Y H:i') }}<br>
                <strong>Tệp:</strong> <a href="{{ route('bai-nop.download', $mySubmission) }}">{{ $mySubmission->file_name }}</a><br>
                <strong>Điểm:</strong> {{ $mySubmission->diem !== null ? $mySubmission->diem . '/' . $baiTap->diem_toi_da : 'Chưa chấm' }}<br>
                <strong>Nhận xét:</strong> {{ $mySubmission->nhan_xet ?: '-' }}
            </p>
        @elseif ($baiTap->han_nop->isPast())
            <div class="alert alert-warning">Đã quá hạn nộp bài.</div>
        @endif

        @if (!$baiTap->han_nop->isPast())
            <form action="{{ route('baitap.submit', $baiTap) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Tệp bài nộp <span class="text-danger">*</span></label>
                    <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.zip" required>
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="ghi_chu" class="form-label">Ghi chú</label>
                    <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu', $mySubmission?->ghi_chu) }}</textarea>
                    @error('ghi_chu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ $mySubmission ? 'Nộp lại bài' : 'Nộp bài' }}</button>
            </form>
        @endif
    @else
        <h2>Danh sách bài nộp</h2>
        <table>
            <tr><th>MSV</th><th>Họ tên</th><th>Lớp</th><th>Thời gian nộp</th><th>Tệp</th><th>Chấm điểm</th></tr>
            @forelse ($baiNops as $baiNop)
                <tr>
                    <td>{{ $baiNop->sinhVien?->msv }}</td>
                    <td>{{ $baiNop->sinhVien?->ho_ten }}</td>
                    <td>{{ $baiNop->sinhVien?->lopHoc?->ten_lop }}</td>
                    <td>{{ $baiNop->nop_luc?->format('d/m/Y H:i') }}</td>
                    <td><a href="{{ route('bai-nop.download', $baiNop) }}">{{ $baiNop->file_name }}</a></td>
                    <td class="grade-form">
                        <form action="{{ route('bai-nop.grade', $baiNop) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="input-group mb-2">
                                <input type="number" name="diem" min="0" max="{{ $baiTap->diem_toi_da }}" step="0.01" class="form-control" value="{{ $baiNop->diem }}" placeholder="Điểm">
                                <span class="input-group-text">/{{ $baiTap->diem_toi_da }}</span>
                            </div>
                            <textarea name="nhan_xet" rows="2" class="form-control mb-2" placeholder="Nhận xét">{{ $baiNop->nhan_xet }}</textarea>
                            <button type="submit" class="btn btn-sm btn-primary">Lưu điểm</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Chưa có bài nộp.</td></tr>
            @endforelse
        </table>

        <h2>Sinh viên chưa nộp</h2>
        <table>
            <tr><th>MSV</th><th>Họ tên</th><th>Lớp</th></tr>
            @forelse ($chuaNops as $sinhVien)
                <tr><td>{{ $sinhVien->msv }}</td><td>{{ $sinhVien->ho_ten }}</td><td>{{ $sinhVien->lopHoc?->ten_lop }}</td></tr>
            @empty
                <tr><td colspan="3" class="text-center">Tất cả sinh viên đã nộp.</td></tr>
            @endforelse
        </table>
    @endif

    <div class="mt-3">
        @if (!$isStudent && auth()->user()->role !== 'sinh_vien')
            <a href="{{ route('baitap.edit', $baiTap) }}" class="btn btn-primary">Sửa bài tập</a>
        @endif
        <a href="{{ route('baitap.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
@endsection
