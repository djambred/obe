<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pemetaan Mata Kuliah ke Bahan Kajian
     */
    public function up(): void
    {
        Schema::create('course_study_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('study_field_id')->constrained()->cascadeOnDelete();
            $table->enum('coverage_level', ['Pengantar', 'Dasar', 'Lanjut', 'Expert'])->default('Dasar');
            $table->text('topics_covered')->nullable(); // JSON array topik yang dibahas
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'study_field_id'], 'unique_course_study_field');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_study_field');
    }
};
