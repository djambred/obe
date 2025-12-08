<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * CPL (Capaian Pembelajaran Lulusan) - turunan dari PL
     */
    public function up(): void
    {
        Schema::create('program_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // CPL-01, CPL-02, etc
            $table->text('description');

            // Kategori sesuai SN-Dikti
            $table->enum('category', [
                'Sikap',                    // S - Attitude
                'Pengetahuan',              // P - Knowledge
                'Keterampilan Umum',        // KU - General Skills
                'Keterampilan Khusus'       // KK - Specific Skills
            ])->default('Pengetahuan');

            // Level Taksonomi
            $table->enum('bloom_cognitive_level', ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'])->nullable(); // Cognitive domain (untuk Pengetahuan)
            $table->enum('bloom_affective_level', ['A1', 'A2', 'A3', 'A4', 'A5'])->nullable(); // Affective domain (untuk Sikap)
            $table->enum('bloom_psychomotor_level', ['P1', 'P2', 'P3', 'P4', 'P5'])->nullable(); // Psychomotor domain (untuk Keterampilan)

            // Pemetaan ke referensi eksternal
            $table->text('sndikti_reference')->nullable(); // Referensi ke SN-Dikti
            $table->text('kkni_level')->nullable(); // Level KKNI (1-9)
            $table->text('industry_reference')->nullable(); // Referensi ke standar industri

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
        Schema::dropIfExists('program_learning_outcomes');
    }
};
