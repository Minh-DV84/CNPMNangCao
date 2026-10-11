<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSinhVienRequest;
use App\Http\Requests\UpdateSinhVienRequest;
use App\Models\SinhVien;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SinhVienController extends Controller
{
    private const SORTABLE = ['id', 'msv', 'ho_ten', 'gioi_tinh', 'ngay_sinh', 'lop_hoc_id', 'so_dien_thoai'];

    private const PER_PAGE_OPTIONS = [10, 20, 50];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'lop_hoc_id' => 'nullable|exists:lop_hocs,id',
            'gioi_tinh' => 'nullable|in:Nam,Nữ',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'perPage' => 'nullable|in:' . implode(',', self::PER_PAGE_OPTIONS),
        ])->valid();

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';
        $perPage = (int) ($filters['perPage'] ?? 10);

        $sinhViensQuery = SinhVien::query()
            ->with('lopHoc')
            ->when($user->role === 'giang_vien', function ($query) use ($user) {
                $query->whereHas('lopHoc', function ($lopHocQuery) use ($user) {
                    $lopHocQuery->where('giang_vien_id', $user->giang_vien_id);
                });
            })
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $keyword = '%' . $filters['keyword'] . '%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('msv', 'like', $keyword)
                        ->orWhere('ho_ten', 'like', $keyword)
                        ->orWhere('so_dien_thoai', 'like', $keyword);
                });
            })
            ->when(isset($filters['lop_hoc_id']), fn ($query) => $query->where('lop_hoc_id', $filters['lop_hoc_id']))
            ->when(isset($filters['gioi_tinh']), fn ($query) => $query->where('gioi_tinh', $filters['gioi_tinh']))
            ->orderBy($sort, $direction);

        $lopHocsQuery = LopHoc::query();
        if ($user->role === 'giang_vien') {
            $lopHocsQuery->where('giang_vien_id', $user->giang_vien_id);
        }

        $lopHocs = $lopHocsQuery->orderBy('ten_lop')->get();
        $sinhViens = $sinhViensQuery->paginate($perPage)->withQueryString();

        return view('sinhvien.index', [
            'sinhViens' => $sinhViens,
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
        $lopHocs = LopHoc::orderBy('ten_lop')->get();
        return view('sinhvien.create', ['lopHocs' => $lopHocs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSinhVienRequest $request)
    {
        SinhVien::create($request->validated());

        return redirect()->route('sinhvien.index')->with('success', 'Sinh viên đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SinhVien $sinhvien)
    {
        $user = request()->user();
        if ($user->role === 'giang_vien'
            && $sinhvien->lopHoc?->giang_vien_id !== $user->giang_vien_id) {
            abort(403, 'Bạn không có quyền xem sinh viên của lớp khác.');
        }

        return view('sinhvien.show', ['sinhVien' => $sinhvien->load('lopHoc')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SinhVien $sinhvien)
    {
        $lopHocs = LopHoc::orderBy('ten_lop')->get();
        return view('sinhvien.edit', ['sinhVien' => $sinhvien, 'lopHocs' => $lopHocs]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSinhVienRequest $request, SinhVien $sinhvien)
    {
        $sinhvien->update($request->validated());

        return redirect()->route('sinhvien.index')->with('success', 'Sinh viên đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SinhVien $sinhvien)
    {
        $sinhvien->delete();

        return back()->with('success', 'Sinh viên đã được xóa.');
    }
}
