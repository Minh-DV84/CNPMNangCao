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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('sinh_vien');
            $table->foreignId('sinh_vien_id')->nullable()->constrained('sinh_viens')->nullOnDelete();
            $table->foreignId('giang_vien_id')->nullable()->constrained('giang_viens')->nullOnDelete();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sinh_vien_id']);
            $table->dropForeign(['giang_vien_id']);
            $table->dropColumn(['role', 'sinh_vien_id', 'giang_vien_id']);
            $table->string('email')->nullable(false)->change();
        });
    }
};
