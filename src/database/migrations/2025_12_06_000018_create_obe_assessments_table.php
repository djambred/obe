<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * OBE Assessment & Analytics
     */
    public function up(): void
    {
        Schema::create('obe_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rps_id')->nullable()->constrained()->nullOnDelete();
            $table->string('academic_year');
            $table->enum('semester', ['Ganjil', 'Genap', 'Pendek'])->default('Ganjil');
            $table->string('class_code')->nullable();

            // Student Performance Data
            $table->integer('total_students')->default(0);
            $table->integer('passed_students')->default(0);
            $table->integer('failed_students')->default(0);

            // CLO Achievement (CPMK)
            $table->json('clo_achievement')->nullable();
            // Structure: {clo_id: {avg_score: 85, passed: 30, failed: 5, attainment_level: 'High'}}

            // PLO Achievement (CPL) - calculated from CLO
            $table->json('plo_achievement')->nullable();
            // Structure: {plo_id: {avg_score: 82, contribution_from_clos: [...], attainment_level: 'Medium'}}

            // Statistical Analysis
            $table->json('statistics')->nullable();
            // Structure: {mean, median, mode, std_deviation, min, max, quartiles, distribution}

            $table->decimal('average_score', 5, 2)->nullable();
            $table->decimal('passing_rate', 5, 2)->nullable(); // Percentage
            $table->decimal('clo_attainment_rate', 5, 2)->nullable(); // CPMK attainment %
            $table->decimal('plo_attainment_rate', 5, 2)->nullable(); // CPL attainment %

            // Analysis & Improvement
            $table->text('strengths')->nullable(); // Kekuatan
            $table->text('weaknesses')->nullable(); // Kelemahan
            $table->text('analysis')->nullable(); // Analisis mendalam
            $table->text('recommendations')->nullable(); // Rekomendasi
            $table->json('improvement_actions')->nullable(); // Tindakan perbaikan
            $table->json('best_practices')->nullable(); // Best practices yang ditemukan

            // Continuous Improvement
            $table->text('previous_improvements')->nullable(); // Perbaikan dari periode sebelumnya
            $table->boolean('improvement_effective')->nullable(); // Apakah perbaikan efektif

            // Metabase Dashboard
            $table->string('metabase_dashboard_url')->nullable(); // Link ke dashboard Metabase

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'academic_year', 'semester', 'class_code'], 'unique_obe_assessment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obe_assessments');
    }
};
