<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LopHocController;
use App\Http\Controllers\Api\SinhVienController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('lop-hoc', LopHocController::class);
Route::apiResource('sinh-vien', SinhVienController::class);
