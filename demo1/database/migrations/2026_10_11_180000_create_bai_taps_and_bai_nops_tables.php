<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bai_taps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mon_hoc_id')->constrained('mon_hocs')->cascadeOnDelete();
            $table->string('tieu_de');
            $table->text('noi_dung');
            $table->dateTime('han_nop');
            $table->unsignedTinyInteger('diem_toi_da')->default(10);
            $table->timestamps();
        });

        Schema::create('bai_nops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bai_tap_id')->constrained('bai_taps')->cascadeOnDelete();
            $table->foreignId('sinh_vien_id')->constrained('sinh_viens')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->text('ghi_chu')->nullable();
            $table->dateTime('nop_luc');
            $table->decimal('diem', 4, 2)->nullable();
            $table->text('nhan_xet')->nullable();
            $table->timestamps();
            $table->unique(['bai_tap_id', 'sinh_vien_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bai_nops');
        Schema::dropIfExists('bai_taps');
    }
};
