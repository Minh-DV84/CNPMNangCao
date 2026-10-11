<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use App\Models\SinhVien;
use App\Models\GiangVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TongQuanController extends Controller
{
    /**
     * Trang Tổng quan - hiển thị theo role
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'admin') {
            // Admin: thấy số lượng sinh viên, lớp học, giảng viên
            $data = [
                'total_sinh_vien' => SinhVien::count(),
                'total_lop_hoc' => LopHoc::count(),
                'total_giang_vien' => GiangVien::count(),
            ];
        } elseif ($user->role === 'giang_vien') {
            // Giảng viên: thấy các lớp mình phụ trách
            $lopHocs = LopHoc::where('giang_vien_id', $user->giang_vien_id)
                ->withCount('sinhViens')
                ->get();
            $data = [
                'lop_hocs' => $lopHocs,
                'total_lop' => $lopHocs->count(),
                'total_sinh_vien' => $lopHocs->sum('sinh_viens_count'),
            ];
        } elseif ($user->role === 'sinh_vien') {
            // Sinh viên: thấy thông tin cá nhân
            $sinhVien = $user->sinhVien;
            $data = [
                'sinh_vien' => $sinhVien,
            ];
        }

        return view('tongquan.index', [
            'user' => $user,
            'data' => $data,
        ]);
    }
}
