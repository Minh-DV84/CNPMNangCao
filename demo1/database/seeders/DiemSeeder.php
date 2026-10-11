<?php

namespace Database\Seeders;

use App\Models\Diem;
use App\Models\SinhVien;
use Illuminate\Database\Seeder;

class DiemSeeder extends Seeder
{
    public function run(): void
    {
        SinhVien::with('lopHoc.monHocs')->get()->each(function (SinhVien $sinhVien) {
            foreach ($sinhVien->lopHoc?->monHocs ?? [] as $monHoc) {
                Diem::updateOrCreate(
                    [
                        'sinh_vien_id' => $sinhVien->id,
                        'mon_hoc_id' => $monHoc->id,
                    ],
                    [
                        'diem_chuyen_can' => fake()->randomFloat(1, 5, 10),
                        'diem_giua_ky' => fake()->randomFloat(1, 5, 10),
                        'diem_cuoi_ky' => fake()->randomFloat(1, 5, 10),
                    ],
                );
            }
        });
    }
}
