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
        Schema::table('enrollments', function (Blueprint $table) {
            // Add level column to track SHS vs College
            if (!Schema::hasColumn('enrollments', 'level')) {
                $table->enum('level', ['shs', 'college'])->default('college')->after('course_code');
            }

            // Add form_137_path if not exists (fix for form_138_path)
            if (!Schema::hasColumn('enrollments', 'form_137_path')) {
                $table->string('form_137_path')->nullable()->after('id_picture_path');
            }

            // Add sf10_path for SHS students
            if (!Schema::hasColumn('enrollments', 'sf10_path')) {
                $table->string('sf10_path')->nullable()->after('form_137_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'level')) {
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('enrollments', 'form_137_path')) {
                $table->dropColumn('form_137_path');
            }
            if (Schema::hasColumn('enrollments', 'sf10_path')) {
                $table->dropColumn('sf10_path');
            }
        });
    }
};
