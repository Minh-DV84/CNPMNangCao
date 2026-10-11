<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeBaiNopRequest;
use App\Http\Requests\SubmitBaiTapRequest;
use App\Models\BaiNop;
use App\Models\BaiTap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BaiNopController extends Controller
{
    public function store(SubmitBaiTapRequest $request, BaiTap $baitap)
    {
        $user = $request->user();
        $this->ensureStudentCanAccess($request, $baitap);

        if (now()->isAfter($baitap->han_nop)) {
            throw ValidationException::withMessages([
                'file' => 'Đã quá hạn nộp bài, bạn không thể nộp hoặc nộp lại bài này.',
            ]);
        }

        $data = $request->validated();
        $file = $data['file'];
        $baiNop = BaiNop::firstOrNew([
            'bai_tap_id' => $baitap->id,
            'sinh_vien_id' => $user->sinh_vien_id,
        ]);
        $oldFilePath = $baiNop->file_path;
        $newFilePath = $file->store("baitap/{$baitap->id}", 'public');

        if ($oldFilePath) {
            Storage::disk('public')->delete($oldFilePath);
        }

        $baiNop->fill([
            'file_path' => $newFilePath,
            'file_name' => $file->getClientOriginalName(),
            'ghi_chu' => $data['ghi_chu'] ?? null,
            'nop_luc' => now(),
            'diem' => null,
            'nhan_xet' => null,
        ])->save();

        return redirect()->route('baitap.show', $baitap)->with('success', 'Bài tập đã được nộp thành công.');
    }

    public function download(Request $request, BaiNop $bainop)
    {
        $bainop->load('baiTap');
        $this->ensureMaterialIsVisible($request, $bainop->baiTap);

        if ($request->user()->role === 'sinh_vien' && $bainop->sinh_vien_id !== $request->user()->sinh_vien_id) {
            abort(403, 'Bạn không có quyền tải bài nộp của sinh viên khác.');
        }

        abort_unless(Storage::disk('public')->exists($bainop->file_path), 404, 'Tệp bài nộp không tồn tại.');

        return Storage::disk('public')->download($bainop->file_path, $bainop->file_name);
    }

    public function grade(GradeBaiNopRequest $request, BaiNop $bainop)
    {
        $bainop->load('baiTap');
        $this->ensureMaterialIsVisible($request, $bainop->baiTap);
        $data = $request->validated();

        if ((float) $data['diem'] > $bainop->baiTap->diem_toi_da) {
            throw ValidationException::withMessages([
                'diem' => "Điểm không được lớn hơn {$bainop->baiTap->diem_toi_da}.",
            ]);
        }

        $bainop->update([
            'diem' => $data['diem'],
            'nhan_xet' => $data['nhan_xet'] ?? null,
        ]);

        return back()->with('success', 'Đã cập nhật điểm và nhận xét.');
    }

    private function ensureStudentCanAccess(Request $request, BaiTap $baitap): void
    {
        $user = $request->user();
        abort_unless($user->role === 'sinh_vien' && $user->sinh_vien_id, 403, 'Bạn không có quyền nộp bài.');
        $this->ensureMaterialIsVisible($request, $baitap);
    }

    private function ensureMaterialIsVisible(Request $request, BaiTap $baitap): void
    {
        abort_unless(\App\Models\MonHoc::visibleTo($request->user())->whereKey($baitap->mon_hoc_id)->exists(), 403, 'Bạn không có quyền truy cập bài tập này.');
    }
}
