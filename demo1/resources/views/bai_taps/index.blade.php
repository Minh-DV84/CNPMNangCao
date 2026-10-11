@extends('layouts.layoutmaster')
@section('title', 'Danh sách bài tập')

@push('styles')
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; white-space: nowrap; }
        .filter-box { background: #fafcff; border: 1px solid #e1e8f0; border-radius: 6px; padding: 16px; margin-bottom: 16px; }
    </style>
@endpush

@section('content')
    <h1>Danh sách bài tập</h1>
    @if ($canManage)
        <a href="{{ route('baitap.create') }}" class="btn btn-primary mb-3">Thêm bài tập</a>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('baitap.index') }}" method="GET" class="filter-box">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="keyword" class="form-label">Từ khóa</label>
                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Tiêu đề, nội dung, môn học..." value="{{ $filters['keyword'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="mon_hoc_id" class="form-label">Môn học</label>
                <select id="mon_hoc_id" name="mon_hoc_id" class="form-select">
                    <option value="">Tất cả môn học</option>
                    @foreach ($monHocs as $monHoc)
                        <option value="{{ $monHoc->id }}" @selected(($filters['mon_hoc_id'] ?? '') == $monHoc->id)>{{ $monHoc->ma_mon }} - {{ $monHoc->ten_mon }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="con_han" @selected(($filters['trang_thai'] ?? '') === 'con_han')>Còn hạn</option>
                    <option value="het_han" @selected(($filters['trang_thai'] ?? '') === 'het_han')>Hết hạn</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('baitap.index') }}" class="btn btn-outline-secondary mt-2 mt-md-0">Xóa</a>
            </div>
        </div>
    </form>

    <p>Tìm thấy <strong>{{ $baiTaps->total() }}</strong> bài tập.</p>
    <table>
        <tr>
            <th>ID</th><th>Tiêu đề</th><th>Môn học</th><th>Hạn nộp</th><th>Trạng thái</th>
            @if ($isStudent)<th>Bài nộp của tôi</th>@endif
            <th>Thao tác</th>
        </tr>
        @forelse ($baiTaps as $baiTap)
            @php
                $submission = $isStudent ? $baiTap->baiNops->first() : null;
                $expired = $baiTap->han_nop->isPast();
            @endphp
            <tr>
                <td>{{ $baiTap->id }}</td>
                <td><a href="{{ route('baitap.show', $baiTap) }}">{{ $baiTap->tieu_de }}</a></td>
                <td>{{ $baiTap->monHoc?->ten_mon }}</td>
                <td>{{ $baiTap->han_nop->format('d/m/Y H:i') }}</td>
                <td>{{ $expired ? 'Hết hạn' : 'Còn hạn' }}</td>
                @if ($isStudent)
                    <td>
                        @if ($submission)
                            Đã nộp @if ($submission->diem !== null) - {{ $submission->diem }}/{{ $baiTap->diem_toi_da }} điểm @endif
                        @elseif ($expired)
                            Quá hạn
                        @else
                            Chưa nộp
                        @endif
                    </td>
                @endif
                <td>
                    <a href="{{ route('baitap.show', $baiTap) }}" class="btn btn-sm btn-secondary">Xem</a>
                    @if ($canManage)
                        <a href="{{ route('baitap.edit', $baiTap) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('baitap.destroy', $baiTap) }}" method="POST" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài tập này không?')">Xóa</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ $isStudent ? 7 : 6 }}" class="text-center">Không có bài tập nào phù hợp.</td></tr>
        @endforelse
    </table>
    <div class="mt-3">{{ $baiTaps->links() }}</div>
@endsection
