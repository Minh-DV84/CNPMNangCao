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
        Schema::table('lop_hocs', function (Blueprint $table) {
            $table->foreignId('giang_vien_id')->nullable()->constrained('giang_viens')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lop_hocs', function (Blueprint $table) {
            $table->dropForeign(['giang_vien_id']);
            $table->dropColumn('giang_vien_id');
        });
    }
};
