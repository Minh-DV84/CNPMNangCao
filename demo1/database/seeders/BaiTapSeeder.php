<?php

namespace Database\Seeders;

use App\Models\BaiNop;
use App\Models\BaiTap;
use App\Models\MonHoc;
use Illuminate\Database\Seeder;

class BaiTapSeeder extends Seeder
{
    public function run(): void
    {
        $monHocs = MonHoc::orderBy('ma_mon')->get();

        foreach ($monHocs as $index => $monHoc) {
            $baiTapQuaHan = BaiTap::updateOrCreate(
                [
                    'mon_hoc_id' => $monHoc->id,
                    'tieu_de' => "Bài tập 1 - {$monHoc->ten_mon}",
                ],
                [
                    'noi_dung' => "Hoàn thành bài tập thực hành số 1 của môn {$monHoc->ten_mon}.",
                    'han_nop' => now()->copy()->subDays(7),
                    'diem_toi_da' => 10,
                ],
            );

            BaiTap::updateOrCreate(
                [
                    'mon_hoc_id' => $monHoc->id,
                    'tieu_de' => "Bài tập 2 - {$monHoc->ten_mon}",
                ],
                [
                    'noi_dung' => "Hoàn thành bài tập thực hành số 2 của môn {$monHoc->ten_mon}.",
                    'han_nop' => now()->copy()->addDays(7),
                    'diem_toi_da' => 10,
                ],
            );

            if ($index < 3) {
                $sinhVien = $monHoc->lopHocs()
                    ->with('sinhViens')
                    ->get()
                    ->flatMap(fn ($lopHoc) => $lopHoc->sinhViens)
                    ->first();

                if ($sinhVien) {
                    BaiNop::updateOrCreate(
                        [
                            'bai_tap_id' => $baiTapQuaHan->id,
                            'sinh_vien_id' => $sinhVien->id,
                        ],
                        [
                            'file_path' => "baitap/sample/mh{$index}_bai_nop.pdf",
                            'file_name' => "bai-nop-mau-mh{$index}.pdf",
                            'ghi_chu' => 'Bài nộp mẫu để minh họa.',
                            'nop_luc' => now()->copy()->subDays(8),
                            'diem' => 8.50,
                            'nhan_xet' => 'Bài làm đạt yêu cầu.',
                        ],
                    );
                }
            }
        }
    }
}
