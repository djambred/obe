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
            $table->text('learning_materials')->nullable()->after('course_description');
            $table->string('prerequisites')->nullable()->after('learning_materials');
            $table->text('performance_indicators')->nullable()->after('plo_mapped');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            $table->dropColumn(['learning_materials', 'prerequisites', 'performance_indicators']);
        });
    }
};
