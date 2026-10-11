@extends('layouts.layoutmaster')
@section('title', 'Danh sách sinh viên')

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
    <h1>Danh sách sinh viên</h1>
    <a href="{{ route('sinhvien.create') }}" class="btn btn-primary mb-3">Thêm sinh viên</a>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('sinhvien.index') }}" method="GET" class="filter-box">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="keyword" class="form-label">Từ khóa</label>
                <input type="text" id="keyword" name="keyword" class="form-control"
                       placeholder="MSV, họ tên, số điện thoại..." value="{{ $filters['keyword'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="lop_hoc_id" class="form-label">Lớp</label>
                <select id="lop_hoc_id" name="lop_hoc_id" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($lopHocs as $lop)
                        <option value="{{ $lop->id }}" @selected(($filters['lop_hoc_id'] ?? '') == $lop->id)>{{ $lop->ten_lop }} ({{ $lop->ma_lop }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="gioi_tinh" class="form-label">Giới tính</label>
                <select id="gioi_tinh" name="gioi_tinh" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="Nam" @selected(($filters['gioi_tinh'] ?? '') === 'Nam')">Nam</option>
                    <option value="Nữ" @selected(($filters['gioi_tinh'] ?? '') === 'Nữ')">Nữ</option>
                </select>
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
                <a href="{{ route('sinhvien.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </div>
    </form>

    <p>Tìm thấy <strong>{{ $sinhViens->total() }}</strong> sinh viên.</p>

    @php
        $columns = [
            'id' => 'ID',
            'msv' => 'MSV',
            'ho_ten' => 'Họ và tên',
            'gioi_tinh' => 'Giới tính',
            'ngay_sinh' => 'Ngày sinh',
            'lop_hoc_id' => 'Lớp',
            'so_dien_thoai' => 'Số điện thoại',
        ];
    @endphp

    <table>
        <tr>
            @foreach ($columns as $column => $label)
                <th>
                    @if (in_array($column, $sortable))
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
        @forelse ($sinhViens as $sinhVien)
            <tr>
                <td>{{ $sinhVien->id }}</td>
                <td>{{ $sinhVien->msv }}</td>
                <td>{{ $sinhVien->ho_ten }}</td>
                <td>{{ $sinhVien->gioi_tinh }}</td>
                <td>{{ \Carbon\Carbon::parse($sinhVien->ngay_sinh)->format('d/m/Y') }}</td>
                <td>{{ $sinhVien->lopHoc ? $sinhVien->lopHoc->ten_lop : '-' }}</td>
                <td>{{ $sinhVien->so_dien_thoai }}</td>
                <td>
                    <a href="{{ route('sinhvien.edit', $sinhVien) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('sinhvien.destroy', $sinhVien) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này không?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + 1 }}" class="text-center">Không có sinh viên nào phù hợp.</td>
            </tr>
        @endforelse
    </table>

    <div class="mt-3">
        {{ $sinhViens->links() }}
    </div>
@endsection