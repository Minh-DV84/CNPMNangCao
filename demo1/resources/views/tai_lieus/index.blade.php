@extends('layouts.layoutmaster')
@section('title', 'Danh sách tài liệu')

@push('styles')
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; white-space: nowrap; }
        .filter-box { background: #fafcff; border: 1px solid #e1e8f0; border-radius: 6px; padding: 16px; margin-bottom: 16px; }
        .description { max-width: 320px; }
    </style>
@endpush

@section('content')
    <h1>Danh sách tài liệu</h1>
    @if ($canManage)
        <a href="{{ route('tailieu.create') }}" class="btn btn-primary mb-3">Thêm tài liệu</a>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('tailieu.index') }}" method="GET" class="filter-box">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="keyword" class="form-label">Từ khóa</label>
                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Tiêu đề, mô tả, môn học..." value="{{ $filters['keyword'] ?? '' }}">
            </div>
            <div class="col-md-5">
                <label for="mon_hoc_id" class="form-label">Môn học</label>
                <select id="mon_hoc_id" name="mon_hoc_id" class="form-select">
                    <option value="">Tất cả môn học</option>
                    @foreach ($monHocs as $monHoc)
                        <option value="{{ $monHoc->id }}" @selected(($filters['mon_hoc_id'] ?? '') == $monHoc->id)>{{ $monHoc->ma_mon }} - {{ $monHoc->ten_mon }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('tailieu.index') }}" class="btn btn-outline-secondary mt-2 mt-md-0">Xóa</a>
            </div>
        </div>
    </form>

    <p>Tìm thấy <strong>{{ $taiLieus->total() }}</strong> tài liệu.</p>
    <table>
        <tr>
            <th>ID</th><th>Tiêu đề</th><th>Môn học</th><th>Mô tả</th><th>Ngày đăng</th><th>Tệp đính kèm</th><th>Thao tác</th>
        </tr>
        @forelse ($taiLieus as $taiLieu)
            <tr>
                <td>{{ $taiLieu->id }}</td>
                <td><a href="{{ route('tailieu.show', $taiLieu) }}">{{ $taiLieu->tieu_de }}</a></td>
                <td>{{ $taiLieu->monHoc?->ten_mon }}</td>
                <td class="description">{{ \Illuminate\Support\Str::limit($taiLieu->mo_ta ?: '-', 80) }}</td>
                <td>{{ $taiLieu->created_at?->format('d/m/Y H:i') }}</td>
                <td>
                    @if ($taiLieu->file_path)
                        <a href="{{ route('tailieu.download', $taiLieu) }}">Tải về</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($canManage)
                        <a href="{{ route('tailieu.edit', $taiLieu) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('tailieu.destroy', $taiLieu) }}" method="POST" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài liệu này không?')">Xóa</button>
                        </form>
                    @else
                        <a href="{{ route('tailieu.show', $taiLieu) }}" class="btn btn-sm btn-secondary">Xem</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Không có tài liệu nào phù hợp.</td></tr>
        @endforelse
    </table>
    <div class="mt-3">{{ $taiLieus->links() }}</div>
@endsection
