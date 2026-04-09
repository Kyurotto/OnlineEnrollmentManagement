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
            // FIX: Safely drop the old conflicting enum column if it exists in an older database state
            if (Schema::hasColumn('payments', 'payment_status')) {
                $table->dropColumn('payment_status');
            }

            // FIX: Only add the new status column if it wasn't already created by the updated primary migration
            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('Pending')->after('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'status')) {
                $table->dropColumn('status');
            }
            // Revert back to the old structure if rolling back
            if (!Schema::hasColumn('payments', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            }
        });
    }
};
