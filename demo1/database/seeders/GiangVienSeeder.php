<?php

namespace Database\Seeders;

use App\Models\GiangVien;
use App\Models\LopHoc;
use Illuminate\Database\Seeder;

class GiangVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lops = LopHoc::whereIn('ma_lop', ['LH-01', 'LH-02', 'LH-03', 'LH-04', 'LH-05', 'LH-06'])
            ->pluck('id', 'ma_lop')
            ->toArray();

        $giangViens = [
            [
                'ma_gv' => 'GV001',
                'ho_ten' => 'Nguyễn Văn An',
                'email' => 'gv1@huce.edu.vn',
                'so_dien_thoai' => '0912345678',
            ],
            [
                'ma_gv' => 'GV002',
                'ho_ten' => 'Trần Thị Bình',
                'email' => 'gv2@huce.edu.vn',
                'so_dien_thoai' => '0912345679',
            ],
            [
                'ma_gv' => 'GV003',
                'ho_ten' => 'Lê Văn Cường',
                'email' => 'gv3@huce.edu.vn',
                'so_dien_thoai' => '0912345680',
            ],
        ];

        $createdGVs = [];
        foreach ($giangViens as $gv) {
            $createdGVs[$gv['ma_gv']] = GiangVien::updateOrCreate(
                ['ma_gv' => $gv['ma_gv']],
                $gv
            );
        }

        // Gán giảng viên cho lớp học (mỗi giảng viên 2 lớp)
        $lopAssignments = [
            'GV001' => ['LH-01', 'LH-02'],
            'GV002' => ['LH-03', 'LH-04'],
            'GV003' => ['LH-05', 'LH-06'],
        ];

        foreach ($lopAssignments as $maGV => $maLops) {
            $gv = $createdGVs[$maGV];
            foreach ($maLops as $maLop) {
                if (isset($lops[$maLop])) {
                    LopHoc::where('id', $lops[$maLop])->update(['giang_vien_id' => $gv->id]);
                }
            }
        }
    }
}
