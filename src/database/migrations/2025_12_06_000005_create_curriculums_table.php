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
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->year('academic_year_start');
            $table->year('academic_year_end')->nullable();

            // Total SKS
            $table->integer('total_credits')->default(144);
            $table->integer('mandatory_university_credits')->default(0); // MK Wajib Universitas
            $table->integer('mandatory_faculty_credits')->default(0); // MK Wajib Fakultas
            $table->integer('mandatory_program_credits')->default(0); // MK Wajib Prodi
            $table->integer('elective_credits')->default(0); // MK Pilihan
            $table->integer('concentration_credits')->default(0); // MK Konsentrasi

            $table->text('description')->nullable();
            $table->json('structure')->nullable(); // Struktur kurikulum per semester
            $table->text('concentration_list')->nullable(); // JSON array daftar konsentrasi

            $table->boolean('is_active')->default(true);
            $table->date('effective_date')->nullable();
            $table->string('document_file')->nullable(); // MinIO path

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculums');
    }
};
