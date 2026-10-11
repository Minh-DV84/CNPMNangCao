<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBaiGiangRequest;
use App\Http\Requests\UpdateBaiGiangRequest;
use App\Models\BaiGiang;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BaiGiangController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = Validator::make($request->query(), [
            'keyword' => ['nullable', 'string', 'max:100'],
            'mon_hoc_id' => ['nullable', 'integer', 'exists:mon_hocs,id'],
        ])->valid();

        $monHocs = MonHoc::visibleTo($user)->orderBy('ten_mon')->get();
        $visibleMonHocIds = $monHocs->modelKeys();

        $baiGiangs = BaiGiang::query()
            ->with('monHoc')
            ->whereIn('mon_hoc_id', $visibleMonHocIds)
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $keyword = '%' . $filters['keyword'] . '%';
                $query->where(function ($materialQuery) use ($keyword) {
                    $materialQuery->where('tieu_de', 'like', $keyword)
                        ->orWhere('mo_ta', 'like', $keyword)
                        ->orWhereHas('monHoc', fn ($subjectQuery) => $subjectQuery->where('ten_mon', 'like', $keyword));
                });
            })
            ->when(isset($filters['mon_hoc_id']), fn ($query) => $query->where('mon_hoc_id', $filters['mon_hoc_id']))
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('bai_giangs.index', [
            'baiGiangs' => $baiGiangs,
            'monHocs' => $monHocs,
            'filters' => $filters,
            'canManage' => in_array($user->role, ['admin', 'giang_vien'], true),
        ]);
    }

    public function create(Request $request)
    {
        return view('bai_giangs.create', [
            'monHocs' => MonHoc::visibleTo($request->user())->orderBy('ten_mon')->get(),
        ]);
    }

    public function store(StoreBaiGiangRequest $request)
    {
        $data = $request->validated();
        $this->ensureSubjectIsVisible($request, (int) $data['mon_hoc_id']);

        $file = $data['file'] ?? null;
        unset($data['file']);
        if ($file) {
            $data['file_path'] = $file->store('baigiang', 'public');
            $data['file_name'] = $file->getClientOriginalName();
        }

        BaiGiang::create($data);

        return redirect()->route('baigiang.index')->with('success', 'Bài giảng đã được tạo thành công.');
    }

    public function show(Request $request, BaiGiang $baigiang)
    {
        $this->ensureMaterialIsVisible($request, $baigiang);

        return view('bai_giangs.show', ['baiGiang' => $baigiang->load('monHoc')]);
    }

    public function download(Request $request, BaiGiang $baigiang)
    {
        $this->ensureMaterialIsVisible($request, $baigiang);
        abort_unless($baigiang->file_path && Storage::disk('public')->exists($baigiang->file_path), 404, 'Tệp đính kèm không tồn tại.');

        return Storage::disk('public')->download(
            $baigiang->file_path,
            $baigiang->file_name ?: basename($baigiang->file_path),
        );
    }

    public function edit(Request $request, BaiGiang $baigiang)
    {
        $this->ensureMaterialIsVisible($request, $baigiang);

        return view('bai_giangs.edit', [
            'baiGiang' => $baigiang,
            'monHocs' => MonHoc::visibleTo($request->user())->orderBy('ten_mon')->get(),
        ]);
    }

    public function update(UpdateBaiGiangRequest $request, BaiGiang $baigiang)
    {
        $this->ensureMaterialIsVisible($request, $baigiang);
        $data = $request->validated();
        $this->ensureSubjectIsVisible($request, (int) $data['mon_hoc_id']);

        $file = $data['file'] ?? null;
        unset($data['file']);
        if ($file) {
            $this->deleteFile($baigiang);
            $data['file_path'] = $file->store('baigiang', 'public');
            $data['file_name'] = $file->getClientOriginalName();
        }

        $baigiang->update($data);

        return redirect()->route('baigiang.index')->with('success', 'Bài giảng đã được cập nhật thành công.');
    }

    public function destroy(Request $request, BaiGiang $baigiang)
    {
        $this->ensureMaterialIsVisible($request, $baigiang);
        $this->deleteFile($baigiang);
        $baigiang->delete();

        return back()->with('success', 'Bài giảng đã được xóa.');
    }

    private function ensureSubjectIsVisible(Request $request, int $monHocId): void
    {
        abort_unless(MonHoc::visibleTo($request->user())->whereKey($monHocId)->exists(), 403, 'Bạn không có quyền sử dụng môn học này.');
    }

    private function ensureMaterialIsVisible(Request $request, BaiGiang $baigiang): void
    {
        $this->ensureSubjectIsVisible($request, (int) $baigiang->mon_hoc_id);
    }

    private function deleteFile(BaiGiang $baigiang): void
    {
        if ($baigiang->file_path) {
            Storage::disk('public')->delete($baigiang->file_path);
        }
    }
}
