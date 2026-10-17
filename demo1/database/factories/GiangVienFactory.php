<?php

namespace Database\Factories;

use App\Models\GiangVien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GiangVien>
 */
class GiangVienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ma_gv' => $this->faker->unique()->regexify('GV[0-9]{4}'),
            'ho_ten' => $this->faker->name(),
            'gioi_tinh' => $this->faker->randomElement(['Nam', 'Nữ']),
            'ngay_sinh' => $this->faker->date('Y-m-d', '1980-01-01'),
            'so_dien_thoai' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'dia_chi' => $this->faker->address(),
        ];
    }
}
