@php($taiLieu = $taiLieu ?? null)

<div class="mb-3">
    <label for="mon_hoc_id" class="form-label">Môn học <span class="text-danger">*</span></label>
    <select id="mon_hoc_id" name="mon_hoc_id" class="form-select @error('mon_hoc_id') is-invalid @enderror">
        <option value="">-- Chọn môn học --</option>
        @foreach ($monHocs as $monHoc)
            <option value="{{ $monHoc->id }}" @selected(old('mon_hoc_id', $taiLieu?->mon_hoc_id) == $monHoc->id)>{{ $monHoc->ma_mon }} - {{ $monHoc->ten_mon }}</option>
        @endforeach
    </select>
    @error('mon_hoc_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="tieu_de" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
    <input type="text" id="tieu_de" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror" value="{{ old('tieu_de', $taiLieu?->tieu_de) }}">
    @error('tieu_de') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="mo_ta" class="form-label">Mô tả</label>
    <textarea id="mo_ta" name="mo_ta" rows="4" class="form-control @error('mo_ta') is-invalid @enderror">{{ old('mo_ta', $taiLieu?->mo_ta) }}</textarea>
    @error('mo_ta') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="file" class="form-label">Tệp đính kèm</label>
    <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip">
    @if ($taiLieu?->file_name)
        <div class="form-text">Tệp hiện tại: {{ $taiLieu->file_name }}. Không chọn tệp mới để giữ tệp cũ.</div>
    @endif
    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
