<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mon_hocs', function (Blueprint $table) {
            $table->id();
            $table->string('ma_mon')->unique();
            $table->string('ten_mon');
            $table->unsignedTinyInteger('so_tin_chi');
            $table->foreignId('giang_vien_id')
                ->nullable()
                ->constrained('giang_viens')
                ->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('lop_hoc_mon_hoc', function (Blueprint $table) {
            $table->foreignId('lop_hoc_id')
                ->constrained('lop_hocs')
                ->cascadeOnDelete();
            $table->foreignId('mon_hoc_id')
                ->constrained('mon_hocs')
                ->cascadeOnDelete();
            $table->unique(['lop_hoc_id', 'mon_hoc_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lop_hoc_mon_hoc');
        Schema::dropIfExists('mon_hocs');
    }
};
