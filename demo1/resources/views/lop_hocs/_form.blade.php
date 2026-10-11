{{-- Dùng chung cho create và edit. Khi sửa thì có biến $lopHoc, khi thêm mới thì không. --}}
@php($lopHoc = $lopHoc ?? null)

<div class="mb-3">
    <label for="ten_lop" class="form-label">Tên lớp <span class="text-danger">*</span></label>
    <input type="text" id="ten_lop" name="ten_lop"
           class="form-control @error('ten_lop') is-invalid @enderror"
           value="{{ old('ten_lop', $lopHoc?->ten_lop) }}">
    @error('ten_lop')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ma_lop" class="form-label">Mã lớp <span class="text-danger">*</span></label>
    <input type="text" id="ma_lop" name="ma_lop"
           class="form-control @error('ma_lop') is-invalid @enderror"
           value="{{ old('ma_lop', $lopHoc?->ma_lop) }}">
    <div class="form-text">Tối đa 6 ký tự, không trùng với lớp khác.</div>
    @error('ma_lop')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="giao_vien" class="form-label">Giáo viên <span class="text-danger">*</span></label>
    <input type="text" id="giao_vien" name="giao_vien"
           class="form-control @error('giao_vien') is-invalid @enderror"
           value="{{ old('giao_vien', $lopHoc?->giao_vien) }}">
    @error('giao_vien')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="so_dien_thoai_gvcn" class="form-label">Số điện thoại GVCN</label>
    <input type="text" id="so_dien_thoai_gvcn" name="so_dien_thoai_gvcn"
           class="form-control @error('so_dien_thoai_gvcn') is-invalid @enderror"
           value="{{ old('so_dien_thoai_gvcn', $lopHoc?->so_dien_thoai_gvcn) }}">
    @error('so_dien_thoai_gvcn')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ghi_chu" class="form-label">Ghi chú</label>
    <textarea id="ghi_chu" name="ghi_chu" rows="3"
              class="form-control @error('ghi_chu') is-invalid @enderror">{{ old('ghi_chu', $lopHoc?->ghi_chu) }}</textarea>
    @error('ghi_chu')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="si_so" class="form-label">Sĩ số <span class="text-danger">*</span></label>
    <input type="number" id="si_so" name="si_so"
           class="form-control @error('si_so') is-invalid @enderror"
           value="{{ old('si_so', $lopHoc?->si_so) }}">
    @error('si_so')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
    <select id="trang_thai" name="trang_thai"
            class="form-select @error('trang_thai') is-invalid @enderror">
        <option value="1" @selected(old('trang_thai', $lopHoc?->trang_thai ?? 1) == 1)>Hoạt động</option>
        <option value="0" @selected(old('trang_thai', $lopHoc?->trang_thai ?? 1) == 0)>Không hoạt động</option>
    </select>
    @error('trang_thai')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
