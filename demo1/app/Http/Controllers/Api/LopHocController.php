<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use App\Http\Resources\LopHocResource;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class LopHocController extends Controller
{
    use ApiResponse;

    private const SORTABLE = ['id', 'ten_lop', 'ma_lop', 'giao_vien', 'si_so', 'trang_thai'];

    public function index(Request $request)
    {
        $currentUser = $request->user();

        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'trang_thai' => 'nullable|in:0,1',
            'si_so_min' => 'nullable|integer|min:0',
            'si_so_max' => 'nullable|integer|min:0',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ])->valid();

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';
        $perPage = (int) ($filters['per_page'] ?? 15);

        $query = LopHoc::with('giangVien');

        if ($currentUser->role === 'giang_vien') {
            $query->where('giang_vien_id', $currentUser->giang_vien_id);
        }

        $query->when(isset($filters['keyword']), function ($q) use ($filters) {
            $keyword = '%' . $filters['keyword'] . '%';
            $q->where(function ($subQ) use ($keyword) {
                $subQ->where('ten_lop', 'like', $keyword)
                    ->orWhere('ma_lop', 'like', $keyword)
                    ->orWhere('giao_vien', 'like', $keyword);
            });
        })
        ->when(isset($filters['trang_thai']), fn ($q) => $q->where('trang_thai', $filters['trang_thai']))
        ->when(isset($filters['si_so_min']), fn ($q) => $q->where('si_so', '>=', $filters['si_so_min']))
        ->when(isset($filters['si_so_max']), fn ($q) => $q->where('si_so', '<=', $filters['si_so_max']))
        ->orderBy($sort, $direction);

        $lopHocs = $query->paginate($perPage);

        return $this->success([
            'data' => LopHocResource::collection($lopHocs),
            'pagination' => [
                'total' => $lopHocs->total(),
                'per_page' => $lopHocs->perPage(),
                'current_page' => $lopHocs->currentPage(),
                'last_page' => $lopHocs->lastPage(),
                'from' => $lopHocs->firstItem(),
                'to' => $lopHocs->lastItem(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();

        $validated = $request->validate([
            'ten_lop' => 'required|string|max:255',
            'ma_lop' => 'required|string|max:6|unique:lop_hocs,ma_lop',
            'giao_vien' => 'required|string|max:255',
            'so_dien_thoai_gvcn' => 'nullable|string|max:20',
            'ghi_chu' => 'nullable|string|max:500',
            'si_so' => 'required|integer|min:1',
            'trang_thai' => 'required|boolean',
            'giang_vien_id' => 'nullable|exists:giang_viens,id',
        ]);

        if ($currentUser->role === 'giang_vien') {
            $validated['giang_vien_id'] = $currentUser->giang_vien_id;
        }

        $lopHoc = LopHoc::create($validated);

        return $this->success(new LopHocResource($lopHoc), 'Lớp học đã được tạo thành công.', 201);
    }

    public function show(Request $request, LopHoc $lopHoc)
    {
        $currentUser = $request->user();

        if ($currentUser->role === 'giang_vien' && $lopHoc->giang_vien_id !== $currentUser->giang_vien_id) {
            return $this->error('Bạn không có quyền xem lớp học này.', 403);
        }

        $lopHoc->load('giangVien');
        return $this->success(new LopHocResource($lopHoc));
    }

    public function update(Request $request, LopHoc $lopHoc)
    {
        $currentUser = $request->user();

        if ($currentUser->role === 'giang_vien' && $lopHoc->giang_vien_id !== $currentUser->giang_vien_id) {
            return $this->error('Bạn không có quyền sửa lớp học này.', 403);
        }

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

    public function destroy(Request $request, LopHoc $lopHoc)
    {
        $currentUser = $request->user();

        if ($currentUser->role === 'giang_vien' && $lopHoc->giang_vien_id !== $currentUser->giang_vien_id) {
            return $this->error('Bạn không có quyền xóa lớp học này.', 403);
        }

        $lopHoc->delete();

        return $this->success(null, 'Lớp học đã được xóa thành công.');
    }
}
