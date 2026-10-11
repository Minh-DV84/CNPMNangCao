@extends('layouts.layoutmaster')
@section('title', 'Danh sách bài giảng')

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
    <h1>Danh sách bài giảng</h1>
    @if ($canManage)
        <a href="{{ route('baigiang.create') }}" class="btn btn-primary mb-3">Thêm bài giảng</a>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('baigiang.index') }}" method="GET" class="filter-box">
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
                <a href="{{ route('baigiang.index') }}" class="btn btn-outline-secondary mt-2 mt-md-0">Xóa</a>
            </div>
        </div>
    </form>

    <p>Tìm thấy <strong>{{ $baiGiangs->total() }}</strong> bài giảng.</p>
    <table>
        <tr>
            <th>ID</th><th>Tiêu đề</th><th>Môn học</th><th>Mô tả</th><th>Ngày đăng</th><th>Tệp đính kèm</th><th>Thao tác</th>
        </tr>
        @forelse ($baiGiangs as $baiGiang)
            <tr>
                <td>{{ $baiGiang->id }}</td>
                <td><a href="{{ route('baigiang.show', $baiGiang) }}">{{ $baiGiang->tieu_de }}</a></td>
                <td>{{ $baiGiang->monHoc?->ten_mon }}</td>
                <td class="description">{{ \Illuminate\Support\Str::limit($baiGiang->mo_ta ?: '-', 80) }}</td>
                <td>{{ $baiGiang->created_at?->format('d/m/Y H:i') }}</td>
                <td>
                    @if ($baiGiang->file_path)
                        <a href="{{ route('baigiang.download', $baiGiang) }}">Tải về</a>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if ($canManage)
                        <a href="{{ route('baigiang.edit', $baiGiang) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('baigiang.destroy', $baiGiang) }}" method="POST" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài giảng này không?')">Xóa</button>
                        </form>
                    @else
                        <a href="{{ route('baigiang.show', $baiGiang) }}" class="btn btn-sm btn-secondary">Xem</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Không có bài giảng nào phù hợp.</td></tr>
        @endforelse
    </table>
    <div class="mt-3">{{ $baiGiangs->links() }}</div>
@endsection
