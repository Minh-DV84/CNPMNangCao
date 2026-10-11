@php($baiTap = $baiTap ?? null)

<div class="mb-3">
    <label for="mon_hoc_id" class="form-label">Môn học <span class="text-danger">*</span></label>
    <select id="mon_hoc_id" name="mon_hoc_id" class="form-select @error('mon_hoc_id') is-invalid @enderror">
        <option value="">-- Chọn môn học --</option>
        @foreach ($monHocs as $monHoc)
            <option value="{{ $monHoc->id }}" @selected(old('mon_hoc_id', $baiTap?->mon_hoc_id) == $monHoc->id)>{{ $monHoc->ma_mon }} - {{ $monHoc->ten_mon }}</option>
        @endforeach
    </select>
    @error('mon_hoc_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="tieu_de" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
    <input type="text" id="tieu_de" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror" value="{{ old('tieu_de', $baiTap?->tieu_de) }}">
    @error('tieu_de') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="noi_dung" class="form-label">Nội dung <span class="text-danger">*</span></label>
    <textarea id="noi_dung" name="noi_dung" rows="6" class="form-control @error('noi_dung') is-invalid @enderror">{{ old('noi_dung', $baiTap?->noi_dung) }}</textarea>
    @error('noi_dung') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3">
    <div class="col-md-6 mb-3">
        <label for="han_nop" class="form-label">Hạn nộp <span class="text-danger">*</span></label>
        <input type="datetime-local" id="han_nop" name="han_nop" class="form-control @error('han_nop') is-invalid @enderror" value="{{ old('han_nop', $baiTap?->han_nop?->format('Y-m-d\\TH:i')) }}">
        @error('han_nop') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="diem_toi_da" class="form-label">Điểm tối đa <span class="text-danger">*</span></label>
        <input type="number" id="diem_toi_da" name="diem_toi_da" min="1" max="100" class="form-control @error('diem_toi_da') is-invalid @enderror" value="{{ old('diem_toi_da', $baiTap?->diem_toi_da ?? 10) }}">
        @error('diem_toi_da') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
