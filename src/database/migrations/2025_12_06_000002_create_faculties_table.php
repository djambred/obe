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
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('vision')->nullable();
            $table->text('mission')->nullable(); // JSON array
            $table->text('objectives')->nullable(); // JSON array (Tujuan Fakultas)
            $table->text('description')->nullable();
            $table->string('logo')->nullable(); // MinIO path
            $table->string('dean_name')->nullable(); // Nama Dekan
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('accreditation')->nullable();
            $table->date('accreditation_date')->nullable();
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
        Schema::dropIfExists('faculties');
    }
};
