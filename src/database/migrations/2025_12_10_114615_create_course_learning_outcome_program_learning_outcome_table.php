<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot table untuk many-to-many relationship antara CPMK dan CPL
     */
    public function up(): void
    {
        Schema::create('course_learning_outcome_program_learning_outcome', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_learning_outcome_id');
            $table->unsignedBigInteger('program_learning_outcome_id');
            $table->timestamps();

            // Foreign keys dengan nama pendek
            $table->foreign('course_learning_outcome_id', 'cpmk_cpl_cpmk_fk')
                ->references('id')
                ->on('course_learning_outcomes')
                ->cascadeOnDelete();

            $table->foreign('program_learning_outcome_id', 'cpmk_cpl_cpl_fk')
                ->references('id')
                ->on('program_learning_outcomes')
                ->cascadeOnDelete();

            // Unique constraint untuk menghindari duplikasi
            $table->unique(
                ['course_learning_outcome_id', 'program_learning_outcome_id'],
                'cpmk_cpl_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_learning_outcome_program_learning_outcome');
    }
};
