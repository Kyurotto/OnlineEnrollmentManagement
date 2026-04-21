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
            // Add installment tracking fields
            $table->enum('installment_type', ['Prelim', 'Midterm', 'Final', 'Full Payment'])->default('Full Payment')->after('status');
            $table->decimal('down_payment_total', 10, 2)->nullable()->after('installment_type'); // Total downpayment amount for reference
            $table->boolean('is_installment')->default(false)->after('down_payment_total'); // Flag to identify installment payments
            $table->string('payment_method')->default('Cash')->change(); // Ensure payment_method exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['installment_type', 'down_payment_total', 'is_installment']);
        });
    }
};
