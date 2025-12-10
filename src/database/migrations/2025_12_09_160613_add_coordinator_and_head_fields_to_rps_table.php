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
        Schema::table('rps', function (Blueprint $table) {
            $table->foreignId('coordinator_id')->nullable()->after('lecturer_id')->constrained('lecturers')->nullOnDelete();
            $table->foreignId('head_of_program_id')->nullable()->after('coordinator_id')->constrained('lecturers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            $table->dropForeign(['coordinator_id']);
            $table->dropForeign(['head_of_program_id']);
            $table->dropColumn(['coordinator_id', 'head_of_program_id']);
        });
    }
};
