@extends('layouts.layoutmaster')
@section('title', 'Quản lý menu')

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
    <h1>Quản lý menu</h1>
    <a href="{{ route('menu.create') }}" class="btn btn-primary mb-3">Thêm menu</a>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('menu.index') }}" method="GET" class="filter-box">
        {{-- Giữ nguyên cột đang sắp xếp khi bấm Lọc --}}
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="keyword" class="form-label">Từ khóa</label>
                <input type="text" id="keyword" name="keyword" class="form-control"
                       placeholder="Tên menu, đường dẫn, nhóm..." value="{{ $filters['keyword'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="vi_tri" class="form-label">Vị trí</label>
                <select id="vi_tri" name="vi_tri" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach (\App\Models\Menu::VI_TRI as $giaTri => $nhan)
                        <option value="{{ $giaTri }}" @selected(($filters['vi_tri'] ?? '') === $giaTri)>{{ $nhan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select id="trang_thai" name="trang_thai" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" @selected(($filters['trang_thai'] ?? '') === '1')>Hiển thị</option>
                    <option value="0" @selected(($filters['trang_thai'] ?? '') === '0')>Ẩn</option>
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
                <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </div>
    </form>

    <p>Tìm thấy <strong>{{ $menus->total() }}</strong> menu.</p>

    @php
        $columns = [
            'id' => 'ID',
            'ten' => 'Tên menu',
            'url' => 'Đường dẫn',
            'vi_tri' => 'Vị trí',
            'nhom' => 'Nhóm',
            'thu_tu' => 'Thứ tự',
            'trang_thai' => 'Trạng thái',
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
        @forelse ($menus as $menu)
            <tr>
                <td>{{ $menu->id }}</td>
                <td>{{ $menu->ten }}</td>
                <td><code>{{ $menu->url }}</code></td>
                <td>{{ \App\Models\Menu::VI_TRI[$menu->vi_tri] ?? $menu->vi_tri }}</td>
                <td>{{ $menu->nhom }}</td>
                <td>{{ $menu->thu_tu }}</td>
                <td>{{ $menu->trang_thai ? 'Hiển thị' : 'Ẩn' }}</td>
                <td>
                    <a href="{{ route('menu.edit', $menu) }}" class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('menu.destroy', $menu) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa menu này không?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + 1 }}" class="text-center">Không có menu nào phù hợp.</td>
            </tr>
        @endforelse
    </table>

    <div class="mt-3">
        {{ $menus->links() }}
    </div>
@endsection
