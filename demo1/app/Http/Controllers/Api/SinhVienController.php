<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use App\Http\Resources\SinhVienResource;
use App\Traits\ApiResponse;

class SinhVienController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $sinhViens = SinhVien::with('lopHoc')->get();
        return $this->success(SinhVienResource::collection($sinhViens));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'msv' => 'required|string|size:7|regex:/^[01]{5}68$/|unique:sinh_viens,msv',
            'ho_ten' => 'required|string|max:255',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'ngay_sinh' => 'required|date',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'so_dien_thoai' => 'required|string|max:20',
        ]);

        $sinhVien = SinhVien::create($validated);

        return $this->success(new SinhVienResource($sinhVien), 'Sinh viên đã được tạo thành công.', 201);
    }

    public function show(SinhVien $sinhVien)
    {
        $sinhVien->load('lopHoc');
        return $this->success(new SinhVienResource($sinhVien));
    }

    public function update(Request $request, SinhVien $sinhVien)
    {
        $validated = $request->validate([
            'msv' => 'required|string|size:7|regex:/^[01]{5}68$/|unique:sinh_viens,msv,' . $sinhVien->id,
            'ho_ten' => 'required|string|max:255',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'ngay_sinh' => 'required|date',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'so_dien_thoai' => 'required|string|max:20',
        ]);

        $sinhVien->update($validated);

        return $this->success(new SinhVienResource($sinhVien), 'Sinh viên đã được cập nhật thành công.');
    }

    public function destroy(SinhVien $sinhVien)
    {
        $sinhVien->delete();

        return $this->success(null, 'Sinh viên đã được xóa thành công.');
    }
}
