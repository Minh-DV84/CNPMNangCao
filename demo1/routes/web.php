<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\LopHocController;
use App\Http\Controllers\MenuController;
use App\Http\Middleware\CheckGioHanhChinh;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/sinhvien', [SinhVienController::class, 'index']);
// Route::get('/sinhvien/add', [SinhVienController::class, 'add']);
// Route::post('/sinhvien/store', [SinhVienController::class, 'store'])->name('sinhvien.store');

// Route::prefix('sinhvien')->group(function () {
//     Route::get('/', [SinhVienController::class, 'index']);
//     Route::get('/add', [SinhVienController::class, 'add']);
//     Route::post('/store', [SinhVienController::class, 'store'])->name('sinhvien.store');

// });

Route::resource('sinhvien', SinhVienController::class);
Route::middleware('check.gio.hanh.chinh')->group(function () {
    Route::resource('lophoc', LopHocController::class);
    Route::resource('menu', MenuController::class)->except('show');
});
Route::resource('menu', MenuController::class)->except('show');

