<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Profil Lulusan (PL) - turunan dari Visi Misi Tujuan Prodi
     */
    public function up(): void
    {
        Schema::create('graduate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // PL-01, PL-02, etc
            $table->string('name');
            $table->text('description');
            $table->text('career_prospects')->nullable(); // Prospek karir
            $table->text('work_areas')->nullable(); // JSON array bidang pekerjaan
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
        Schema::dropIfExists('graduate_profiles');
    }
};
