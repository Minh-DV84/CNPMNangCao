<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ten' => $this->faker->words(2, true),
            'url' => '/' . $this->faker->slug(2),
            'vi_tri' => $this->faker->randomElement(array_keys(Menu::VI_TRI)),
            'nhom' => null,
            'thu_tu' => $this->faker->numberBetween(0, 20),
            'trang_thai' => true,
        ];
    }
}
