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
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('level', ['D3', 'D4', 'S1', 'S2', 'S3', 'Profesi', 'Spesialis'])->default('S1');

            // Visi Misi Tujuan Prodi
            $table->text('vision')->nullable();
            $table->text('mission')->nullable(); // JSON array
            $table->text('objectives')->nullable(); // JSON array (Tujuan Prodi)

            $table->text('description')->nullable();
            $table->string('head_of_program')->nullable(); // Kepala Prodi
            $table->string('secretary')->nullable(); // Sekretaris Prodi

            // Akreditasi
            $table->string('accreditation')->nullable(); // A, B, C, Unggul, Baik Sekali
            $table->date('accreditation_date')->nullable();
            $table->string('accreditation_number')->nullable();

            $table->integer('standard_study_period')->default(8); // Lama studi standar (semester)
            $table->string('degree_awarded')->nullable(); // Gelar yang diberikan (S.Kom, S.T, dll)

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
        Schema::dropIfExists('study_programs');
    }
};
