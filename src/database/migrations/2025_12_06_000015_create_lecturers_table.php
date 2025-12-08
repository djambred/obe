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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->foreignId('study_program_id')->nullable()->constrained()->nullOnDelete();

            // Identitas
            $table->string('nidn')->unique(); // Nomor Induk Dosen Nasional
            $table->string('nip')->unique()->nullable(); // Nomor Induk Pegawai
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();

            // Status Kepegawaian
            $table->enum('employment_status', ['PNS', 'PPPK', 'Dosen Tetap', 'Dosen Tidak Tetap', 'Dosen LB'])->default('Dosen Tetap');

            // Jabatan Akademik
            $table->enum('academic_rank', [
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Profesor'
            ])->nullable();

            // Jabatan Fungsional
            $table->enum('functional_position', [
                'Tenaga Pengajar',
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Guru Besar'
            ])->nullable();

            // Pendidikan
            $table->enum('highest_education', ['S1', 'S2', 'S3'])->default('S2');
            $table->string('education_field')->nullable(); // Bidang pendidikan
            $table->text('education_background')->nullable(); // JSON riwayat pendidikan lengkap

            // Expertise & Research
            $table->text('expertise_areas')->nullable(); // JSON array bidang keahlian
            $table->text('research_interests')->nullable(); // JSON array minat penelitian
            $table->text('certifications')->nullable(); // JSON array sertifikasi profesional

            // SINTA Profile
            $table->string('sinta_id')->nullable();
            $table->string('sinta_score')->nullable();
            $table->integer('sinta_rank_national')->nullable();
            $table->integer('sinta_rank_affiliation')->nullable();
            $table->integer('sinta_publications')->nullable();
            $table->json('sinta_data')->nullable(); // Full SINTA data

            // Google Scholar Profile
            $table->string('google_scholar_id')->nullable();
            $table->integer('h_index')->nullable();
            $table->integer('i10_index')->nullable();
            $table->integer('total_citations')->nullable();
            $table->integer('total_publications')->nullable();
            $table->json('google_scholar_data')->nullable(); // Full GS data

            // Additional Info
            $table->string('photo')->nullable(); // MinIO path
            $table->text('biography')->nullable();
            $table->text('achievements')->nullable(); // JSON array penghargaan

            $table->boolean('is_active')->default(true);
            $table->timestamp('last_profile_sync')->nullable(); // Last sync from SINTA/GS
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
