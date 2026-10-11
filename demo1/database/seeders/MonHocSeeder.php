<?php

namespace Database\Seeders;

use App\Models\GiangVien;
use App\Models\LopHoc;
use App\Models\MonHoc;
use Illuminate\Database\Seeder;

class MonHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $monHocs = [
            ['ma_mon' => 'MH01', 'ten_mon' => 'Lập trình web', 'so_tin_chi' => 3, 'ma_gv' => 'GV001'],
            ['ma_mon' => 'MH02', 'ten_mon' => 'Cơ sở dữ liệu', 'so_tin_chi' => 3, 'ma_gv' => 'GV001'],
            ['ma_mon' => 'MH03', 'ten_mon' => 'Mạng máy tính', 'so_tin_chi' => 3, 'ma_gv' => 'GV002'],
            ['ma_mon' => 'MH04', 'ten_mon' => 'Công nghệ phần mềm', 'so_tin_chi' => 3, 'ma_gv' => 'GV002'],
            ['ma_mon' => 'MH05', 'ten_mon' => 'Cấu trúc dữ liệu', 'so_tin_chi' => 2, 'ma_gv' => 'GV003'],
            ['ma_mon' => 'MH06', 'ten_mon' => 'Tiếng Anh chuyên ngành', 'so_tin_chi' => 2, 'ma_gv' => 'GV003'],
        ];

        $monHocIds = [];
        foreach ($monHocs as $monHocData) {
            $giangVien = GiangVien::where('ma_gv', $monHocData['ma_gv'])->firstOrFail();

            $monHoc = MonHoc::updateOrCreate(
                ['ma_mon' => $monHocData['ma_mon']],
                [
                    'ten_mon' => $monHocData['ten_mon'],
                    'so_tin_chi' => $monHocData['so_tin_chi'],
                    'giang_vien_id' => $giangVien->id,
                ],
            );

            $monHocIds[] = $monHoc->id;
        }

        LopHoc::whereIn('ma_lop', ['LH-01', 'LH-02', 'LH-03', 'LH-04', 'LH-05', 'LH-06'])
            ->get()
            ->each(fn (LopHoc $lopHoc) => $lopHoc->monHocs()->syncWithoutDetaching($monHocIds));
    }
}
