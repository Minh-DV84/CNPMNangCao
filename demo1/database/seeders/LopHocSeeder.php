<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LopHoc;

class LopHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        LopHoc::factory()->count(20)->create();
        LopHoc::factory()->create([
            'ten_lop' => 'Lớp 1A',
            'ma_lop' => 'LA001',
            'giao_vien' => 'Nguyễn Văn A',
            'so_dien_thoai_gvcn' => '0123456789',
            'ghi_chu' => 'Lớp học đầu tiên',
            'si_so' => 30,
            'trang_thai' => true,
        ]);
    }
}
