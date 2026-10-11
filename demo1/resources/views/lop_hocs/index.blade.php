@extends('layouts.layoutmaster')
@section('title', 'Danh sách lớp học')

@push('styles')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            white-space: nowrap;
        }

        th a {
            color: inherit;
            text-decoration: none;
        }

        th a:hover {
            text-decoration: underline;
        }

        .filter-box {
            background: #fafcff;
            border: 1px solid #e1e8f0;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 16px;
        }
    </style>
@endpush

@section('content')
    <h1>Danh sách lớp học</h1>
    <a href="{{ route('lophoc.create') }}" class="btn btn-primary mb-3">Thêm lớp học</a>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Bộ lọc: form GET nên điều kiện lọc nằm trên URL, copy link gửi người khác vẫn ra đúng kết quả --}}
    <form action="{{ route('lophoc.index') }}" method="GET" class="filter-box">
        {{-- Giữ nguyên cột đang sắp xếp khi bấm Lọc --}}
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="keyword" class="form-label">Từ khóa</label>
                <input type="text" id="keyword" name="keyword" class="form-control"
                       placeholder="Tên lớp, mã lớp, giáo viên..." value="{{ $filters['keyword'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(($filters['trang_thai'] ?? '') === '1')>Hoạt động</option>
                    <option value="0" @selected(($filters['trang_thai'] ?? '') === '0')>Không hoạt động</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="si_so_min" class="form-label">Sĩ số từ</label>
                <input type="number" id="si_so_min" name="si_so_min" min="0" class="form-control"
                       value="{{ $filters['si_so_min'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="si_so_max" class="form-label">đến</label>
                <input type="number" id="si_so_max" name="si_so_max" min="0" class="form-control"
                       value="{{ $filters['si_so_max'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="perPage" class="form-label">Số dòng / trang</label>
                <select id="perPage" name="perPage" class="form-select" onchange="this.form.submit()">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('lophoc.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </div>
    </form>

    <p>Tìm thấy <strong>{{ $lopHocs->total() }}</strong> lớp học.</p>

    @php
        $columns = [
            'id' => 'ID',
            'ten_lop' => 'Tên lớp',
            'ma_lop' => 'Mã lớp',
            'giao_vien' => 'Giáo viên',
            'so_dien_thoai_gvcn' => 'Số điện thoại GVCN',
            'ghi_chu' => 'Ghi chú',
            'si_so' => 'Sĩ số',
            'trang_thai' => 'Trạng thái',
        ];
    @endphp

    <table>
        <tr>
            @foreach ($columns as $column => $label)
                <th>
                    @if (in_array($column, $sortable))
                        {{-- Bấm vào cột đang sắp xếp thì đảo chiều; đổi cột thì về lại trang 1 --}}
                        <a href="{{ request()->fullUrlWithQuery([
                            'sort' => $column,
                            'direction' => $sort === $column && $direction === 'asc' ? 'desc' : 'asc',
                            'page' => null,
                        ]) }}">
                            {{ $label }}
                            @if ($sort === $column)
                                {{ $direction === 'asc' ? '▲' : '▼' }}
                            @endif
                        </a>
                    @else
                        {{ $label }}
                    @endif
                </th>
            @endforeach
            <th>Thao tác</th>
        </tr>
        @forelse ($lopHocs as $lopHoc)
            <tr>
                <td>{{ $lopHoc->id }}</td>
                <td><a href="{{ route('lophoc.show', $lopHoc) }}">{{ $lopHoc->ten_lop }}</a></td>
                <td>{{ $lopHoc->ma_lop }}</td>
                <td>{{ $lopHoc->giao_vien }}</td>
                <td>{{ $lopHoc->so_dien_thoai_gvcn }}</td>
                <td>{{ $lopHoc->ghi_chu }}</td>
                <td>{{ $lopHoc->si_so }}</td>
                <td>{{ $lopHoc->trang_thai ? 'Hoạt động' : 'Không hoạt động' }}</td>
                <td>
                    <a href="{{ route('lophoc.edit', $lopHoc) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('lophoc.destroy', $lopHoc) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này không?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + 1 }}" class="text-center">Không có lớp học nào phù hợp.</td>
            </tr>
        @endforelse
    </table>

    <div class="mt-3">
        {{ $lopHocs->links() }}
    </div>
@endsection
