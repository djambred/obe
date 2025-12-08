<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tracking Continuous Improvement per Program Studi
     */
    public function up(): void
    {
        Schema::create('continuous_improvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('academic_year');
            $table->enum('semester', ['Ganjil', 'Genap', 'Pendek'])->default('Ganjil');

            // Improvement Details
            $table->enum('improvement_area', [
                'Kurikulum',
                'Pembelajaran',
                'Penilaian',
                'Sumber Daya',
                'Infrastruktur',
                'SDM Dosen',
                'Mahasiswa',
                'Lainnya'
            ]);

            $table->text('issue_identified')->nullable(); // Masalah yang teridentifikasi
            $table->text('root_cause')->nullable(); // Akar masalah
            $table->text('improvement_plan')->nullable(); // Rencana perbaikan
            $table->json('action_items')->nullable(); // Item aksi yang harus dilakukan

            // Timeline
            $table->date('planned_start_date')->nullable();
            $table->date('planned_end_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();

            // Responsible Person
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('stakeholders')->nullable(); // JSON array stakeholders terlibat

            // Status & Results
            $table->enum('status', [
                'Planned',
                'In Progress',
                'Completed',
                'On Hold',
                'Cancelled'
            ])->default('Planned');

            $table->integer('progress_percentage')->default(0);
            $table->text('implementation_notes')->nullable();
            $table->text('results')->nullable(); // Hasil yang dicapai
            $table->boolean('is_effective')->nullable(); // Apakah efektif
            $table->text('effectiveness_evidence')->nullable(); // Bukti efektivitas

            // Evidence Documents
            $table->json('evidence_files')->nullable(); // MinIO paths untuk bukti

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('continuous_improvements');
    }
};
