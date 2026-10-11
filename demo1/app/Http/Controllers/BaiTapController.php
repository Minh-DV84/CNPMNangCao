<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBaiTapRequest;
use App\Http\Requests\UpdateBaiTapRequest;
use App\Models\BaiTap;
use App\Models\MonHoc;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaiTapController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = Validator::make($request->query(), [
            'keyword' => ['nullable', 'string', 'max:100'],
            'mon_hoc_id' => ['nullable', 'integer', 'exists:mon_hocs,id'],
            'trang_thai' => ['nullable', 'in:con_han,het_han'],
        ])->valid();

        $monHocs = MonHoc::visibleTo($user)->orderBy('ten_mon')->get();
        $visibleMonHocIds = $monHocs->modelKeys();

        $baiTaps = BaiTap::query()
            ->with(['monHoc', 'baiNops' => function ($query) use ($user) {
                if ($user->role === 'sinh_vien') {
                    $query->where('sinh_vien_id', $user->sinh_vien_id);
                } else {
                    $query->whereKey(0);
                }
            }])
            ->whereIn('mon_hoc_id', $visibleMonHocIds)
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $keyword = '%' . $filters['keyword'] . '%';
                $query->where(function ($assignmentQuery) use ($keyword) {
                    $assignmentQuery->where('tieu_de', 'like', $keyword)
                        ->orWhere('noi_dung', 'like', $keyword)
                        ->orWhereHas('monHoc', fn ($subjectQuery) => $subjectQuery->where('ten_mon', 'like', $keyword));
                });
            })
            ->when(isset($filters['mon_hoc_id']), fn ($query) => $query->where('mon_hoc_id', $filters['mon_hoc_id']))
            ->when(($filters['trang_thai'] ?? null) === 'con_han', fn ($query) => $query->where('han_nop', '>=', now()))
            ->when(($filters['trang_thai'] ?? null) === 'het_han', fn ($query) => $query->where('han_nop', '<', now()))
            ->orderBy('han_nop')
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('bai_taps.index', [
            'baiTaps' => $baiTaps,
            'monHocs' => $monHocs,
            'filters' => $filters,
            'isStudent' => $user->role === 'sinh_vien',
            'canManage' => in_array($user->role, ['admin', 'giang_vien'], true),
        ]);
    }

    public function create(Request $request)
    {
        return view('bai_taps.create', [
            'monHocs' => MonHoc::visibleTo($request->user())->orderBy('ten_mon')->get(),
        ]);
    }

    public function store(StoreBaiTapRequest $request)
    {
        $data = $request->validated();
        $this->ensureSubjectIsVisible($request, (int) $data['mon_hoc_id']);
        BaiTap::create($data);

        return redirect()->route('baitap.index')->with('success', 'Bài tập đã được tạo thành công.');
    }

    public function show(Request $request, BaiTap $baitap)
    {
        $this->ensureAssignmentIsVisible($request, $baitap);
        $user = $request->user();
        $baitap->load('monHoc.lopHocs');

        if ($user->role === 'sinh_vien') {
            $mySubmission = $baitap->baiNops()
                ->where('sinh_vien_id', $user->sinh_vien_id)
                ->first();

            return view('bai_taps.show', [
                'baiTap' => $baitap,
                'isStudent' => true,
                'mySubmission' => $mySubmission,
                'baiNops' => collect(),
                'chuaNops' => collect(),
            ]);
        }

        $baiNops = $baitap->baiNops()->with('sinhVien.lopHoc')->latest('nop_luc')->get();
        $lopHocIds = $baitap->monHoc->lopHocs->modelKeys();
        $submittedStudentIds = $baiNops->pluck('sinh_vien_id');
        $chuaNops = SinhVien::query()
            ->with('lopHoc')
            ->whereIn('lop_hoc_id', $lopHocIds)
            ->whereNotIn('id', $submittedStudentIds)
            ->orderBy('ho_ten')
            ->get();

        return view('bai_taps.show', [
            'baiTap' => $baitap,
            'isStudent' => false,
            'mySubmission' => null,
            'baiNops' => $baiNops,
            'chuaNops' => $chuaNops,
        ]);
    }

    public function edit(Request $request, BaiTap $baitap)
    {
        $this->ensureAssignmentIsVisible($request, $baitap);

        return view('bai_taps.edit', [
            'baiTap' => $baitap,
            'monHocs' => MonHoc::visibleTo($request->user())->orderBy('ten_mon')->get(),
        ]);
    }

    public function update(UpdateBaiTapRequest $request, BaiTap $baitap)
    {
        $this->ensureAssignmentIsVisible($request, $baitap);
        $data = $request->validated();
        $this->ensureSubjectIsVisible($request, (int) $data['mon_hoc_id']);
        $baitap->update($data);

        return redirect()->route('baitap.index')->with('success', 'Bài tập đã được cập nhật thành công.');
    }

    public function destroy(Request $request, BaiTap $baitap)
    {
        $this->ensureAssignmentIsVisible($request, $baitap);
        $baitap->delete();

        return back()->with('success', 'Bài tập đã được xóa.');
    }

    private function ensureSubjectIsVisible(Request $request, int $monHocId): void
    {
        abort_unless(MonHoc::visibleTo($request->user())->whereKey($monHocId)->exists(), 403, 'Bạn không có quyền sử dụng môn học này.');
    }

    private function ensureAssignmentIsVisible(Request $request, BaiTap $baitap): void
    {
        $this->ensureSubjectIsVisible($request, (int) $baitap->mon_hoc_id);
    }
}
