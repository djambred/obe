<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sub-CPMK - breakdown detail dari CPMK per pertemuan/topik
     */
    public function up(): void
    {
        Schema::create('sub_course_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_learning_outcome_id')->constrained()->cascadeOnDelete();
            $table->string('code'); // Sub-CPMK-01, Sub-CPMK-02, etc
            $table->text('description');

            // Level Taksonomi (lebih detail dari parent CPMK)
            $table->enum('bloom_cognitive_level', ['C1', 'C2', 'C3', 'C4', 'C5', 'C6'])->nullable();
            $table->enum('bloom_affective_level', ['A1', 'A2', 'A3', 'A4', 'A5'])->nullable();
            $table->enum('bloom_psychomotor_level', ['P1', 'P2', 'P3', 'P4', 'P5'])->nullable();

            // Pemetaan ke pertemuan
            $table->integer('week_number')->nullable(); // Minggu ke berapa
            $table->text('learning_materials')->nullable(); // Materi pembelajaran
            $table->text('learning_methods')->nullable(); // Metode pembelajaran untuk sub-CPMK ini
            $table->integer('duration_minutes')->default(0); // Durasi pembelajaran (menit)

            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_learning_outcome_id', 'code'], 'unique_cpmk_sub_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_course_learning_outcomes');
    }
};
