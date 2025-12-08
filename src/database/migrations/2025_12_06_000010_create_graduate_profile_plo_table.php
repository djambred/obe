<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pemetaan Profil Lulusan (PL) ke CPL
     */
    public function up(): void
    {
        Schema::create('graduate_profile_plo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('graduate_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_learning_outcome_id')->constrained()->cascadeOnDelete();
            $table->enum('contribution_level', ['Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'])->default('Tinggi');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['graduate_profile_id', 'program_learning_outcome_id'], 'unique_pl_cpl_mapping');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduate_profile_plo');
    }
};
