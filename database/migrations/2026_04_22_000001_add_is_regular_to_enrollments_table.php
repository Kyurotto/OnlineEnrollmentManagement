<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('enrollments', 'is_regular')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->boolean('is_regular')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('enrollments', 'is_regular')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropColumn('is_regular');
            });
        }
    }
};
