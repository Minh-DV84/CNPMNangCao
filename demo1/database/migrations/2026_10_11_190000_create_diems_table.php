<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sinh_vien_id')->constrained('sinh_viens')->cascadeOnDelete();
            $table->foreignId('mon_hoc_id')->constrained('mon_hocs')->cascadeOnDelete();
            $table->decimal('diem_chuyen_can', 4, 2)->nullable();
            $table->decimal('diem_giua_ky', 4, 2)->nullable();
            $table->decimal('diem_cuoi_ky', 4, 2)->nullable();
            $table->timestamps();
            $table->unique(['sinh_vien_id', 'mon_hoc_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diems');
    }
};
