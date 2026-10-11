<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaiLieuRequest;
use App\Http\Requests\UpdateTaiLieuRequest;
use App\Models\MonHoc;
use App\Models\TaiLieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaiLieuController extends Controller
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

        $taiLieus = TaiLieu::query()
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

        return view('tai_lieus.index', [
            'taiLieus' => $taiLieus,
            'monHocs' => $monHocs,
            'filters' => $filters,
            'canManage' => in_array($user->role, ['admin', 'giang_vien'], true),
        ]);
    }

    public function create(Request $request)
    {
        return view('tai_lieus.create', [
            'monHocs' => MonHoc::visibleTo($request->user())->orderBy('ten_mon')->get(),
        ]);
    }

    public function store(StoreTaiLieuRequest $request)
    {
        $data = $request->validated();
        $this->ensureSubjectIsVisible($request, (int) $data['mon_hoc_id']);

        $file = $data['file'] ?? null;
        unset($data['file']);
        if ($file) {
            $data['file_path'] = $file->store('tailieu', 'public');
            $data['file_name'] = $file->getClientOriginalName();
        }

        TaiLieu::create($data);

        return redirect()->route('tailieu.index')->with('success', 'Tài liệu đã được tạo thành công.');
    }

    public function show(Request $request, TaiLieu $tailieu)
    {
        $this->ensureMaterialIsVisible($request, $tailieu);

        return view('tai_lieus.show', ['taiLieu' => $tailieu->load('monHoc')]);
    }

    public function download(Request $request, TaiLieu $tailieu)
    {
        $this->ensureMaterialIsVisible($request, $tailieu);
        abort_unless($tailieu->file_path && Storage::disk('public')->exists($tailieu->file_path), 404, 'Tệp đính kèm không tồn tại.');

        return Storage::disk('public')->download(
            $tailieu->file_path,
            $tailieu->file_name ?: basename($tailieu->file_path),
        );
    }

    public function edit(Request $request, TaiLieu $tailieu)
    {
        $this->ensureMaterialIsVisible($request, $tailieu);

        return view('tai_lieus.edit', [
            'taiLieu' => $tailieu,
            'monHocs' => MonHoc::visibleTo($request->user())->orderBy('ten_mon')->get(),
        ]);
    }

    public function update(UpdateTaiLieuRequest $request, TaiLieu $tailieu)
    {
        $this->ensureMaterialIsVisible($request, $tailieu);
        $data = $request->validated();
        $this->ensureSubjectIsVisible($request, (int) $data['mon_hoc_id']);

        $file = $data['file'] ?? null;
        unset($data['file']);
        if ($file) {
            $this->deleteFile($tailieu);
            $data['file_path'] = $file->store('tailieu', 'public');
            $data['file_name'] = $file->getClientOriginalName();
        }

        $tailieu->update($data);

        return redirect()->route('tailieu.index')->with('success', 'Tài liệu đã được cập nhật thành công.');
    }

    public function destroy(Request $request, TaiLieu $tailieu)
    {
        $this->ensureMaterialIsVisible($request, $tailieu);
        $this->deleteFile($tailieu);
        $tailieu->delete();

        return back()->with('success', 'Tài liệu đã được xóa.');
    }

    private function ensureSubjectIsVisible(Request $request, int $monHocId): void
    {
        abort_unless(MonHoc::visibleTo($request->user())->whereKey($monHocId)->exists(), 403, 'Bạn không có quyền sử dụng môn học này.');
    }

    private function ensureMaterialIsVisible(Request $request, TaiLieu $tailieu): void
    {
        $this->ensureSubjectIsVisible($request, (int) $tailieu->mon_hoc_id);
    }

    private function deleteFile(TaiLieu $tailieu): void
    {
        if ($tailieu->file_path) {
            Storage::disk('public')->delete($tailieu->file_path);
        }
    }
}
