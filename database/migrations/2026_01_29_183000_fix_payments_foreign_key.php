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
        Schema::table('payments', function (Blueprint $table) {
            // 1. (Skipped) We removed the old constraint from the initial migration
            // so migrate:fresh works smoothly.
            // $table->dropForeign('payments_application_id_foreign');

            // 2. Add the new foreign key constraint pointing to 'enrollments'
            $table->foreign('application_id')
                  ->references('id')
                  ->on('enrollments')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
        });
    }
};
