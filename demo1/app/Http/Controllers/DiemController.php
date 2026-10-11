<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveDiemRequest;
use App\Models\Diem;
use App\Models\LopHoc;
use App\Models\MonHoc;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DiemController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'sinh_vien') {
            return $this->studentIndex($user);
        }

        $filters = $this->validatedFilters($request);
        $monHocs = $this->accessibleMonHocs($user);
        $lopHocs = $this->accessibleLopHocs($user);
        $selectedMonHocId = isset($filters['mon_hoc_id']) ? (int) $filters['mon_hoc_id'] : null;
        $selectedLopHocId = isset($filters['lop_hoc_id']) ? (int) $filters['lop_hoc_id'] : null;

        if ($selectedMonHocId !== null && !$monHocs->contains('id', $selectedMonHocId)) {
            abort(403, 'Bạn không có quyền xem môn học này.');
        }
        if ($selectedLopHocId !== null && !$lopHocs->contains('id', $selectedLopHocId)) {
            abort(403, 'Bạn không có quyền xem lớp học này.');
        }

        $students = null;
        if ($selectedMonHocId !== null && $selectedLopHocId !== null) {
            $students = $this->studentsWithScores($selectedLopHocId, $selectedMonHocId, $filters['keyword'] ?? null)
                ->paginate(self::PER_PAGE)
                ->withQueryString();
        }

        return view('diems.index', [
            'isStudent' => false,
            'monHocs' => $monHocs,
            'lopHocs' => $lopHocs,
            'students' => $students,
            'filters' => $filters,
            'selectedMonHocId' => $selectedMonHocId,
            'selectedLopHocId' => $selectedLopHocId,
        ]);
    }

    public function save(SaveDiemRequest $request)
    {
        $user = $request->user();
        abort_if($user->role === 'sinh_vien', 403, 'Sinh viên không có quyền lưu điểm.');

        $data = $request->validated();
        [$monHoc, $lopHoc] = $this->assertManagerSelection($user, (int) $data['mon_hoc_id'], (int) $data['lop_hoc_id']);

        foreach ($data['diems'] as $scoreData) {
            $student = SinhVien::whereKey($scoreData['sinh_vien_id'])
                ->where('lop_hoc_id', $lopHoc->id)
                ->first();

            abort_unless($student, 403, 'Sinh viên không thuộc lớp đang chấm điểm.');

            Diem::updateOrCreate(
                [
                    'sinh_vien_id' => $student->id,
                    'mon_hoc_id' => $monHoc->id,
                ],
                [
                    'diem_chuyen_can' => $scoreData['diem_chuyen_can'] ?? null,
                    'diem_giua_ky' => $scoreData['diem_giua_ky'] ?? null,
                    'diem_cuoi_ky' => $scoreData['diem_cuoi_ky'] ?? null,
                ],
            );
        }

        return redirect()->route('diemso', [
            'mon_hoc_id' => $monHoc->id,
            'lop_hoc_id' => $lopHoc->id,
            'keyword' => $request->input('keyword'),
        ])->with('success', 'Đã lưu bảng điểm.');
    }

    public function export(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'sinh_vien') {
            $student = $this->currentStudent($user);
            $monHocs = MonHoc::visibleTo($user)
                ->with(['diems' => fn ($query) => $query->where('sinh_vien_id', $student->id)])
                ->orderBy('ma_mon')
                ->get();
            $rows = $monHocs->map(function (MonHoc $monHoc) {
                $diem = $monHoc->diems->first();
                return [
                    $monHoc->ma_mon,
                    $monHoc->ten_mon,
                    $diem?->diem_chuyen_can,
                    $diem?->diem_giua_ky,
                    $diem?->diem_cuoi_ky,
                    $diem?->diem_tong_ket,
                    $diem?->xep_loai,
                ];
            });

            return $this->csvResponse('bang-diem-cua-toi.csv', ['Mã môn', 'Tên môn', 'Chuyên cần', 'Giữa kỳ', 'Cuối kỳ', 'Tổng kết', 'Xếp loại'], $rows);
        }

        $filters = $this->validatedFilters($request);
        [$monHoc, $lopHoc] = $this->assertManagerSelection(
            $user,
            (int) ($filters['mon_hoc_id'] ?? 0),
            (int) ($filters['lop_hoc_id'] ?? 0),
        );
        $students = $this->studentsWithScores($lopHoc->id, $monHoc->id, $filters['keyword'] ?? null)->get();
        $rows = $students->map(function (SinhVien $student) use ($monHoc, $lopHoc) {
            $diem = $student->diems->first();
            return [
                $student->msv,
                $student->ho_ten,
                $lopHoc->ten_lop,
                $monHoc->ma_mon,
                $monHoc->ten_mon,
                $diem?->diem_chuyen_can,
                $diem?->diem_giua_ky,
                $diem?->diem_cuoi_ky,
                $diem?->diem_tong_ket,
                $diem?->xep_loai,
            ];
        });

        return $this->csvResponse('bang-diem-' . $lopHoc->ma_lop . '-' . $monHoc->ma_mon . '.csv', ['MSV', 'Họ tên', 'Lớp', 'Mã môn', 'Tên môn', 'Chuyên cần', 'Giữa kỳ', 'Cuối kỳ', 'Tổng kết', 'Xếp loại'], $rows);
    }

    private function studentIndex($user)
    {
        $student = $this->currentStudent($user);
        $monHocs = MonHoc::visibleTo($user)
            ->with(['diems' => fn ($query) => $query->where('sinh_vien_id', $student->id)])
            ->orderBy('ma_mon')
            ->get();
        $diems = $monHocs->map(fn (MonHoc $monHoc) => $monHoc->diems->first())->filter();
        $completeScores = $diems->filter(fn (Diem $diem) => $diem->diem_tong_ket !== null);
        $average = $completeScores->isEmpty() ? null : round($completeScores->avg(fn (Diem $diem) => $diem->diem_tong_ket), 2);

        return view('diems.student', [
            'monHocs' => $monHocs,
            'average' => $average,
        ]);
    }

    private function validatedFilters(Request $request): array
    {
        return Validator::make($request->query(), [
            'mon_hoc_id' => ['nullable', 'integer', 'exists:mon_hocs,id'],
            'lop_hoc_id' => ['nullable', 'integer', 'exists:lop_hocs,id'],
            'keyword' => ['nullable', 'string', 'max:100'],
        ])->valid();
    }

    private function accessibleMonHocs($user)
    {
        return MonHoc::visibleTo($user)->orderBy('ma_mon')->get();
    }

    private function accessibleLopHocs($user)
    {
        $query = LopHoc::query()->orderBy('ma_lop');
        if ($user->role === 'giang_vien') {
            $query->where('giang_vien_id', $user->giang_vien_id);
        }
        return $query->get();
    }

    private function assertManagerSelection($user, int $monHocId, int $lopHocId): array
    {
        abort_if($monHocId < 1 || $lopHocId < 1, 422, 'Vui lòng chọn môn học và lớp học.');

        $monHoc = $this->accessibleMonHocs($user)->firstWhere('id', $monHocId);
        $lopHoc = $this->accessibleLopHocs($user)->firstWhere('id', $lopHocId);
        abort_unless($monHoc && $lopHoc, 403, 'Bạn không có quyền chấm điểm môn học hoặc lớp học này.');

        return [$monHoc, $lopHoc];
    }

    private function studentsWithScores(int $lopHocId, int $monHocId, ?string $keyword)
    {
        return SinhVien::query()
            ->with(['diems' => fn ($query) => $query->where('mon_hoc_id', $monHocId)])
            ->where('lop_hoc_id', $lopHocId)
            ->when($keyword, function ($query) use ($keyword) {
                $value = '%' . $keyword . '%';
                $query->where(function ($studentQuery) use ($value) {
                    $studentQuery->where('msv', 'like', $value)->orWhere('ho_ten', 'like', $value);
                });
            })
            ->orderBy('ho_ten');
    }

    private function currentStudent($user): SinhVien
    {
        $student = $user->sinhVien;
        abort_unless($student, 403, 'Tài khoản chưa được liên kết với sinh viên.');
        return $student;
    }

    private function csvResponse(string $fileName, array $headers, $rows)
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
