@extends('layouts.layoutmaster')
@section('title', 'Sửa người dùng')

@section('content')
    <h1>Sửa người dùng: {{ $user->name }}</h1>
    <a href="{{ route('users.index') }}" class="btn btn-secondary mb-3">Quay lại</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
            <small class="text-muted">Email chỉ bắt buộc với admin</small>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu mới</label>
            <input type="password" id="password" name="password" class="form-control" minlength="6">
            <small class="text-muted">Để trống nếu không muốn đổi mật khẩu</small>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
            <select id="role" name="role" class="form-select" required onchange="toggleRoleFields()">
                <option value="">-- Chọn vai trò --</option>
                @foreach ($roleOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3" id="sinh_vien_field" style="display: none;">
            <label for="sinh_vien_id" class="form-label">Sinh viên</label>
            <select id="sinh_vien_id" name="sinh_vien_id" class="form-select">
                <option value="">-- Chọn sinh viên --</option>
                @foreach ($sinhViens as $sv)
                    <option value="{{ $sv->id }}" @selected(old('sinh_vien_id', $user->sinh_vien_id) == $sv->id)>
                        {{ $sv->msv }} - {{ $sv->ho_ten }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3" id="giang_vien_field" style="display: none;">
            <label for="giang_vien_id" class="form-label">Giảng viên</label>
            <select id="giang_vien_id" name="giang_vien_id" class="form-select">
                <option value="">-- Chọn giảng viên --</option>
                @foreach ($giangViens as $gv)
                    <option value="{{ $gv->id }}" @selected(old('giang_vien_id', $user->giang_vien_id) == $gv->id)>
                        {{ $gv->ma_gv }} - {{ $gv->ho_ten }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>
    </form>

    <script>
        function toggleRoleFields() {
            const role = document.getElementById('role').value;
            document.getElementById('sinh_vien_field').style.display = role === 'sinh_vien' ? 'block' : 'none';
            document.getElementById('giang_vien_field').style.display = role === 'giang_vien' ? 'block' : 'none';

            if (role === 'sinh_vien') {
                document.getElementById('sinh_vien_id').required = true;
                document.getElementById('giang_vien_id').required = false;
            } else if (role === 'giang_vien') {
                document.getElementById('sinh_vien_id').required = false;
                document.getElementById('giang_vien_id').required = true;
            } else {
                document.getElementById('sinh_vien_id').required = false;
                document.getElementById('giang_vien_id').required = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoleFields();
        });
    </script>
@endsection
