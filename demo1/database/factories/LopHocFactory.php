<?php

namespace Database\Factories;

use App\Models\LopHoc;
use App\Models\GiangVien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LopHoc>
 */
class LopHocFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ten_lop' => $this->faker->word(),
            'ma_lop' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            'giao_vien' => $this->faker->name(),
            'so_dien_thoai_gvcn' => $this->faker->phoneNumber(),
            'ghi_chu' => $this->faker->sentence(),
            'si_so' => $this->faker->numberBetween(10, 50),
            'trang_thai' => $this->faker->boolean(),
            'giang_vien_id' => GiangVien::factory(),
        ];
    }

    /**
     * Indicate that the lop hoc has no giang vien (to avoid circular dependency)
     */
    public function withoutGiangVien(): static
    {
        return $this->state(fn (array $attributes) => [
            'giang_vien_id' => null,
        ]);
    }
}
