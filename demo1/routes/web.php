<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\LopHocController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TongQuanController;
use App\Http\Controllers\BaiGiangController;
use App\Http\Controllers\TaiLieuController;
use App\Http\Controllers\BaiTapController;
use App\Http\Controllers\BaiNopController;
use App\Http\Controllers\DiemController;
use App\Http\Middleware\CheckGioHanhChinh;

// Redirect home based on auth status
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('tongquan');
    }
    return redirect()->route('login');
});

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes (require auth)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Tổng quan - all roles
    Route::get('/tongquan', [TongQuanController::class, 'index'])->name('tongquan');

    // Bài giảng và tài liệu: mọi role được xem, chỉ admin/giảng viên được quản lý
    Route::middleware('role:admin,giang_vien,sinh_vien')->group(function () {
        Route::get('baigiang/{baigiang}/download', [BaiGiangController::class, 'download'])->name('baigiang.download');
        Route::resource('baigiang', BaiGiangController::class)->only(['index', 'show']);

        Route::get('tailieu/{tailieu}/download', [TaiLieuController::class, 'download'])->name('tailieu.download');
        Route::resource('tailieu', TaiLieuController::class)->only(['index', 'show']);
    });
    Route::middleware('role:admin,giang_vien')->group(function () {
        Route::resource('baigiang', BaiGiangController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('tailieu', TaiLieuController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    // Bài tập: mọi role được xem nội dung phù hợp; admin/giảng viên quản lý bài tập
    Route::middleware('role:admin,giang_vien,sinh_vien')->group(function () {
        Route::get('bai-nop/{bainop}/download', [BaiNopController::class, 'download'])->name('bai-nop.download');
        Route::resource('baitap', BaiTapController::class)->only(['index', 'show']);
    });
    Route::middleware('role:admin,giang_vien')->group(function () {
        Route::resource('baitap', BaiTapController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::patch('bai-nop/{bainop}/cham-diem', [BaiNopController::class, 'grade'])->name('bai-nop.grade');
    });
    Route::middleware('role:sinh_vien')->group(function () {
        Route::post('baitap/{baitap}/nop-bai', [BaiNopController::class, 'store'])->name('baitap.submit');
    });
    // Điểm số: sinh viên chỉ xem điểm của mình; admin/giảng viên được nhập điểm theo quyền
    Route::middleware('role:admin,giang_vien,sinh_vien')->group(function () {
        Route::get('/diemso', [DiemController::class, 'index'])->name('diemso');
        Route::get('/diemso/xuat-csv', [DiemController::class, 'export'])->name('diemso.export');
    });
    Route::middleware('role:admin,giang_vien')->group(function () {
        Route::post('/diemso/luu', [DiemController::class, 'save'])->name('diemso.save');
    });
    Route::get('/huongdan', function () { return view('placeholder', ['title' => 'Hướng dẫn']); })->name('huongdan');
    Route::get('/cauhoi', function () { return view('placeholder', ['title' => 'Câu hỏi thường gặp']); })->name('cauhoi');

    // sinhvien: admin full, giang_vien read-only, sinh_vien denied
    Route::middleware('role:admin,giang_vien')->group(function () {
        Route::resource('sinhvien', SinhVienController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    });
    Route::middleware('role:admin')->group(function () {
        Route::resource('sinhvien', SinhVienController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    // lophoc: admin full, giang_vien read-only, sinh_vien denied
    Route::middleware('role:admin,giang_vien')->group(function () {
        Route::resource('lophoc', LopHocController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    });
    Route::middleware(['role:admin', 'check.gio.hanh.chinh'])->group(function () {
        Route::resource('lophoc', LopHocController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    // menu: admin only
    Route::middleware(['role:admin', 'check.gio.hanh.chinh'])->group(function () {
        Route::resource('menu', MenuController::class)->except('show');
    });
});

