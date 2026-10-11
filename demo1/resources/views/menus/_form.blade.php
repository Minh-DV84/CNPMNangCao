{{-- Dùng chung cho create và edit. Khi sửa thì có biến $menu, khi thêm mới thì không. --}}
@php($menu = $menu ?? null)

<div class="mb-3">
    <label for="ten" class="form-label">Tên menu <span class="text-danger">*</span></label>
    <input type="text" id="ten" name="ten"
           class="form-control @error('ten') is-invalid @enderror"
           value="{{ old('ten', $menu?->ten) }}">
    @error('ten')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="url" class="form-label">Đường dẫn <span class="text-danger">*</span></label>
    <input type="text" id="url" name="url"
           class="form-control @error('url') is-invalid @enderror"
           value="{{ old('url', $menu?->url) }}">
    <div class="form-text">Ví dụ: <code>/lophoc</code>, <code>#</code> hoặc <code>https://huce.edu.vn</code>.</div>
    @error('url')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="vi_tri" class="form-label">Vị trí <span class="text-danger">*</span></label>
    <select id="vi_tri" name="vi_tri"
            class="form-select @error('vi_tri') is-invalid @enderror">
        <option value="">-- Chọn vị trí --</option>
        @foreach (\App\Models\Menu::VI_TRI as $giaTri => $nhan)
            <option value="{{ $giaTri }}" @selected(old('vi_tri', $menu?->vi_tri) === $giaTri)>{{ $nhan }}</option>
        @endforeach
    </select>
    @error('vi_tri')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="nhom" class="form-label">Nhóm</label>
    <input type="text" id="nhom" name="nhom"
           class="form-control @error('nhom') is-invalid @enderror"
           value="{{ old('nhom', $menu?->nhom) }}">
    <div class="form-text">Tiêu đề nhóm trong sidebar, ví dụ: Danh mục, Hỗ trợ. Header không dùng nhóm.</div>
    @error('nhom')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="thu_tu" class="form-label">Thứ tự <span class="text-danger">*</span></label>
    <input type="number" id="thu_tu" name="thu_tu"
           class="form-control @error('thu_tu') is-invalid @enderror"
           value="{{ old('thu_tu', $menu?->thu_tu ?? 0) }}">
    <div class="form-text">Số nhỏ hiển thị trước.</div>
    @error('thu_tu')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
    <select id="trang_thai" name="trang_thai"
            class="form-select @error('trang_thai') is-invalid @enderror">
        <option value="1" @selected(old('trang_thai', $menu?->trang_thai ?? 1) == 1)>Hiển thị</option>
        <option value="0" @selected(old('trang_thai', $menu?->trang_thai ?? 1) == 0)>Ẩn</option>
    </select>
    @error('trang_thai')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
