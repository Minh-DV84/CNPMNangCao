<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LopHoc;

class LopHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lops = [
            [
                'ma_lop' => 'LH-01',
                'ten_lop' => '68MHT1',
                'giao_vien' => 'Nguyễn Văn An',
                'so_dien_thoai_gvcn' => '0912345601',
                'ghi_chu' => 'Lớp Mạng máy tính và truyền thông dữ liệu, nhóm 1.',
                'si_so' => 45,
                'trang_thai' => true,
            ],
            [
                'ma_lop' => 'LH-02',
                'ten_lop' => '68MHT2',
                'giao_vien' => 'Lương Xuân Hiếu',
                'so_dien_thoai_gvcn' => '0912345602',
                'ghi_chu' => 'Lớp Mạng máy tính và truyền thông dữ liệu, nhóm 2.',
                'si_so' => 42,
                'trang_thai' => true,
            ],
            [
                'ma_lop' => 'LH-03',
                'ten_lop' => '68MHT3',
                'giao_vien' => 'Lê Quang Cường',
                'so_dien_thoai_gvcn' => '0912345603',
                'ghi_chu' => 'Lớp Mạng máy tính và truyền thông dữ liệu, nhóm 3.',
                'si_so' => 40,
                'trang_thai' => true,
            ],
            [
                'ma_lop' => 'LH-04',
                'ten_lop' => '68PM1',
                'giao_vien' => 'Phạm Thị Dung',
                'so_dien_thoai_gvcn' => '0912345604',
                'ghi_chu' => 'Lớp Công nghệ phần mềm, nhóm 1.',
                'si_so' => 48,
                'trang_thai' => true,
            ],
            [
                'ma_lop' => 'LH-05',
                'ten_lop' => '68PM2',
                'giao_vien' => 'Hoàng Minh Đức',
                'so_dien_thoai_gvcn' => '0912345605',
                'ghi_chu' => 'Lớp Công nghệ phần mềm, nhóm 2.',
                'si_so' => 46,
                'trang_thai' => true,
            ],
            [
                'ma_lop' => 'LH-06',
                'ten_lop' => '68PM3',
                'giao_vien' => 'Vũ Thị Hà',
                'so_dien_thoai_gvcn' => '0912345606',
                'ghi_chu' => 'Lớp Công nghệ phần mềm, nhóm 3.',
                'si_so' => 44,
                'trang_thai' => true,
            ],
        ];

        foreach ($lops as $lop) {
            LopHoc::updateOrCreate(
                ['ma_lop' => $lop['ma_lop']],
                $lop
            );
        }
    }
}