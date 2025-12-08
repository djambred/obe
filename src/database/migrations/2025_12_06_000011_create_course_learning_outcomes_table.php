<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * CPMK (Capaian Pembelajaran Mata Kuliah) - per mata kuliah
     */
    public function up(): void
    {
        Schema::create('course_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // CPMK-01, CPMK-02, etc (unique per course)
            $table->text('description');

            // Level Taksonomi
            $table->enum('bloom_cognitive_level', ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'])->nullable();
            $table->enum('bloom_affective_level', ['A1', 'A2', 'A3', 'A4', 'A5'])->nullable();
            $table->enum('bloom_psychomotor_level', ['P1', 'P2', 'P3', 'P4', 'P5'])->nullable();

            // Bobot dalam penilaian mata kuliah (%)
            $table->decimal('weight', 5, 2)->default(0); // 0-100

            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'code'], 'unique_course_cpmk_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_learning_outcomes');
    }
};
