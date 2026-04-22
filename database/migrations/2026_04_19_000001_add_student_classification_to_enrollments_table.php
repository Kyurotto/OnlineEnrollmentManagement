<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Add classification_reason (maps to existing irregular_reason) if not present
            if (!Schema::hasColumn('enrollments', 'classification_reason')) {
                $table->string('classification_reason')->nullable();
            }

            // Add last_audited_at if not present
            if (!Schema::hasColumn('enrollments', 'last_audited_at')) {
                $table->timestamp('last_audited_at')->nullable()->after('classification_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'classification_reason')) {
                $table->dropColumn('classification_reason');
            }
            if (Schema::hasColumn('enrollments', 'last_audited_at')) {
                $table->dropColumn('last_audited_at');
            }
        });
    }
};
