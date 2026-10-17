<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SinhVien;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use App\Http\Resources\SinhVienResource;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class SinhVienController extends Controller
{
    use ApiResponse;

    private const SORTABLE = ['id', 'msv', 'ho_ten', 'gioi_tinh', 'ngay_sinh', 'lop_hoc_id'];

    public function index(Request $request)
    {
        $currentUser = $request->user();

        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'gioi_tinh' => 'nullable|in:Nam,Nữ',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ])->valid();

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';
        $perPage = (int) ($filters['per_page'] ?? 15);

        $query = SinhVien::with('lopHoc');

        if ($currentUser->role === 'giang_vien') {
            $query->whereHas('lopHoc', function ($lopHocQuery) use ($currentUser) {
                $lopHocQuery->where('giang_vien_id', $currentUser->giang_vien_id);
            });
        }

        $query->when(isset($filters['keyword']), function ($q) use ($filters) {
            $keyword = '%' . $filters['keyword'] . '%';
            $q->where(function ($subQ) use ($keyword) {
                $subQ->where('msv', 'like', $keyword)
                    ->orWhere('ho_ten', 'like', $keyword)
                    ->orWhere('so_dien_thoai', 'like', $keyword);
            });
        })
        ->when(isset($filters['lop_hoc_id']), fn ($q) => $q->where('lop_hoc_id', $filters['lop_hoc_id']))
        ->when(isset($filters['gioi_tinh']), fn ($q) => $q->where('gioi_tinh', $filters['gioi_tinh']))
        ->orderBy($sort, $direction);

        $sinhViens = $query->paginate($perPage);

        return $this->success([
            'data' => SinhVienResource::collection($sinhViens),
            'pagination' => [
                'total' => $sinhViens->total(),
                'per_page' => $sinhViens->perPage(),
                'current_page' => $sinhViens->currentPage(),
                'last_page' => $sinhViens->lastPage(),
                'from' => $sinhViens->firstItem(),
                'to' => $sinhViens->lastItem(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();

        $validated = $request->validate([
            'msv' => 'required|string|size:7|regex:/^[01]{5}68$/|unique:sinh_viens,msv',
            'ho_ten' => 'required|string|max:255',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'ngay_sinh' => 'required|date',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'so_dien_thoai' => 'required|string|max:20',
        ]);

        if ($currentUser->role === 'giang_vien' && $validated['lop_hoc_id']) {
            $lopHoc = LopHoc::find($validated['lop_hoc_id']);
            if (!$lopHoc || $lopHoc->giang_vien_id !== $currentUser->giang_vien_id) {
                return $this->error('Bạn không có quyền thêm sinh viên vào lớp này.', 403);
            }
        }

        $sinhVien = SinhVien::create($validated);

        return $this->success(new SinhVienResource($sinhVien), 'Sinh viên đã được tạo thành công.', 201);
    }

    public function show(Request $request, SinhVien $sinhVien)
    {
        $currentUser = $request->user();

        if ($currentUser->role === 'giang_vien' && $sinhVien->lopHoc?->giang_vien_id !== $currentUser->giang_vien_id) {
            return $this->error('Bạn không có quyền xem sinh viên này.', 403);
        }

        $sinhVien->load('lopHoc');
        return $this->success(new SinhVienResource($sinhVien));
    }

    public function update(Request $request, SinhVien $sinhVien)
    {
        $currentUser = $request->user();

        if ($currentUser->role === 'giang_vien' && $sinhVien->lopHoc?->giang_vien_id !== $currentUser->giang_vien_id) {
            return $this->error('Bạn không có quyền sửa sinh viên này.', 403);
        }

        $validated = $request->validate([
            'msv' => 'required|string|size:7|regex:/^[01]{5}68$/|unique:sinh_viens,msv,' . $sinhVien->id,
            'ho_ten' => 'required|string|max:255',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'ngay_sinh' => 'required|date',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'so_dien_thoai' => 'required|string|max:20',
        ]);

        if ($currentUser->role === 'giang_vien' && $validated['lop_hoc_id']) {
            $lopHoc = LopHoc::find($validated['lop_hoc_id']);
            if (!$lopHoc || $lopHoc->giang_vien_id !== $currentUser->giang_vien_id) {
                return $this->error('Bạn không có quyền chuyển sinh viên vào lớp này.', 403);
            }
        }

        $sinhVien->update($validated);

        return $this->success(new SinhVienResource($sinhVien), 'Sinh viên đã được cập nhật thành công.');
    }

    public function destroy(Request $request, SinhVien $sinhVien)
    {
        $currentUser = $request->user();

        if ($currentUser->role === 'giang_vien' && $sinhVien->lopHoc?->giang_vien_id !== $currentUser->giang_vien_id) {
            return $this->error('Bạn không có quyền xóa sinh viên này.', 403);
        }

        $sinhVien->delete();

        return $this->success(null, 'Sinh viên đã được xóa thành công.');
    }
}
