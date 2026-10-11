<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GiangVien;
use App\Models\SinhVien;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin account
        User::updateOrCreate(
            ['email' => 'admin@huce.edu.vn'],
            [
                'name' => 'Quản trị viên',
                'email' => 'admin@huce.edu.vn',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
            ]
        );

        // 2. Giang vien accounts
        $giangViens = GiangVien::all();
        foreach ($giangViens as $gv) {
            User::updateOrCreate(
                ['email' => $gv->email],
                [
                    'name' => $gv->ho_ten,
                    'email' => $gv->email,
                    'password' => Hash::make('GiangVien@123'),
                    'role' => 'giang_vien',
                    'giang_vien_id' => $gv->id,
                ]
            );
        }

        // 3. Sinh vien accounts (each sinh_vien gets an account)
        $sinhViens = SinhVien::all();
        foreach ($sinhViens as $sv) {
            User::updateOrCreate(
                ['sinh_vien_id' => $sv->id],
                [
                    'name' => $sv->ho_ten,
                    'email' => null,
                    'password' => Hash::make('SinhVien@123'),
                    'role' => 'sinh_vien',
                    'sinh_vien_id' => $sv->id,
                ]
            );
        }
    }
}
