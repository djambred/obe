<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pemetaan CPMK ke CPL (Matrix Mapping)
     */
    public function up(): void
    {
        Schema::create('clo_plo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_learning_outcome_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_learning_outcome_id')->constrained()->cascadeOnDelete();
            $table->enum('contribution_level', ['Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'])->default('Tinggi');
            $table->decimal('weight', 5, 2)->default(0); // Bobot kontribusi (%)
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['course_learning_outcome_id', 'program_learning_outcome_id'], 'unique_cpmk_cpl_mapping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clo_plo');
    }
};
