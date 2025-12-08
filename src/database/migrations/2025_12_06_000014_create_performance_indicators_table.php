<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Indikator Kinerja - ukuran pencapaian Sub-CPMK
     */
    public function up(): void
    {
        Schema::create('performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_course_learning_outcome_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('course_learning_outcome_id')->nullable()->constrained()->cascadeOnDelete(); // Bisa langsung ke CPMK
            $table->string('code'); // IK-01, IK-02, etc
            $table->text('description');
            $table->text('criteria')->nullable(); // Kriteria penilaian
            $table->text('rubric')->nullable(); // JSON rubrik penilaian detail

            // Bobot dalam penilaian
            $table->decimal('weight', 5, 2)->default(0); // 0-100 percentage

            // Metode Assessment
            $table->enum('assessment_type', [
                'Ujian Tulis',
                'Ujian Praktik',
                'Ujian Lisan',
                'Tugas Individu',
                'Tugas Kelompok',
                'Proyek',
                'Presentasi',
                'Portofolio',
                'Observasi',
                'Praktikum',
                'Studi Kasus',
                'Quiz',
                'Lainnya'
            ])->nullable();

            // Standar Kelulusan
            $table->decimal('passing_grade', 5, 2)->default(70.00); // Nilai minimum kelulusan
            $table->text('grading_scale')->nullable(); // JSON skala penilaian (A, B, C, dst)

            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_indicators');
    }
};
