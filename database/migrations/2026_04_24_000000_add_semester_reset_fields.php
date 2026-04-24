<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Carry-forward unpaid balance from prior term
            if (!Schema::hasColumn('enrollments', 'previous_balance')) {
                $table->decimal('previous_balance', 10, 2)->nullable()->default(0)->after('status');
            }

            // When this record was archived by a semester reset
            if (!Schema::hasColumn('enrollments', 'archived_at')) {
                $table->timestamp('archived_at')->nullable()->after('previous_balance');
            }

            // Denormalized semester name for easy archive grouping
            if (!Schema::hasColumn('enrollments', 'semester_name')) {
                $table->string('semester_name')->nullable()->after('archived_at');
            }

            // Denormalized academic year for archive grouping
            if (!Schema::hasColumn('enrollments', 'academic_year_name')) {
                $table->string('academic_year_name')->nullable()->after('semester_name');
            }
        });

        // Drop unique constraint on lrn if it exists (allows same LRN across terms)
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropUnique(['lrn']);
            });
        } catch (\Exception $e) {
            // Constraint may not exist, ignore
        }
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'previous_balance')) {
                $table->dropColumn('previous_balance');
            }
            if (Schema::hasColumn('enrollments', 'archived_at')) {
                $table->dropColumn('archived_at');
            }
            if (Schema::hasColumn('enrollments', 'semester_name')) {
                $table->dropColumn('semester_name');
            }
            if (Schema::hasColumn('enrollments', 'academic_year_name')) {
                $table->dropColumn('academic_year_name');
            }
        });
    }
};
