<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan level skala nilai untuk mendukung standar universitas/fakultas/prodi
     */
    public function up(): void
    {
        Schema::table('performance_indicators', function (Blueprint $table) {
            // Level skala nilai: Universitas, Fakultas, atau Program Studi
            $table->enum('grading_scale_level', [
                'Universitas',
                'Fakultas',
                'Program Studi'
            ])->default('Universitas')->after('grading_scale');

            // Referensi ke fakultas/prodi jika menggunakan skala khusus
            $table->foreignId('faculty_id')->nullable()->after('grading_scale_level')->constrained()->nullOnDelete();
            $table->foreignId('study_program_id')->nullable()->after('faculty_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performance_indicators', function (Blueprint $table) {
            $table->dropForeign(['study_program_id']);
            $table->dropForeign(['faculty_id']);
            $table->dropColumn(['grading_scale_level', 'faculty_id', 'study_program_id']);
        });
    }
};
