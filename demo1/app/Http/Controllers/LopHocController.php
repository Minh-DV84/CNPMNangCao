<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLopHocRequest;
use App\Http\Requests\UpdateLopHocRequest;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LopHocController extends Controller
{
    // Các cột được phép sắp xếp (không lấy thẳng tên cột từ URL để tránh SQL injection)
    private const SORTABLE = ['id', 'ten_lop', 'ma_lop', 'giao_vien', 'si_so', 'trang_thai'];

    private const PER_PAGE_OPTIONS = [10, 20, 50, 100];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Tham số lọc/sắp xếp nằm trên URL nên người dùng sửa tuỳ ý được:
        // valid() chỉ giữ lại những tham số hợp lệ, cái nào sai thì bỏ qua.
        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'trang_thai' => 'nullable|in:0,1',
            'si_so_min' => 'nullable|integer|min:0',
            'si_so_max' => 'nullable|integer|min:0',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'perPage' => 'nullable|in:' . implode(',', self::PER_PAGE_OPTIONS),
        ])->valid();

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';
        $perPage = (int) ($filters['perPage'] ?? 10);

        $lopHocs = LopHoc::query()
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $keyword = '%' . $filters['keyword'] . '%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('ten_lop', 'like', $keyword)
                        ->orWhere('ma_lop', 'like', $keyword)
                        ->orWhere('giao_vien', 'like', $keyword);
                });
            })
            ->when(isset($filters['trang_thai']), fn ($query) => $query->where('trang_thai', $filters['trang_thai']))
            ->when(isset($filters['si_so_min']), fn ($query) => $query->where('si_so', '>=', $filters['si_so_min']))
            ->when(isset($filters['si_so_max']), fn ($query) => $query->where('si_so', '<=', $filters['si_so_max']))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('lop_hocs.index', [
            'lopHocs' => $lopHocs,
            'filters' => $filters,
            'sort' => $sort,
            'direction' => $direction,
            'perPage' => $perPage,
            'sortable' => self::SORTABLE,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lop_hocs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLopHocRequest $request)
    {
        // Vào được đến đây nghĩa là dữ liệu đã hợp lệ.
        // validated() chỉ trả về các trường có khai báo rule.
        LopHoc::create($request->validated());

        return redirect()->route('lophoc.index')->with('success', 'Lớp học đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LopHoc $lophoc)
    {
        return view('lop_hocs.show', ['lopHoc' => $lophoc]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LopHoc $lophoc)
    {
        return view('lop_hocs.edit', ['lopHoc' => $lophoc]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLopHocRequest $request, LopHoc $lophoc)
    {
        $lophoc->update($request->validated());

        return redirect()->route('lophoc.index')->with('success', 'Lớp học đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LopHoc $lophoc)
    {
        $lophoc->delete();

        // back() để giữ nguyên bộ lọc/sắp xếp đang xem
        return back()->with('success', 'Lớp học đã được xóa.');
    }
}
