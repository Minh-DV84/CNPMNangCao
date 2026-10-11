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
        $menus = [
            ['ten' => 'Trang chủ', 'url' => '/', 'vi_tri' => 'header', 'nhom' => null],
            ['ten' => 'Giới thiệu', 'url' => '#', 'vi_tri' => 'header', 'nhom' => null],
            ['ten' => 'Liên hệ', 'url' => '#', 'vi_tri' => 'header', 'nhom' => null],

            ['ten' => 'Tổng quan', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục'],
            ['ten' => 'Sinh viên', 'url' => '/sinhvien', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục'],
            ['ten' => 'Bài giảng', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục'],
            ['ten' => 'Bài tập', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục'],
            ['ten' => 'Điểm số', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục'],
            ['ten' => 'Tài liệu', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Danh mục'],

            ['ten' => 'Hướng dẫn', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Hỗ trợ'],
            ['ten' => 'Câu hỏi thường gặp', 'url' => '#', 'vi_tri' => 'sidebar', 'nhom' => 'Hỗ trợ'],

            ['ten' => 'Quản lý lớp học', 'url' => '/lophoc', 'vi_tri' => 'sidebar', 'nhom' => 'Quản trị'],
            ['ten' => 'Quản lý menu', 'url' => '/menu', 'vi_tri' => 'sidebar', 'nhom' => 'Quản trị'],
        ];

        foreach ($menus as $thuTu => $menu) {
            Menu::create($menu + ['thu_tu' => $thuTu + 1, 'trang_thai' => true]);
        }
    }
}
