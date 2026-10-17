<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use App\Http\Resources\LopHocResource;
use App\Traits\ApiResponse;

class LopHocController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $lopHocs = LopHoc::with('giangVien')->get();
        return $this->success(LopHocResource::collection($lopHocs));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:6|unique:lop_hocs,ma_lop',
            'giao_vien' => 'required|string|max:255',
            'so_dien_thoai_gvcn' => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string|max:500',
            'si_so' => 'required|integer|min:1',
            'trang_thai' => 'required|boolean',
        ]);

        $lopHoc = LopHoc::create($validated);

        return $this->success(new LopHocResource($lopHoc), 'Lớp học đã được tạo thành công.', 201);
    }

    public function show(LopHoc $lopHoc)
    {
        $lopHoc->load('giangVien');
        return $this->success(new LopHocResource($lopHoc));
    }

    public function update(Request $request, LopHoc $lopHoc)
    {
        $validated = $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:6|unique:lop_hocs,ma_lop,' . $lopHoc->id,
            'giao_vien' => 'required|string|max:255',
            'so_dien_thoai_gvcn' => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string|max:500',
            'si_so' => 'required|integer|min:1',
            'trang_thai' => 'required|boolean',
        ]);

        $lopHoc->update($validated);

        return $this->success(new LopHocResource($lopHoc), 'Lớp học đã được cập nhật thành công.');
    }

    public function destroy(LopHoc $lopHoc)
    {
        $lopHoc->delete();

        return $this->success(null, 'Lớp học đã được xóa thành công.');
    }
}
