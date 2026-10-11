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
        foreach (['bai_giangs', 'tai_lieus'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->foreignId('mon_hoc_id')
                    ->constrained('mon_hocs')
                    ->cascadeOnDelete();
                $table->string('tieu_de');
                $table->text('mo_ta')->nullable();
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tai_lieus');
        Schema::dropIfExists('bai_giangs');
    }
};
