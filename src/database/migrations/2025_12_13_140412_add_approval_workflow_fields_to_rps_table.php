<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            $table->string('kaprodi_approver_name')->nullable()->after('approval_notes');
            $table->timestamp('kaprodi_approved_at')->nullable()->after('kaprodi_approver_name');
            $table->text('kaprodi_approval_notes')->nullable()->after('kaprodi_approved_at');
            
            $table->string('dean_approver_name')->nullable()->after('kaprodi_approval_notes');
            $table->timestamp('dean_approved_at')->nullable()->after('dean_approver_name');
            $table->text('dean_approval_notes')->nullable()->after('dean_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('rps', function (Blueprint $table) {
            $table->dropColumn([
                'kaprodi_approver_name',
                'kaprodi_approved_at',
                'kaprodi_approval_notes',
                'dean_approver_name',
                'dean_approved_at',
                'dean_approval_notes',
            ]);
        });
    }
};
