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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('nim', 30)->unique(); // Student number
            $table->string('name', 255);
            $table->string('email', 255)->nullable()->unique();
            $table->string('phone', 50)->nullable();

            // Academic relations
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $table->foreignId('study_program_id')->nullable()->constrained('study_programs')->nullOnDelete();

            // Academic details
            $table->year('enrollment_year')->nullable();
            $table->string('class_group', 50)->nullable();
            $table->enum('status', ['Aktif', 'Cuti', 'Lulus', 'Nonaktif', 'Dropout'])->default('Aktif');

            // Personal details
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();

            // Guardian / parent info
            $table->string('parent_name')->nullable();
            $table->string('parent_phone', 50)->nullable();

            // Extra metadata
            $table->json('extras')->nullable();

            // Soft delete and timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes for search/filter
            $table->index(['faculty_id', 'study_program_id']);
            $table->index(['status']);
            $table->index(['enrollment_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
