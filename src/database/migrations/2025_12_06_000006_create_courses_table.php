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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained('curriculums')->cascadeOnDelete();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('english_name')->nullable();

            // Tipe Mata Kuliah sesuai OBE 2025
            $table->enum('type', [
                'Wajib Universitas',    // MKWU (Agama, Pancasila, Bahasa Indonesia, dll)
                'Wajib Fakultas',        // MK Wajib level Fakultas
                'Wajib Prodi',          // MK Wajib Program Studi
                'Pilihan',              // MK Pilihan
                'Konsentrasi'           // MK Konsentrasi/Peminatan
            ])->default('Wajib Prodi');

            $table->string('concentration')->nullable(); // Nama konsentrasi jika type = Konsentrasi

            $table->integer('credits')->default(3); // SKS
            $table->integer('theory_credits')->default(2); // SKS Teori
            $table->integer('practice_credits')->default(1); // SKS Praktik
            $table->integer('field_credits')->default(0); // SKS Lapangan

            $table->integer('semester')->default(1);
            $table->text('description')->nullable();
            $table->text('prerequisites')->nullable(); // JSON array of course IDs
            $table->text('corequisites')->nullable(); // JSON array of course IDs (MK yang harus diambil bersamaan)

            $table->text('learning_media')->nullable();
            $table->text('learning_methods')->nullable();
            $table->text('assessment_methods')->nullable(); // JSON array metode penilaian
            $table->text('references')->nullable(); // JSON array

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
        Schema::dropIfExists('courses');
    }
};
