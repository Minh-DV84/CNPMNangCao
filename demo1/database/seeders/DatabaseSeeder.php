<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\LopHocSeeder;
use Database\Seeders\SinhVienSeeder;
use Database\Seeders\GiangVienSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            MenuSeeder::class,
            LopHocSeeder::class,
            GiangVienSeeder::class,
            SinhVienSeeder::class,
            UserSeeder::class,
        ]);

        
    }
}
