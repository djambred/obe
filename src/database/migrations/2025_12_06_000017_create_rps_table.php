<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * RPS (Rencana Pembelajaran Semester)
     */
    public function up(): void
    {
        Schema::create('rps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete(); // Koordinator/PIC
            $table->foreignId('curriculum_id')->constrained('curriculums')->cascadeOnDelete();
            $table->string('academic_year'); // e.g., "2024/2025"
            $table->enum('semester', ['Ganjil', 'Genap', 'Pendek'])->default('Ganjil');
            $table->string('version')->default('1.0');

            // Identitas RPS
            $table->string('class_code')->nullable();
            $table->integer('student_quota')->default(40);
            $table->text('course_description')->nullable();

            // Learning Outcomes yang akan dicapai
            $table->json('clo_list')->nullable(); // CPMK yang akan dicapai
            $table->json('plo_mapped')->nullable(); // CPL yang dipetakan
            $table->json('study_field_mapped')->nullable(); // Bahan Kajian yang dipetakan

            // Rencana Pembelajaran 16 Minggu
            $table->json('weekly_plan')->nullable(); // Detail plan per minggu
            // Structure: [
            //   {week: 1, topics: [], sub_cpmk: [], methods: [], materials: [], assessment: [], duration: 150}
            // ]

            // Assessment Plan
            $table->json('assessment_plan')->nullable(); // Rencana penilaian detail
            $table->json('assessment_rubric')->nullable(); // Rubrik penilaian
            $table->text('grading_system')->nullable(); // Sistem penilaian

            // Learning Resources
            $table->json('main_references')->nullable(); // Referensi utama
            $table->json('supporting_references')->nullable(); // Referensi pendukung
            $table->text('learning_media')->nullable(); // Media pembelajaran
            $table->text('learning_software')->nullable(); // Software yang digunakan

            // Documents
            $table->string('document_file')->nullable(); // MinIO path untuk RPS PDF
            $table->string('syllabus_file')->nullable(); // MinIO path untuk silabus
            $table->string('contract_file')->nullable(); // MinIO path untuk kontrak perkuliahan

            // Approval Workflow
            $table->enum('status', [
                'Draft',
                'Submitted',                // Diajukan
                'Reviewed by Coordinator',  // Direview Koordinator Prodi
                'Reviewed by Head',         // Direview Kaprodi
                'Approved',                 // Disetujui
                'Rejected',                 // Ditolak
                'Revision Required'         // Perlu Revisi
            ])->default('Draft');

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'academic_year', 'semester', 'class_code'], 'unique_rps_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps');
    }
};
