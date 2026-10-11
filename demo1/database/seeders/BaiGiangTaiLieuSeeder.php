<?php

namespace Database\Seeders;

use App\Models\BaiGiang;
use App\Models\MonHoc;
use App\Models\TaiLieu;
use Illuminate\Database\Seeder;

class BaiGiangTaiLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monHocs = MonHoc::orderBy('ma_mon')->get();

        foreach ($monHocs as $monHoc) {
            foreach ([1, 2] as $number) {
                BaiGiang::updateOrCreate(
                    [
                        'mon_hoc_id' => $monHoc->id,
                        'tieu_de' => "Bài giảng {$number} - {$monHoc->ten_mon}",
                    ],
                    [
                        'mo_ta' => "Nội dung bài giảng {$number} của môn {$monHoc->ten_mon}.",
                        'file_path' => null,
                        'file_name' => null,
                    ],
                );

                TaiLieu::updateOrCreate(
                    [
                        'mon_hoc_id' => $monHoc->id,
                        'tieu_de' => "Tài liệu {$number} - {$monHoc->ten_mon}",
                    ],
                    [
                        'mo_ta' => "Tài liệu tham khảo {$number} của môn {$monHoc->ten_mon}.",
                        'file_path' => null,
                        'file_name' => null,
                    ],
                );
            }
        }
    }
}
