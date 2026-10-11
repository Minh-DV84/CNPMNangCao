{{-- Dùng chung cho create và edit. Khi sửa thì có biến $sinhVien, khi thêm mới thì không. --}}
@php($sinhVien = $sinhVien ?? null)

<div class="mb-3">
    <label for="msv" class="form-label">Mã sinh viên <span class="text-danger">*</span></label>
    <input type="text" id="msv" name="msv"
           class="form-control @error('msv') is-invalid @enderror"
           value="{{ old('msv', $sinhVien?->msv) }}">
    <div class="form-text">7 chữ số: 5 chữ số đầu chỉ gồm 0 hoặc 1, 2 chữ số cuối luôn là 68. Ví dụ: 1011068, 0110168.</div>
    @error('msv')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
    <input type="text" id="ho_ten" name="ho_ten"
           class="form-control @error('ho_ten') is-invalid @enderror"
           value="{{ old('ho_ten', $sinhVien?->ho_ten) }}">
    @error('ho_ten')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="gioi_tinh" class="form-label">Giới tính <span class="text-danger">*</span></label>
    <select id="gioi_tinh" name="gioi_tinh"
            class="form-select @error('gioi_tinh') is-invalid @enderror">
        <option value="">-- Chọn giới tính --</option>
        <option value="Nam" @selected(old('gioi_tinh', $sinhVien?->gioi_tinh) === 'Nam')">Nam</option>
        <option value="Nữ" @selected(old('gioi_tinh', $sinhVien?->gioi_tinh) === 'Nữ')">Nữ</option>
    </select>
    @error('gioi_tinh')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ngay_sinh" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
    <input type="text" id="ngay_sinh" name="ngay_sinh"
           class="form-control @error('ngay_sinh') is-invalid @enderror"
           placeholder="dd/mm/yyyy"
           value="{{ old('ngay_sinh', $sinhVien ? \Carbon\Carbon::parse($sinhVien->ngay_sinh)->format('d/m/Y') : '') }}">
    <div class="form-text">Định dạng: dd/mm/yyyy, phải trong năm 2005.</div>
    @error('ngay_sinh')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="lop_hoc_id" class="form-label">Lớp học</label>
    <select id="lop_hoc_id" name="lop_hoc_id"
            class="form-select @error('lop_hoc_id') is-invalid @enderror">
        <option value="">-- Chọn lớp học --</option>
        @foreach ($lopHocs as $lop)
            <option value="{{ $lop->id }}" @selected(old('lop_hoc_id', $sinhVien?->lop_hoc_id) == $lop->id)>{{ $lop->ten_lop }} ({{ $lop->ma_lop }})</option>
        @endforeach
    </select>
    @error('lop_hoc_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="so_dien_thoai" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
    <input type="text" id="so_dien_thoai" name="so_dien_thoai"
           class="form-control @error('so_dien_thoai') is-invalid @enderror"
           value="{{ old('so_dien_thoai', $sinhVien?->so_dien_thoai) }}">
    @error('so_dien_thoai')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
