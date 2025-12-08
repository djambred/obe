<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bahan Kajian (Study Fields/Body of Knowledge)
     */
    public function up(): void
    {
        Schema::create('study_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // BK-01, BK-02, etc
            $table->string('name');
            $table->text('description');
            $table->enum('category', [
                'Dasar Keilmuan',
                'Keahlian Berkarya',
                'Perilaku Berkarya',
                'Cara Berkehidupan Bermasyarakat'
            ])->default('Keahlian Berkarya');
            $table->text('sub_fields')->nullable(); // JSON array of sub bahan kajian
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
        Schema::dropIfExists('study_fields');
    }
};
