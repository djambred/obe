<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Plotting/Assignment Dosen ke Mata Kuliah
     */
    public function up(): void
    {
        Schema::create('course_lecturer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->string('academic_year'); // e.g., "2024/2025"
            $table->enum('semester', ['Ganjil', 'Genap', 'Pendek'])->default('Ganjil');
            $table->string('class_code')->nullable(); // Kelas A, B, C, etc

            // Peran Dosen
            $table->enum('role', [
                'Koordinator',              // Koordinator Mata Kuliah
                'Pengampu',                 // Dosen Pengampu
                'Asisten',                  // Asisten Dosen
                'Team Teaching'             // Team Teaching
            ])->default('Pengampu');

            // Match Score - kesesuaian dosen dengan MK
            $table->decimal('expertise_match_score', 5, 2)->nullable(); // 0-100 based on expertise match
            $table->decimal('publication_match_score', 5, 2)->nullable(); // 0-100 based on publication relevance
            $table->decimal('overall_match_score', 5, 2)->nullable(); // 0-100 overall score
            $table->text('match_reasons')->nullable(); // JSON array explaining the match

            // Beban Kerja
            $table->integer('student_count')->default(0); // Jumlah mahasiswa
            $table->decimal('workload_sks', 5, 2)->default(0); // Beban SKS

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'lecturer_id', 'academic_year', 'semester', 'class_code'], 'unique_course_lecturer_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lecturer');
    }
};
