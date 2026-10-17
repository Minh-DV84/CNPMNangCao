<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LopHocController;
use App\Http\Controllers\Api\SinhVienController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // API lớp học: admin full, giảng viên chỉ lớp của mình
    Route::middleware('role:admin,giang_vien')->group(function () {
        Route::apiResource('lop-hoc', LopHocController::class);
    });

    // API sinh viên: admin full, giảng viên chỉ sinh viên trong lớp của mình
    Route::middleware('role:admin,giang_vien')->group(function () {
        Route::apiResource('sinh-vien', SinhVienController::class);
    });
});
