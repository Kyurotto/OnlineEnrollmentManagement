<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Add extension field (e.g., Jr., Sr., III)
            if (!Schema::hasColumn('enrollments', 'extension')) {
                $table->string('extension')->nullable()->after('last_name');
            }

            // Add LRN (Learner Reference Number)
            if (!Schema::hasColumn('enrollments', 'lrn')) {
                $table->string('lrn')->nullable()->unique()->after('extension');
            }

            // Add facebook_account
            if (!Schema::hasColumn('enrollments', 'facebook_account')) {
                $table->string('facebook_account')->nullable()->after('contact');
            }

            // Add religion_church (Religion/Church affiliation)
            if (!Schema::hasColumn('enrollments', 'religion_church')) {
                $table->string('religion_church')->nullable()->after('religion');
            }

            // Add junior_high_school
            if (!Schema::hasColumn('enrollments', 'junior_high_school')) {
                $table->string('junior_high_school')->nullable()->after('birthplace');
            }

            // Add health_concerns
            if (!Schema::hasColumn('enrollments', 'health_concerns')) {
                $table->text('health_concerns')->nullable()->after('religion_church');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'extension')) {
                $table->dropColumn('extension');
            }
            if (Schema::hasColumn('enrollments', 'lrn')) {
                $table->dropColumn('lrn');
            }
            if (Schema::hasColumn('enrollments', 'facebook_account')) {
                $table->dropColumn('facebook_account');
            }
            if (Schema::hasColumn('enrollments', 'religion_church')) {
                $table->dropColumn('religion_church');
            }
            if (Schema::hasColumn('enrollments', 'junior_high_school')) {
                $table->dropColumn('junior_high_school');
            }
            if (Schema::hasColumn('enrollments', 'health_concerns')) {
                $table->dropColumn('health_concerns');
            }
        });
    }
};

