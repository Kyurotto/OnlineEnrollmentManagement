<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('voucher_type')->nullable()->comment('Type of voucher: free_tuition, discounted');
            $table->timestamp('voucher_applied_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['voucher_type', 'voucher_applied_at']);
        });
    }
};
