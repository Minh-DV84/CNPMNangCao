@extends('layouts.layoutmaster')
@section('title', 'Quản lý người dùng')

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
    <h1>Quản lý người dùng</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Thêm người dùng</a>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('users.index') }}" method="GET" class="filter-box">
        <input type="hidden" name="sort" value="{{ $sort }}">
        <input type="hidden" name="direction" value="{{ $direction }}">

        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="keyword" class="form-label">Từ khóa</label>
                <input type="text" id="keyword" name="keyword" class="form-control"
                       placeholder="Tên, email..." value="{{ $filters['keyword'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label">Vai trò</label>
                <select id="role" name="role" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($roleOptions as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['role'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
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
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </div>
    </form>

    <p>Tìm thấy <strong>{{ $users->total() }}</strong> người dùng.</p>

    @php
        $columns = [
            'id' => 'ID',
            'name' => 'Tên',
            'email' => 'Email',
            'role' => 'Vai trò',
            'created_at' => 'Ngày tạo',
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
            <th>Thông tin liên kết</th>
            <th>Thao tác</th>
        </tr>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email ?? '-' }}</td>
                <td>{{ $user->role_label }}</td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                <td>
                    @if ($user->sinhVien)
                        <span class="badge bg-info">SV: {{ $user->sinhVien->msv }}</span>
                    @elseif ($user->giangVien)
                        <span class="badge bg-success">GV: {{ $user->giangVien->ma_gv }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">Xem</a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Sửa</a>
                    @if (auth()->id() !== $user->id)
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">Xóa</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + 2 }}" class="text-center">Không có người dùng nào phù hợp.</td>
            </tr>
        @endforelse
    </table>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
@endsection
