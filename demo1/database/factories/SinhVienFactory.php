<?php

namespace Database\Factories;

use App\Models\SinhVien;
use App\Models\LopHoc;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SinhVien>
 */
class SinhVienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'msv' => $this->faker->unique()->regexify('[01]{5}68'),
            'ho_ten' => $this->faker->name(),
            'gioi_tinh' => $this->faker->randomElement(['Nam', 'Nữ']),
            'ngay_sinh' => $this->faker->date('Y-m-d', '2005-01-01'),
            'lop_hoc_id' => LopHoc::factory(),
            'so_dien_thoai' => $this->faker->phoneNumber(),
        ];
    }
}
