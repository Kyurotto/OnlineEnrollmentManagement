<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->date('drop_date')->nullable()->after('status');
            $table->enum('drop_reason', [
                'Financial',
                'Personal',
                'Transfer',
                'Academic',
                'Health',
                'Other',
            ])->nullable()->after('drop_date');
            $table->text('drop_notes')->nullable()->after('drop_reason');
            $table->decimal('base_tuition', 10, 2)->default(0)->after('drop_notes');
            $table->integer('consecutive_absences')->default(0)->after('base_tuition');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['drop_date', 'drop_reason', 'drop_notes', 'base_tuition', 'consecutive_absences']);
        });
    }
};
