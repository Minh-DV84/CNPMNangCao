<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Menu::query()->delete();
        $menus = [
            ['ten' => 'Trang chủ', 'url' => '/', 'vi_tri' => 'header', 'nhom' => null, 'roles' => 'admin,giang_vien,sinh_vien'],
            ['ten' => 'Giới thiệu', 'url' => '#', 'vi_tri' => 'header', 'nhom' => null, 'roles' => 'admin,giang_vien,sinh_vien'],
            ['ten' => 'Liên hệ', 'url' => '#', 'vi_tri' => 'header', 'nhom' => null, 'roles' => 'admin,giang_vien,sinh_vien'],

            ['ten' => 'Tổng quan', 'url' => '/tongquan', 'vi_tri' => 'sidebar', 'nhom' => '', 'roles' => 'admin,giang_vien,sinh_vien'],
            ['ten' => 'Hướng dẫn', 'url' => '/huongdan', 'vi_tri' => 'sidebar', 'nhom' => '', 'roles' => 'admin,giang_vien,sinh_vien'],
            ['ten' => 'Câu hỏi thường gặp', 'url' => '/cauhoi', 'vi_tri' => 'sidebar', 'nhom' => '', 'roles' => 'admin,giang_vien,sinh_vien'],

            ['ten' => 'Sinh viên', 'url' => '/sinhvien', 'vi_tri' => 'sidebar', 'nhom' => 'Quản lý', 'roles' => 'admin,giang_vien'],
            ['ten' => 'Quản lý lớp học', 'url' => '/lophoc', 'vi_tri' => 'sidebar', 'nhom' => 'Quản lý', 'roles' => 'admin,giang_vien'],
            ['ten' => 'Quản lý menu', 'url' => '/menu', 'vi_tri' => 'sidebar', 'nhom' => 'Quản lý', 'roles' => 'admin'],

            ['ten' => 'Bài giảng', 'url' => '/baigiang', 'vi_tri' => 'sidebar', 'nhom' => 'Học tập', 'roles' => 'admin,giang_vien,sinh_vien'],
            ['ten' => 'Bài tập', 'url' => '/baitap', 'vi_tri' => 'sidebar', 'nhom' => 'Học tập', 'roles' => 'admin,giang_vien,sinh_vien'],
            ['ten' => 'Điểm số', 'url' => '/diemso', 'vi_tri' => 'sidebar', 'nhom' => 'Học tập', 'roles' => 'admin,giang_vien,sinh_vien'],
            ['ten' => 'Tài liệu', 'url' => '/tailieu', 'vi_tri' => 'sidebar', 'nhom' => 'Học tập', 'roles' => 'admin,giang_vien,sinh_vien'],
        ];

        foreach ($menus as $thuTu => $menu) {
            Menu::create($menu + ['thu_tu' => $thuTu + 1, 'trang_thai' => true]);
        }
    }
}
